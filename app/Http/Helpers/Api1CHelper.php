<?php

namespace App\Http\Helpers;

class Api1CHelper {
    public static function post($action, $params, $headersNeed = false, $timeout = 0) {
        $host = env('1C_HOST');

        $url = $host . $action;
        $content = json_encode($params, JSON_UNESCAPED_UNICODE);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_USERPWD, env("1C_BASIC_USER") . ":" . env("1C_BASIC_PASSWORD"));

        if($headersNeed) {
            curl_setopt($curl, CURLOPT_HEADER, 1);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=UTF-8',
                'Content-Length: ' . strlen($content))
        );
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout + 5);

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
        $host = env('1C_HOST');

        $url = $host . $action;

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
        $host = env('1C_HOST');

        $url = $host . $action;
        $content = json_encode($params, JSON_UNESCAPED_UNICODE);

        $curlConnect = curl_init();
        curl_setopt($curlConnect, CURLOPT_URL, $url);
        curl_setopt($curlConnect, CURLOPT_POST,   1);
        curl_setopt($curlConnect, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($curlConnect, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curlConnect, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlConnect, CURLOPT_USERPWD, env("1C_BASIC_USER") . ":" . env("1C_BASIC_PASSWORD"));
        $result = curl_exec($curlConnect);

        if (!curl_errno($curlConnect)) {
            switch ($http_code = curl_getinfo($curlConnect, CURLINFO_HTTP_CODE)) {
                case 200:
                    return $result;
                    break;

                default:
                    break;
            }
        }

        return false;
    }
}
