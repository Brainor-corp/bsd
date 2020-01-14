<?php

namespace App\Rules;

use App\Http\Helpers\GCaptchaRequestHelper;
use Illuminate\Contracts\Validation\Rule;

class GoogleReCaptchaV2 implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value)
    {
        try{
            $gResponse = GCaptchaRequestHelper::sendRequest($value);
        }
        catch (\Exception $exception){
            return true;
        }

        return $gResponse->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Подтвердите, что Вы не робот.';
    }
}
