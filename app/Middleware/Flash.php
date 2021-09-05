<?php

namespace App\Middleware;

use App\App;
use App\Message\Message;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Flash\Messages;
use Slim\Views\Twig;

/**
 * Class Flash.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Flash
{
    /**
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @throws DependencyException
     * @throws NotFoundException
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        /** @var Twig $view */
        $view = App::getContainer()->get(Twig::class);
        $message = App::getContainer()->get(Messages::class);

        $message = Message::getMessage($message);
        $view->getEnvironment()->addGlobal('messages', $message);

        return $handler->handle($request);
    }
}
