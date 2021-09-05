<?php

namespace App\Factory;

use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;

/**
 * Class Mailer.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Mailer implements FactoryInterface
{
    public static function register()
    {
        app()->getContainer()->set(PHPMailer::class, function () {
            $configName = 'default';
            $configsOverride = [];
            $defaultConfigs = app()->getConfig("mail.{$configName}");
            $configs = array_merge($defaultConfigs, $configsOverride);

            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->Host = $configs['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $configs['username'];
            $mail->Password = $configs['password'];
            $mail->SMTPSecure = $configs['secure'];
            $mail->Port = $configs['port'];

            $mail->setFrom($configs['from'], $configs['fromName']);

            return $mail;
        });
    }

    public static function create(ContainerInterface $container)
    {
        // TODO: Implement create() method.
    }
}
