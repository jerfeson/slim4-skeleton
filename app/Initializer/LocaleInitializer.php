<?php

namespace App\Initializer;

use App\App;
use App\Validator\AbstractValidator;
use Psr\Container\ContainerInterface;
use Respect\Validation\Factory;
use Symfony\Component\Translation\Translator;

/***
 * Class LocaleInitializer
 *  Initializes any functionality related to the application's localization and translation.
 * @package App\Initializer
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 *
 */
class LocaleInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     */
    public static function initialize(ContainerInterface $container)
    {
        $config = App::getConfig()->get('default');
        setlocale(LC_ALL, "{$config['locale']}.{$config['encoding']}");
        date_default_timezone_set($config['timezone']);
        locale_set_default($config['locale']);

        $translator = $container->get(Translator::class);
        Factory::setDefaultInstance((new Factory())->withTranslator([$translator, 'trans']));
        AbstractValidator::setDefaultMessage('INVALID_FIELD', 'the field is invalid');
        AbstractValidator::setErrorFormat('field', 'message', 'code');
    }
}
