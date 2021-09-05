<?php

namespace App\Validator;

/**
 * Interface ValidatorInterface.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
interface ValidatorInterface
{
    /**
     * @param $data
     * @param string $fieldPrefix prefixo a se adicionar nos nomes dos campos validados
     *
     * @return bool
     */
    public function validate($data, string $fieldPrefix = ''): bool;

    /**
     * @return array
     */
    public function getMessages(): array;

    /**
     * @return bool
     */
    public function failed(): bool;

    /**
     * @return mixed
     */
    public function clearMessages();
}
