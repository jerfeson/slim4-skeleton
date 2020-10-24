<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ClientModel.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.1.0
 *
 * @version 1.1.0
 *
 */
class ClientModel extends Model
{
    protected $table = 'client';
    protected $fillable = ['name', 'status'];

    /**
     * @return HasOne
     */
    public function oAuthClient()
    {
        return $this->hasOne(OAuthClientModel::class, 'id', 'oauth_client_id');
    }


    /**
     * @return HasMany
     */
    public function user()
    {
        return $this->hasMany(UserModel::class, 'client_id', 'id');
    }
}
