<?php

namespace App\Factory;

use App\Entity\Entity;

interface FromJson
{
    public static function fromJson(array $data) : Entity;
}
