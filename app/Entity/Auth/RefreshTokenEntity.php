<?php

namespace App\Entity\Auth;

use App\Entity\Entity;

/**
 * Class RefreshTokenEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class RefreshTokenEntity extends Entity
{
    /**
     * @var string
     */
    protected $table = 'oauth2_refresh_token';
}
