<?php

namespace App\Http\Controllers;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Models\BRPost;
use Bradmin\Cms\Models\BRTag;
use Bradmin\Cms\Models\BRTerm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Helpers\StreamSMSHelper;
use Illuminate\Support\Facades\DB;

class TestController extends Controller {
    public function test1(Request $request) {

//        $server = 'http://gateway.api.sc/rest/';
//        $login = 'tk_bsd';
//        $password = '7121364Andrey';
//
//        $sms =  new StreamSMSHelper();
//        $smsSession = $sms->GetSessionId_Post($server,$login,$password);
//
//        $sourceAddress = 'TK-BSD.COM';
//        $destinationAddress = '79054339913';
//        $data = 'proverka otpravki s sayta';
//
//        $smsSend = $sms->SendSms($server,$smsSession,$sourceAddress,$destinationAddress,$data,1440);
//
//        dd($smsSend);


        $fixed_tariff = DB::table('inside_forwarding')
            ->join('forward_thresholds', function($join)
            {
                $join->on('inside_forwarding.forward_threshold_id', '=', 'forward_thresholds.id');
            })
            ->where([
                ['city_id', 53],
                ['forward_thresholds.weight', '>=', 1],
                ['forward_thresholds.volume', '>=', 0.001],
                ['forward_thresholds.units', '>=', 0],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->orderBy('forward_thresholds.units', 'ASC')
            ->first();
        dd($fixed_tariff);
    }
}
