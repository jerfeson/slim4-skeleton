<?php

namespace App\Service\Register;

use App\Repository\Register\UserRepository;
use App\Service\Service;

/**
 * Class UserService.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class UserService extends Service
{
    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return UserRepository::class;
    }
}
