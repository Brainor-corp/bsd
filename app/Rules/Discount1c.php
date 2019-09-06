<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class Discount1c implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $discount = 0;

        $user = Auth::user();
        if(isset($user->guid)) {
            $response1c = \App\Http\Helpers\Api1CHelper::post(
                'client/discount',
                [
                    "user_id" => $user->guid,
                ]
            );

            if($response1c['status'] === 200 && $response1c['response']['status'] === 'success') {
                $discount = $response1c['response']['result'];
            }
        }

        return $value == $discount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Некорректное значение скидки';
    }
}
