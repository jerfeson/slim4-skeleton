<?php

namespace App\Helpers;

use Exception;
use stdClass;

/**
 * Class Dynamic.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Dynamic extends stdClass
{
    /**
     * @param $key
     * @param $params
     *
     * @throws Exception
     */
    public function __call($key, $params)
    {
        if (!isset($this->{$key})) {
            throw new Exception(
                'Call to undefined method ' . get_class($this) . '::' . $key . '()'
            );
        }

        $subject = $this->{$key};
        call_user_func_array($subject, $params);
    }

    /**
     * @param $class
     * @param $param
     *
     * @return bool
     */
    public static function getIfExist($class, $param)
    {
        return $class && property_exists($class, $param) && $class->{$param} ? $class->{$param} : null;
    }
}
