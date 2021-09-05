<?php

namespace App\Factory;

use App\App;
use Psr\Container\ContainerInterface;
use SlashTrace\EventHandler\DebugHandler;
use SlashTrace\SlashTrace as ST;

/**
 * Class SlashTraceFactory.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class SlashTraceFactory implements FactoryInterface
{
    public static function create(ContainerInterface $container)
    {
        $st = new ST();
        $st->addHandler(new DebugHandler());
        App::getConfig()->get('slashtrace', $st);

        return $st;
    }
}
