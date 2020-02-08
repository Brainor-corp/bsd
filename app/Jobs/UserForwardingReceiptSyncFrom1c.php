<?php

namespace App\Jobs;

use App\ForwardingReceipt;
use App\Http\Helpers\Api1CHelper;
use App\Type;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserForwardingReceiptSyncFrom1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 10;

    private $user, $document;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $document
     */
    public function __construct($user, $document)
    {
        $this->user = $user;
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $document = $this->document;

        if(!ForwardingReceipt::where('code_1c', $document['id'])->exists()) {
            $response1c = Api1CHelper::post(
                'document/id',
                [
                    "user_id" => $user->guid,
                    "document_id" => $document['id'],
                    "type" => 2,
                    "empty_fields" => true
                ],
                false,
                5
            );

            if($response1c['status'] == 200 && !empty($response1c['response'])) {
                $statusName = 'Не определён';
                if(isset($response1c['response']['Статус']) && !empty(trim($response1c['response']['Статус']))) {
                    $statusName = $response1c['response']['Статус'];
                }

                $status = Type::firstOrCreate(
                    [
                        'class' => 'cargo_status',
                        'name' => $statusName
                    ]
                );

                $forwardingReceipt = new ForwardingReceipt();
                $forwardingReceipt->code_1c = $response1c['response']['УникальныйИдентификатор'] ?? '';
                $forwardingReceipt->number = $response1c['response']['Номер'] ?? "";
                $forwardingReceipt->cargo_status_id = $status->id;
                $forwardingReceipt->order_date = isset($response1c['response']['Дата']) ?
                    Carbon::parse($response1c['response']['Дата'])->format("Y-m-d") :
                    null;
                $forwardingReceipt->packages_count = intval($response1c['response']['КоличествоМест'] ?? 0);
                $forwardingReceipt->volume = floatval($response1c['response']['Объем'] ?? 0);
                $forwardingReceipt->weight = floatval($response1c['response']['Вес'] ?? 0);
                $forwardingReceipt->ship_city = $response1c['response']['ГородОтправления'] ?? "-";
                $forwardingReceipt->dest_city = $response1c['response']['ГородНазначения'] ?? "-";
                $forwardingReceipt->sender_name = $response1c['response']['Грузоотправитель'] ?? '-';
                $forwardingReceipt->recipient_name = $response1c['response']['Грузополучатель'] ?? '-';
                $forwardingReceipt->user_id = $user->id;

                $paymentStatusName = $response1c['response']['СтатусОплаты'] ?? '';
                if(!empty($paymentStatusName) && in_array($paymentStatusName, ['Оплачена', 'Не оплачена'])) {
                    $paymentStatus = Type::where([
                        ['class', 'OrderPaymentStatus'],
                        ['name', $paymentStatusName]
                    ])->firstOrFail();
                    $forwardingReceipt->payment_status_id = $paymentStatus->id;
                }

                $forwardingReceipt->save();
            }
        }
    }
}
