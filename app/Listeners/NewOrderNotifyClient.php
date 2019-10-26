<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendOrderCreatedMailToClient;

class NewOrderNotifyClient
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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        if(isset($order->user->email)) {
            SendOrderCreatedMailToClient::dispatch($order->user->email, $order);
        }
    }
}
