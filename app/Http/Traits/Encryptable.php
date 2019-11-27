<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 28.08.2019
 * Time: 10:35
 */

namespace App\Http\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if(empty($value)) {
            return $value;
        }

        if(in_array($key, $this->encryptable)) {
            $value = Crypt::decryptString($value);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if(in_array($key, $this->encryptable)) {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }
}