<?php

namespace App\Model\OAuth;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use App\Model\Model;

/**
 * Class OAuthAccessTokenModel.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
class OAuthAccessTokenModel extends Model implements AccessTokenEntityInterface
{
    protected $table = 'oauth_access_token';

    /**
     * @var OAuthClientModel
     */
    protected $client;

    /**
     * @var UserModel
     */
    protected $userIdentifier;

    /**
     * @var
     */
    private $expiryDateTime;

    /**
     * @var CryptKey|mixed
     */
    private $privateKey;

    /**
     * @return OAuthClientModel
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }

    /**
     * @param ClientEntityInterface $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return UserModel|int|string|null
     */
    public function getUserIdentifier()
    {
        return $this->userIdentifier;
    }

    /**
     * @param int|string|null $userIdentifier
     */
    public function setUserIdentifier($userIdentifier)
    {
        $this->userIdentifier = $userIdentifier;
    }

    /**
     * @param DateTimeImmutable $dateTime
     */
    public function setExpiryDateTime(DateTimeImmutable $dateTime)
    {
        $this->expiryDateTime = $dateTime;
    }

    /**
     * Get the token's expiry date time.
     *
     * @return DateTimeImmutable
     */
    public function getExpiryDateTime()
    {
        return $this->expiryDateTime;
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        // TODO: Implement addScope() method.
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        // TODO: Implement getScopes() method.
    }

    /**
     * @param CryptKey $privateKey
     */
    public function setPrivateKey(CryptKey $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return CryptKey|mixed
     */
    public function getPrivateKey()
    {
        return  $this->privateKey;
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function __toString()
    {
        //todo #TOSKELETON
        $imutable = new DateTimeImmutable();
        $barrer = (new Builder())
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt($imutable)
            ->canOnlyBeUsedAfter($imutable)
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo($this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->getToken(new Sha256(), new Key($this->getPrivateKey()->getKeyPath(), $this->getPrivateKey()->getPassPhrase()));

        return (string)$barrer;
    }


}
