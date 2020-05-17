<?php

namespace App\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class OAuthClientModel.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
class OAuthClientModel extends Model implements ClientEntityInterface
{
    protected $table = 'oauth_client';
    protected $fillable = ['secret'];

    /**
     * Get the client's name.
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getRedirectUri()
    {
        // TODO: Implement getRedirectUri() method.
    }

    public function isConfidential()
    {
        // TODO: Implement isConfidential() method.
    }
}
