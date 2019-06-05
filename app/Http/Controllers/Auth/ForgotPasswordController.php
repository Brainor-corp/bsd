<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SMSHelper;
use App\PasswordResetsPhone;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

    // Время жизни кода СМС (в минутах)
    private $smsCodeLifeTime = 15;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        if(!empty($request->get('phone'))) { // Восстановление пароля по СМС
            $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->phone)]);
            $validator = $this->validatePhone($request);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            PasswordResetsPhone::where('phone', $request->get('phone'))->delete();

            $smsCode = rand(100000, 999999);
            $passwordResetPhone = new PasswordResetsPhone;
            $passwordResetPhone->phone = $request->get('phone');
            $passwordResetPhone->code = $smsCode;
            $passwordResetPhone->save();

            // todo Проверка времени (3 мин)
            SMSHelper::sendSms($request->get('phone'), $smsCode);

            return redirect(route(''));
        } elseif(!empty($request->get('email'))) { // Восстановление пароля по номеру телефона
            $this->validateEmail($request);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        }

        return redirect()->back()->withErrors(['error' => 'Укажите E-Mail или Телефон']);
    }

    protected function validatePhone(Request $request)
    {
        $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('phone'))]);

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/\d{11}/', 'exists:users,phone'],
        ]);

        return $validator;
    }
}
