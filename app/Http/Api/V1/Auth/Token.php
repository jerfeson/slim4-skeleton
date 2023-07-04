<?php

namespace App\Http\Api\V1\Auth;

use App\Http\Api\ApiController;
use App\Service\Auth\AccessTokenService;
use App\Service\Auth\ClientService;
use DI\Annotation\Inject;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Token.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class Token extends ApiController
{
    /**
     * @Inject
     *
     * @var AccessTokenService
     */
    private AccessTokenService $accessTokenService;

    /**
     * @Inject
     *
     * @var AuthorizationServer
     */
    private AuthorizationServer $authorizationServer;

    /**
     * @inject
     *
     * @var ClientService
     */
    private ClientService $clientService;

    /**
     * @throws OAuthServerException|\ReflectionException
     *
     * @return ResponseInterface
     */
    public function postAction(): ResponseInterface
    {
        $data = $this->getRequest()->getParsedBody();

        $client = $this->accessTokenService->getClientByGrant($data);

        $payload = [];
        $payload['grant_type'] = $data['grant_type'];
        $payload['client_id'] = $client->first()->identifier;
        $payload['client_secret'] = $client->first()->secret;

        if ($data['grant_type'] === 'password') {
            $payload['username'] = $data['username'];
            $payload['password'] = $data['password'];
        }

        $request = $this->getRequest()->withParsedBody($payload);

        $this->authorizationServer->respondToAccessTokenRequest($request, $this->getResponse());

        return $this->getResponse()->withStatus(200);
    }
}
