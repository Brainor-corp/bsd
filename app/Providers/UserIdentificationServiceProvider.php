<?php

namespace App\Providers;

use Cookie;
use Illuminate\Support\ServiceProvider;

class UserIdentificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!isset($_COOKIE['enter_id'])) {
            function generateCode($length = 6)
            {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
                $code = "";
                $clen = strlen($chars) - 1;
                while (strlen($code) < $length) {
                    $code .= $chars[mt_rand(0, $clen)];
                }
                return $code;
            }

            $hash1 = generateCode(10);
            $hash2 = uniqid('_');
            $hash = '' . $hash1 . '' . $hash2 . '';
            setcookie("enter_id", $hash, time() + (10 * 365 * 24 * 60 * 60), "/");
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
