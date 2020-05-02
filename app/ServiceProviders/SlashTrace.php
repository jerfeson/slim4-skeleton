<?php

namespace App\ServiceProviders;
use SlashTrace\SlashTrace as ST;
use SlashTrace\EventHandler\DebugHandler;

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
        $st = new ST();
        $st->addHandler(new DebugHandler());

        app()->getContainer()->set('slashtrace', $st);
    }

}