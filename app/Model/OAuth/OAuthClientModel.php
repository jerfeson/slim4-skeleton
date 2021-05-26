<?php

namespace App\Model\OAuth;

use App\Model\ClientModel;
use App\Model\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class OAuthClientModel.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
class OAuthClientModel extends Model implements ClientEntityInterface
{
    protected $table = 'oauth_client';
    protected $fillable = ['identifier', 'secret'];


    /**
     * @return HasOne
     */
    public function client()
    {
        return $this->hasOne(ClientModel::class, 'oauth_client_id', 'id');
    }

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
        return "/";
    }

    public function isConfidential()
    {
        return true;
    }

}
