<?php

namespace App\Helpers;


class CryptoHelper
{

    public static function encrypt($value)
    {
        $secretKey = env('SECRET_KEY');
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt(
            $value,
            'aes-256-cbc',
            $secretKey,
            0,
            $iv
        );

        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($value)
    {
        $secretKey = env('SECRET_KEY');
        $data = base64_decode($value);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt(
            $encrypted,
            'aes-256-cbc',
            $secretKey,
            0,
            $iv
        );
    }
}
