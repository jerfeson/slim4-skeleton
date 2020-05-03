<?php

namespace App\Model;

/**
 * Class User.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class UserModel extends Model
{
    protected $table = 'user';
    protected $fillable = ['name', 'user', 'password'];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
}
