<?php

namespace App\Entity\Auth;

use App\Entity\Register\UserEntity;

/**
 * Class IdentityStorage.
 *
 * @author  Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class IdentityStorage
{
    /**
     * @var null|UserEntity
     */
    private ?UserEntity $User;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->User !== null;
    }

    /**
     * @return null|UserEntity
     */
    public function getUser(): ?UserEntity
    {
        return $this->User;
    }

    /**
     * @param null|UserEntity $User
     */
    public function setUser(?UserEntity $User): void
    {
        $this->User = $User;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }
}
