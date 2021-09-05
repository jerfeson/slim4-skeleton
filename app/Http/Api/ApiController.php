<?php

namespace App\Http\Api;

use App\Entity\Auth\IdentityStorage;
use App\Entity\Register\UserEntity;
use App\Http\Controller;
use DI\Annotation\Inject;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;

/**
 * Class ApiController.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ApiController extends Controller
{
    /**
     * @Inject
     *
     * @var IdentityStorage
     */
    public IdentityStorage $identityStorage;

    /**
     * @return null|UserEntity
     */
    public function getUser(): ?UserEntity
    {
        return $this->identityStorage->getUser();
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->identityStorage->getClientId();
    }

    /**
     * @param Request $request
     *
     * @return HttpMethodNotAllowedException
     */
    protected function actionNotFound(Request $request): HttpMethodNotAllowedException
    {
        return new HttpMethodNotAllowedException($request);
    }
}
