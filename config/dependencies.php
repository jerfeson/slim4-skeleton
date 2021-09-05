<?php

use App\Entity\Register\UserEntity;
use App\Factory\AuthorizationServerFactory;
use App\Factory\DbConnectionFactory;
use App\Factory\MonologFactory;
use App\Factory\SlashTraceFactory;
use App\Factory\TranslatorFactory;
use App\Factory\TwigFactory;
use App\Service\Auth\AccessTokenService;
use App\Service\Auth\ClientService;
use App\Service\Register\UserService;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Log\LoggerInterface;
use SlashTrace\SlashTrace as ST;
use Slim\Flash\Messages;
use function DI\autowire;
use function DI\factory;

// Configures the dependencies that will be started
return [
    /**
     * Injection of common project dependencies.
     */
    //Middlewares
    App\Middleware\ApiAuthentication::class => autowire(),

    //Routing's
    App\Routing\ApiRouteResolver::class => autowire(),

    //Entity's
    App\Entity\Auth\IdentityStorage::class => autowire(),
    UserEntity::class => autowire(),

    //Repository's
    App\Repository\RepositoryManager::class => autowire(),

    /**
     * Application Module Dependency Injection.
     */
    // Services|Business

    AccessTokenService::class => autowire(),
    ClientService::class => autowire(),
    UserService::class => autowire(),

    /**
     * Provider dependency injection.
     */

    // Flash Message
    Messages::class => autowire(),
    // Mono Log
    LoggerInterface::class => factory([
        MonologFactory::class,
        'create',
    ]),
    // OAuth
    AuthorizationServer::class => factory([
        AuthorizationServerFactory::class,
        'create',
    ]),
    // Slash Trace
    ST::class => factory([
        SlashTraceFactory::class,
        'create',
    ]),
    // Symfony Translator
    Symfony\Component\Translation\Translator::class => factory([
        TranslatorFactory::class,
        'create',
    ]),
    // Symfony Twig
    Slim\Views\Twig::class => factory([
        TwigFactory::class,
        'create',
    ]),
    // Laravel Eloquent
    Illuminate\Database\ConnectionInterface::class => factory([
        DbConnectionFactory::class,
        'create',
    ]),
];
