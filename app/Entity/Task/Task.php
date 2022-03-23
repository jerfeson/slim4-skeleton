<?php

namespace App\Entity\Task;

use App\Entity\Entity;

class Task extends Entity
{
    protected $table = 'task';

    protected $attributes = [
        'done' => 0
    ];

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description): Task
    {
        $this->attributes['description'] = $description;
        return $this;
    }
}
