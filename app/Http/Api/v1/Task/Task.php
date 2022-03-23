<?php

namespace App\Http\Api\v1\Task;

use App\Factory\TaskFactory;
use App\Http\Api\ApiController;
use App\Repository\Task\TaskRepository;
use Psr\Http\Message\ResponseInterface;

class Task extends ApiController
{
    private TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ResponseInterface
     */
    public function postAction(): ResponseInterface
    {
        $data = $this->getRequest()->getParsedBody();

        $task = TaskFactory::fromJson($data);

        $this->repository->save($task);

        return $this->getResponse()->withStatus(200);
    }
}
