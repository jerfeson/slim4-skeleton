<?php

namespace App\Payload;

/**
 * Interface PayloadInterface
 * Represents a JSON response.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
interface PayloadInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}
