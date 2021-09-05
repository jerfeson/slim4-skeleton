<?php

namespace App\Repository\Register;

use App\Entity\Register\UserEntity;
use App\Helpers\Password;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * Class UserRepository.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return UserEntity::class;
    }

    /**
     * @param string                $username
     * @param string                $password
     * @param string                $grantType
     * @param ClientEntityInterface $clientEntity
     *
     * @return null|false|mixed|UserEntityInterface
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $queryBuilder = new UserEntity();

        $queryBuilder->where('username', '=', $username);
        $user = $queryBuilder->get()->first();

        if (!Password::verify($password, $user->password)) {
            return false;
        }

        if ($user->client_id != $clientEntity->id) {
            return false;
        }

        return $user;
    }

    /**
     * @param array $data
     *
     * @return null|false|mixed|UserEntityInterface
     */
    public function getUserEntityByCredentials(array $data)
    {
        $queryBuilder = $this->newQuery();

        $queryBuilder->where('username', '=', $data['username']);
        $user = $queryBuilder->get()->first();

        if (!Password::verify($data['password'], $user->password)) {
            return false;
        }

        return $user;
    }
}
