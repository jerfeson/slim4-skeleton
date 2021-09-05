<?php

namespace App\Repository\Auth;

use App\Entity\Auth\ClientEntity;
use App\Repository\Repository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class ClientRepository.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 *
 * @method ClientEntity|null findOneBy(array $params, array $with = [])
 */
class ClientRepository extends Repository implements ClientRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return ClientEntity::class;
    }

    /**
     * @param string $clientIdentifier
     *
     * @return ClientEntity
     */
    public function getClientEntity($clientIdentifier)
    {
        $entity = new ClientEntity();
        $this->setEntity($entity);
        /** @var ClientEntity $client */
        $client = $this->findOneBy(['identifier' => $clientIdentifier]);
        $client->setIdentifier($client->id);

        return $client;
    }

    /**
     * @param string      $clientIdentifier
     * @param string|null $clientSecret
     * @param string|null $grantType
     *
     * @return bool|void
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        return true;
    }
}
