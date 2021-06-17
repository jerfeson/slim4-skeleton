<?php

namespace Lib\Framework;

use App\Handlers\HttpErrorHandler;
use App\Http\Controller;
use App\ServiceProviders\ProviderInterface;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Lib\Utils\DotNotation;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Slim\Exception\HttpException;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

/**
 * Class App.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 */
class App
{
    const DEVELOPMENT = 'development';

    const PRODUCTION = 'production';
    /**
     * @var null
     */
    private static $instance = null;
    /**
     * @var string
     */
    public $appType;
    /**
     * @var array
     */
    private $settings;
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * App constructor.
     *
     * @param string $appType
     * @param array $settings
     *
     * @throws Exception
     */
    public function __construct($appType = '', $settings = [])
    {
        $this->appType = $appType;
        $this->settings = $settings;

        // Instantiate PHP-DI ContainerBuilder
        $container = new Container();

        AppFactory::setContainer($container);
        $this->app = AppFactory::create();
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();
        $response = $this->app->getResponseFactory()->createResponse();
        $this->getContainer()->set(Request::class, $request);
        $this->getContainer()->set(Response::class, $response);
    }

    public function prepare()
    {
        // Add Routing Middleware
        $this->app->addRoutingMiddleware();
        $this->errorHandlers();
    }

