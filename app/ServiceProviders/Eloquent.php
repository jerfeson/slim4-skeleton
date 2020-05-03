<?php


namespace App\ServiceProviders;


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionInterface;


/**
 * Class Eloquent
 * @package App\ServiceProviders
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class Eloquent implements ProviderInterface
{

    /**
     *
     */
    public static function register()
    {
        $dbSettings = app()->getConfig('settings.database');

        // register connections
        $capsule = new Capsule;
        foreach ($dbSettings as $name => $configs) {
            $capsule->addConnection($dbSettings[$name], $name);
        }

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        app()->getContainer()->set(ConnectionInterface::class, function ()
        {
            $conn = Capsule::connection('default');
            if ($conn->getConfig('profiling') == true) {
                $conn->enableQueryLog();
            }

            return $conn;
        });
    }
}