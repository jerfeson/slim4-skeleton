<?php

namespace App\Business\User;

use App\Business\Business;
use App\Model\UserModel;
use App\Repository\UserRepository;

/**
 * Class UserBusiness.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class UserBusiness extends Business
{
    /**
     * @var string
     */
    protected $repositoryClass = UserRepository::class;

    /**
     * @return mixed
     * @throws \Exception
     *
     */
    public function getUserByUserCredentials()
    {
        $user = $this->getRepository()->getUser(
            $this->getRequest()->getParsedBody()['username'],
            $this->getRequest()->getParsedBody()['password']
        );

        $this->validate($user);

        return $user;
    }

    /**
     * @param $user
     *
     * @throws \Exception
     */
    private function validate($user)
    {
        if ($user->status == UserModel::STATUS_INACTIVE) {
            throw new \Exception('error');
        }
    }
}
