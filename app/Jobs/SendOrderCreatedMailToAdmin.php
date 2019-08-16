<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendOrderCreatedMailToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $recipient, $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recicpient, Order $order)
    {
        $this->recipient = $recicpient;
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
