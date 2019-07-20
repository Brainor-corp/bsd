<?php

namespace App\Jobs;

use App\City;
use App\Order;
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
            ]
        );

        if($response1c['status'] == 200 && !empty($response1c['response'])) {
            $order = new Order();
            $order->shipping_name = $response1c['response']['Груз'] ?? "-";
            $order->cargo_type = isset($response1c['response']['Груз']) ? (Type::where([
                    ['class', 'cargo_type'],
                    ['name', $response1c['response']['Груз']]
                ])->first()->id ?? null) : null;
            $order->total_weight = floatval($response1c['response']['Вес'] ?? 0);
            $order->status_id = Type::where([
                    ['class', 'order_status'],
                    ['name', $response1c['response']['Статус']] ?? 'Не определён' // todo Добавить статус
                ])->first()->id ?? null;
            $order->ship_city_id = City::where('name', $response1c['response']['ГородОтправления'] ?? "-")->first()->id ?? null;
            $order->ship_city_name = $response1c['response']['ГородОтправления'] ?? "-";
            $order->dest_city_id = City::where('name', $response1c['response']['ГородНазначения'] ?? "-")->first()->id ?? null;
            $order->dest_city_name = $response1c['response']['ГородНазначения'] ?? "-";
            $order->take_address = $response1c['response']['АдресЗабора'] ?? "";
            $order->take_address = $response1c['response']['АдресЗабора'] ?? "";
            $order->delivery_address = $response1c['response']['АдресДоставки'] ?? "";
            $order->order_date = isset($response1c['response']['ДатаИсполнения']) ?
                Carbon::createFromFormat("d.m.Y h:i:s", $response1c['response']['ДатаИсполнения'])->format("") :
                null;

            $order->total_price = 0; // todo Нет в API?
            $order->base_price = 0; // todo Нет в API?
            $order->insurance = 0; // todo Нет в API?
            $order->insurance_amount = 0; // todo Нет в API?

            $order->user_id = $user->id;
            $order->code_1c = $response1c['response']['УникальныйИдентификатор'] ?? '';

            $order->sync_need = false;

            $order->save();
        } else {
            if($response1c['status'] != 200) {
                // Тригерим ошибку, чтобы job с неудачным заказом упал в failed jobs
                throw new \Exception(
                    "Для пользователя " . $user->guid . " не удалось получить информацию о заказе (Api вернуло ошибку) " . $document['id']
                );
            }

            if(empty($response1c['response'])) {
                // Тригерим ошибку, чтобы job с неудачным заказом упал в failed jobs
                throw new \Exception(
                    "Для пользователя " . $user->guid . " не удалось получить информацию о заказе (Api не вернуло данные) " . $document['id']
                );
            }
        }
    }
}
