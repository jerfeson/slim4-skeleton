<?php

namespace App\Middleware;

use App\Message\Message;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Flash\Messages;
use Slim\Views\Twig;

/**
 * Class Flash
 * @package App\Middleware
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class Flash
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        /** @var Twig $view */
        $view = app()->getContainer()->get(Twig::class);
        $message = app()->getContainer()->get(Messages::class);

        $message = Message::getMessage($message);
        $view->getEnvironment()->addGlobal('message', $message);

        return $handler->handle($request);
    }
}