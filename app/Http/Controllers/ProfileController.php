<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {

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
            'password' => ['nullable', 'string', 'min:8'],
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
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        if(!empty($request->password)) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['Старый пароль введен неверно']);
            }

            $toUpdate['password'] = Hash::make($request->password);
        }

        // Если поменялся номер телефона, то вышлем код повторно
        // и пометим пользователя как неподтвержденного
        if($currentPhone != $request->phone) {
            $smsCode = rand(100000, 999999);

            $toUpdate['phone_verification_code'] = $smsCode;
            $toUpdate['verified'] = 0;

            SMSHelper::sendSms($request->phone, strval($smsCode));
            $redirectData = ['cn=1'];
        }

        $user->update($toUpdate);

        return redirect(route('profile-data-show', $redirectData))->withSuccess('Данные успешно обновлены');
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

        $user->phone_verification_code = rand(100000, 999999);
        $user->update();

        SMSHelper::sendSms($user->phone, $user->phone_verification_code);

        return redirect(route('index', ['cn=1']))->withSuccess("Код подтверждения отправлен повторно");
    }

}
