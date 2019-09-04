<?php

namespace App\Http\Controllers;

use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use App\Http\Helpers\DocumentHelper;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

define('SPECIAL_ARRAY_TYPE', CellSetterArrayValueSpecial::class);

class ReportsController extends Controller
{
    public function showReportListPage() {
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where(function ($ordersQuery) {
                return Auth::check() ? $ordersQuery
                    ->where('user_id', Auth::id())
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $ordersQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return View::make('v1.pages.profile.profile-inner.report-list-page')->with(compact('orders'));
    }

    public function showReportPage($id) {
        $order = Order::where('id', $id)
            ->where(function ($ordersQuery) {
                return Auth::check() ? $ordersQuery
                    ->where('user_id', Auth::id())
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $ordersQuery->where('enter_id', $_COOKIE['enter_id']);
            })->with([
                'status',
                'cargo_type',
                'order_items',
                'payment',
                'take_polygon',
                'bring_polygon',
                'order_services' => function ($services) {
                    return $services->withPivot('price');
                }
            ])->whereDoesntHave('status', function ($statusQuery) {
                return $statusQuery->where('slug', 'chernovik');
            })->firstOrFail();

        $userTypes = Type::where('class', 'UserType')->get();

        return View::make('v1.pages.profile.profile-inner.report-show-page')->with(compact('order', 'userTypes'))->render();
    }

    public function getDownloadDocumentsModal(Request $request) {
        $order = Order::where('id', $request->get('order_id'))
            ->where(function ($ordersQuery) {
                return Auth::check() ? $ordersQuery
                    ->where('user_id', Auth::id())
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $ordersQuery->where('enter_id', $_COOKIE['enter_id']);
            })->firstOrFail();

        $user_1c = $order->user->guid ?? null;
//        $user_1c = "f008aa7f-29d6-11e9-80c7-000d3a396ad2";
//        $user_1c = "77feff2c-14dc-11e9-a990-000d3a28f168";

        $code1c = $order->code_1c;
//        $code1c = "2ef09a62-8dbb-11e9-a688-001c4208e0b2";
//        $code1c = "aaea8a04-afa5-11e9-1d9c-001c4208e0b2";

        $documents = [];

        if(!empty($user_1c) && !empty($code1c)) {
            $response1c = \App\Http\Helpers\Api1CHelper::post(
                'document_list',
                [
                    "user_id" => $user_1c,
                    "order_id" => $code1c
                ]
            );

            if($response1c['response']['status'] == 'success') {
                $documents = [];
                foreach($response1c['response']['documents'] as $document) {
                    if($document['type'] !== 1) {
                        array_push($documents, $document);
                    }
                }
            }
        }

        return view('v1.partials.reports.download-documents-modal-content')
            ->with(compact('documents'));
    }

    public function downloadOrderDocument(Request $request, $document_id_1c, $document_type_id_1c) {
        $user = Auth::user();

        if(!isset($user)) {
        	return redirect(route('login'));
        }

        $document_type_id_1c = 3; // todo убрать
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/id',
            [
//                "user_id" => $user->guid,
//                "document_id" => $document_id_1c,
                "type" => intval($document_type_id_1c),
                "user_id" => "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "document_id" => "bd434e46-c59b-11e9-2288-001c4208e0b2",
                "empty_fields" => true
            ]
        );

        if($response1c['status'] !== 200) {
            return redirect(route('report-list'));
        }

        $documentData = $response1c['response'] ?? [];
        $file = [];

        dd($documentData);

        switch ($document_type_id_1c) {
            case 2:
                // Экспедиторская расписка
                $file = DocumentHelper::generateReceiptDocument(
                    $documentData['Представление'],
                    $documentData
                );
                break;
            case 3:
                // Заявка на экспедирование
                $file = DocumentHelper::generateRequestDocument(
                    $documentData,
                    $documentData['Представление'] . '.pdf');
                break;
            case 5:
                // Счет на оплату
                $file = DocumentHelper::generateInvoiceDocument(
                    $documentData,
                    $documentData['Представление']
                );
                break;
            case 6:
                // Реализация (акт, накладная) (УПД)
                // Счет-фактура(УПД???)
                $file = DocumentHelper::generateTransferDocument(
                    $documentData,
                    $documentData['Представление']
                );
                break;

            default: break;
        }

        if(isset($file['tempFile']) && isset($file['fileName'])) {
            return response()->download($file['tempFile'], $file['fileName'])
                ->deleteFileAfterSend(true);
        }

        return redirect(route('report-list'));
    }

    public function actionDownloadReports(Request $request) {
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->when($request->get('id'), function ($order) use ($request) {
                return $order->where('id', 'LIKE', '%' . $request->get('id') . '%');
            })
            ->when($request->get('finished') === 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type) {
                    return $type->where('slug', 'dostavlen');
                });
            })
            ->when($request->get('finished') !== 'true' && $request->get('status'), function ($order) use ($request) {
                return $order->where('status_id', $request->get('status'));
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Балтийская служба доставки')
            ->setLastModifiedBy('Балтийская служба доставки')
            ->setTitle('Отчеты о заказах')
            ->setSubject('Отчеты о заказах')
            ->setDescription('Отчеты о заказах');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Отчеты');

        $headers = [
            '№ ',
            'Дата',
            'Параметры груза',
            'Город отправителя',
            'Город получателя',
            'Отправитель',
            'Получатель',
            'Стоимость',
            'Статус заказа',
        ];

        $x = range('A', 'Z');
        foreach ($headers as $key => $header) {
            $sheet->setCellValue($x[$key] . 1, $header);
        }
        foreach ($orders as $key => $order) {
            $items = "Вес: " . $order->total_weight . ".\nГабариты: ";

            foreach ($order->order_items as $order_item) {
                $items .= "\nДхШхВ: " . $order_item->length . "х" . $order_item->width . "х" . $order_item->height . " м";
            }

            $sheet->setCellValue('A' . ($key + 2), $order->id);
            $sheet->setCellValue('B' . ($key + 2), $order->created_at->format('d.m.Y'));
            $sheet->setCellValue('C' . ($key + 2), $items);
            $sheet->setCellValue('D' . ($key + 2), $order->ship_city_name ?? $order->ship_city->name ?? '-');
            $sheet->setCellValue('E' . ($key + 2), $order->dest_city_name ?? $order->dest_city->name ?? '-');
            $sheet->setCellValue('F' . ($key + 2), $order->sender_name ?? '-');
            $sheet->setCellValue('G' . ($key + 2), $order->recipient_name ?? '-');
            $sheet->setCellValue('H' . ($key + 2), $order->total_price . 'р');
            $sheet->setCellValue('I' . ($key + 2), $order->status->name);
        }

        $rowIterator = $sheet->getRowIterator()->resetStart();
        $rowIterator->rewind();
        $cellIterator = $rowIterator->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);

        foreach ($cellIterator as $cell) {
            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            $sheet->getStyle($cell->getColumn())->getAlignment()->setHorizontal('center');
        }

        $writer = new Xlsx($spreadsheet);

        $name = md5('Отчеты bsd' . time()) . '.xlsx';
        $path = storage_path('app/public/reports/');

        File::makeDirectory($path, $mode = 0777, true, true);
        $writer->save($path . $name);

        return response()->download($path . $name, 'Отчет БСД - ' . date('d.m.Y') . '.xlsx')->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentContract(Request $request) {
        $documentData['УникальныйИдентификатор'] = '123123';
        $file = DocumentHelper::generateContractDocument($documentData);
        return response()->download($file, 'test.docx')->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentInvoice(Request $request) {
        $order = Order::where('id', $request->id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('status', 'ship_city', 'dest_city')->firstOrFail();

        $orderDate = Date::parse($order->created_at)->format('d F Y');
        $documentExtension = '.xlsx';
        $templateFile = public_path('templates/InvoiceTemplate.xlsx');
        $documentName = "Счет на оплату №$order->id от $orderDate г.";

        $params = [
            '{order_id}' => $order->id,
            '{order_date}' => $order->created_at->format('d.m.Y h:i:s'),
        ];

        $params['[service_number]'] = new ExcelParam(CellSetterArrayValueSpecial::class, range(3, $order->order_services->count() + 2));
        $params['[service_name]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $order->order_services->pluck('name')->toArray());
        $params['[service_quantity]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $order->order_services->pluck('quantity')->toArray());
        $params['[service_point]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $order->order_services->pluck('point')->toArray());
        $params['[service_price]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $order->order_services->pluck('price')->toArray());
        $params['[service_summary]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $order->order_services->pluck('summary')->toArray());

        $name = md5('docs bsd' . time()) . $documentExtension;
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile($templateFile, $tempFile, $params);
        return response()->download($tempFile, $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentReceipt(Request $request) {
        $order = Order::where('id', $request->id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('status', 'ship_city', 'dest_city')->firstOrFail();

        $orderDate = Date::parse($order->created_at)->format('d F Y');
        $documentName = "Экспедиторская расписка №$order->id от $orderDate г.";
        $documentExtension = '.xlsx';

        $templateFile = public_path('templates/ReceiptTemplate.xlsx');

        $params = [
            '{order_id}' => $order->id,
            '{order_date}' => $order->created_at->format('d.m.Y h:i:s'),
            '{ship_city}' => $order->ship_city_name ?? $order->ship_city->name,
            '{dest_city}' => $order->dest_city_name ?? $order->dest_city->name,
        ];

        $params['[item_name]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $order->order_items->pluck('name')->toArray());
        $params['[item_volume]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $order->order_items->pluck('volume')->toArray());
        $params['[item_weight]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $order->order_items->pluck('weight')->toArray());
        $params['[item_quantity]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $order->order_items->pluck('quantity')->toArray());
        $params['[item_price]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $order->order_items->pluck('price')->toArray());

        $name = md5('docs bsd' . time()) . $documentExtension;
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile($templateFile, $tempFile, $params);
        return response()->download($tempFile, $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentTransfer(Request $request) {
        $order = Order::where('id', $request->id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('status', 'ship_city', 'dest_city')->firstOrFail();

        $orderDate = Date::parse($order->created_at)->format('d F Y');
        $templateFile = public_path('templates/TransferTemplate.xlsx');

        $documentName = "УПД (статус 1) №$order->id от $orderDate г.";
        $documentExtension = '.xlsx';

        $params = [
            '{order_id}' => $order->id,
            '{order_date}' => $orderDate,
        ];

        $documentTestData['Товары'] = [
            [
                'Содержание' => 'Name1',
                'Количество' => '2',
                'Цена' => '222',
                'Сумма' => '233',
            ],
            [
                'Содержание' => 'Name2',
                'Количество' => '1',
                'Цена' => '2',
            ],
        ];

        $file = DocumentHelper::generateTransferDocument($documentTestData, 'test');

//        $name = md5('docs bsd' . time()) . $documentExtension;
//        $path = storage_path('app/public/documents/');

//        $tempFile = $path . $name;
//        File::makeDirectory($path, $mode = 0777, true, true);
//
//        PhpExcelTemplator::saveToFile($templateFile, $tempFile, $params);
        return response()->download($file['tempFile'], $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentRequest(Request $request)
    {
        $order = Order::where('id', $request->id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('status', 'ship_city', 'dest_city')
            ->firstOrFail();

        $orderDate = Date::parse($order->created_at)->format('d F Y');
        $documentName = "Заявка №$order->id от $orderDate г.";
        $documentExtension = '.docx';

        $templateFile = public_path('templates/RequestTemplate.docx');

        $params = [
            '{order_id}' => $order->id,
            '{order_date}' => $order->created_at->format('d.m.Y h:i:s'),
            '{ship_city}' => $order->ship_city_name ?? $order->ship_city->name,
            '{dest_city}' => $order->dest_city_name ?? $order->dest_city->name,
        ];

        $blocks['blk'] = [
            [
                'length' => 1,
                'width' => 22,
                'height' => 2,
                'volume' => 23,
                'weight' => 2,
                'quantity' => 4,
            ],
            [
                'length' => 113121,
                'width' => 222,
                'height' => 2332,
                'volume' => 223,
                'weight' => 233,
                'quantity' => 42232,
            ],
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('v1.pdf.document-request', $blocks);

        return $pdf->download($documentName);
    }
}
