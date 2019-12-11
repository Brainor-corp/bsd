<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\OrderSyncTo1c;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderSendTo1c
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        dispatch(new OrderSyncTo1c($order));
    }
}
