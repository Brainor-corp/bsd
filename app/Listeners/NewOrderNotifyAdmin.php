<?php

namespace App\Listeners;

use App\ContactEmail;
use App\Events\OrderCreated;
use App\Jobs\SendOrderCreatedMailToAdmin;

class NewOrderNotifyAdmin
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

        foreach(ContactEmail::where('active', true)->get() as $email) {
            SendOrderCreatedMailToAdmin::dispatch($email->email, $order);
        }
    }
}
