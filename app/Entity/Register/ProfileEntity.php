<?php

namespace App\Entity\Register;

use App\Entity\Entity;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ProfileEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ProfileEntity extends Entity
{
    /**
     * @var string
     */
    protected $table = 'profile';

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserEntity::class);
    }
}
