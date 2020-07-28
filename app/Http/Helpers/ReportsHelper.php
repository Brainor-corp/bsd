<?php

namespace App\Http\Helpers;

use App\ForwardingReceipt;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportsHelper {

    public static function showReports(Request $request)
    {
        $forwardingReceipt = ForwardingReceipt::where('user_id', Auth::id())
            ->when(!empty($request->get('number')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('number', 'LIKE', '%' . $request->get('number') . '%');
            })
            ->when(!empty($request->get('sender')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('sender_name', 'LIKE', '%' . $request->get('sender') . '%');
            })
            ->when(!empty($request->get('recipient')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('recipient_name', 'LIKE', '%' . $request->get('recipient') . '%');
            })
            ->when($request->finished == 'true', function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->whereHas('cargo_status', function ($type) {
                    return $type->where('name', 'Груз выдан');
                });
            })
            ->when($request->finished != 'true' && !empty($request->get('status')), function ($order) use ($request) {
                return $order->where('cargo_status_id', $request->get('status'));
            })
            ->select(
                'id',
                DB::raw("\"receipt\" as source"),
                'packages_count',
                'code_1c',
                'number',
                'cargo_status_id',
                'order_date',
                'volume',
                'weight',
                'ship_city',
                'dest_city',
                'sender_name',
                'recipient_name',
                'user_id',
                'created_at',
                'updated_at',
                'payment_status_id',
                DB::raw("NULL as status_id"),
                DB::raw("NULL as sender_company_name"),
                DB::raw("NULL as recipient_company_name")
            );

        $orders = Order::available()
            ->when($request->get('number'), function ($order) use ($request) {
                return $order->where(function ($orderSubQ) use ($request) {
                    return $orderSubQ->where('id', 'LIKE', '%' . $request->get('number') . '%')
                        ->orWhere('cargo_number', 'LIKE', '%' . $request->get('number') . '%');
                });
            })
            ->when(!empty($request->get('sender')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where(function ($forwardingReceiptSubQ) use ($request) {
                    return $forwardingReceiptSubQ->where('sender_name', 'LIKE', '%' . $request->get('sender') . '%')
                        ->orWhere('sender_company_name', 'LIKE', '%' . $request->get('sender') . '%');
                });
            })
            ->when(!empty($request->get('recipient')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where(function ($forwardingReceiptSubQ) use ($request) {
                    return $forwardingReceiptSubQ->where('recipient_name', 'LIKE', '%' . $request->get('recipient') . '%')
                        ->orWhere('recipient_company_name', 'LIKE', '%' . $request->get('recipient') . '%');
                });
            })
            ->when($request->finished == 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type) {
                    return $type->where('name', 'Закрыта');
                });
            })
            ->when($request->finished != 'true' && $request->get('status'), function ($order) use ($request) {
                return $order->where(function ($orderSubQ) use ($request) {
                    return $orderSubQ->where('status_id', $request->get('status'))
                        ->orWhere('cargo_status_id', $request->get('status'));
                });
            })
            ->select(
                'id',
                DB::raw("\"order\" as source"),
                DB::raw("NULL as packages_count"),
                'code_1c',
                'cargo_number as number',
                'cargo_status_id',
                'order_date',
                'actual_volume as volume',
                'actual_weight as weight',
                'ship_city_name as ship_city',
                'dest_city_name as dest_city',
                'sender_name',
                'recipient_name',
                'user_id',
                'created_at',
                'updated_at',
                'payment_status_id',
                'status_id',
                'sender_company_name',
                'recipient_company_name'
            )
            ->with(
                'status',
                'ship_city',
                'dest_city',
                'payment_status',
                'payment',
                'order_items'
            )
            ->union($forwardingReceipt)
            ->orderBy('order_date', 'desc')
            ->paginate(10);

