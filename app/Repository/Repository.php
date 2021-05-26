<?php

namespace App\Repository;

use App\Enum\HttpStatusCode;
use App\Helpers\Dynamic;
use App\Message\Message;
use App\Model\Model;
use DI\NotFoundException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Support\Collection;
use Slim\Flash\Messages;

/**
 * Class Repository.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
abstract class Repository
{
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @param $item
     *
     * @return mixed
     * @throws Exception
     *
     */
    public function insert($item)
    {
        $qb = $this->newQuery();

        return $qb->create($item);
    }

    /**
     * @return Builder
     * @throws Exception
     *
     */
    protected function newQuery()
    {
        if ($model = $this->getModel()) {
            return (new $model())->newQuery();
        }

        throw new Exception(Message::MODEL_CLASS_NOT_DEFINED);
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->modelClass;
    }

    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance.
     *
     * @param int  $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     * @throws Exception
     *
     */
    public function getAll($params = null, $take = 15, $paginate = true)
    {
        return $this->doQuery(null, $take, $paginate, $params);
    }

    /**
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @param int                               $take
     * @param bool                              $paginate
     *
     * @return LengthAwarePaginator|EloquentQueryBuilder[]|EloquentCollection|Collection
     * @throws Exception
     *
     */
    protected function doQuery($query = null, $take = 15, $paginate = true, $params = null)
    {

        $last = Dynamic::getIfExist($params, 'last');

        if (is_null($query)) {
            $query = $this->newQuery();
            if ($params) {
                foreach ($params as $key => $param) {
                    if (in_array($key, Model::defaultFilters())) {
                        continue;
                    }
                    $query->where($key, '=', $param);
                }
            }
        }

        if (true == $paginate && !$last) {
            $paginate = $query->paginate($take, ['*'], 'page', Dynamic::getIfExist($params, 'page'));
            if (!$paginate->count()) {
                throw new Exception(Message::NO_RESULTS, HttpStatusCode::NO_CONTENT);
            }

            return $paginate;
        } else {
            if ($last) {
                return $query->get()->last();
            }
        }


        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        $result = $query->get();
        if (!$result->count()) {
            throw new Exception(Message::NO_RESULTS, HttpStatusCode::NO_CONTENT);
        }

        return $result;
    }

    /**
     * @param string      $column
     * @param string|null $key
     *
     * @return Collection
     * @throws Exception
     *
     */
    public function lists($column, $key = null)
    {
        return $this->newQuery()->lists($column, $key);
    }

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param int  $id
     * @param bool $fail
     *
     * @return Model
     * @throws Exception
     *
     * @throws NotFoundException
     */
    public function findById($id, $fail = true)
    {
        if ($fail) {
            $response = $this->newQuery()->find($id);
            if (!$response) {
                $message = app()->getContainer()->get(Messages::class);
                $message->addMessage(Message::STATUS_ERROR, Message::REGISTER_NOT_FOUND);
                throw new NotFoundException(Message::NO_RESULTS);
            }

            return $response;
        }

        return $this->newQuery()->find($id);
    }

    /**
     * Companion use this only if the select is not too specific
     *
     * @param array $params
     *
     * @return Collection
     * @throws Exception
     */
    public function findBy(array $params)
    {
        $query = $this->newQuery();
        foreach ($params as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $this->doQuery($query, false, false);
    }
}
