<?php


namespace App\Business\Client;


use App\Business\Business;
use App\Repository\OAuth\OAuthClientRepository;

/**
 * Class OAuthClientBusiness
 *
 * @package App\Business\Client
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 *
 * @method OAuthClientRepository getRepository()
 *
 */
class OAuthClientBusiness extends Business
{
    protected $repositoryClass = OAuthClientRepository::class;
}
