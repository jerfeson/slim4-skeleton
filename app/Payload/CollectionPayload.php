<?php

namespace App\Payload;

use Illuminate\Database\Eloquent\Collection;
use ReflectionClass;
use RuntimeException;

/**
 * Class CollectionPayload.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class CollectionPayload implements PayloadInterface
{
    /**
     * @var string
     */
    private string $payloadClass;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private Collection $objectList;

    /**
     * @param \Illuminate\Database\Eloquent\Collection $objectList
     * @param string                                   $payloadClass
     *
     * @throws \ReflectionException
     */
    public function __construct(Collection $objectList, string $payloadClass)
    {
        if (!class_exists($payloadClass)) {
            throw new RuntimeException('Invalid payload class');
        }

        $reflection = new ReflectionClass($payloadClass);

        if (!$reflection->implementsInterface(PayloadInterface::class)) {
            throw new RuntimeException('Invalid payload class');
        }

        $this->objectList = $objectList;
        $this->payloadClass = $payloadClass;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $payload = $this->payloadClass;
        $items = [];

        foreach ($this->objectList as $object) {
            $result = (new $payload($object))->toArray();

            if (!empty($result)) {
                $items[] = $result;
            }
        }

        return [
            'total' => count($this->objectList),
            'itens' => $items,
        ];
    }
}
