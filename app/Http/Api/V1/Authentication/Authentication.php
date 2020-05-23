<?php

namespace App\Http\Api\V1\Authentication;

use App\Business\AuthenticationBusiness;
use App\Business\UserBusiness;
use App\Enum\HttpStatusCode;
use App\Http\Controller;
use App\Message\Message;
use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Authentication.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class Authentication extends Controller
{
    /**
     * @var AuthenticationBusiness
     */
    private $authenticationBusiness;
    /**
     * @var UserBusiness
     */
    private $userBusiness;

    /**
     * @return mixed
     */
    public function loginAction()
    {
        try {
            return $this->getAuthenticationBusiness()->login();
        } catch (Exception $e) {
            $payload = [
                'result' => Message::STATUS_ERROR,
                'message' => $e->getMessage(),
            ];

            $this->getResponse()->getBody()->write(json_encode($payload));

            return $this->getResponse()->withHeader(
                'Content-Type',
                'application/json'
            )->withStatus(HttpStatusCode::UNAUTHORIZED);
        }
    }

    /**
     * @return ResponseInterface
     */
    public function tokenAction()
    {
        try {
            return $this->getOAuthServer()->respondToAccessTokenRequest($this->getRequest(), $this->getResponse());
        } catch (Exception $e) {
            $payload = [
                'result' => Message::STATUS_ERROR,
                'message' => $e->getMessage(),
            ];

            $this->getResponse()->getBody()->write(json_encode($payload));

            return $this->getResponse()->withHeader(
                'Content-Type',
                'application/json'
            )->withStatus(HttpStatusCode::UNAUTHORIZED);
        }
    }

    /**
     * @return UserBusiness
     */
    protected function getUserBusiness()
    {
        if (!$this->userBusiness) {
            $this->userBusiness = new UserBusiness(
                $this->getRequest(),
                $this->getResponse()
            );
        }

        return $this->userBusiness;
    }

    /**
     * @return AuthenticationBusiness
     */
    private function getAuthenticationBusiness()
    {
        if (!$this->authenticationBusiness) {
            $this->authenticationBusiness = new AuthenticationBusiness(
                $this->getRequest(),
                $this->getResponse()
            );
        }

        return $this->authenticationBusiness;
    }
}
