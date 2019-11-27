<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class INN implements Rule
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
     */
    public function passes($attribute, $value)
    {
        if (preg_match('/\D/', $value)) return false;

        $value = (string)$value;
        $len = strlen($value);

        if ($len === 10) {
            return $value[9] === (string)(((
                            2 * $value[0] + 4 * $value[1] + 10 * $value[2] +
                            3 * $value[3] + 5 * $value[4] + 9 * $value[5] +
                            4 * $value[6] + 6 * $value[7] + 8 * $value[8]
                        ) % 11) % 10);
        } elseif ($len === 12) {
            $num10 = (string)(((
                        7 * $value[0] + 2 * $value[1] + 4 * $value[2] +
                        10 * $value[3] + 3 * $value[4] + 5 * $value[5] +
                        9 * $value[6] + 4 * $value[7] + 6 * $value[8] +
                        8 * $value[9]
                    ) % 11) % 10);

            $num11 = (string)(((
                        3 * $value[0] + 7 * $value[1] + 2 * $value[2] +
                        4 * $value[3] + 10 * $value[4] + 3 * $value[5] +
                        5 * $value[6] + 9 * $value[7] + 4 * $value[8] +
                        6 * $value[9] + 8 * $value[10]
                    ) % 11) % 10);

            return $value[11] === $num11 && $value[10] === $num10;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Введён некорректный ИНН контрагента (:attribute)';
    }
}
