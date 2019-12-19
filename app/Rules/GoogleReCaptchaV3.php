<?php

namespace App\Rules;

use App\Http\Helpers\GCaptchaRequestHelper;
use Illuminate\Contracts\Validation\Rule;
use Mockery\Exception;

class GoogleReCaptchaV3 implements Rule
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
        catch (Exception $exception){
            return false;
        }

        return $gResponse->success && $gResponse->score > config('app.g_captcha_min_score');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'При обработке запроса произошла ошибка';
    }
}
