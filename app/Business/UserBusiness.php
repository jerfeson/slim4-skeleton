<?php

namespace App\Business;

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
}
