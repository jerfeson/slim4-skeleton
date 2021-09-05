<?php

namespace App\Factory;

use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as FlySystem;
use League\Flysystem\FilesystemInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FileSystem.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class FileSystem implements FactoryInterface
{
    public static function register()
    {
        app()->getContainer()->set(FilesystemInterface::class, function () {
            $configName = 'local';
            $configsOverride = [];
            $defaultConfigs = app()->getConfig("filesystem.{$configName}");
            $configs = array_merge($defaultConfigs, $configsOverride);

            $filesystem = null;

            switch ($configs['driver']) {
                case 'local':
                    $adapter = new Local($configs['root']);
                    $filesystem = new FlySystem($adapter);

                    break;

                case 'ftp':
                    $adapter = new FtpAdapter($configs);
                    $filesystem = new FlySystem($adapter);

                    break;

                default:
                    throw new \Exception('filesystem driver not found');

                    break;
            }

            return $filesystem;
        });
    }

    public static function create(ContainerInterface $container)
    {
        // TODO: Implement create() method.
    }
}
