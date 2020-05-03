<?php

use Lib\Framework\App;

/**
 * @param null $appName
 * @param array $settings
 *
 * @return App
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 */
function app($appName = null, $settings = [])
{
    return App::instance($appName, $settings);
}
