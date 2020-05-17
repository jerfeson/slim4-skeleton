<?php

namespace App\Repository;

use App\Helpers\Password;
use App\Model\OAuthClientModel;
use DI\NotFoundException;
use Exception;
use Illuminate\Database\Query\Builder;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class OAuthClientRepository.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class OAuthClientRepository extends Repository implements ClientRepositoryInterface
{
    protected $modelClass = OAuthClientModel::class;

    /**
     * @param string $clientIdentifier
     * @param string|null $clientSecret
     * @param string|null $grantType
     *
     * @throws NotFoundException
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        /** @var Builder $qb */
        $client = $this->findById($clientIdentifier);
        if (Password::verify($clientSecret, $client->secret) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param string $clientIdentifier
     *
     * @throws Exception
     *
     * @return ClientEntityInterface|mixed|null
     */
    public function getClientEntity($clientIdentifier)
    {
        /** @var Builder $qb */
        $client = $this->findById($clientIdentifier);
        $client->setIdentifier($clientIdentifier);
        //Define scope by client
        //$client->setScope($client['scope']);
        return $client;
    }
}
