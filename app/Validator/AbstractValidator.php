<?php

namespace App\Validator;

use Respect\Validation\Exceptions\ValidationException;

/**
 * Class AbstractValidator.
 *
 * Basic implementation of the validator.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
abstract class AbstractValidator implements ValidatorInterface
{
    protected array $messages = [];

    protected string $fieldPrefix = '';

    /**
     * @var string[]
     */
    protected array $messageMap = [];
    private static string $defaultCode = 'invalid_field';
    private static string $defaultMessage = 'the field is invalid';
    private static string $fieldKey = 'field';
    private static string $messageKey = 'message';
    private static string $codeKey = 'code';

    public function clearMessages()
    {
        $this->messages = [];
    }

    public function failed(): bool
    {
        return !empty($this->getMessages());
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setFieldPrefix(string $fieldPrefix)
    {
        $this->fieldPrefix = $fieldPrefix;
    }

    public static function setDefaultMessage(string $code, string $message)
    {
        self::$defaultCode = $code;
        self::$defaultMessage = $message;
    }

    public static function setErrorFormat(string $fieldKey, string $messageKey, string $codeKey)
    {
        self::$fieldKey = $fieldKey;
        self::$messageKey = $messageKey;
        self::$codeKey = $codeKey;
    }

    /**
     * @param \Respect\Validation\ChainedValidator|\Respect\Validation\Validator $validator
     * @param string                                                             $fieldName
     * @param $fieldValue
     */
    protected function validateField($validator, string $fieldName, $fieldValue)
    {
        try {
            $validator->setName($fieldName);
            $validator->check($fieldValue);
        } catch (ValidationException $exception) {
            $this->addMessage(
                $fieldName,
                $exception->getMessage(),
                self::$defaultCode
            );
        }
    }

    /**
     * Retorna o nome do campo com base no caminho do campo.
     *
     * @param string ...$fieldPath
     *
     * @return string
     */
    protected function getFieldName(string ...$fieldPath): string
    {
        $result = '';
        $addToPrefix = true;

        foreach ($fieldPath as $param) {
            $result .= $addToPrefix ?
                $this->addToPrefix($param) :
                '[' . $param . ']';
            $addToPrefix = false;
        }

        return $result;
    }

    protected function addMessage(string $fieldName, string $message = '', string $code = ''): self
    {
        $message = str_replace('{{field}}', $fieldName, $message);
        $code = empty($code) ? self::$defaultCode : $code;
        $message = empty($message) ? self::$defaultMessage : $message;

        $this->messages[] = [
            self::$fieldKey => $fieldName,
            self::$messageKey => $message,
            self::$codeKey => $code,
        ];

        return $this;
    }

    /**
     * Adiciona uma nova mensagem de erro com base no código do erro.
     *
     * @param string $code
     * @param string $fieldName
     *
     * @return self
     */
    protected function addErrorCode(string $code, string $fieldName): self
    {
        $this->addMessage($fieldName, $this->messageMap[$code] ?? '', $code);

        return $this;
    }

    /**
     * Adiciona o prefixo configurado no nome do campo.
     *
     * @param $fieldName
     *
     * @return string
     */
    protected function addToPrefix($fieldName): string
    {
        return empty($this->fieldPrefix) ?
            sprintf('%s', $fieldName) :
            sprintf("{$this->fieldPrefix}[%s]", $fieldName);
    }

    /**
     * Adiciona uma lista mensagens que vieram de outro validador.
     *
     * @param array $list
     */
    protected function addMessageList(array $list)
    {
        foreach ($list as $messageData) {
            $this->addMessage(
                $messageData['campo'],
                $messageData['mensagem'],
                $messageData['codigo']
            );
        }
    }
}
