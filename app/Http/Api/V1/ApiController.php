<?php

namespace App\Http\Api\V1;

use App\Http\Controller;

/**
 * Class ApiController
 * @package App\Http
 */
abstract class ApiController extends Controller
{
    public abstract function getAction();
    public abstract function deleteAction();
    public abstract function postAction();
    public abstract function putAction();
    protected abstract function validatePost();
    protected abstract function validatePut();
    protected abstract function validateDelete();
}
