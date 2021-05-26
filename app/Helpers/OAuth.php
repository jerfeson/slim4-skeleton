<?php

namespace App\Helpers;

use App\Enum\HttpStatusCode;
use App\Message\Message;
use App\Repository\OAuth\OAuthAccessTokenRepository;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Lib\Utils\Session;
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
        try {
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
            $response = $validator->validateAuthorization($request);

            if (!Session::exist('user')) {
                $data = new Dynamic();
                $data->client = new Dynamic();
                $data->client->id = $response->getAttribute('oauth_client_id');
                self::fillSession($data);
            }

            return $response;
        } catch (OAuthServerException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw OAuthServerException::accessDenied('Access token could not be verified');
        }

    }

    /**
     * @param array|Dynamic $data
     */
    public static function fillSession($data)
    {
        $arrSession = [];
        foreach ($data as $key => $value) {
            $arrSession[$key]["{$key}_id"] = $value->id;
        }

        Session::set('user', $arrSession);
    }
}
