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

    public const RESOURCE_NOT_FOUND = 'Resource not found';
    public const METHOD_NOT_ALLOWED = 'Action not allowed';
    public const UNAUTHORIZED = 'Unauthorized User';
    public const FORBIDDEN = 'User without access permission';
    public const BAD_REQUEST = 'Invalid request';
    public const INTERNAL_ERROR = 'An internal error occurred while processing your request.';
    public const VALIDATION_FAILED = 'Validation failed';

    /**
     * @param $name
     * @param $arguments
     *
     * @return string
     */
    public static function __callStatic($name, $arguments): string
    {
        $message = constant("self::{$name}");

        return vsprintf($message, $arguments);
    }

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
}
