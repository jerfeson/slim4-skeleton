# Slim Framework 4 Skeleton Application (http + cli)

[![CircleCI](https://circleci.com/gh/jerfeson/slim4-skeleton.svg?style=svg)](https://circleci.com/gh/jerfeson/slim4-skeleton)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application (Tested with slim 4.5). This application handles http and command line requests. This application ships with a few service providers and a Session middleware out of the box. Supports container resolution and auto-wiring.

To remove a service provider comment it on config/app.php file and remove it from composer.json, update composer.

Available service providers:

- [SlashTrace]
- [Monolog]
- [Twig]
- [Flash Message]
- [Codeception]

### Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project jerfeson/slim4-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `storage/` is web writable.
* make the necessary changes in config file config/app.php

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

### Codeception test examples

Have the version 79 of chrome installed. otherwise, [download] your version driver 

go to the test folder and run the following command. (Windows)
```
tests/_drivers/chromedriver_79_windows.exe --url-base=/wd/hub
```
go to the test folder and run the following command. (linux)
```
./tests/_drivers/chromedriver_79_linux --url-base=/wd/hub
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

jerfeson/slim4-skeleton is release under the Apache 2 license.
 
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