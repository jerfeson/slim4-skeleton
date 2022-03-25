<?php

namespace App\Factory;

use App\Entity\Entity;

interface FromArray
{
    public static function fromArray(array $data) : Entity;
}
