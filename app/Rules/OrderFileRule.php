<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OrderFileRule implements Rule
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
        return file_exists(public_path($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Схема проезда не загружена. Пожалуйста, попробуйте снова.';
    }
}
