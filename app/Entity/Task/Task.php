<?php

namespace App\Entity\Task;

use App\Entity\Entity;

class Task extends Entity implements \JsonSerializable
{
    protected $table = 'task';

    protected $attributes = [
        'done' => 0
    ];

    protected $fillable = [
        'done',
        'description',
        'updated_at',
    ];

    public function setDescription($description)
    {
        $this->attributes['description'] = $description;
    }

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
