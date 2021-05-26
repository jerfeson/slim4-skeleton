<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Lib\Utils\Session;

/**
 * Class Model.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    /**
     * @var int
     */
    public const STATUS_ACTIVE = 1;

    /**
     * @var int
     */
    public const STATUS_INACTIVE = 0;

    /**
     * @var int
     */
    protected $identifier;

    /**
     * @var string[]
     */
    protected $hidden = ['password'];

    /**
     * ObjectiveModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (Session::get('user') && !empty($this->attributes)) {
            foreach ($this->attributes as $key => $attribute) {
                $val = "{$key}_{$attribute}";
                $this->setRawAttributes([$val => Session::get('user')[$key][$val]], true);
            }
        }

        parent::__construct($attributes);
    }

    /**
     * @return string[]
     */
    public static function defaultFilters()
    {
        return [
            'page',
            'last'
        ];
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

}
