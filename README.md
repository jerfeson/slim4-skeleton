# Slim Framework 4 Skeleton Application (http + cli)

[![Latest Version on Packagist](https://img.shields.io/github/release/jerfeson/slim4-skeleton.svg)](https://packagist.org/packages/jerfeson/slim4-skeleton)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/jerfeson/slim4-skeleton.svg)](https://packagist.org/packages/jerfeson/slim4-skeleton/stats)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application (Tested with slim 4.5). This application handles http and command line requests. This application ships with a few service providers and a Session middleware out of the box. Supports container resolution and auto-wiring.

To remove a service provider comment it on config/app.php file and remove it from composer.json, update composer.

Available service providers:

- [SlashTrace]
- [Monolog]
- [Twig]
- [Flash Message]
- [Codeception]
- [oAuth2]

### Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project jerfeson/slim4-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `storage/` is web writable.
* make the necessary changes in config file config/app.php

## Set permissions (Linux only)

    sudo chown -R www-data storage/
    sudo chmod -R ug+w storage/
    
    sudo chmod -R 760 storage/
    
    chmod +x bin/console.php
    
## Database setup
Create a new database for development

    mysql -e 'CREATE DATABASE IF NOT EXISTS default'

Copy the file: config/env.example.php to config/development.php

    cp config/env.example.php config/development.php
    
Change the connection configuration in config/development.php:

    'settings' => [
        'database' => [
            'default' => [
                'driver'    => 'mysql',
                'host'      => 'localhost',
                'database'  => 'default',
                'username'  => '',
                'password'  => '',



### Run it:

1. `$ cd [my-app-name]\public`
2. `$ php -S localhost:8080`
3. Browse to http://localhost:8080

### Key directories

* `app`:        Application code (models, controllers, cli commands, handlers, middleware, service providers and others)
* `config`:     Configuration files like db, mail, routes...
* `lib`:        Other project classes like utils, business logic and framework extensions
* `resources`:  Views as well as your raw, un-compiled assets such as LESS, SASS, or JavaScript.
* `storage`:    Log files, cache files...
* `public`:     The public directory contains `index.php` file, assets such as images, JavaScript, and CSS
* `vendor`:     Composer dependencies

### Console usage

* Usage: php bin/console.php [command-name]
* List: php bin/console.php For list all commands 
How to create a new command:
 1. Create a class under directory app\Console in namespace App\Console
 2. Your class should extend Symfony\Component\Console\Command\Command
 4. DONE!

Example:

Command class:
```php
namespace App\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The container
     * @param string|null $name The name
     */
    public function __construct(ContainerInterface $container, ?string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('example');
        $this->setDescription('A sample command');
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input The input
     * @param OutputInterface $output The output
     *
     * @return int The error code, 0 on success
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<info>Hello, console</info>'));
        return 0;
    }
}
```

Execute the class:method from command line:

```php
php bin/console.php example
```

### Code examples

Get application instance
```php
$app = \Lib\Framework\App::instance();
// or simpler using a helper function
$app = app();
```

### Codeception test examples

Have the version 79 of chrome installed. otherwise, [download] your version driver 

go to the test folder and run the following command. (Windows)
```
tests/_drivers/chromedriver.exe --url-base=/wd/hub
```
go to the test folder and run the following command. (linux)
```
./tests/_drivers/chromedriver --url-base=/wd/hub
```
go to project folder and run the following command.

```
./vendor/bin/codecept run --steps 
```
or 
``` 
php vendor/bin/codecept run --steps
``` 

## Roadmap

 - [ ] more service providers
 - [ ] more code examples
 
## Contributing

 - welcome to discuss a bugs, features and ideas.
 
## License

jerfeson/slim4-skeleton  is release under the MIT license.
 
## Thanks

This project is based on the project in [jupitern/slim3-skeleton] feel free to contribute to this and the other project.

- [jupitern]

[jupitern]: https://github.com/jupitern
[jupitern/slim3-skeleton]: https://github.com/jupitern/slim3-skeleton

[SlashTrace]:https://github.com/slashtrace/slashtrace
[Monolog]:https://github.com/Seldaek/monolog
[Eloquent]:https://github.com/illuminate/database
[Twig]:https://github.com/twigphp/Twig
[Flash Message]:https://github.com/slimphp/Slim-Flash
[Codeception]:https://codeception.com
[download]:https://sites.google.com/a/chromium.org/chromedriver/downloads
[oAuth2]:https://oauth2.thephpleague.com/