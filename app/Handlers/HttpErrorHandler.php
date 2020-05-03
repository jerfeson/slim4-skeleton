<?php


namespace App\Handlers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Throwable;

/**
 * Class HttpErrorHandler
 * @package App\Handlers
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class HttpErrorHandler extends ErrorHandler
{
    public const BAD_REQUEST             = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED             = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED         = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND      = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR            = 'SERVER_ERROR';
    public const UNAUTHENTICATED         = 'UNAUTHENTICATED';

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            }
        }

        // Log the message
        if (app()->getContainer()->has(LoggerInterface::class) && !$this->displayErrorDetails) {
            app()->resolve(LoggerInterface::class)->error($exception);
        }

        $response = $this->responseFactory->createResponse($statusCode);


        if (app()->getContainer()->has('slashtrace') && (app()->isConsole() || $this->displayErrorDetails)) {
            app()->resolve('slashtrace')->register();
            http_response_code($statusCode);
            $response->getBody()->write($exception);
        } else {

            if (
                !($exception instanceof HttpException)
                && ($exception instanceof Exception || $exception instanceof Throwable)
                && $this->displayErrorDetails
            ) {
                $description = $exception->getMessage();
            }

            $error = [
                'statusCode' => $statusCode,
                'error' => [
                    'type' => $type,
                    'description' => $description,
                ],
            ];
            $payload = json_encode($error, JSON_PRETTY_PRINT);
            $response->getBody()->write($payload);
        }

        return $response;
    }
}