//        $orders = $orders->sortByDesc('order_date');

        $user = Auth::user();
        $types = Type::where('class', 'order_status')
            ->whereHas('ordersByStatus', function ($ordersQuery) use ($user) {
                return $ordersQuery->where('user_id', $user->id);
            })
            ->get();

        return View::make('v1.pages.profile.profile-inner.report-list-page')
            ->with(compact('orders', 'request', 'types'));
    }

    public static function downloadReports(Request $request)
    {
        $forwardingReceipt = ForwardingReceipt::where('user_id', Auth::id())
            ->when(!empty($request->get('number')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('number', 'LIKE', '%' . $request->get('number') . '%');
            })
            ->when(!empty($request->get('sender')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('sender_name', 'LIKE', '%' . $request->get('sender') . '%');
            })
            ->when(!empty($request->get('recipient')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where('recipient_name', 'LIKE', '%' . $request->get('recipient') . '%');
            })
            ->when($request->finished == 'true', function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->whereHas('cargo_status', function ($type) {
                    return $type->where('name', 'Груз выдан');
                });
            })
            ->when($request->finished != 'true' && !empty($request->get('status')), function ($order) use ($request) {
                return $order->where('cargo_status_id', $request->get('status'));
            })
            ->select(
                'id',
                DB::raw("\"receipt\" as source"),
                'packages_count',
                'code_1c',
                'number',
                'cargo_status_id',
                'order_date',
                'volume',
                'weight',
                'ship_city',
                'dest_city',
                'sender_name',
                'recipient_name',
                'user_id',
                'created_at',
                'updated_at',
                'payment_status_id',
                DB::raw("NULL as status_id"),
                DB::raw("NULL as sender_company_name"),
                DB::raw("NULL as recipient_company_name")
            );

        $orders = Order::available()
            ->when($request->get('number'), function ($order) use ($request) {
                return $order->where(function ($orderSubQ) use ($request) {
                    return $orderSubQ->where('id', 'LIKE', '%' . $request->get('number') . '%')
                        ->orWhere('cargo_number', 'LIKE', '%' . $request->get('number') . '%');
                });
            })
            ->when(!empty($request->get('sender')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where(function ($forwardingReceiptSubQ) use ($request) {
                    return $forwardingReceiptSubQ->where('sender_name', 'LIKE', '%' . $request->get('sender') . '%')
                        ->orWhere('sender_company_name', 'LIKE', '%' . $request->get('sender') . '%');
                });
            })
            ->when(!empty($request->get('recipient')), function ($forwardingReceipt) use ($request) {
                return $forwardingReceipt->where(function ($forwardingReceiptSubQ) use ($request) {
                    return $forwardingReceiptSubQ->where('recipient_name', 'LIKE', '%' . $request->get('recipient') . '%')
                        ->orWhere('recipient_company_name', 'LIKE', '%' . $request->get('recipient') . '%');
                });
            })
            ->when($request->finished == 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type) {
                    return $type->where('name', 'Закрыта');
                });
            })
            ->when($request->finished != 'true' && $request->get('status'), function ($order) use ($request) {
                return $order->where(function ($orderSubQ) use ($request) {
                    return $orderSubQ->where('status_id', $request->get('status'))
                        ->orWhere('cargo_status_id', $request->get('status'));
                });
            })
            ->select(
                'id',
                DB::raw("\"order\" as source"),
                DB::raw("NULL as packages_count"),
                'code_1c',
                'cargo_number as number',
                'cargo_status_id',
                'order_date',
                'actual_volume as volume',
                'actual_weight as weight',
                'ship_city_name as ship_city',
                'dest_city_name as dest_city',
                'sender_name',
                'recipient_name',
                'user_id',
                'created_at',
                'updated_at',
                'payment_status_id',
                'status_id',
                'sender_company_name',
                'recipient_company_name'
            )
            ->with(
                'status',
                'ship_city',
                'dest_city',
                'payment_status',
                'payment',
                'order_items'
            )
            ->union($forwardingReceipt)
            ->orderBy('order_date', 'desc')
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
            'Тип',                          // A
            '№ заявки',                     // B
            '№ Экспедиторский расписки',    // C
            'Статус груза',                 // D
            'Дата',                         // E
            'Параметры груза',              // F
            'Город отправителя',            // G
            'Город получателя',             // H
            'Отправитель',                  // I
            'Получатель',                   // J
            'Оплата услуги',                // K
            'Статус заявки',                // L
        ];

        $x = range('A', 'Z');
        foreach ($headers as $key => $header) {
            $sheet->setCellValue($x[$key] . 1, $header);
        }
        foreach ($orders as $key => $order) {
            if($order->source === 'order') {
                $items = "Вес: " . $order->weight . ".\nГабариты: ";

                foreach ($order->order_items as $order_item) {
                    $items .= "\nДхШхВ: " . $order_item->length . "х" . $order_item->width . "х" . $order_item->height . " м";
                }

                $sheet->setCellValue('A' . ($key + 2), 'Заявка');
                $sheet->setCellValue('B' . ($key + 2), $order->id);
                $sheet->setCellValue('C' . ($key + 2), $order->number);
                $sheet->setCellValue('D' . ($key + 2), $order->cargo_status->name ?? '');
                $sheet->setCellValue('E' . ($key + 2), isset($order->order_date) ? $order->order_date->format('d.m.Y') : $order->created_at->format('d.m.Y'));
                $sheet->setCellValue('F' . ($key + 2), $items);
                $sheet->setCellValue('G' . ($key + 2), $order->ship_city ?? '-');
                $sheet->setCellValue('H' . ($key + 2), $order->dest_city ?? '-');
                $sheet->setCellValue('I' . ($key + 2), $order->sender_name ?? ($order->sender_company_name ?? '-'));
                $sheet->setCellValue('J' . ($key + 2), $order->recipient_name ?? ($order->recipient_company_name ?? '-'));
                $sheet->setCellValue('K' . ($key + 2), $order->payment_status->name ?? '');
                $sheet->setCellValue('L' . ($key + 2), $order->status->name);
            } else {
                $items = "Кол-во мест: $order->packages_count\nОбъем: $order->volumen\nВес: $order->weight";

                $sheet->setCellValue('A' . ($key + 2), 'Экспедиторская расписка');
                $sheet->setCellValue('B' . ($key + 2), '');
                $sheet->setCellValue('C' . ($key + 2), $order->number);
                $sheet->setCellValue('D' . ($key + 2), $order->cargo_status->name ?? '');
                $sheet->setCellValue('E' . ($key + 2), $order->order_date->format('d.m.Y'));
                $sheet->setCellValue('F' . ($key + 2), $items);
                $sheet->setCellValue('G' . ($key + 2), $order->ship_city);
                $sheet->setCellValue('H' . ($key + 2), $order->dest_city);
                $sheet->setCellValue('I' . ($key + 2), $order->sender_name);
                $sheet->setCellValue('J' . ($key + 2), $order->recipient_name);
                $sheet->setCellValue('K' . ($key + 2), $order->payment_status->name ?? '');
                $sheet->setCellValue('L' . ($key + 2), '');
            }
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
