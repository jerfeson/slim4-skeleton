<?php

namespace App\Message;

use App\Helpers\Dynamic;
use Slim\Flash\Messages;

/**
 * Class Message.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 *
 * @method Message CANNOT_DELETE
 */
class Message
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    /*COMMON MESSAGES*/
    public const SUCCESSFUL_OPERATION = 'Operation performed successfully';
    public const REGISTER_NOT_FOUND = 'Register not found';
    public const MODEL_CLASS_NOT_DEFINED = 'Model class not defined';
    public const REPOSITORY_CLASS_NOT_DEFINED = 'Repository class not defined';
    public const BUSINESS_CLASS_NOT_DEFINED = 'Business class not defined';
    public const PUBLIC_KEYS_NOT_DEFINED = 'Public oAuth2  key have not been defined';
    public const DUPLICATE = 'The data has already been entered into our database';
    public const NO_RESULTS = 'No results found';
    public const ID_NOT_INFORMED = 'Id was not informed';

    /*Authentication messages*/
    public const ACCESS_DENIED = 'Access denied';
    public const LOGIN_SUCCESSFUL = 'Login Successful';

    /*Default messages*/
    public const UNKNOWN_ERROR = 'An unknown error has occurred, contact your system administrator';
    public const CANNOT_DELETE = "You cannot delete this record, you need to first remove the %s";


    /**
     * @param Messages $messages
     *
     * @return Dynamic
     */
    public static function getMessage(Messages $messages)
    {
        $message = '';

        if ($messages->hasMessage(self::STATUS_SUCCESS)) {
            $message = new Dynamic();

            $message->class = 'success';
            $message->icon = 'confetti';
            $message->message = $messages->getFirstMessage(self::STATUS_SUCCESS);
        } elseif ($messages->hasMessage(self::STATUS_ERROR)) {
            $message = new Dynamic();

            $message->class = 'danger';
            $message->icon = 'danger';
            $message->message = $messages->getFirstMessage(self::STATUS_ERROR);
        }

        return $message;
    }

    /**
     * @param $name
     * @param $arguments
     * @return int
     */
    public static function __callStatic($name, $arguments)
    {
        $message = constant("self::$name");
        return vsprintf($message, $arguments);
    }
}
