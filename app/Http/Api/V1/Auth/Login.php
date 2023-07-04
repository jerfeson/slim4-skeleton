<?php

namespace App\Http\Api\v1\Auth;

use App\Http\Api\ApiController;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Login.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class Login extends ApiController
{
    /**
     * @return ResponseInterface
     */
    public function postAction(): ResponseInterface
    {
        return $this->getResponse()->withStatus(200);
    }
}
