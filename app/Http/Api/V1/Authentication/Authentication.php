<?php

namespace App\Http\Api\V1\Authentication;

use App\Business\AuthenticationBusiness;
use App\Business\UserBusiness;
use App\Enum\HttpStatusCode;
use App\Http\Controller;
use App\Message\Message;
use Exception;
use Lib\Utils\Session;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

/**
 * Class Authentication.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 *
 * @method AuthenticationBusiness getBusiness()
 */
class Authentication extends Controller
{

    /**
     * @var string
     */
    protected $businessClass = AuthenticationBusiness::class;

    /**
     * @var AuthenticationBusiness
     */
    private $authenticationBusiness;
    /**
     * @var UserBusiness
     */
    private $userBusiness;

    /**
     * @return ResponseInterface
     */
    public function tokenAction()
    {
        try {
            $this->validate();
            $this->setRequest($this->getBusiness()->prepareTokenRequest());
            return $this->getOAuthServer()->respondToAccessTokenRequest($this->getRequest(), $this->getResponse());
        } catch (Exception $e) {
            $payload = [
                'result' => $e->getCode(),
                'message' => $e->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::UNAUTHORIZED);
        }
    }


    /**
     * @throws Exception
     */
    private function validate()
    {
        $this->getValidator()->validate($this->getRequest(), [
            'username' => Validator::notBlank()->NoWhitespace(),
            'password' => Validator::notBlank()->NoWhitespace(),
        ]);

        if ($this->getValidator()->failed()) {
            throw new Exception($this->getValidator()->getErros(true));
        }
    }

    /**
     * @return mixed
     */
    public function loginAction()
    {
        try {
            $this->getAuthenticationBusiness()->login();
            $payload = [
                'result' => Message::STATUS_SUCCESS,
                'message' => Message::LOGIN_SUCCESSFUL,
                'redirect' => '#',
            ];

            return $this->respondWithData($payload);

        } catch (Exception $e) {
            $payload = [
                'result' => $e->getCode(),
                'message' => $e->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::UNAUTHORIZED);
        }
    }

    /**
     * @return mixed
     */
    public function logoutAction()
    {
        Session::destroy();

        return $this->getResponse()->withHeader(
            'Location',
            '/'
        )->withStatus(302);
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

}
