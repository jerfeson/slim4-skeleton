<?php

namespace App\Entity\Register;

use App\Entity\Auth\ClientEntity;
use App\Entity\Entity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class UserEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class UserEntity extends Entity implements UserEntityInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProfileEntity::class);
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientEntity::class);
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->id;
    }
}
