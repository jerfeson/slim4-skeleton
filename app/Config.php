<?php

namespace App;

use App\Helpers\DotNotation;

/**
 * Class Config
 * Represents the application's configuration.
 *
 * @author Thiago Daher <thiago_tsda@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
final class Config
{
    /**
     * @var DotNotation
     */
    private DotNotation $dotNotation;

    /**
     * Config constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->dotNotation = new DotNotation($settings);
    }

    /**
     * Gets the configuration with the specified name.
     *
     * @param string $param nome da configuração
     * @param null $defaultValue
     *
     * @return array|mixed|string|null
     */
    public function get(string $param, $defaultValue = null)
    {
        return $this->dotNotation->get($param, $defaultValue);
    }
}
