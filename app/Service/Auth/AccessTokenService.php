<?php

namespace App\Service\Auth;

use App\Repository\Auth\AccessTokenRepository;
use App\Service\Register\UserService;
use App\Service\Service;
use DI\Annotation\Inject;

/**
 * Class AccessTokenService.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class AccessTokenService extends Service
{
    /**
     * @Inject
     *
     * @var ClientService
     */
    private ClientService $clientService;

    /**
     * @Inject
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * @param $data
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function getClientByGrant($data)
    {
        $grant_type = $data['grant_type'];

        switch ($grant_type) {
            case 'client_credentials':
                return $this->getTokenByClientCredentials($data);

            case 'password':
                return $this->getTokenByUserPassword($data);
        }
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return AccessTokenRepository::class;
    }

    /**
     * @param $data
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    private function getTokenByClientCredentials($data)
    {
        return $this->clientService->getRepository()->findBy(
            [
                'identifier' => $data['client_id'],
                'secret' => $data['client_secret'],
            ]
        )->first();
    }

    /**
     * @param $data
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    private function getTokenByUserPassword($data)
    {
        $user = $this->userService->getRepository()->getUserEntityByCredentials($data);

        return $user->client()->first();
    }
}
