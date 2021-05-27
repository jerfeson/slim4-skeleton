<?php


namespace App\Helpers\Payload;

use App\Enum\HttpStatusCode;
use JsonSerializable;

/**
 * Class Payload
 *
 * @package App\Helpers\Payload
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 */
class Payload implements JsonSerializable
{

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    /**
     * @var Error|null
     */
    private $error;

    /**
     * @param int $statusCode
     * @param array|object|null $data
     * @param Error|null $error
     */
    public function __construct(
        int $statusCode = 200,
        $data = null,
        ?Error $error = null
    )
    {
        $this->statusCode = $statusCode ? $statusCode : HttpStatusCode::INTERNAL_SERVER_ERROR;
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return Error|null
     */
    public function getError(): ?Error
    {
        return $this->error;
    }

    public function jsonSerialize()
    {
        $payload = [];

        if ($this->data !== null) {
            $payload = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
        }

        return $payload;
    }
}