<?php


namespace App\Http\Api\V1\Authentication;


use App\Business\Authentication\AuthenticationBusiness;
use App\Enum\HttpStatusCode;
use App\Http\Api\V1\ApiController;
use App\Message\Message;

/**
 * Class Authorize
 *
 * @package App\Http\Api\V1\Authentication
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 *
 * @method AuthenticationBusiness getBusiness()
 *
 */
class Authorize extends ApiController
{
    protected $businessClass = AuthenticationBusiness::class;

    public function getAction()
    {
        // TODO: Implement getAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }

    public function postAction()
    {
        try {
            $this->getBusiness()->login();
            $payload = [
                'message' => Message::LOGIN_SUCCESSFUL,
                'redirect' => '#',
            ];

            return $this->respondWithData($payload);

        } catch (\Exception $e) {
            $payload = [
                'message' => $e->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::UNAUTHORIZED);
        }
    }

    public function putAction()
    {
        // TODO: Implement putAction() method.
    }

    protected function validatePost()
    {
        // TODO: Implement validatePost() method.
    }

    protected function validatePut()
    {
        // TODO: Implement validatePut() method.
    }

    protected function validateDelete()
    {
        // TODO: Implement validateDelete() method.
    }
}
