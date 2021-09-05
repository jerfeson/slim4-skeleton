<?php

namespace App\Helpers;

/**
 * Class Crypto.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Crypto
{
    /**
     * crypto_rand_secure($min, $max) works as a drop in replacement for rand() or mt_rand.
     * It uses openssl_random_pseudo_bytes to help create a random number between $min and $max.
     *
     * @param $min
     * @param $max
     *
     * @return int
     */
    public static function randSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        } // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1;    // length in bytes
        $bits = (int)$log + 1;           // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }

    /**
     * Creates an alphabet to use within the token and then creates a string of length $length.
     *
     * @param $length
     *
     * @return string
     */
    public static function getToken($length)
    {
        $token = '';
        $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
        $codeAlphabet .= '0123456789';
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::randSecure(0, $max - 1)];
        }

        return $token;
    }
}
