<?php

namespace App\Business\Authentication;

use App\Business\Business;
use App\Business\Client\OAuthClientBusiness;
use App\Business\Client\UserBusiness;
use App\Helpers\Dynamic;
use App\Helpers\OAuth;
use App\Model\OAuth\ClientModel;
use App\Model\OAuth\OAuthAccessTokenModel;
use App\Model\OAuth\OAuthClientModel;
use App\Model\OAuth\UserModel;
use Carbon\Carbon;
use Exception;
use function preg_replace;
use function trim;

/**
 * Class AuthenticationBusiness.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.0.0
 *
 * @version 1.1.0
 */
class AuthenticationBusiness extends Business
{
    /**
     * @var UserBusiness
     */
    private $userBusiness;

    /**
     * @var OAuthClientBusiness
     */
    private $oAuthClientBusiness;

    /**
     * @throws Exception
     */
    public function prepareTokenRequest()
    {
        $grantType = 'password';
        $clientId  = null;
        $clientSecret  = null;
        $userName = null;
        $password = null;

        if (isset($this->getRequest()->getParsedBody()['grant_type'])) {
            $grantType = $this->getRequest()->getParsedBody()['grant_type'];

            if (isset($this->getRequest()->getParsedBody()['client_id'])) {
                $clientId = $this->getRequest()->getParsedBody()['client_id'];
                $clientSecret = $this->getRequest()->getParsedBody()['client_secret'];
            }

            /** @var OAuthClientModel $oAuthClient */
            $oAuthClient = $this->getOAuthClientBusiness()->getRepository()->findBy(
                [
                    'identifier' => $clientId,
                    'secret' => $clientSecret
                ]
            )->first();

            /** @var ClientModel $client */
            $client = $oAuthClient->client()->first();
            /** @var OAuthAccessTokenModel $lastToken */

            if ($client->accessToken()->count()) {
                $lastToken = $client->accessToken()->latest('id')->first();
                if ($expireDateTime = $this->checkLatestToken($lastToken)) {
                    /**
                     * @see https://oauth2.thephpleague.com/authorization-server/refresh-token-grant/#refresh-token-grant
                     * todo see a way to return the same token until it expires
                     * todo implement the refresh token grant, to update an already expired token
                     */
                }
            }



        } else {
            /** @var UserModel $user */
            $user = $this->getUserBusiness()->getUserByUserCredentials();
            /** @var ClientModel $client */
            $client = $user->client()->first();
            $clientId =  $client->oAuthClient()->first()->identifier;
            $clientSecret = $client->oAuthClient()->first()->secret;
            $userName = $this->getRequest()->getParsedBody()['username'];
            $password = $this->getRequest()->getParsedBody()['password'];
        }

        return $this->getRequest()->withParsedBody([
            'grant_type' => $grantType,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => '/',
            'username' => $userName,
            'password' => $password,
        ]);
    }

    /**
     * @param OAuthAccessTokenModel $lastToken
     *
     * @return bool
     * @throws Exception
     */
    private function checkLatestToken(OAuthAccessTokenModel $lastToken)
    {
        $dateTimeImmutable = new \DateTimeImmutable();
        $expiryDateTime = Carbon::create($lastToken->expiry_date_time);
        if ($expiryDateTime->getTimestamp() > $dateTimeImmutable->getTimestamp()) {
            return $expiryDateTime->getTimestamp();
        }

        return false;
    }

    /**
     * @return UserBusiness
     */
    private function getUserBusiness()
    {
        if (!$this->userBusiness) {
            $this->userBusiness = new UserBusiness();
        }

        return $this->userBusiness;
    }

    /**
     * @return OAuthClientBusiness
     */
    private function getOAuthClientBusiness()
    {
        if (!$this->oAuthClientBusiness) {
            $this->oAuthClientBusiness = new OAuthClientBusiness();
        }

        return $this->oAuthClientBusiness;
    }



    /**
     * @throws Exception
     */
    public function login()
    {
        $this->setRequest(OAuth::validateBearer($this->getRequest()));
        $this->fillSession();
    }

    /**
     */
    public function fillSession()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $id = $this->getRequest()->getAttribute('oauth_user_id');
            $client = $this->getRequest()->getAttribute('oauth_client_id');

            $header = $this->getRequest()->getHeader('authorization');
            $token = trim((string)preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

            $data = new Dynamic();
            $data->user = new UserModel();
            $data->user->id = $id;

            $data->OAuthAccessToken = new OAuthAccessTokenModel();
            $data->OAuthAccessToken->access_token = $token;

            $data->client = new ClientModel();
            $data->client->id = $client;
            OAuth::fillSession($data);
        }
    }
}
