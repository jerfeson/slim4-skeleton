<?php

namespace App\Repository;

use App\Model\UserModel;

/**
 * Class UserRepository.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class UserRepository extends Repository
{
    /**
     * @var string
     */
    protected $modelClass = UserModel::class;
}
