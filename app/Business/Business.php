<?php

namespace App\Business;

use App\Message\Message;
use App\Model\Model;
use App\Repository\Repository;
use DI\NotFoundException;
use Exception;
use Illuminate\Database\QueryException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Business.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
abstract class Business
{
    /**
     * @var string
     */
    protected $repositoryClass;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;

    /**
     * Business constructor.
     *
     */
    public function __construct()
    {
        $this->request = app()->getContainer()->get(Request::class);
        $this->response = app()->getContainer()->get(Response::class);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return all results.
     *
     * @param $params
     *
     * @return mixed
     * @throws Exception
     */
    public function getAll($params = null)
    {
        $this->validateParams($params);
        return $this->getRepository()->getAll($params, 15, $paginate = true);
    }

    /**
     * @throws Exception
     */
    private function validateParams($params)
    {
        $modelClass = $this->getRepository()->getModel();
        $class = new $modelClass();
        $filters = array_merge($class->getFillable(), Model::defaultFilters());
        $diff = array_diff(array_keys((array)$params), $filters);

        if (count($diff)) {
            $params = implode(",", $diff);
            throw new Exception(Message::PARAMETERS_NOT_ACCEPTED($params));
        }

    }

    /**
     * @return Repository $repository
     * @throws Exception
     *
     */
    public function getRepository()
    {
        if ($repository = $this->repositoryClass) {
            return new $repository();
        }
        throw new Exception(Message::REPOSITORY_CLASS_NOT_DEFINED);
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        try {
            Model::getConnectionResolver()->connection()->beginTransaction();
            $model = $this->getRepository()->getModel();
            $item = new $model();
            $item->fill($this->getParams(false));
            $item->save();
            Model::getConnectionResolver()->connection()->commit();
            return $item;
        } catch (Exception $exception) {
            Model::getConnectionResolver()->connection()->rollBack();
            if (strpos($exception->getMessage(), "uindex")) {
                throw new Exception(Message::DUPLICATE);
            }
            throw $exception;
        }
    }

    /**
     * @param bool $cast
     * @param bool $merge
     *
     * @return object|array
     */
    public function getParams($cast = true, $merge = false)
    {
        $parsedBody = $this->getRequest()->getParsedBody();
        $queryParams = $this->getRequest()->getQueryParams();

        if ($merge && $parsedBody && $queryParams) {
            $form = array_merge($parsedBody, $queryParams);
        } else {
            $form = $parsedBody ? $parsedBody : $queryParams;
        }

        return $cast ? (object)$form : $form;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function update(int $id)
    {
        try {
            Model::getConnectionResolver()->connection()->beginTransaction();
            $item = $this->getRepository()->findById($id);
            $item->fill($this->getParams(false));
            $item->save();
            Model::getConnectionResolver()->connection()->commit();
            return $item;
        } catch (Exception $exception) {
            Model::getConnectionResolver()->connection()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function delete(int $id)
    {
        try {
            Model::getConnectionResolver()->connection()->beginTransaction();
            $item = $this->getRepository()->findById($id);
            $item->delete();
            Model::getConnectionResolver()->connection()->commit();
        } catch (QueryException $exception) {
            $this->treatMysqlException($exception);
            Model::getConnectionResolver()->connection()->rollBack();
            throw $exception;
        } catch (Exception $exception) {
            Model::getConnectionResolver()->connection()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param $exception
     *
     * @throws Exception
     */
    private function treatMysqlException($exception)
    {
        // Integrity constraint violate
        if ($exception->getCode() === '23000') {
            if ($model = explode("`", $exception->getMessage())[3]) {
                $arr = explode("_", $model);
                $string = array_map(function ($val) {
                    return ucfirst($val) . " ";
                }, $arr);
                throw new Exception(Message::CANNOT_DELETE(implode($string)));
            }
        }
    }
}
