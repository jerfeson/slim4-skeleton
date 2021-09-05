<?php

namespace App\Service\Auth;

use App\Repository\Auth\ClientRepository;
use App\Service\Service;

/**
 * Class ClientService.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ClientService extends Service
{
    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ClientRepository::class;
    }
}
