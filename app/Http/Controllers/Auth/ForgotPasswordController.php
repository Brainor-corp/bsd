<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SMSHelper;
use App\PasswordResetsPhone;
use Carbon\Carbon;
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

    private $smsCodeLifeTime = 20; // Время жизни кода СМС (в минутах)
    private $sendTimeLimit = 3; // Минимальный срок для повторной отправки СМС
    private $sendTimeLimitText = "Отправка повторного СМС на указанный номер будет доступна через: ";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetSmsCode(Request $request) {
        $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->phone)]);
        $validator = $this->validatePhone($request);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // Пробуем найти существующий запрос на восстановление пароля по указанному номеру
        $existsPRP = PasswordResetsPhone::where('phone', $request->get('phone'))->first();
        if(isset($existsPRP)) { // Если нашли
            $now = Carbon::now();

            // Если с существующего запроса не прошло времени, требуемого для повторной отправки СМС
            if($now->lt($existsPRP->created_at->addMinutes($this->sendTimeLimit))) {
                $timeLimit = Carbon::now()->diffForHumans($existsPRP->created_at->addMinutes($this->sendTimeLimit), true);

                // Говорим пользователю, чтобы подождал
                return redirect()->back()->withErrors(['error' => $this->sendTimeLimitText . $timeLimit])->withInput();
            }

            // Если уже можно отправить новый код СМС, удаляем существующий запрос
            $existsPRP->delete();
        }

        // Генерируем и отправляем код по СМС.
        // Добавляем запрос в базу.
        $smsCode = rand(100000, 999999);
        $passwordResetPhoneRow = new PasswordResetsPhone;
        $passwordResetPhoneRow->phone = $request->get('phone');
        $passwordResetPhoneRow->code = $smsCode;
        $passwordResetPhoneRow->token = md5(rand(1, 10) . microtime());
        $passwordResetPhoneRow->save();

        SMSHelper::sendSms($request->get('phone'), $passwordResetPhoneRow->code);

        // Направляем пользователя на страницу с вводом кода подтверждения.
        return redirect(route('restore-phone-confirm', ['phone' => $passwordResetPhoneRow->phone]));
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

    protected function validatePhone(Request $request)
    {
        $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('phone'))]);

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/\d{11}/', 'exists:users,phone'],
        ]);

        return $validator;
    }

    public function restorePhoneConfirmShow($request) {
        $phone = $request->get('phone');

        if(
            empty($phone) ||
            !PasswordResetsPhone::where('phone', $phone)->exists()
        ) {
            return abort(404);
        }

        return view('')->with(compact('phone'));
    }
}
