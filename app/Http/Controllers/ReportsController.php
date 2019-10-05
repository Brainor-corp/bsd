<?php

namespace App\Http\Controllers;

use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use App\Http\Helpers\Api1CHelper;
use App\Http\Helpers\DocumentHelper;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
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
        $orders = Order::available()
            ->with(
                'status',
                'ship_city',
                'dest_city',
                'payment_status',
                'payment',
                'order_items'
            )
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
        $order = Order::available()
            ->where('id', $id)
            ->with([
                'status',
                'cargo_type',
                'order_items',
                'payment',
                'take_polygon',
                'bring_polygon',
                'order_services' => function ($services) {
                    return $services->withPivot('price');
                }
            ])
            ->whereDoesntHave('status', function ($statusQuery) {
                return $statusQuery->where('slug', 'chernovik');
            })
            ->firstOrFail();

        $userTypes = Type::where('class', 'UserType')->get();

        return View::make('v1.pages.profile.profile-inner.report-show-page')->with(compact('order', 'userTypes'))->render();
    }

    public function getDownloadDocumentsModal(Request $request) {
        $order = Order::available()
            ->where('id', $request->get('order_id'))
            ->firstOrFail();

        $user_1c = $order->user->guid ?? null;
        $code1c = $order->code_1c;

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

        $send = [
            "user_id" => $user->guid,
            "document_id" => $document_id_1c,
            "type" => intval($document_type_id_1c),
            "empty_fields" => true
        ];

        switch ($document_type_id_1c) {
            case 3:
                // Заявка на экспедирование

                $response1c = \App\Http\Helpers\Api1CHelper::post(
                    'document/id',
                    $send
                );
                if($response1c['status'] !== 200) {
                    return redirect(route('report-list'));
                }

                $documentData = $response1c['response'] ?? [];

                $file = DocumentHelper::generateRequestDocument(
                    $documentData,
                    $documentData['Представление'] . '.pdf');
                if(isset($file['tempFile']) && isset($file['fileName'])) {
                    return response()->download($file['tempFile'], $file['fileName'])
                        ->deleteFileAfterSend(true);
                }

                break;

            case 2:
            case 5:
            case 6:
                    $result = Api1CHelper::getPdf(
                        'print_form',
                        $send
                    );

                    header('Cache-Control: public');
                    header('Content-type: application/pdf');
                    header('Content-Disposition: attachment; filename="new.pdf"');
                    header('Content-Length: '.strlen($result));

                    return $result;
                    break;

            default: break;
        }

        return redirect(route('report-list'));
    }

    public function actionDownloadReports(Request $request) {
        $orders = Order::available()
            ->with('status', 'ship_city', 'dest_city')
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
            '№ заявки',                     // A
            '№ Экспедиторский расписки',    // B
            'Статус груза',                 // C
            'Дата',                         // D
            'Параметры груза',              // E
            'Город отправителя',            // F
            'Город получателя',             // G
            'Отправитель',                  // H
            'Получатель',                   // I
            'Оплата услуги',                // J
            'Статус заявки',                // K
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
            $sheet->setCellValue('B' . ($key + 2), $order->cargo_number);
            $sheet->setCellValue('C' . ($key + 2), $order->cargo_status->name ?? '');
            $sheet->setCellValue('D' . ($key + 2), $order->created_at->format('d.m.Y'));
            $sheet->setCellValue('E' . ($key + 2), $items);
            $sheet->setCellValue('F' . ($key + 2), $order->ship_city_name ?? $order->ship_city->name ?? '-');
            $sheet->setCellValue('G' . ($key + 2), $order->dest_city_name ?? $order->dest_city->name ?? '-');
            $sheet->setCellValue('H' . ($key + 2), $order->sender_name ?? ($order->sender_company_name ?? '-'));
            $sheet->setCellValue('I' . ($key + 2), $order->recipient_name ?? ($order->recipient_company_name ?? '-'));
            $sheet->setCellValue('J' . ($key + 2), $order->payment_status->name ?? '');
            $sheet->setCellValue('K' . ($key + 2), $order->status->name);
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
}
