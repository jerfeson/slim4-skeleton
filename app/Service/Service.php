<?php

namespace App\Service;

use App\Repository\Repository;
use App\Repository\RepositoryManager;
use Illuminate\Database\ConnectionInterface;
use ReflectionException;
use Throwable;

/**
 * Class Service.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
abstract class Service
{
    /**
     * @var RepositoryManager
     */
    private RepositoryManager $repositoryManager;

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @throws ReflectionException
     *
     * @return Repository|object|string
     */
    public function getRepository()
    {
        return $this->repositoryManager->get($this->getRepositoryClass());
    }

    /**
     * @return string
     */
    abstract protected function getRepositoryClass(): string;

    /**
     * @throws Throwable
     */
    protected function beginTransaction()
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * @return ConnectionInterface
     */
    protected function getConnection(): ConnectionInterface
    {
        return $this->repositoryManager->getConnection();
    }

    /**
     * @throws Throwable
     */
    protected function rollBack()
    {
        $this->getConnection()->rollBack();
    }

    /**
     * @throws Throwable
     */
    protected function commit()
    {
        $this->getConnection()->commit();
    }

    /**
     * @return RepositoryManager
     */
    protected function getRepositoryManager(): RepositoryManager
    {
        return $this->repositoryManager;
    }
}
