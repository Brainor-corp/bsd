<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderCreatedMailToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $recipient, $order;

    /**
     * Create a new job instance.
     *
     * @param $recipient
     * @param Order $order
     */
    public function __construct($recipient, Order $order)
    {
        $this->recipient = $recipient;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->recipient)->send(new OrderCreated($this->order));

    }
}
