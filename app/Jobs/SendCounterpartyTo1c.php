<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCounterpartyTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $counterparty;

    /**
     * Create a new job instance.
     *
     * @param $counterparty
     */
    public function __construct($counterparty)
    {
        $this->counterparty = $counterparty;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $counterparty = $this->counterparty;

        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'new_client',
            [
                "ПравоваяФорма" => $counterparty->legal_form ?? '',
                "НаименованиеПолное" => $counterparty->company_name ?? '',
                "ЮридическоеФизическоеЛицо" => "ФизическоеЛицо",
                "ИНН" => $counterparty->inn ?? '',
                "КПП" => $counterparty->kpp ?? '',
                "ДокументУдостоверяющийЛичность" => "$counterparty->passport_series $counterparty->passport_number",
                "ОсновноеКонтактноеЛицо" => $counterparty->contact_person ?? '',
                "Комментарий" => $counterparty->addition_info ?? '',
                "ДатаСоздания" => $counterparty->created_at->format('Y-m-d\TH:i:s'),
                "ТелефонЗначениеJSON" => [
                    "type" => "Телефон",
                    "value" => intval($counterparty->phone),
                    "CountryCode" => "",
                    "AreaCode" => "",
                    "Number" => $counterparty->phone,
                    "НомерТелефонаБезКодов" => mb_substr($counterparty->phone, -7)
                ],
                "ЮридическийАдресЗначениеJSON" => [
                    "type" => "Адрес",
                    "value" => "$counterparty->legal_address_city, $counterparty->legal_address_street, д. $counterparty->legal_address_house, корп. $counterparty->legal_address_block, стр. $counterparty->legal_address_building, кв. $counterparty->legal_address_apartment",
                    "Страна" => "РОССИЯ",
                    "Город" => $counterparty->legal_address_city ?? "",
                    "НомерТелефона" => "",
                    "НомерТелефонаБезКодов" => ""
                ],
            ]
        );

        if(isset($response1c['response']['status']) && $response1c['response']['status'] === "success") {
            $counterparty->code_1c = $response1c['response']['id'];
            $counterparty->save();
        } else {
            throw new \Exception(print_r($response1c, 1));
        }
    }
}
