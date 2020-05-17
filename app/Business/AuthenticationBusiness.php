<?php

namespace App\Business;

use App\Enum\HttpStatusCode;
use App\Message\Message;
use App\Repository\OAuthAccessTokenRepository;
use Exception;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Class AuthenticationBusiness.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
class AuthenticationBusiness extends Business
{
    /**
     * @throws Exception
     */
    public function login()
    {
        $this->validator();
        $payload = [
            'result' => Message::STATUS_SUCCESS,
            'message' => Message::LOGIN_SUCCESSFUL,
            'redirect_url' => '',
        ];
        $this->getResponse()->getBody()->write(json_encode($payload));

        return $this->getResponse()->withHeader(
            'Content-Type',
            'application/json'
        )->withStatus(HttpStatusCode::OK);
    }

    /**
     * @throws OAuthServerException
     */
    private function validator()
    {
        $this->validateBearer();
    }

    /**
     *  Check if Bearer token is ok, check expiration date and token is revoked
     *  Update the request with data about the user.
     *
     * @throws Exception
     * @throws OAuthServerException
     *
     * @return mixed
     */
    private function validateBearer()
    {
        $oauth2Config = app()->getConfig('oauth2');

        $publicKey = file_get_contents($oauth2Config['public_key']);
        if (!$publicKey) {
            throw new OAuthServerException(
                Message::PUBLIC_KEYS_NOT_DEFINED,
                HttpStatusCode::UNAUTHORIZED,
                Message::PUBLIC_KEYS_NOT_DEFINED
            );
        }

        $accessTokenRepository = new OAuthAccessTokenRepository();
        $validator = new BearerTokenValidator($accessTokenRepository);
        $cryptKey = new CryptKey($publicKey);
        $validator->setPublicKey($cryptKey);
        $this->setRequest($validator->validateAuthorization($this->getRequest()));
    }
}
