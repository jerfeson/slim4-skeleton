<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Class Session.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class Session
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $settings = app()->getConfig('session');

            if (!is_dir($settings['filesPath'])) {
                mkdir($settings['filesPath'], 0777, true);
            }

            $current = session_get_cookie_params();
            $lifetime = (int)($settings['lifetime'] ?: $current['lifetime']);
            $path = $settings['path'] ?: $current['path'];
            $domain = $settings['domain'] ?: $current['domain'];
            $secure = (bool)$settings['secure'];
            $httponly = (bool)$settings['httponly'];

//            session_save_path($settings['filesPath']);
            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
            session_name($settings['name']);
            session_cache_limiter($settings['cache_limiter']);
            session_start();
        }

        return $handler->handle($request);
    }
}
