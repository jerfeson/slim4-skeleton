<?php

namespace App\ServiceProviders;


use App\Repository\OAuth\OAuthAccessTokenRepository;
use App\Repository\OAuth\OAuthClientRepository;
use App\Repository\OAuth\OAuthRefreshTokenRepository;
use App\Repository\OAuth\OAuthScopeRepository;
use App\Repository\OAuth\UserRepository;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
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
    private const ENCRYPTION_KEY = 'uVJd46ThfGSN4PXc9yMXyjmOqMtg3GArizMYbbSL7Wc=';

    public static function register()
    {
        app()->getContainer()->set(AuthorizationServer::class, function ($c) {
            $oauth2Config = app()->getConfig('oauth2');
            $clienteRepository = new OAuthClientRepository();
            $scopeRepository = new OAuthScopeRepository();
            $tokenRepository = new OAuthAccessTokenRepository();
            $userRepository = new UserRepository();
            $refreshTokenRepository = new OAuthRefreshTokenRepository();

            $server = new AuthorizationServer(
                $clienteRepository,
                $tokenRepository,
                $scopeRepository,
                $oauth2Config['private_key'],
                self::ENCRYPTION_KEY
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

            $clientCredentialsGrant = new ClientCredentialsGrant();
            $clientCredentialsGrant->setRefreshTokenTTL(new DateInterval('P1M'));
            $server->enableGrantType(
                $clientCredentialsGrant,
                new DateInterval('PT1H') // access tokens will expire after 1 hour
            );

            return $server;
        });
    }
}
