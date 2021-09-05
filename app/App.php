<?php

/**
 * @noinspection PhpIncludeInspection
 */

namespace App;

use App\Handler\HttpErrorHandler;
use App\Helpers\ArrayUtils;
use App\Initializer\InitializerInterface;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;
use Slim\App as SlimApp;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;
use Symfony\Component\Console\Application as ConsoleApp;

/**
 * Class App.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @author  Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
final class App
{
    public const DEVELOPMENT = 'development';

    public const PRODUCTION = 'production';
    private const VERSION = '3.0.0';

    /**
     * @var bool
     */
    private static bool $initialized = false;

    /**
     * @var Config
     */
    private static Config $config;

    /**
     * @var Container
     */
    private static Container $container;

//    /**
//     * @var bool
//     */
//    private static bool $testMode;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private static Request $request;

//    /**
//     * Indica se o ambiente é de desenvolvimento e possui parâmetro TESTING=TRUE
//     * na url da requisição. Nesse caso, significa que a requisição está
//     * rodando em algum teste de aceitação do codeception, e portanto
//     * as configurações de teste devem ser carregadas.
//     *
//     * @var bool
//     */
//    private static bool $hasTestingParam;

    /**
     * Bootstrap the application.
     *
     * @param bool $testMode
     *
     * @throws \Exception
     */
    public static function bootstrap(bool $testMode = false)
    {
        if (self::$initialized) {
            return;
        }

//        self::$hasTestingParam = false;
//        self::$testMode = $testMode && self::isDevelopment() && self::isConsole();
        self::initServerRequest();
        self::$initialized = true;
        self::defineAppConstants();
        self::$config = new Config(self::getSettingsArray());
        self::initContainer();
        self::$container->set(Config::class, self::$config);
        self::loadInitializers();

//        if (self::$testMode) {
//            return;
//        }

        if (self::isConsole()) {
            self::runConsoleCommands();
        }

        AppFactory::setContainer(self::$container);
        $app = AppFactory::create();
        self::$container->set(SlimApp::class, $app);
        self::$container->set(ServerRequestInterface::class, self::$request);
        self::loadCallableFile(CONFIG_PATH . 'middlewares.php')($app);
        self::loadCallableFile(CONFIG_PATH . 'routes.php')($app);
        self::addDefaultMiddleware($app);
        $app->run();
    }

    /**
     * @return bool
     */
    public static function isDevelopment(): bool
    {
        return self::getAppEnv() == self::DEVELOPMENT;
    }

    /**
     * @return array|false|string
     */
    public static function getAppEnv()
    {
        return getenv('APP_ENV') ? strtolower(getenv('APP_ENV')) : self::DEVELOPMENT;
    }

    /**
     * @return bool
     */
    public static function isConsole(): bool
    {
        return self::getAppType() == 'console';
    }

    /**
     * @return string
     */
    public static function getAppType(): string
    {
        return php_sapi_name() == 'cli' ? 'console' : 'http';
    }

    /**
     * @return bool
     */
    public static function isProduction(): bool
    {
        return self::getAppEnv() == self::PRODUCTION;
    }

    /**
     * @return \DI\Container
     */
    public static function getContainer(): Container
    {
        return self::$container;
    }

    /**
     * @return Config
     */
    public static function getConfig(): Config
    {
        return self::$config;
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        if (self::isDevelopment()) {
            return (string) time();
        }

        return self::VERSION;
    }

    /**
     * Inits the server request.
     */
    private static function initServerRequest()
    {
        //|| self::$testMode
        if (self::isConsole()) {
            return;
        }

        $serverRequestCreator = ServerRequestCreatorFactory::create();
        self::$request = $serverRequestCreator->createServerRequestFromGlobals();

        if (!self::isDevelopment()) {
            return;
        }

//        $params = self::$request->getQueryParams();
//        self::$hasTestingParam = isset($params['TESTING']) && $params['TESTING'] === 'TRUE';
    }

    /**
     * Defines all app constants.
     */
    private static function defineAppConstants()
    {
        define('DS', DIRECTORY_SEPARATOR);
        define('DATA_PATH', realpath(__DIR__ . '/../data/') . DS);
        define('CONFIG_PATH', realpath(__DIR__ . '/../config/') . DS);
        define('STORAGE_PATH', realpath(__DIR__ . '/../storage/') . DS);
        define('RESOURCES_PATH', realpath(__DIR__ . '/../resources/') . DS);
        define('MIGRATION_PATH', realpath(__DIR__ . '/../console/migration/') . DS);

//        if (self::$testMode || self::$hasTestingParam) {
//            define('TESTS_DATA_PATH', realpath(__DIR__ . '/../tests/_data/') . DIRECTORY_SEPARATOR);
//        }
    }

    /**
     * @return array
     */
    private static function getSettingsArray(): array
    {
        $default = self::loadArrayFile(CONFIG_PATH . 'default.php');
        $console = self::loadArrayFile(CONFIG_PATH . 'console.php');
        $environment = self::loadArrayFile(CONFIG_PATH . $default['default']['env'] . '.php');
//        $testing = (self::$testMode || self::$hasTestingParam) ? self::loadArrayFile(CONFIG_PATH . 'testing.php') : [];

        return ArrayUtils::arrayMergeRecursiveDistinct(
            $default,
            $console,
            $environment,
            //            $testing
        );
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private static function loadArrayFile(string $path): array
    {
        $result = self::loadFile($path);
        if (!is_array($result)) {
            throw new RuntimeException('The file ' . basename($path) . ' is not an configuration file!');
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return array|callable|mixed
     */
    private static function loadFile(string $path)
    {
        if (!file_exists($path)) {
            throw new RuntimeException('The file ' . basename($path) . ' does not exist!');
        }

        return require $path;
    }

    /**
     * @throws \Exception
     */
    private static function initContainer()
    {
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);

        if (!self::isDevelopment()) {
            $builder->enableCompilation(STORAGE_PATH . 'cache/container');
        }

        $builder->addDefinitions(self::loadArrayFile(CONFIG_PATH . 'dependencies.php'));
        self::$container = $builder->build();
    }

    /**
     * Load any initializers configured.
     */
    private static function loadInitializers()
    {
        foreach (self::loadArrayFile(CONFIG_PATH . 'initializers.php') as $class) {
            if (!in_array(InitializerInterface::class, class_implements($class))) {
                throw new RuntimeException('Invalid initializer provided: ' . $class);
            }

            $class::initialize(self::getContainer());
        }
    }

    /**
     * @throws \Exception
     */
    private static function runConsoleCommands()
    {
        $commands = self::$config->get('commands');
        $consoleApp = new ConsoleApp();

        if (empty($commands)) {
            exit(0);
        }

        foreach ($commands as $commandClass) {
            $consoleApp->add(self::$container->get($commandClass));
        }

        exit($consoleApp->run());
    }

    /**
     * @param string $path
     *
     * @return callable
     */
    private static function loadCallableFile(string $path): callable
    {
        $result = self::loadFile($path);

        if (!is_callable($result)) {
            throw new RuntimeException('The file ' . basename($path) . ' is not an configuration file!');
        }

        return $result;
    }

    /**
     * @param SlimApp $app
     */
    private static function addDefaultMiddleware(SlimApp $app)
    {
        $app->addRoutingMiddleware();
        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $displayErrorDetails = self::$config->get('default.display_error_details');
        $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
        self::$container->set(RouteResolverInterface::class, $app->getRouteResolver());
        self::$container->set(RouteCollectorInterface::class, $app->getRouteCollector());
    }
}
