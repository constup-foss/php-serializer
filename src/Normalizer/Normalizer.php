<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use ReflectionClass;

class Normalizer
{
    private ?AttributeProcessorInterface $attributeProcessor;

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
        array|object $attributeArguments = [],
    ): array {
        $attributeArgumentsPath = 'root';

        return $this->normalizeObject($object, $attributeArgumentsPath, $attributeArguments);
    }

    private function normalizeObject(
        object $object,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): array {
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
                $attributeArgumentsPath,
                $attributeArguments,
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
                $property->attributeArgumentsPath,
                $attributeArguments,
            );

            /* since null values are handled above, this null value means that the property is not serializable */
            if ($value === null) {
                continue;
            }

            $result[$property->name] = $value;
        }

        return $result;
    }

    private function normalizeValue(
        mixed $value,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): mixed {
        switch (true) {
            case is_bool($value):
            case is_int($value):
            case is_float($value):
            case is_string($value):
                return $value;
            case is_object($value):
                return $this->normalizeObject($value, $attributeArgumentsPath, $attributeArguments);
            case is_array($value):
                return $this->normalizeArray($value, $attributeArgumentsPath, $attributeArguments);
            default:
                return null;
        }
    }

    private function normalizeArray(
        array $array,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): array {
        $result = [];

        foreach ($array as $key => $item) {
            $itemContextPath = $this->resolveArrayItemAttributeArgumentsPath($item, $attributeArgumentsPath, $attributeArguments);

            $result[$key] = $this->normalizeValue($item, $itemContextPath, $attributeArguments);
        }

        return $result;
    }

    private function resolveArrayItemAttributeArgumentsPath(
        mixed $item,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): string {
        if (!is_object($item)) {
            return $attributeArgumentsPath;
        }

        $candidatePath = $attributeArgumentsPath . '->__types->' . get_class($item);

        if (AttributeArgumentsUtility::hasPath($attributeArguments, $candidatePath)) {
            return $candidatePath;
        }

        return $attributeArgumentsPath;
    }
}
