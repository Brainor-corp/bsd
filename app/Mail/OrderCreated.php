<?php

namespace App\Mail;

use App\Http\Helpers\DocumentHelper;
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
        $documentData = [
            'Представление' => "Заявка на перевозку № $order->id",
            'ДатаИсполнения' => 'todo',
            'Груз' => $order->shipping_name,
            'Вес' => $order->total_weight,
            'Объем' => $order->total_volume,
            'КоличествоМест' => 'todo',
            'МягкаяУпаковка' => 'todo',
            'ЖесткаяУпаковка' => 'todo',
            'Паллетирование' => 'todo',
            'Сумма_страховки' => $order->insurance,
            'ГородОтправления' => $order->ship_city_name,
            'ОрганизацияОтправления' => 'todo',
            'ГрузоотправительТелефон' => $order->sender_phone,
            'АдресЗабора' => $order->take_address ?? "",
            'РежимРаботыСклада' => 'todo',
            'ГородНазначения' => $order->dest_city_name,
            'Грузополучатель' => '',
            'ГрузополучательТелефон' => $order->recipient_phone,
            'АдресДоставки' => $order->delivery_address ?? "",
            'КонтактноеЛицоГрузополучателя' => $order->recipient_contact_person ?? "",
            'АдресГрузополучателя' => $order->recipient_legal_address ?? "",
            'Плательщик' => 'todo',
            'ФормаОплаты' => $order->payment->name ?? "",
            'Заявку_заполнил' => $order->order_creator,
            'ПлательщикEmail' => $order->payer_email
        ];

        if(isset($order->recipient_type->name)) {
            if($order->recipient_type->slug === 'fizicheskoe-lico') {
                $documentData['Грузополучатель'] = $order->recipient_name;
            } else {
                $documentData['Грузополучатель'] = $order->recipient_company_name;
            }
        }

        foreach($order->order_items as $package) {
            $documentData['Места'][] = [
                'Длина' => $package->length,
                'Ширина' => $package->width,
                'Высота' => $package->height,
                'Объем' => $package->volume,
                'Вес' => $package->weight,
                'Количество' => $package->quantity,
            ];
        }

        $file = DocumentHelper::generateRequestDocument(
            $documentData,
            $documentName);

        return $this->view('emails.order-created')
            ->attach($file['tempFile'], [
                'as' => $documentName,
                'mime' => 'application/pdf',
            ])
            ->with(['order' => $this->order]);
    }
}
