<?php

namespace App\Repository;

use App\Entity\Entity;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Repository.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @author  Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
abstract class Repository
{
    private Entity $entity;
    private RepositoryManager $repositoryManager;

    public function __construct()
    {
        $entity = $this->getEntityClass();
        $this->setEntity(new $entity());
    }

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @param Entity $entity
     */
    public function save(Entity $entity)
    {
        $entity->save();
    }

    /**
     * @param array $item
     *
     * @return Builder|Entity
     */
    public function insert(array $item)
    {
        return $this->newQuery()->create($item);
    }

    /**
     * @param $id
     *
     * @return null|Entity|mixed
     */
    public function findById($id): ?Entity
    {
        return $this->newQuery()->find($id);
    }

    /**
     * @param int   $start
     * @param int   $limit
     * @param array $params
     * @param array $with
     *
     * @return Collection|Entity[]
     */
    public function findAllByPagination(int $start, int $limit, array $params = [], array $with = [])
    {
        return $this->queryWhere($params, $with)->limit($limit)->offset($start)->get();
    }

    /**
     * @param array $params
     * @param array $with
     *
     * @return Collection|object[]
     */
    public function findBy(array $params, array $with = [])
    {
        return $this->queryWhere($params, $with)->get();
    }

    /**
     * @param array $params
     * @param array $with
     *
     * @return null|Entity
     */
    public function findOneBy(array $params, array $with = []): ?object
    {
        return $this->queryWhere($params, $with)->limit(1)->get()->first();
    }

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function setRepositoryManager(RepositoryManager $repositoryManager): void
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return $this->entity->newQuery();
    }

    /**
     * @param array $params
     * @param array $with
     *
     * @return Builder
     */
    protected function queryWhere(array $params, array $with = []): Builder
    {
        $query = $this->newQuery();

        foreach ($params as $key => $value) {
            $query->where($key, '=', $value);
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * @return RepositoryManager
     */
    protected function getRepositoryManager(): RepositoryManager
    {
        return $this->repositoryManager;
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return $this->entity->getConnection();
    }
}
