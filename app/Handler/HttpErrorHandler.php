<?php

namespace App\Handler;

use App\App;
use App\Exception\ValidationException;
use App\Message\Message;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use SlashTrace\SlashTrace;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;

/**
 * Class HttpErrorHandler.
 *
 * @author Thiago Daher
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class HttpErrorHandler extends ErrorHandler
{
    const EXPECTED_EXCEPTIONS = [
        HttpException::class,
        ValidationException::class,
    ];

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $message = Message::INTERNAL_ERROR;

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();

            if ($exception instanceof HttpNotFoundException) {
                $message = Message::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $message = Message::METHOD_NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $message = Message::UNAUTHORIZED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $message = Message::FORBIDDEN;
            } elseif ($exception instanceof HttpBadRequestException) {
                $message = Message::BAD_REQUEST;
            }
        }

        if (App::getContainer()->has(LoggerInterface::class) && !$this->displayErrorDetails) {
            App::getContainer()->get(LoggerInterface::class)->error($exception);
        }

        if (App::getContainer()->has(SlashTrace::class) && (App::isConsole() || $this->displayErrorDetails)) {
            $st = App::getContainer()->get(SlashTrace::class);
            $st->register();
            throw $exception;
        }

        if ($exception instanceof ValidationException) {
            $message = $exception->getMessages();
        }

        $messageKey = is_array($message) ? 'mensagens' : 'mensagem';
        $this->logException($exception);
        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode([
            $messageKey => $message,
        ], JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function logException(?object $exception)
    {
        if (!is_object($exception)) {
            return;
        }

        foreach (self::EXPECTED_EXCEPTIONS as $expectedException) {
            if ($exception instanceof $expectedException) {
                return;
            }
        }

        error_log($exception);
    }
}
