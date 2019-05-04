<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller {

    public function profileData() {
	    $user = User::whereId(Auth::user()->id)->first();
        $showPassResetMsg = null;

    	if(isset($user) && $user->need_password_reset){
    		$user->need_password_reset = false;
    		$user->update();
		    $showPassResetMsg = true;
	    }
	    return view('v1.pages.profile.profile-data.profile-data')->with(compact('showPassResetMsg'));
    }

    public function edit(Request $request) {

        $user = User::where('id', Auth::user()->id)->first();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                User::where('id', $request->user_id)->update(
                    [
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'patronomic' => $request->patronomic,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]
                );
                $message = [
                    'type' => 'alert-success',
                    'data' => 'Данные успешно обновлены'
                ];
            }
            else {
                $message = [
                    'type' => 'alert-warning',
                    'data' => 'Старый пароль введен неверно'
                ];
            }
        }
        else {
            $message = [
                'type' => 'alert-warning',
                'data' => 'Пользователь с указанным ID не найден'
            ];
        }
        return redirect()->back()->with($message['type'], $message['data']);
    }

}
