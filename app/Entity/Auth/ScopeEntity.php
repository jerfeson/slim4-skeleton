<?php

namespace App\Entity\Auth;

use App\Entity\Entity;

/**
 * Class ScopeEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ScopeEntity extends Entity
{
    /**
     * @var string
     */
    protected $table = 'oauth2_scope';
}
