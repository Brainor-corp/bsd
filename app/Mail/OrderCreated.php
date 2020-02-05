<?php

namespace App\Mail;

use App\Http\Helpers\DocumentHelper;
use App\Http\Helpers\OrderHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    private $order, $recipient;

    /**
     * Create a new message instance.
     *
     * @param $order
     * @param $recipient
     */
    public function __construct($order, $recipient)
    {
        $this->order = $order;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;

        $documentName = "Заявка на перевозку № $order->id.pdf";
        $documentData = OrderHelper::orderToPdfData($order);

        $file = DocumentHelper::generateRequestDocument(
            $documentData,
            $documentName,
            $this->recipient
        );

        return $this
            ->subject("Заявка №$order->id от $order->created_at")
            ->view('emails.order-created')
            ->attach($file['tempFile'], [
                'as' => $documentName,
                'mime' => 'application/pdf',
            ])
            ->with([
                'order' => $this->order,
                'tempFile' => $file['tempFile']
            ]);
    }
}
