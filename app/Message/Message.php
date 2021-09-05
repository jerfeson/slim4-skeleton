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
 * @version 3.0.0
 */
class Message
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    const RESOURCE_NOT_FOUND = 'Resource not found';
    const METHOD_NOT_ALLOWED = 'Action not allowed';
    const UNAUTHORIZED = 'Unauthorized User';
    const FORBIDDEN = 'User without access permission';
    const BAD_REQUEST = 'Invalid request';
    const INTERNAL_ERROR = 'An internal error occurred while processing your request.';
    const VALIDATION_FAILED = 'Validation failed';

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
     *
     * @return string
     */
    public static function __callStatic($name, $arguments): string
    {
        $message = constant("self::$name");

        return vsprintf($message, $arguments);
    }
}
