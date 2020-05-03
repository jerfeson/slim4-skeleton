<?php


namespace App\Business;

use App\Message\Message;
use App\Repository\Repository;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Business
 * @package App\Business
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
abstract class Business
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    protected $repositoryClass;

    /**
     * Business constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Repository $repository
     * @throws Exception
     */
    public function getRepository()
    {
        if ($repository = $this->repositoryClass) {
            return new $repository();
        }
        throw new Exception(Message::REPOSITORY_CLASS_NOT_DEFINED);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return all results
     * @return mixed
     * @throws Exception
     */
    public function getAll()
    {
        return $this->getRepository()->getAll(15, $paginate = true);
    }
}