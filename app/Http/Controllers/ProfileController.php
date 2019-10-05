<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {

    private $smsTimeLimit = 3; // минимальный период до повторной отправки сми (в минутах)
    protected $sendTimeLimitText = "Отправка повторного СМС на указанный номер будет доступна через: ";

    public function profileData() {
	    $user = User::whereId(Auth::user()->id)->firstOrFail();
        $showPassResetMsg = null;

    	if(isset($user) && $user->need_password_reset){
    		$user->need_password_reset = false;
    		$user->update();
		    $showPassResetMsg = true;
	    }

	    return view('v1.pages.profile.profile-data.profile-data')->with(compact('showPassResetMsg'));
    }

    public function edit(Request $request) {
        $user = User::where('id', Auth::user()->id)->firstOrFail();
        $currentPhone = $user->phone;
        $request->merge(['phone' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->phone)]);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'patronomic' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,$user->id"],
            'password' => [
                'nullable',
                'string',
                'min:8', // По тз: Не менее 8 символов
                'confirmed',
                'regex:/[a-zA-Zа-яА-Я]/', // По тз: Как минимум одна буква
                'regex:/[0-9]/' // По тз: Как минимум одна цифра
            ],
            'phone' => ['required', "unique:users,phone,$user->id", 'regex:/\d{11}/'],
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }

        $redirectData = [];
        $toUpdate = [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronomic' => $request->patronomic,
            'email' => $request->email,
        ];

        if(!empty($request->password)) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['Старый пароль введен неверно']);
            }

            $toUpdate['password'] = Hash::make($request->password);
        }

        $errors = [];
        // Если поменялся номер телефона, то вышлем код повторно
        // и пометим пользователя как неподтвержденного
        if($currentPhone != $request->phone) {
            $now = Carbon::now();
            if(!empty($user->code_send_at) && $now->lt($user->code_send_at->addMinutes($this->smsTimeLimit))) {
                $timeLimit = $now->diffForHumans($user->code_send_at->addMinutes($this->smsTimeLimit), true);

                $errors[] = "Сменить номер телефона можно будет через: $timeLimit";
            } else {
                $smsCode = rand(100000, 999999);

                $toUpdate['phone_verification_code'] = $smsCode;
                $toUpdate['phone'] = $request->phone;
                $toUpdate['code_send_at'] = $now;
                $toUpdate['verified'] = 0;

                SMSHelper::sendSms($request->phone, strval($smsCode));
                $redirectData = ['cn=1'];
            }
        }

        $user->update($toUpdate);

        return redirect(route('profile-data-show', $redirectData))
            ->withSuccess('Данные успешно обновлены')
            ->withErrors($errors);
    }

    public function phoneConfirm(Request $request) {
        $user = User::where('id', Auth::user()->id)->firstOrFail();

        if($user->verified) {
            return redirect()->back();
        }

        if($user->phone_verification_code == $request->get('code')) {
            $user->verified = true;
            $user->save();
            return redirect()->back();
        }

        return redirect(route('index', ['cn=1']))->withErrors(["code" => ["Код подтверждения ввёден неверно"]]);
    }

    public function resendPhoneConfirmCode() {
        $user = User::where('id', Auth::user()->id)->firstOrFail();

        if($user->verified) {
            return redirect(route('index'));
        }

        $now = Carbon::now();

        if(!empty($user->code_send_at) && $now->lt($user->code_send_at->addMinutes($this->smsTimeLimit))) {
            $timeLimit = $now->diffForHumans($user->code_send_at->addMinutes($this->smsTimeLimit), true);

            return redirect(route('index', ['cn=1']))->withErrors(['code' => $this->sendTimeLimitText . $timeLimit]);
        }

        $user->phone_verification_code = rand(100000, 999999);
        $user->code_send_at = $now;
        $user->update();

        SMSHelper::sendSms($user->phone, $user->phone_verification_code);

        return redirect(route('index', ['cn=1']))->withSuccess("Код подтверждения отправлен повторно");
    }

    public function balancePageShow()
    {
        return view('v1.pages.profile.profile-data.profile-balance');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function balanceGet()
    {
        $user = Auth::user();

        if(isset($user->guid)) {
            $response1c = \App\Http\Helpers\Api1CHelper::post(
                'client/total',
                [
                    "user_id" => $user->guid,
                ]
            );
            if($response1c['response']['status'] == 'success') {
                return $response1c['response']['result'];
            }
        }

        throw new \Exception('Произошла ошибка. Обновите страницу или попробуйте позднее.');
    }

    public function contractPageShow()
    {
        return view('v1.pages.profile.profile-data.profile-contract');
    }

    public function contractDownload()
    {
        $user = Auth::user();

        if(isset($user->guid)) {
            $response1c = \App\Http\Helpers\Api1CHelper::post(
                'client/contract',
                [
                    "user_id" => $user->guid,
                ]
            );

            if(
                !empty($response1c['response']) &&
                !empty($response1c['response']['УникальныйИдентификатор']) &&
                !empty($response1c['response']['ЮридическоеФизическоеЛицо'])
            ) {
                $data = $response1c['response'];

                switch ($data['ЮридическоеФизическоеЛицо']) {
                    case 'Юридическое лицо':
                        $type = 'ur';
                        $data['ПостфиксПолаКонтрагента'] = 'его';
                        if(isset($data['ПолРуководителяКонтрагента']) && $data['ПолРуководителяКонтрагента'] == 'Женский') {
                            $data['ПостфиксПолаКонтрагента'] = 'ей';
                        }

                        break;
                    case 'Физическое лицо':
                        $type = 'fiz';

                        $data['ДокументУдостоверяющийЛичностьСерия'] = '';
                        $data['ДокументУдостоверяющийЛичностьНомер'] = '';

                        if(!empty($data['ДокументУдостоверяющийЛичность'])) {
                            $passportParts = explode(' ', $data['ДокументУдостоверяющийЛичность']);

                            $data['ДокументУдостоверяющийЛичностьСерия'] = $passportParts[0] ?? '';
                            $data['ДокументУдостоверяющийЛичностьНомер'] = $passportParts[1] ?? '';
                        }

                        break;

                    default: break;
                }

                $view = view("v1.pdf.contracts.contract-$type")
                    ->with(compact('data'))
                    ->render();

                // instantiate and use the dompdf class
                $options = new Options();
                $options->setIsRemoteEnabled(true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($view);

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4');

                // Render the HTML as PDF
                $dompdf->render();

                $font = $dompdf->getFontMetrics()->get_font("Times New Roman", "normal");
                $dompdf->getCanvas()->page_text(270, 14, "стр. {PAGE_NUM} из {PAGE_COUNT}", $font, 8, [0, 0, 0]);

                $output = $dompdf->output();
                $filename = "Договор";
                $path = storage_path() . '/' . md5($filename. time());;
                file_put_contents($path, $output);

                return response()->download($path, "$filename.pdf")
                        ->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->withErrors(['В данный момент генерация договора недоступна.']);
            }
        }
        return redirect()->back()->withErrors(['Произошла ошибка. Обновите страницу или попробуйте позднее.']);
    }
}
