<?php

namespace App\Repository\OAuth;

use App\Model\OAuth\OAuthRefreshTokenCodeModel;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * Class OAuthRefreshTokenRepository
 *
 * @package App\Repository\OAuth
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 */
class OAuthRefreshTokenRepository extends Repository implements RefreshTokenRepositoryInterface
{
    protected $modelClass = OAuthRefreshTokenCodeModel::class;

    /**
     * Creates a new refresh token.
     *
     * @return RefreshTokenEntityInterface|void
     */
    public function getNewRefreshToken()
    {
        // TODO: Implement getNewRefreshToken() method.
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // TODO: Implement persistNewRefreshToken() method.
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId)
    {
        // TODO: Implement revokeRefreshToken() method.
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        // TODO: Implement isRefreshTokenRevoked() method.
    }
}
