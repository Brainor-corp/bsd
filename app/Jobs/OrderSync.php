<?php

namespace App\Jobs;

use App\Http\Helpers\Api1CHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OrderSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $order;

    /**
     * Create a new job instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $order = $this->order;

        $response1c = Api1CHelper::post('create_order', $order);
        if(
            $response1c['status'] == 200 &&
            $response1c['response']['status'] === 'success' &&
            !empty($response1c['response']['id'])
        ) {
            DB::table('orders')->where('id', $order['Идентификатор_на_сайте'])->update([
                'code_1c' => $response1c['response']['id'],
                'sync_need' => false
            ]);
        } else {
            // Тригерим ошибку, чтобы job с неудачным заказом упал в failed jobs
            throw new \Exception("Заказ " . $order['Идентификатор_на_сайте'] . " не обработан.");
        }
    }
}
