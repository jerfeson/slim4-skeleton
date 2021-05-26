<?php

namespace App\Repository\OAuth;

use App\Model\OAuth\OAuthScopeModel;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class OAuthScopeRepository
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
class OAuthScopeRepository extends Repository implements ScopeRepositoryInterface
{
    //todo #TOSKELETON
    protected $modelClass = OAuthScopeModel::class;

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return void
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        // TODO: Implement getScopeEntityByIdentifier() method.
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param string|null $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        return [];
    }
}
