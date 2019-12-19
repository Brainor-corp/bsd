<?php

namespace App\Jobs;

use App\Http\Helpers\Api1CHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendOrderPaymentStatusTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

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
        $data = [
            'order_id' => $order->code_1c,
            'user_id' => $order->user->guid ?? '',
            'status' => $order->payment_status->name ?? ''
        ];

        $response1c = Api1CHelper::post('order/update_payment_status', $data);
        if(
            $response1c['status'] == 200 &&
            $response1c['response']['status'] === 'success'
        ) {
            DB::table('orders')->where('id', $order->id)->update([
                'payment_sync_need' => false
            ]);
        } else
        {
            throw new \Exception(print_r($response1c, true));
        }
    }
}
