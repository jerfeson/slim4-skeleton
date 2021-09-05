<?php

namespace App\Exception;

use App\Message\Message;
use Exception;

/**
 * Class ValidationException.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ValidationException extends Exception
{
    private array $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
        parent::__construct(Message::VALIDATION_FAILED);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
