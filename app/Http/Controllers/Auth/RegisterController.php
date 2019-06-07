<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SMSHelper;
use App\Rules\GoogleReCaptchaV3;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/?cn=1'; // Покажем окно подтверждения телефона

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone'] = str_replace(array('+', ' ', '(' , ')', '-'), '', $data['phone']);

        return Validator::make($data, [
            'gToken' => ['required', new GoogleReCaptchaV3()],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8', // По тз: Не менее 8 символов
                'confirmed',
                'regex:/[a-zA-Zа-яА-Я]/', // По тз: Как минимум одна буква
                'regex:/[0-9]/' // По тз: Как минимум одна цифра
            ],
            'phone' => ['required', 'unique:users', 'regex:/\d{11}/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['phone'] = str_replace(array('+', ' ', '(' , ')', '-'), '', $data['phone']);
        $smsCode = rand(100000, 999999);

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'phone_verification_code' => $smsCode,
            'code_send_at' => Carbon::now(),
            'verified' => 0,
        ]);

        SMSHelper::sendSms($user->phone, strval($smsCode));

        return $user;
    }
}
