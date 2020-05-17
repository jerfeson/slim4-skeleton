<?php

namespace App\ServiceProviders;

use App\Repository\OAuthAccessTokenRepository;
use App\Repository\OAuthClientRepository;
use App\Repository\OAuthRefreshTokenRepository;
use App\Repository\OAuthScopeRepository;
use App\Repository\UserRepository;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;

/***
 * Class OAuthServer
 * @package App\ServiceProviders
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class OAuthServer implements ProviderInterface
{
    public static function register()
    {
        app()->getContainer()->set(AuthorizationServer::class, function ($c) {
            $oauth2Config = app()->getConfig('oauth2');
            $clienteRepository = new OAuthClientRepository();
            $scopeRepository = new OAuthScopeRepository();
            $tokenRepository = new OAuthAccessTokenRepository();
            $userRepository = new UserRepository();
            $refreshTokenRepository = new OAuthRefreshTokenRepository();

            // todo put it in Readme.md
            /*
               Remember generate your encryption key,you can use that:
               php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
             */
            $server = new AuthorizationServer(
                $clienteRepository,
                $tokenRepository,
                $scopeRepository,
                file_get_contents($oauth2Config['private_key']),
                'uVJd46ThfGSN4PXc9yMXyjmOqMtg3GArizMYbbSL7Wc='
            );

            $grant = new PasswordGrant(
                $userRepository,
                $refreshTokenRepository
            );

            $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

            // Enable the password grant on the server
            $server->enableGrantType(
                $grant,
                new DateInterval('PT1H') // access tokens will expire after 1 hour
            );

            return $server;
        });
    }
}
