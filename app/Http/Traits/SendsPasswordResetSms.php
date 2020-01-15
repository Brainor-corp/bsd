<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.06.2019
 * Time: 12:25
 */

namespace App\Http\Traits;

use App\Http\Helpers\SMSHelper;
use App\PasswordResetsPhone;
use App\Rules\GoogleReCaptchaV2;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait SendsPasswordResetSms
{
    protected $smsCodeLifeTime = 30; // Время жизни кода СМС (в минутах)
    protected $sendTimeLimit = 3; // Минимальный срок для повторной отправки СМС
    protected $sendTimeLimitText = "Отправка повторного СМС на указанный номер будет доступна через: ";

    protected function validatePhone(Request $request)
    {
        $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('phone'))]);

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/\d{11}/', 'exists:users,phone'],
        ]);

        return $validator;
    }

    protected function validatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8', // По тз: Не менее 8 символов
                'confirmed',
                'regex:/[a-zA-Zа-яА-Я]/', // По тз: Как минимум одна буква
                'regex:/[0-9]/' // По тз: Как минимум одна цифра
            ],
        ]);

        return $validator;
    }

    protected function sendResetSmsCode(Request $request) {
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
                // Направляем пользователя на страницу с вводом кода, который отправляли ранее
                return redirect(route('password.restore-phone-confirm', ['q' => Crypt::encrypt($existsPRP->phone)]));
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
        return redirect(route('password.restore-phone-confirm', ['q' => Crypt::encrypt($passwordResetPhoneRow->phone)]));
    }

    protected function restorePhoneConfirmShow(Request $request)
    {
        $encryptedPhone = $request->get('q');
        try {
            $phone = Crypt::decrypt($encryptedPhone);
        } catch (DecryptException $exception) {
            return abort(404);
        }

        if(!PasswordResetsPhone::where('phone', $phone)->exists()
        ) {
            return abort(404);
        }

        return view('auth.passwords.sms-confirm')
            ->with(compact('phone', 'encryptedPhone'));
    }

    protected function restorePhoneConfirmAction(Request $request) {
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()]
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $encryptedPhone = $request->get('q');

        try {
            $phone = Crypt::decrypt($encryptedPhone);
        } catch (DecryptException $exception) {
            return abort(404);
        }

        $passwordResetsPhoneRow = PasswordResetsPhone::where([
            ['phone', $phone],
            ['code', $request->get('code')]
        ])->first();

        if(!isset($passwordResetsPhoneRow)) {
            return redirect()->back()->withErrors(['error' => "Указанный код подтверждения недействителен."])->withInput();
        }

        return redirect(route('password.reset-by-phone', ['token' => $passwordResetsPhoneRow->token]));
    }

    protected function resetByPhonePage($token) {
        return view('auth.passwords.reset-by-phone')->with(compact('token'));
    }

    protected function resetByPhoneAction(Request $request) {
        $validator = self::validatePassword($request);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if(empty($request->get('token'))) {
            return abort(404);
        }

        $passwordResetsPhoneRow = PasswordResetsPhone::where('token', $request->get('token'))->first();
        if(
            !isset($passwordResetsPhoneRow) ||
            Carbon::now()->gt($passwordResetsPhoneRow->created_at->addMinutes($this->smsCodeLifeTime))
        ) {
            if(isset($passwordResetsPhoneRow)) {
                $passwordResetsPhoneRow->delete();
            }

            return redirect()->back()->withErrors(['error' => "Ссылка восстановления недействительна."])->withInput();
        }

        $user = User::where('phone', $passwordResetsPhoneRow->phone)->first();
        if(!isset($user)) {
            return redirect()->back()->withErrors(['error' => "Ссылка восстановления недействительна."])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->verified = true;
        $user->update();

        $passwordResetsPhoneRow->delete();

        return redirect(route('login'))->withSuccess('Пароль успешно изменён! Используйте его, чтобы войти в свой профиль.');
    }

    protected function resendSmsCode(Request $request) {
        $encryptedPhone = $request->get('q');

        try {
            $phone = Crypt::decrypt($encryptedPhone);
        } catch (DecryptException $exception) {
            return abort(404);
        }

        $existsPRP = PasswordResetsPhone::where('phone', $phone)->firstOrFail();
        $now = Carbon::now();

        // Если с существующего запроса не прошло времени, требуемого для повторной отправки СМС
        if($now->lt($existsPRP->created_at->addMinutes($this->sendTimeLimit))) {
            $timeLimit = $now->diffForHumans($existsPRP->created_at->addMinutes($this->sendTimeLimit), true);

            // Направляем пользователя на страницу с вводом кода, который отправляли ранее
            return redirect(route('password.restore-phone-confirm', ['q' => $encryptedPhone]))
                ->withErrors(['error' => $this->sendTimeLimitText . $timeLimit]);
        }

        $existsPRP->delete();

        // Генерируем и отправляем код по СМС.
        // Добавляем запрос в базу.
        $smsCode = rand(100000, 999999);
        $passwordResetPhoneRow = new PasswordResetsPhone;
        $passwordResetPhoneRow->phone = $phone;
        $passwordResetPhoneRow->code = $smsCode;
        $passwordResetPhoneRow->token = md5(rand(1, 10) . microtime());
        $passwordResetPhoneRow->save();

        SMSHelper::sendSms($phone, $passwordResetPhoneRow->code);

        // Направляем пользователя на страницу с вводом кода подтверждения.
        return redirect(route('password.restore-phone-confirm', ['q' => Crypt::encrypt($passwordResetPhoneRow->phone)]))
            ->withStatus('Код успешно отправлен повторно!');
    }
}
