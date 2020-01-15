<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\SendsPasswordResetSms;
use App\Rules\GoogleReCaptchaV2;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    use SendsPasswordResetSms;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetMethodRedirect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()]
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if(!empty($request->get('email'))) { // Восстановление пароля по EMAIL
            return self::sendResetLinkEmail($request);
        } elseif(!empty($request->get('phone'))) { // Восстановление пароля по СМС
            return self::sendResetSmsCode($request);
        }

        return redirect()->back()->withErrors(['error' => 'Укажите E-Mail или Телефон']);
    }


}
