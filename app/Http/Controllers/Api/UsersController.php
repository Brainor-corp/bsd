<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\User1CRegister;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createUser(Request $request) {
        $request->merge(['tel' => $request->get('tel')]);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'tel' => ['required', 'unique:users,phone', 'numeric', 'digits:11'],
            'id' => ['required', 'unique:users,guid'],
        ]);

        if ($validator->fails()) {
            return response(
                [
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ],
                400
            );
        }

        $password = $this->generateRandomString();

        $user = new User;

        $user->guid = $request->get('id');
        $user->sync_need = false;
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->phone = strval($request->get('tel'));
        $user->verified = true;
        $user->email_verified_at = Carbon::now();
        $user->need_password_reset = true;
        $user->password = Hash::make($password);

        $user->save();

        try {
            Mail::to($user->email)->send(new User1CRegister($user, $password));
        } catch (\Exception $e) {}

        return [
            'status' => 'success'
        ];
    }
}
