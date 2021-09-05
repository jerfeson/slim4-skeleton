<?php

namespace App\Repository;

use Illuminate\Database\ConnectionInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Class RepositoryManager.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class RepositoryManager
{
    private ConnectionInterface $connection;

    /**
     * @var Repository[]
     */
    private array $cache;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param string $repositoryClass
     *
     * @throws ReflectionException
     *
     * @return Repository|object
     */
    public function get(string $repositoryClass): Repository
    {
        if (isset($this->cache[$repositoryClass])) {
            return $this->cache[$repositoryClass];
        }

        if (!class_exists($repositoryClass)) {
            throw new RuntimeException('The repository does not exist!');
        }

        $reflection = new ReflectionClass($repositoryClass);

        if (!$reflection->isSubclassOf(Repository::class)) {
            throw new RuntimeException('The specified class is not an repository!');
        }

        $repository = new $repositoryClass();
        $entityClass = $repository->getEntityClass();
        $repository->setEntity(new $entityClass());
        $repository->setRepositoryManager($this);
        $this->cache[$repositoryClass] = $repository;

        return $repository;
    }
}
