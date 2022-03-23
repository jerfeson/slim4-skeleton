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
        try {
            $data = $this->getRequest()->getParsedBody();

            $task = TaskFactory::fromJson($data);

            $this->repository->save($task);
        } catch (\Exception $exception) {
            return $this->respondWithJson([
                "message" => 'Deu ruim'
            ], 500);
        }

        return $this->getResponse()->withStatus(200);
    }

    /**
     * @return ResponseInterface
     */
    public function getAction(): ResponseInterface
    {
        try {
            $id = $this->getRequest()->getAttribute('id');

            $task = $this->repository->findById($id);

            if (!$task) {
                throw new \DomainException("Não achei");
            }

            return $this->respondWithJson($task->jsonSerialize(), 200);
        } catch (\Exception $exception) {
            return $this->respondWithJson([
                "message" => 'não consegui achar'
            ], 500);
        }
    }
}
