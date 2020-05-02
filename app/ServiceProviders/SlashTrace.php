<?php

namespace App\ServiceProviders;

use SlashTrace\EventHandler\DebugHandler;
use SlashTrace\SlashTrace as ST;

/**
 * Class SlashTrace
 * @package App\ServiceProviders
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class SlashTrace implements ProviderInterface
{

    /**
     *
     */
    public static function register()
    {
        app()->getContainer()->set('slashtrace', function () {
            $st = new ST();
            $st->addHandler(new DebugHandler());
            return $st;
        });
    }

}