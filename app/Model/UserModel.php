<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class User.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 */
class UserModel extends Model implements UserEntityInterface
{
    protected $table = 'user';
    protected $fillable = ['name', 'user', 'password'];

    /**
     * @return HasOne
     */
    public function client()
    {
        return $this->hasOne(ClientModel::class, 'id', 'client_id');
    }
}
