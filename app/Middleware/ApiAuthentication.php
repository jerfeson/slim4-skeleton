<?php

namespace App\Middleware;

use App\App;
use App\Entity\Auth\AccessTokenEntity;
use App\Entity\Auth\IdentityStorage;
use App\Repository\Auth\AccessTokenRepository;
use App\Repository\Auth\ClientRepository;
use App\Repository\Register\UserRepository;
use App\Repository\RepositoryManager;
use App\Routing\ApiRouteResolver;
use App\Service\RouteFinder;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ReflectionException;
use Slim\Exception\HttpUnauthorizedException;

/**
 * Class ApiAuthentication.
 *
 * @author Thiago Daher
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ApiAuthentication implements MiddlewareInterface
{
    private IdentityStorage   $storage;
    private RepositoryManager $repositoryManager;
    private ApiRouteResolver  $apiResolver;
    private RouteFinder       $routeFinder;

    private string $accessToken;

    /**
     * @param RouteFinder       $routeFinder
     * @param ApiRouteResolver  $apiResolver
     * @param IdentityStorage   $storage
     * @param RepositoryManager $repository
     */
    public function __construct(
        RouteFinder $routeFinder,
        ApiRouteResolver $apiResolver,
        IdentityStorage $storage,
        RepositoryManager $repository
    ) {
        $this->storage = $storage;
        $this->repositoryManager = $repository;
        $this->routeFinder = $routeFinder;
        $this->apiResolver = $apiResolver;
    }

    /**
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @throws ReflectionException
     * @throws HttpUnauthorizedException
     * @throws OAuthServerException
     *
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $route = $this->routeFinder->getRouteFromRequest($request);

        if (
            !$route ||
            $route->getCallable() !== ApiRouteResolver::class ||
            !$this->apiResolver->isRouteValid($route->getArguments())
        ) {
            return $handler->handle($request);
        }

        if (!$request->hasHeader('Authorization')) {
            throw new HttpUnauthorizedException($request);
        }

        $header = $request->getHeader('authorization');
        $this->accessToken = trim((string)preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

        if (strlen($this->accessToken) < 100) {
            $request = $this->authenticateWithPureToken($request);
        } else {
            $request = $this->authenticateWithJwt($request);
        }

        return $handler->handle($request);
    }

    /**
     * @param Request $request
     *
     * @throws HttpUnauthorizedException
     * @throws ReflectionException
     *
     * @return Request
     */
    private function authenticateWithPureToken(Request $request): Request
    {
        $accessTokenRepo = $this->repositoryManager->get(AccessTokenRepository::class);

        /** @var AccessTokenEntity|null $accessToken */
        $accessToken = $accessTokenRepo->findOneBy([
            'access_token' => $this->accessToken,
        ]);

        if (!$accessToken || $accessToken->revoked || $accessToken->type !== AccessTokenEntity::TYPE_PERMANENT_KEY) {
            throw new HttpUnauthorizedException($request);
        }

        $request = $request
            ->withAttribute('oauth_access_token_id', $accessToken->accessToken)
            ->withAttribute('oauth_client_id', $accessToken->oauth2_client_id)
            ->withAttribute('oauth_user_id', $accessToken->user_id)
            ->withAttribute('oauth_scopes', ['all']);

        $this->setUpStorage($request);

        return $request;
    }

    /**
     * @param Request $request
     *
     * @throws ReflectionException
     * @throws HttpUnauthorizedException
     */
    private function setUpStorage(Request $request)
    {
        $userRepo = $this->repositoryManager->get(UserRepository::class);
        $user = $userRepo->findById($request->getAttribute('oauth_user_id'));

        if (!$user) {
            throw new HttpUnauthorizedException($request);
        }

        $this->storage->setUser($user);
        $this->storage->setClientId($request->getAttribute('oauth_client_id'));
    }

    /**
     * @param Request $request
     *
     * @throws ReflectionException
     * @throws HttpUnauthorizedException
     * @throws OAuthServerException
     *
     * @return Request
     */
    private function authenticateWithJwt(Request $request): Request
    {
        $server = new ResourceServer(
            $this->repositoryManager->get(AccessTokenRepository::class),
            App::getConfig()->get('oauth2.public_key')
        );

        $request = $server->validateAuthenticatedRequest($request);
        $clientRepository = $this->repositoryManager->get(ClientRepository::class);
        $client = $clientRepository->findOneBy([
            'id' => $request->getAttribute('oauth_client_id'),
        ]);

        if (!$client) {
            throw new HttpUnauthorizedException($request);
        }

        $request = $request->withAttribute('oauth_client_id', $client->id);
        $this->setUpStorage($request);

        return $request;
    }
}
