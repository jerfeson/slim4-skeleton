<?php

namespace App\Business;

use App\Helpers\OAuth;
use App\Model\UserModel;
use DI\NotFoundException;
use Exception;
use Lib\Utils\Session;
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
     * @throws Exception
     */
    public function prepareTokenRequest()
    {
        $user = $this->getUserBusiness()->getUserByUserCredentials();
        $client = $user->client()->first();
        $grantType = isset($this->getRequest()->getParsedBody()['grant_type']) ? $this->getRequest()->getParsedBody()['grant_type'] : null;
        return $this->getRequest()->withParsedBody([
            'grant_type' => $grantType ? $grantType : 'password',
            'client_id' => $client->id,
            //  Define your logic for get client
            'client_secret' => "12345",
            //  Define your secret pattern
            'redirect_uri' => '#',
            'username' => $this->getRequest()->getParsedBody()['username'],
            'password' => $this->getRequest()->getParsedBody()['password'],
        ]);
    }

    /**
     * @return UserBusiness
     */
    private function getUserBusiness()
    {
        if (!$this->userBusiness) {
            $this->userBusiness = new UserBusiness($this->getRequest(), $this->getResponse());
        }

        return $this->userBusiness;
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
     * @param bool $userId
     *
     * @throws NotFoundException
     */
    public function fillSession()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $id = $this->getRequest()->getAttribute('oauth_user_id');
            /** @var UserModel $user */
            $user = $this->getUserBusiness()->getRepository()->findById($id);

            $header = $this->getRequest()->getHeader('authorization');
            $token = trim((string)preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

            Session::set(
                'user',
                [
                    'id' => $user->id,
                    'firstName' => $user->client()->first()->first_name,
                    'lastName' => $user->client()->first()->last_name,
                    'token' => $token
                ]
            );
        }
    }
}
