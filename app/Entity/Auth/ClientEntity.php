<?php

namespace App\Entity\Auth;

use App\Entity\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class ClientEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ClientEntity extends Entity implements ClientEntityInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_client';

    /**
     * @return string|void
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return string|string[]|void
     */
    public function getRedirectUri()
    {
        // TODO: Implement getRedirectUri() method.
    }

    /**
     * @todo check it
     *
     * @return bool
     */
    public function isConfidential()
    {
        return true;
    }
}
