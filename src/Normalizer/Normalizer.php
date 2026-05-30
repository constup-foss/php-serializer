<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use ConstupFoss\PhpSerializer\Utility\ContextUtility;
use ReflectionClass;

class Normalizer
{
    private ?AttributeProcessor $attributeProcessor;

    public function __construct(
        ?AttributeProcessorInterface $attributeProcessor = null,
    ) {
        if ($attributeProcessor === null) {
            $this->attributeProcessor = new AttributeProcessor();
        } else {
            $this->attributeProcessor = $attributeProcessor;
        }
    }

    public function normalize(
        object $object,
        array|object $context = [],
    ): array
    {
        $contextPath = 'root';

        return $this->normalizeObject($object, $contextPath, $context);
    }

    private function normalizeObject(
        object $object,
        string $contextPath,
        array|object $context,
    ): array
    {
        $result = [];
        $reflectionClass = new ReflectionClass($object);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!$reflectionProperty->isInitialized($object)) {
                continue;
            }

            // handles null values before any potential transformation by attributes
            if ($reflectionProperty->getValue($object) === null) {
                $result[$reflectionProperty->getName()] = null;
                continue;
            }

            $property = $this->attributeProcessor->processAttributes(
                $reflectionProperty,
                $object,
                $contextPath,
                $context,
            );

            // DoNotSerialize attribute is present, skip this property
            if ($property === null) {
                continue;
            }

            // handles null values after any potential transformation by attributes
            if ($property->value === null) {
                $result[$property->name] = null;

                continue;
            }

            $value = $this->normalizeValue(
                $property->value,
                $property->contextPath,
                $context,
            );

            /** since null values are handled above, this null value means that the property is not serializable */
            if ($value === null) {
                continue;
            }

            $result[$property->name] = $value;
        }

        return $result;
    }

    private function normalizeValue(
        mixed $value,
        string $contextPath,
        array|object $context,
    ): mixed
    {
        switch (true) {
            case is_bool($value):
            case is_int($value):
            case is_float($value):
            case is_string($value):
                return $value;
            case is_object($value):
                return $this->normalizeObject($value, $contextPath, $context);
            case is_array($value):
                return $this->normalizeArray($value, $contextPath, $context);
            default:
                return null;
        }
    }

    private function normalizeArray(
        array $array,
        string $contextPath,
        array|object $context,
    ): array
    {
        $result = [];

        foreach ($array as $key => $item) {
            $itemContextPath = $this->resolveArrayItemContextPath($item, $contextPath, $context);

            $result[$key] = $this->normalizeValue($item, $itemContextPath, $context);
        }

        return $result;
    }

    private function resolveArrayItemContextPath(
        mixed $item,
        string $contextPath,
        array|object $context,
    ): string {
        if (!is_object($item)) {
            return $contextPath;
        }

        $candidatePath = $contextPath . '->__types->' . get_class($item);

        if (ContextUtility::hasPath($context, $candidatePath)) {
            return $candidatePath;
        }

        return $contextPath;
    }
}