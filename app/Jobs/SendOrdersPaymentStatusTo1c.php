<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrdersPaymentStatusTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('payment_sync_need', true)
            ->whereNotNull('code_1c')
            ->has('payment_status')
            ->whereHas('user', function ($userQ) {
                return $userQ->whereNotNull('guid');
            })
            ->with('payment_status', 'user')
            ->get();

        foreach($orders as $order) {
            SendOrderPaymentStatusTo1c::dispatch($order);
        }
    }
}
