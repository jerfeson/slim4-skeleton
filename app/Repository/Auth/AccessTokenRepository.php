<?php

namespace App\Repository\Auth;

use App\Entity\Auth\AccessTokenEntity;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Class AccessTokenRepository.
 *
 * @author  Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 *
 * @method null|AccessTokenEntity findOneBy(array $params, array $with = [])
 */
class AccessTokenRepository extends Repository implements AccessTokenRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return AccessTokenEntity::class;
    }

    /**
     * @param ClientEntityInterface $clientEntity
     * @param array                 $scopes
     * @param null                  $userIdentifier
     *
     * @return null
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        if ($userIdentifier) {
            $accessToken->setUserIdentifier($userIdentifier);
        }

        return $accessToken;
    }

    /**
     * @param AccessTokenEntityInterface $accessTokenEntity
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessTokenEntity->access_token = $accessTokenEntity->getIdentifier();
        $accessTokenEntity->expiry_date_time = $accessTokenEntity->getExpiryDateTime();

        if ($accessTokenEntity->getUserIdentifier()) {
            $accessTokenEntity->user_id = $accessTokenEntity->getUserIdentifier();
        }

        $accessTokenEntity->oauth2_client_id = $accessTokenEntity->getClient()->getIdentifier();
        $this->save($accessTokenEntity);
    }

    /**
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId)
    {
        $token = $this->findOneBy([
            'identifier' => $tokenId,
        ]);

        if (!$token instanceof AccessTokenEntity) {
            return;
        }

        $token->revoked = true;
        $token->save();
    }

    /**
     * @param string $tokenId
     *
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        $token = $this->findOneBy([
            'access_token' => $tokenId,
        ]);

        return !$token || $token->revoked;
    }
}
