<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\SendsPasswordResetSms;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

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
        if(!empty($request->get('phone'))) { // Восстановление пароля по СМС
            return self::sendResetSmsCode($request);
        } elseif(!empty($request->get('email'))) { // Восстановление пароля по номеру телефона
            return self::sendResetLinkEmail($request);
        }

        return redirect()->back()->withErrors(['error' => 'Укажите E-Mail или Телефон']);
    }


}
