<?php

namespace App\Http\Api\V1\Authentication;

use App\Business\Authentication\AuthenticationBusiness;
use App\Http\Controller;
use Exception;
use Lib\Utils\Session;
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
     * @throws Exception
     */
    protected function validate()
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
    public function logoutAction()
    {
        Session::destroy();

        return $this->getResponse()->withHeader(
            'Location',
            '/'
        )->withStatus(302);
    }
}
