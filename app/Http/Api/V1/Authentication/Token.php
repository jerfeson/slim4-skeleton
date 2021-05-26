<?php


namespace App\Http\Api\V1\Authentication;


use App\Business\Authentication\AuthenticationBusiness;
use App\Enum\HttpStatusCode;
use App\Http\Api\V1\ApiController;
use Psr\Http\Message\RequestInterface;

/**
 * Class Token
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
class Token extends ApiController
{
    protected $businessClass = AuthenticationBusiness::class;

    public function postAction()
    {
        try {
            $prepare = $this->getBusiness()->prepareTokenRequest();

            if ($prepare instanceof RequestInterface) {
                $this->setRequest($prepare);
                return $this->getOAuthServer()->respondToAccessTokenRequest($this->getRequest(), $this->getResponse());
            }

            $json = json_encode($prepare, JSON_PRETTY_PRINT);
            $this->getResponse()->getBody()->write($json);

            return $this->getResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(HttpStatusCode::OK);

        } catch (\Exception $e) {
            $payload = [
                'message' => $e->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::UNAUTHORIZED);
        }
    }

    public function getAction()
    {
        // TODO: Implement deleteAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
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