    private function errorHandlers()
    {
        $displayErrorDetails = $this->getConfig('default.debug');
        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = $this->app->addErrorMiddleware($displayErrorDetails, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
        return $this->app->getContainer();
    }

    /**
     * @param $settings
     *
     * @return bool
     */
    public function isProduction($settings)
    {
        if ($this->getConfig('default.env') == self::PRODUCTION) {
            return true;
        }

        return false;
    }

    /**
     * Application Singleton Factory.
     *
     * @param string $appName
     * @param array $settings
     *
     * @throws Exception
     *
     * @return App
     */
    final public static function instance($appName = '', $settings = [])
    {
        if (null === static::$instance) {
            static::$instance = new static($appName, $settings);
        }

        return static::$instance;
    }

    /**
     * @param $fn
     * @param array $args
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function __call($fn, $args = [])
    {
        if (method_exists($this->app, $fn)) {
            return call_user_func_array([
                $this->app,
                $fn,
            ], $args);
        }
        throw new Exception('Method not found :: ' . $fn);
    }

    /**
     * register providers.
     *
     * @return void
     */
    public function registerProviders()
    {
        $providers = (array)$this->getConfig('providers');
        array_walk($providers, function ($appName, $provider) {
            if (strpos($appName, $this->appType) !== false) {
                /** @var $provider ProviderInterface */
                $provider::register();
            }
        });
    }

    /**
     * get configuration param.
     *
     * @param string $param
     * @param string $defaultValue
     *
     * @return mixed
     */
    public function getConfig($param, $defaultValue = null)
    {
        $dn = new DotNotation($this->settings);

        return $dn->get($param, $defaultValue);
    }

    /**
     * register providers.
     *
     * @return void
     */
    public function registerMiddleware()
    {
        $middlewares = array_reverse((array)$this->getConfig('middleware'));
        array_walk($middlewares, function ($appType, $middleware) {
            if (strpos($appType, $this->appType) !== false) {
                $this->app->add(new $middleware());
            }
        });
    }

    /**
     * resolve and call a given class / method.
     *
     * @param Request $request
     * @param Response $response
     * @param string $className
     * @param string $methodName
     * @param array $requestParams
     * @param string $namespace
     *
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return Response
     */
    public function resolveRoute($className, $methodName, $requestParams = [], $namespace = "\App\Http")
    {
        try {
            $class = new ReflectionClass($namespace . '\\' . $className);

            if (!$class->isInstantiable() || !$class->hasMethod($methodName)) {
                throw new ReflectionException('route class is not instantiable or method does not exist');
            }
        } catch (ReflectionException $e) {
            throw new HttpException(
                $this->getContainer()->get(
                    Request::class
                ),
                $e->getMessage(),
                404
            );
        }

        $constructorArgs = $this->resolveMethodDependencies($class->getConstructor());
        $controller = $class->newInstanceArgs($constructorArgs);
        $method = $class->getMethod($methodName);
        $args = $this->resolveMethodDependencies($method, $requestParams);


        /**
         * todo make it better
         * @see Controller::setId()
         */
        if (isset($requestParams['idOrClass'])) {
            $controller->setId(intval($requestParams['idOrClass']));
        }

        if (isset($requestParams['method']) && $id = intval($requestParams['method'])) {
            $controller->setId($id);
        }

        $ret = $method->invokeArgs($controller, $args);

        return $this->sendResponse($ret);
    }

    /**
     * resolve dependencies for a given class method.
     *
     * @param ReflectionMethod $method
     * @param array $urlParams
     *
     * @return array
     */
    private function resolveMethodDependencies(ReflectionMethod $method, $urlParams = [])
    {
        return array_map(function ($dependency) use ($urlParams) {
            return $this->resolveDependency($dependency, $urlParams);
        }, $method->getParameters());
    }

    /**
     * resolve a dependency parameter.
     *
     * @param ReflectionParameter $param
     * @param array $urlParams
     *
     * @return mixed
     */
    private function resolveDependency(ReflectionParameter $param, $urlParams = [])
    {
        $resolve = null;
        // try to resolve from container
        try {
            // for controller method para injection from $_GET
            if (count($urlParams) && array_key_exists($param->name, $urlParams)) {
                return $urlParams[$param->name];
            }

            // param is instantiable
            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            if (!$param->getType()->getName()) {
                throw new ReflectionException("Unable to resolve method param {$param->name}");
            }

            $resolve = $this->resolve($param->getType()->getName());
        } catch (DependencyException $e) {
        } catch (NotFoundException $e) {
        } catch (ReflectionException $e) {
        }

        return $resolve;
    }

    /**
     * resolve a dependency from the container.
     *
     * @param string $name
     * @param array $params
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public function resolve($name, $params = [])
    {
        $c = $this->getContainer();
        if ($c->has($name)) {
            return is_callable($c->get($name)) ? call_user_func_array($c->get($name), $params) : $c->get($name);
        }

        if (!class_exists($name)) {
            throw new ReflectionException("Unable to resolve {$name}");
        }

        $reflector = new ReflectionClass($name);

        if (!$reflector->isInstantiable()) {
            throw new ReflectionException("Class {$name} is not instantiable");
        }

        if ($constructor = $reflector->getConstructor()) {
            $dependencies = $this->resolveMethodDependencies($constructor);

            return $reflector->newInstanceArgs($dependencies);
        }

        return new $name();
    }

    /**
     * return a response object.
     *
     * @param mixed $resp
     *
     * @throws ReflectionException
     *
     * @return mixed|Response
     */
    public function sendResponse($resp)
    {
        if ($resp instanceof Response) {
            $response = $resp;
        } elseif (is_array($resp) || is_object($resp)) {
            $response = $this->resolve(Response::class);
            $response = $response->getBody()->write(json_encode($resp));
        } elseif (!$resp) {
            $response = $this->resolve(Response::class);

            $response = $response->getBody()->write(
                json_encode(
                    [
                        'Method not implemented'
                    ]
                )
            );

        } else {
            $response = $this->resolve(Response::class);
            $response = $response->getBody()->write($resp);
        }

        return $response;
    }

    /**
     * get if running application is console.
     *
     * @return bool
     */
    public function isConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * @return string
     */
    public static function getAppEnv()
    {
        $env = self::DEVELOPMENT;
        if ($appEnv = getenv('APP_ENV')) {
            $env = $appEnv;
        }

        return $env;
    }
}
