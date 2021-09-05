<?php

namespace App\Factory;

use App\App;
use App\Repository\Auth\AccessTokenRepository;
use App\Repository\Auth\ClientRepository;
use App\Repository\Auth\RefreshTokenRepository;
use App\Repository\Auth\ScopeRepository;
use App\Repository\Register\UserRepository;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Container\ContainerInterface;

/**
 * Class AuthorizationServerFactory.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class AuthorizationServerFactory implements FactoryInterface
{
    private const ENCRYPTION_KEY = 'uVJd46ThfGSN4PXc9yMXyjmOqMtg3GArizMYbbSL7Wc=';

    /**
     * @param ContainerInterface $container
     *
     * @return AuthorizationServer
     */
    public static function create(ContainerInterface $container)
    {
        $oauth2Config = $oauth2Config ?? App::getConfig()->get('oauth2');
        $clientRepository = new ClientRepository();
        $scopeRepository = new ScopeRepository();
        $tokenRepository = new AccessTokenRepository();
        $userRepository = new UserRepository();
        $refreshTokenRepository = new RefreshTokenRepository();

        $server = new AuthorizationServer(
            $clientRepository,
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
    }
}
