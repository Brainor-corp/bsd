<?php

namespace App\Http\Helpers;

class SMSHelper {

    public static function sendSms($phone, $text) {
        $server = 'http://gateway.api.sc/rest/';
        $login = env('SMS_SERVER_LOGIN');
        $password = env('SMS_SERVER_PASSWORD');

        $sms =  new StreamSMSHelper();
        $smsSession = $sms->GetSessionId_Post($server,$login,$password);

        $sourceAddress = 'TK-BSD.COM';
        $destinationAddress = $phone;

        try {
            $sms->SendSms(
                $server,
                $smsSession,
                $sourceAddress,
                $destinationAddress,
                $text,
                1440
            );
        } catch (\Exception $e) {}
    }

}