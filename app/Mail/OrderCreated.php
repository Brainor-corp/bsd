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

    private $order;

    /**
     * Create a new message instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
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
            $documentName
        );

        return $this
            ->subject('Новая заявка на перевозку')
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
