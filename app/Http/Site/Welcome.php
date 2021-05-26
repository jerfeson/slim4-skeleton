<?php

namespace App\Http\Site;

use App\Http\Controller;
use App\Message\Message;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Welcome.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 */
class Welcome extends Controller
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function index()
    {
        // log some message
        $this->getLogger()->info('log a message');

        //flash message
        $this->getFlash()->addMessage(Message::STATUS_SUCCESS, 'A flash message');

        return $this->getView()->render(
            $this->getResponse(),
            '@site/homepage/welcome.twig'
        );
    }
}
