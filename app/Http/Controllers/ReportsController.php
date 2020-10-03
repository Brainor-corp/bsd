<?php

namespace App\Http\Controllers;

use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use App\ForwardingReceipt;
use App\Http\Helpers\Api1CHelper;
use App\Http\Helpers\DocumentHelper;
use App\Http\Helpers\OrderHelper;
use App\Http\Helpers\ReportsHelper;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function showReportListPage(Request $request) {
        switch ($request->get('action')) {
            case 'download': return ReportsHelper::downloadReports($request); break;
            default: return ReportsHelper::showReports($request); break;
        }
    }

//    public function searchOrders(Request $request) {
//        $ordersRequests = Order::available()
//            ->with(
//                'status',
//                'ship_city',
//                'dest_city',
//                'payment_status',
//                'payment',
//                'order_items'
//            )
//            ->when($request->get('id'), function ($order) use ($request) {
//                return $order->where(function ($orderSubQ) use ($request) {
//                    return $orderSubQ->where('id', 'LIKE', '%' . $request->get('id') . '%')
//                        ->orWhere('cargo_number', 'LIKE', '%' . $request->get('id') . '%');
//                });
//            })
//            ->when($request->finished == 'true', function ($order) use ($request) {
//                return $order->whereHas('status', function ($type) {
//                    return $type->where('name', 'Закрыта');
//                });
//            })
//            ->when($request->finished != 'true' && $request->get('status'), function ($order) use ($request) {
//                return $order->where(function ($orderSubQ) use ($request) {
//                    return $orderSubQ->where('status_id', $request->get('status'))
//                        ->orWhere('cargo_status_id', $request->get('status'));
//                });
//            })
//            ->get();
//
//        $forwardingReceipts = ForwardingReceipt::where('user_id', Auth::id())
//            ->when($request->get('id'), function ($forwardingReceipt) use ($request) {
//                return $forwardingReceipt->where('number', 'LIKE', '%' . $request->get('id') . '%');
//            })
//            ->when($request->finished == 'true', function ($forwardingReceipt) use ($request) {
//                return $forwardingReceipt->whereHas('cargo_status', function ($type) {
//                    return $type->where('name', 'Груз выдан');
//                });
//            })
//            ->when($request->finished != 'true' && $request->get('status'), function ($order) use ($request) {
//                return $order->where('cargo_status_id', $request->get('status'));
//            })
//            ->get();
//
//        $orders = $ordersRequests->merge($forwardingReceipts)->sortByDesc('order_date');
//
//        return View::make('v1.partials.profile.orders')->with(compact('orders'))->render();
//    }

    public function showReportPage($id) {
        $order = Order::available()
            ->where('id', $id)
            ->with([
                'type',
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
        $counterpartyForms = OrderHelper::getCounterpartyForms();

        return View::make('v1.pages.profile.profile-inner.report-show-page')
            ->with(compact('order', 'userTypes', 'counterpartyForms'))
            ->render();
    }

    public function getDownloadDocumentsModal(Request $request) {
        if($request->get('type') === 'request') {
            $order = Order::available()
                ->where('id', $request->get('order_id'))
                ->first();
        } else {
            $order = ForwardingReceipt::where([
                ['user_id', Auth::id()],
                ['id', $request->get('order_id')]
            ])->first();
        }

        $documents = [];

        if(isset($order)) {
            $user_1c = $order->user->guid ?? null;
            $code1c = $order->code_1c;

            if(!empty($user_1c) && !empty($code1c)) {
                $response1c = \App\Http\Helpers\Api1CHelper::post(
                    'document_list',
                    [
                        "user_id" => $user_1c,
                        "document_id" => $code1c,
                        "type" => $request->get('type') === 'request' ? 3 : 2
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
        }

        if(!count($documents) && $order instanceof Order) {
            array_push($documents, [
                'id' => $order->id,
                'type' => 'site_request',
                'name' => "Заявка на перевозку № $order->id"
            ]);
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
            "document_id" => $document_id_1c,
            "type" => intval($document_type_id_1c),
            "empty_fields" => true
        ];
        if(!empty($user->guid)) {
            $send["user_id"] = $user->guid;
        }
        $document_type_id_1c_check = $document_type_id_1c;
        if($document_type_id_1c_check !== 'site_request') {
            $document_type_id_1c_check = intval($document_type_id_1c);
        }
        switch ($document_type_id_1c_check) {
            case 3:
                    // Заявка на экспедирование
                    if(!isset($send['user_id'])) {
                        return redirect(route('report-list'));
                        break;
                    }

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
                        'Заявка на перевозку № ' . $documentData['СайтИД'] . '.pdf');
                    if(isset($file['tempFile']) && isset($file['fileName'])) {
                        return response()->download($file['tempFile'], $file['fileName'])
                            ->deleteFileAfterSend(true);
                    }

                    break;

            case 'site_request':
                    $order = Order::available()
                        ->where('id', $send['document_id'])
                        ->firstOrFail();

                    $documentData = OrderHelper::orderToPdfData($order);

                    $file = DocumentHelper::generateRequestDocument(
                        $documentData,
                        'Заявка на перевозку № ' . $send['document_id'] . '.pdf');
                    if(isset($file['tempFile']) && isset($file['fileName'])) {
                        return response()->download($file['tempFile'], $file['fileName'])
                            ->deleteFileAfterSend(true);
                    }


                    break;

            case 2:
            case 5:
            case 6:
                    if(!isset($send['user_id'])) {
                        return redirect(route('report-list'));
                        break;
                    }

                    $documentName = json_decode($request->get('document_name'));
                    $documentName = str_replace('/', '.', $documentName);
                    $documentName = empty($documentName) ? "doc.pdf" : "$documentName.pdf";

                    $result = Api1CHelper::getPdf(
                        'print_form',
                        $send
                    );

                    if($result) {
                        header('Cache-Control: public');
                        header('Content-type: application/pdf');
                        header("Content-Disposition: attachment; filename=\"$documentName\"");
                        header('Content-Length: '.strlen($result));

                        return $result;
                    }

                    break;

            default: break;
        }

        return redirect(route('report-list'));
    }
}
