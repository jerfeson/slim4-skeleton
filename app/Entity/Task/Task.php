<?php

namespace App\Entity\Task;

use App\Entity\Entity;

class Task extends Entity implements \JsonSerializable
{
    protected $table = 'task';

    protected $attributes = [
        'done' => 0
    ];

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
          "id" => $this->attributes['id'],
          "description" => $this->attributes['description'],
          "done" => $this->attributes['done'],
        ];
    }
}
