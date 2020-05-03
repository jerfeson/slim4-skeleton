<?php

namespace App\Repository;

use App\Helpers\Password;
use App\Model\OAuthClientModel;
use Illuminate\Database\Query\Builder;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class OAuthClientRepository.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
class OAuthClientRepository extends Repository implements ClientRepositoryInterface
{
    protected $model = OAuthClientModel::class;

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     * @param string|null $grantType The grant type used (if sent)
     * @param string|null $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        /** @var Builder $qb */
        $qb = $this->newQuery();
        $qb->where('client_id', '=', $clientIdentifier);
        $client = $this->doQuery($qb, 1, false)->first();
        if (Password::verify($clientSecret, $client->client_secret) === false) {
            return;
        }

        $client->setIdentifier($clientIdentifier);
        //Define scope by client
//        $client->setScope($client['scope']);

        return $client;
    }
}
