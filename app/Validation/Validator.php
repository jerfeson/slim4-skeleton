<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;

/**
 * Class Validator.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.1.0
 *
 * @version 1.1.0
 */
class Validator
{
    protected $erros = [];

    public function validate($request, array $rules)
    {

        foreach ($rules as $field => $rule) {
            if (count($rule->getRules()) > 1) {
                foreach ($rule->getRules() as $rule2) {
                    try {
                        if (isset($request->getParsedBody()[$field])) {
                            $rule2->setName(ucfirst($field))->assert($request->getParsedBody()[$field]);
                        }
                    } catch (ValidationException $exception) {
                        $this->erros[$field][] = $exception->getMessage();
                    }
                }
            } else {
                try {
                    if (isset($request->getParsedBody()[$field])) {
                        $rule->setName(ucfirst($field))->assert($request->getParsedBody()[$field]);
                    }
                } catch (NestedValidationException $exception) {
                    $this->erros[$field] = $exception->getMessages();
                }
            }
        }

        return $this;
    }

    /**
     * @param bool $api
     *
     * @return false|string
     */
    public function getErros($api = false)
    {
        return $api ? json_encode($this->erros) : $this->erros;
    }

    /**
     * @param array $erros
     */
    public function setErros(array $erros)
    {
        foreach ($erros as $key => $erro) {
            $this->erros[$key] = [$erro];
        }

    }

    /**
     * @return bool
     */
    public function failed()
    {
        return !(empty($this->erros));
    }
}
