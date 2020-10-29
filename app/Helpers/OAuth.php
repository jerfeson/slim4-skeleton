<?php

namespace App\Helpers;

use App\Enum\HttpStatusCode;
use App\Message\Message;
use App\Repository\OAuthAccessTokenRepository;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

/**
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.1.0
 *
 * @version 1.1.0
 */
class OAuth
{
    /**
     * @param $request
     * @return ServerRequestInterface
     * @throws OAuthServerException
     */
    public static function validateBearer($request)
    {
        $oauth2Config = app()->getConfig('oauth2');

        $publicKey = $oauth2Config['public_key'];
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
        return $validator->validateAuthorization($request);
    }
}
