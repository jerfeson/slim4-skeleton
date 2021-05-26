<?php

namespace App\Middleware;

use App\Enum\HttpStatusCode;
use App\Message\Message;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Csrf\Guard;

/**
 * Class Csrf.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.1.0
 *
 * @version 1.1.0
 */
class Csrf
{

    /**
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        //todo make it better
        // Validate POST, PUT, DELETE, PATCH requests
        if (in_array($request->getMethod(), [
            'POST',
            'PUT',
            'DELETE',
            'PATCH',
        ])) {
            $form = $request->getParsedBody();
            /** @var Guard $guard */
            $guard = app()->getContainer()->get(Guard::class);
            $name = \App\ServiceProviders\Csrf::PREFIX . 'name';
            $token = \App\ServiceProviders\Csrf::PREFIX . 'value';
            $csrfName = isset($form[$name]) ? $form[$name] : null;
            $csrfToken = isset($form[$token]) ? $form[$token] : null;

            if ($csrfName && $csrfToken && !$guard->validateToken($csrfName, $csrfToken)) {
                $response = app()->getContainer()->get(Response::class);
                $payload = [
                    'result' => Message::STATUS_ERROR,
                    'message' => Message::ACCESS_DENIED,
                ];

                $response->getBody()->write(json_encode($payload));

                return $response->withHeader(
                    'Content-Type',
                    'application/json'
                )->withStatus(HttpStatusCode::UNAUTHORIZED);
            }
        }

        return $handler->handle($request);
    }
}
