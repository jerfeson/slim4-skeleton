<?php

namespace App\Repository\OAuth;

use App\Model\OAuth\OAuthClientModel;
use App\Repository\Repository;
use DI\NotFoundException;
use Exception;
use Illuminate\Database\Query\Builder;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class OAuthClientRepository
 *
 * @package App\Repository\Client
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
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
        $client = $this->findBy([
            'identifier' => $clientIdentifier
        ])->first();

        if ($clientSecret !== $client->secret) {
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
        $client = $this->findBy(
            [
                'identifier' => $clientIdentifier
            ]
        )->first();

        $client->setIdentifier($client->id);
        //Define scope by client
        //$client->setScope($client['scope']);
        return $client;
    }
}
