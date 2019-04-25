<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Helpers\DocumentHelper;
use App\Order;
use App\Type;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProfileController extends Controller {
    public function profileData() {
        return view('v1.pages.profile.profile-data.profile-data');
    }

    public function edit(Request $request) {

        $user = User:: where('id', $request->user_id)->first();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                User::where('id', $request->user_id)->update(
                    [
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'patronomic' => $request->patronomic,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]
                );
                $message = [
                    'type' => 'alert-success',
                    'data' => 'Данные успешно обновлены'
                ];
            }
            else {
                $message = [
                    'type' => 'alert-warning',
                    'data' => 'Старый пароль введен неверно'
                ];
            }
        }
        else {
            $message = [
                'type' => 'alert-warning',
                'data' => 'Пользователь с указанным ID не найден'
            ];
        }
        return redirect()->back()->with($message['type'], $message['data']);
    }

    public function showEventListPage() {
        $user = Auth::user();

        $events = Event::where([['user_id', $user->id], ['visible', true]])->get();
        return View::make('v1.pages.profile.profile-inner.event-list-page')->with(compact('events'));
    }

    public function actionHideEvent(Request $request) {
        $event = Event::whereId($request->event_id)->firstOrFail();

        $event->visible = false;
        $event->update();
        return 'ok';
    }

    public function showReportListPage() {
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where('user_id', Auth::user()->id)
            ->get();
        return View::make('v1.pages.profile.profile-inner.report-list-page')->with(compact('orders'));
    }

    public function searchOrders(Request $request){
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where('user_id', Auth::user()->id)
            ->when($request->get('id'), function ($order) use ($request) {
                return $order->where('id', 'LIKE', '%' . $request->get('id') . '%');
            })
            ->when($request->finished == 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type){
                    return $type->where('slug', 'dostavlen');
                });
            })
            ->when($request->finished  == 'false' && $request->get('status'), function ($order) use ($request) {
                return $order->where('status_id', $request->get('status'));
            })
            ->get();

        return View::make('v1.partials.profile.orders')->with(compact('orders'))->render();
    }

    public function actionGetOrderItems(Request $request){
        $order = Order::where('id', $request->order_id)->with('order_items')->firstOrFail();
        return View::make('v1.partials.profile.order-items-modal-body')->with(compact('order'))->render();
    }

    public function actionGetOrderSearchInput(){
        $types = Type::where('class', 'order_status')->get();
        return View::make('v1.partials.profile.order-search-select')->with(compact('types'))->render();
    }

    public function showReportPage($id){
        $order = Order::where('user_id', Auth::user()->id)->where('id', $id)->with('status', 'order_items', 'order_services', 'payment')->firstOrFail();

        //todo вывод доставки и терминальной перевозки

        return View::make('v1.pages.profile.profile-inner.report-show-page')->with(compact('order'))->render();
    }

    public function actionDownloadReports(Request $request){
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where('user_id', Auth::user()->id)
            ->when($request->get('id'), function ($order) use ($request) {
                return $order->where('id', 'LIKE', '%' . $request->get('id') . '%');
            })
            ->when($request->get('finished') === 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type){
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
        foreach ($headers as $key => $header){
            $sheet->setCellValue($x[$key] . 1, $header);
        }
        foreach ($orders as $key => $order){
            $items = "Вес: " . $order->total_weight . ".\nГабариты: ";

            foreach ($order->order_items as $order_item){
                $items .= "\nДхШхВ: " . $order_item->length . "х" . $order_item->width . "х" . $order_item->height . " м";
            }

            $sheet->setCellValue('A' . ($key +2), $order->id);
            $sheet->setCellValue('B' . ($key +2), $order->created_at->format('d.m.Y'));
            $sheet->setCellValue('C' . ($key +2), $items);
            $sheet->setCellValue('D' . ($key +2), $order->ship_city_name ?? $order->ship_city->name ?? '-');
            $sheet->setCellValue('E' . ($key +2), $order->dest_city_name ?? $order->dest_city->name ?? '-');
            $sheet->setCellValue('F' . ($key +2), $order->sender_name ?? '-');
            $sheet->setCellValue('G' . ($key +2), $order->recepient_name ?? '-');
            $sheet->setCellValue('H' . ($key +2), $order->total_price . 'р');
            $sheet->setCellValue('I' . ($key +2), $order->status->name);
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
        $path = storage_path('app\public\reports\\');

        File::makeDirectory($path, $mode = 0777, true, true);
        $writer->save($path . $name);

        return response()->download($path . $name, 'Отчет БСД - ' . date('d.m.Y') . '.xlsx')->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentContract(Request $request){
        $order = Order::where([['id', $request->id], ['user_id', Auth::user()->id]])->with('status', 'ship_city', 'dest_city', 'user')->firstOrFail();

        $path = public_path('templates\\ContractTemplate.docx');
        $documentName = 'Договор';
        $documentExtension = '.docx';

        $params = [
            'created_at' => $order->created_at,
            'number' => $order->id,
            'client_phone' => $order->user->phone ?? 'Тел. —',
            'client_email' => $order->user->email,
            'client_name' => $order->user->full_name,
            'client_short' => $order->user->surname_initials,
        ];

        $tempFile = DocumentHelper::generateDocument($path, $documentExtension, $params);

        return response()->download($tempFile, $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentInvoice(Request $request){
        $order = Order::where([['id', $request->id], ['user_id', Auth::user()->id]])->with('status', 'ship_city', 'dest_city')->firstOrFail();

        $path = public_path('templates\\InvoiceTemplate.xlsx');
        $documentName = 'Счет на оплату';
        $documentExtension = '.xlsx';

        $params = [
            'created_at' => $order->created_at,
            'number' => $order->id,
        ];

        $tempFile = DocumentHelper::generateDocument($path, $documentExtension, $params);

        return response()->download($tempFile, $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

    public function actionDownloadDocumentReceipt(Request $request){
        $order = Order::where([['id', $request->id], ['user_id', Auth::user()->id]])->with('status', 'ship_city', 'dest_city')->firstOrFail();

        $path = public_path('templates\\ReceiptTemplate.xlsx');
        $documentName = 'Экспедиторская расписка';
        $documentExtension = '.xlsx';

        $params = [
            'created_at' => $order->created_at,
            'number' => $order->id,
        ];

        $tempFile = DocumentHelper::generateDocument($path, $documentExtension, $params);

        return response()->download($tempFile, $documentName . $documentExtension)->deleteFileAfterSend(true);
    }

}
