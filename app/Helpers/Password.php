<?php

namespace App\Helpers;

/**
 * README
 * Hash a password:.
 *
 *  $hash = password_hash('Correct Horse Battery Clip', PASSWORD_DEFAULT, ['cost' => 14]);
 *  Verify against an entered password (This example will not be verified)
 *  if (Password::verify('Correct Horse Battery Clip', $hash)) {
 *    echo 'Correct password!\n';
 *  } else {
 *  echo "Incorrect login attempt!\n";
 *  }
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 */
class Password
{
    /**
     * @param $password
     *
     * @return bool|string
     */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
    }

    /**
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
