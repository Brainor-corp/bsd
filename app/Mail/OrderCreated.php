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

        $mail = $this
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

        if(!empty($order->take_driving_directions_file) && file_exists(public_path($order->take_driving_directions_file))) {
            $ext = pathinfo($order->take_driving_directions_file, PATHINFO_EXTENSION);
            $mail->attach(public_path($order->take_driving_directions_file), ['as' => "Схема проезда для забора груза.$ext"]);
        }

        if(!empty($order->delivery_driving_directions_file && file_exists(public_path($order->delivery_driving_directions_file)))) {
            $ext = pathinfo($order->delivery_driving_directions_file, PATHINFO_EXTENSION);
            $mail->attach(public_path($order->delivery_driving_directions_file), ['as' => "Схема проезда для доставки груза.$ext"]);
        }

        return $mail;
    }
}
