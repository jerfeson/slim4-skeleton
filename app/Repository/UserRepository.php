<?php

namespace App\Repository;

use App\Helpers\Password;
use App\Message\Message;
use App\Model\UserModel;
use Exception;
use Illuminate\Database\Query\Builder;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * Class UserRepository.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelClass = UserModel::class;

    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     *
     * @throws Exception
     *
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        /** @var UserModel $query */
        $user = $this->getUser($username, $password);
        $user->setIdentifier($user);

        return $user;
    }

    /**
     * Get a user entity.
     *
     * @param $username
     * @param $password
     *
     * @throws Exception
     *
     * @return UserEntityInterface
     */
    public function getUser($username, $password)
    {
        /** @var Builder $query */
        $query = $this->newQuery();
        $query->where('username', '=', $username);

        $user = $this->doQuery($query, false, false)->first();
        if (!$user->password && !Password::verify($password, $user->password)) {
            throw new Exception(Message::ACCESS_DENIED);
        }

        return $user;
    }
}
