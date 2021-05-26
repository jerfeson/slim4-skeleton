<?php

namespace App\Http\Api\V1;


use App\Enum\HttpStatusCode;
use App\Message\Message;
use App\Validation\ValidatorException;

/**
 * Class ApiController
 * @package App\Http
 */
class Api extends ApiController
{

    public function postAction()
    {
        if ($this->getId()) {
            return $this->putAction();
        }

        try {
            $this->validate();
            $item = $this->getBusiness()->create();
            return $this->respondWithData($item);
        } catch (ValidatorException $exception) {
            $payload = [
                'code' => $exception->getCode(),
                'messages' => json_decode($exception->getMessage()),
            ];

            return $this->respondWithData($payload, HttpStatusCode::NOT_ACCEPTABLE);
        } catch (\Exception $exception) {
            $payload = [
                'message' => $exception->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::BAD_REQUEST);
        }
    }


    public function putAction()
    {
        try {
            $this->validate();
            $item = $this->getBusiness()->update($this->getId());
            return $this->respondWithData($item);
        } catch (ValidatorException $exception) {
            $payload = [
                'code' => $exception->getCode(),
                'messages' => json_decode($exception->getMessage()),
            ];

            return $this->respondWithData($payload, HttpStatusCode::NOT_ACCEPTABLE);
        } catch (\Exception $exception) {
            $payload = [
                'message' => $exception->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::BAD_REQUEST);
        }
    }

    public function getAction()
    {
        try {
            if ($id = $this->getId()) {
                $data = $this->getBusiness()->getRepository()->findById($id);
            } else {
                $data = $this->getBusiness()->getAll($this->getParams(true, "GET"));
            }

            return $this->respondWithData($data);
        } catch (\Exception $exception) {

            $payload = [
                'message' => $exception->getMessage(),
            ];

            return $this->respondWithData($payload, $exception->getCode());
        }
    }

    public function deleteAction()
    {
        try {
            $this->validate();
            $this->getBusiness()->delete($this->getId());
            return $this->respondWithData([]);
        } catch (ValidatorException $exception) {
            $payload = [
                'code' => $exception->getCode(),
                'messages' => json_decode($exception->getMessage()),
            ];

            return $this->respondWithData($payload, HttpStatusCode::NOT_ACCEPTABLE);
        } catch (\Exception $exception) {
            $payload = [
                'message' => $exception->getMessage(),
            ];

            return $this->respondWithData($payload, HttpStatusCode::BAD_REQUEST);
        }
    }

    protected function validatePost()
    {
        // TODO: Implement validatePost() method.
    }

    protected function validateDelete()
    {
        /**
         * @todo to improve this, this validation was customized, because I had no idea how to put this in the respective
         */
        if (!$this->getId()) {
            $this->getValidator()->setErros(['id' => Message::ID_NOT_INFORMED]);
        }
    }

    protected function validatePut()
    {
        // TODO: Implement validatePut() method.
    }
}
