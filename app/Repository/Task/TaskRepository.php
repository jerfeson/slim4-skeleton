<?php

namespace App\Repository\Task;

use App\Entity\Task\Task;
use App\Repository\Repository;

class TaskRepository extends Repository
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Task::class;
    }
}
