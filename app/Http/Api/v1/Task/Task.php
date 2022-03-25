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
            $id = $this->getRequest()->getAttribute('id');

            $data = $this->getRequest()->getParsedBody();

            if (!$data) {
                throw new \DomainException("Informe os dados");
            }

            if ($id) {
                $newTask = TaskFactory::fromArray($data);

                $task = $this->repository->findById($id);

                if (!$task) {
                    throw new \DomainException("Não achei");
                }

                $fields = $newTask->jsonSerialize();
                unset($fields['id']);

                $task->update($fields);
            } else {
                $task = TaskFactory::fromArray($data);
                $this->repository->save($task);
            }

        } catch (\Exception $exception) {
            return $this->respondWithJson([
                "message" => $exception->getMessage()
            ], 500);
        }

        return $this->respondWithJson($task->jsonSerialize(), 200);
    }

    /**
     * @return ResponseInterface
     */
    public function getAction(): ResponseInterface
    {
        try {
            $id = $this->getRequest()->getAttribute('id');

            if ($id) {
                $tasks = $this->repository->findById($id);
            } else {
                $tasks = $this->repository->findAll();
            }

            if (!$tasks) {
                throw new \DomainException("Não achei");
            }

            return $this->respondWithJson($tasks->jsonSerialize(), 200);
        } catch (\Exception $exception) {
            return $this->respondWithJson([
                "message" => 'não consegui achar'
            ], 500);
        }
    }

    /**
     * @return ResponseInterface
     */
    public function deleteAction(): ResponseInterface
    {
        try {
            $id = $this->getRequest()->getAttribute('id');

            if (!$id) {
                throw new \DomainException("Informe os dados");
            }

            $this->repository->deleteById($id);
        } catch (\Exception $exception) {
            return $this->respondWithJson([
                "message" => 'não consegui achar'
            ], 500);
        }

        return $this->getResponse()->withStatus(200);
    }
}
