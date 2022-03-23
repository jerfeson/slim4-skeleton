<?php

namespace App\Factory;

use App\Entity\Task\Task;

class TaskFactory implements FromJson
{
    /**
     * @param array $data
     * @return Task
     */
    public static function fromJson(array $data): Task
    {
        if (!isset($data['description'])) {
            throw new \DomainException("Improve the corrects parameters");
        }

        $task = new Task();
        $task->setDescription($data['description']);

        return $task;
    }
}
