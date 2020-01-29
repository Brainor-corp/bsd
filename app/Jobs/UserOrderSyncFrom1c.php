<?php

namespace App\Jobs;

use App\City;
use App\Order;
use App\OrderItem;
use App\Type;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserOrderSyncFrom1c implements ShouldQueue
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
     * @throws \Exception
     */
    public function handle()
    {
        $user = $this->user;
        $document = $this->document;

        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/id',
            [
                "user_id" => $user->guid,
                "document_id" => $document['id'],
                "type" => 3,
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
                    'class' => 'order_status',
                    'name' => $statusName
                ]
            );

            $type = Type::where([
                ['class', 'OrderType'],
                ['slug', 'order']
            ])->first();

            $order = new Order();
            $order->shipping_name = $response1c['response']['Груз'] ?? "-";
            $order->type_id = $type->id;
            $order->cargo_type = isset($response1c['response']['Груз']) ? (Type::where([
                    ['class', 'cargo_type'],
                    ['name', $response1c['response']['Груз']]
                ])->first()->id ?? null) : null;
            $order->total_weight = floatval($response1c['response']['Вес'] ?? 0);
            $order->total_volume = floatval($response1c['response']['Объем'] ?? 0);
            $order->status_id = $status->id;
            $order->cargo_number = $response1c['response']['Номер'] ?? "";
            $order->ship_city_id = City::where('name', $response1c['response']['ГородОтправления'] ?? "-")->first()->id ?? null;
            $order->ship_city_name = $response1c['response']['ГородОтправления'] ?? "-";
            $order->dest_city_id = City::where('name', $response1c['response']['ГородНазначения'] ?? "-")->first()->id ?? null;
            $order->dest_city_name = $response1c['response']['ГородНазначения'] ?? "-";

            $order->take_address = $response1c['response']['АдресЗабора'] ?? "";
            if(isset($response1c['response']['АдресЗабора']) && !empty($response1c['response']['АдресЗабора'])) {
                $order->take_need = true;
            }

            $order->delivery_address = $response1c['response']['АдресДоставки'] ?? "";
            if(isset($response1c['response']['АдресДоставки']) && !empty($response1c['response']['АдресДоставки'])) {
                $order->delivery_need = true;
            }

            $order->order_date = isset($response1c['response']['ДатаИсполнения']) ?
                Carbon::parse($response1c['response']['ДатаИсполнения'])->format("Y-m-d H:i:s") :
                Carbon::now();

            $order->total_price = $response1c['response']['Итоговая_цена'] ?? 0;
            $order->base_price = 0; // todo Нет в API?
            $order->insurance = 0; // todo Нет в API?
            $order->insurance_amount = 0; // todo Нет в API?

            $order->user_id = $user->id;
            $order->code_1c = $response1c['response']['УникальныйИдентификатор'] ?? '';

            $paymentStatusName = $response1c['response']['СтатусОплаты'] ?? '';
            if(!empty($paymentStatusName) && in_array($paymentStatusName, ['Оплачена', 'Не оплачена'])) {
                $paymentStatus = Type::where([
                    ['class', 'OrderPaymentStatus'],
                    ['name', $paymentStatusName]
                ])->firstOrFail();

                $order->payment_status_id = $paymentStatus->id;
            }

            $paymentTypeName = $response1c['response']['ФормаОплаты'] ?? '';
            if(!empty($paymentTypeName) && in_array($paymentTypeName, ['Наличная', 'Безналичная'])) {
                $paymentType = Type::where([
                    ['class', 'payment_type'],
                    ['slug', $paymentTypeName === 'Наличная' ? 'nalichnyy-raschet' : 'beznalichnyy-raschet']
                ])->firstOrFail();

                $order->payment_type = $paymentType->id;
            }

            $order->sync_need = false;

            $order->save();

            $packages = [];
            if(isset($response1c['response']['Места']) && count($response1c['response']['Места'])) {
                foreach($response1c['response']['Места'] as $package) {
                    $packages[] = new OrderItem([
                        'length' => floatval($package['Длина']  ?? 0),
                        'width' => floatval($package['Ширина']  ?? 0),
                        'height' => floatval($package['Высота']  ?? 0),
                        'volume' => floatval($package['Объем']  ?? 0),
                        'weight' => floatval($package['Вес']  ?? 0),
                        'quantity' => intval($package['Количество']  ?? 0),
                    ]);
                }
            }

            $order->order_items()->saveMany($packages);
        }
    }
}
