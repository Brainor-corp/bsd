<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 25.03.2019
 * Time: 14:05
 */

namespace App\Http\Helpers;

class GCaptchaRequestHelper
{
    public static function sendRequest($userToken) {
        $secret = env('V2_GOOGLE_CAPTCHA_SECRET');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "secret={$secret}&response={$userToken}");

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
             throw new \Exception("cURL Error #:" . $err);
        } else {
            return json_decode($response);
        }
    }
}
