<?php

namespace App\Http\Helpers;

class Api1CHelper {
    private static $host = "http://s4.tkbsd.ru/copy/hs/rest/";

    public static function post($action, $params, $headersNeed = false) {
        $url = self::$host . $action;
        $content = json_encode($params);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        if($headersNeed) {
            curl_setopt($curl, CURLOPT_HEADER, 1);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($content))
        );

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = [
            'status' => $status,
            'response' => json_decode($json_response, true),
        ];

        if($headersNeed) {
            $headers = [];
            $output = rtrim($json_response);
            $data = explode("\n",$output);
            $headers['status'] = $data[0];
            array_shift($data);

            foreach($data as $part){
                $middle = explode(":",$part,2);
                if ( !isset($middle[1]) ) { $middle[1] = null; }
                $headers[trim($middle[0])] = trim($middle[1]);
            }

            $result['headers'] = $headers;
        }

        return $result;
    }

    public static function get($action, $params) {
        $url = self::$host . $action;

        $paramsQuery = '';
        foreach($params as $key => $value) {
            $paramsQuery .= $key . '=' . $value . '&';
        }

        $paramsQuery = trim($paramsQuery, '&');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url.'?'.$paramsQuery );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 7);
        curl_setopt($curl, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'status' => $status,
            'response' => json_decode($json_response, true)
        ];
    }

    public static function getPdf($action, $params) {
        $url = self::$host . $action;
        $content = json_encode($params);

        $curlConnect = curl_init();
        curl_setopt($curlConnect, CURLOPT_URL, $url);
        curl_setopt($curlConnect, CURLOPT_POST,   1);
        curl_setopt($curlConnect, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($curlConnect, CURLOPT_POSTFIELDS, $content);
        $result = curl_exec($curlConnect);

        return $result;
    }
}