<?php

namespace App\Factory;

use App\Entity\Task\Task;

class TaskFactory implements FromArray
{
    /**
     * @param array $data
     * @return Task
     */
    public static function fromArray(array $data): Task
    {
        if (!isset($data['description'])) {
            throw new \InvalidArgumentException("Improve the corrects parameters");
        }

        $task = new Task();
        $task->setDescription($data['description']);

        return $task;
    }
}
