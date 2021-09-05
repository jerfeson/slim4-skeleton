<?php

namespace App\Entity\Auth;

use App\Entity\Entity;
use App\Entity\Register\UserEntity;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * Class AccessTokenEntity.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class AccessTokenEntity extends Entity implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public const TYPE_JWT = 0;

    public const TYPE_PERMANENT_KEY = 1;

    /**
     * @var string
     */
    protected $table = 'oauth2_access_token';

    /**
     * @var array
     */
    protected $casts = [
        'revoked' => 'boolean',
        'expiry_date_time' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class);
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiry_date_time <= new DateTime();
    }
}
