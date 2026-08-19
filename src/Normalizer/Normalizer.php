<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use ReflectionClass;
use ReflectionException;

readonly class Normalizer implements NormalizerInterface
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

    /**
     * @inheritDoc
     *
     * @param object       $object
     * @param array|object $attributeArguments
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return array
     */
    public function normalize(
        object $object,
        array|object $attributeArguments = [],
    ): array {
        return $this->normalizeObject(
            object: $object,
            attributeArgumentsPath: 'root',
            attributeArguments: $attributeArguments
        );
    }

    /**
     * Converts a PHP object to an array.
     *
     * @param object       $object
     * @param string       $attributeArgumentsPath
     * @param array|object $attributeArguments
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return array
     */
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

            if (
                // Since null values are handled above, this null value means that the property is not serializable.
                $value === null ||
                // When pure service classes or classes without properties are normalized, they return an empty array.
                // We need to filter them out and separate them from array properties that contain an empty array.
                (is_object($property->value) && $value === [])
            ) {
                continue;
            }

            $result[$property->name] = $value;
        }

        return $result;
    }

    /**
     * Converts a value of a property to a value that goes into the normalized array.
     *
     * @param mixed        $value
     * @param string       $attributeArgumentsPath
     * @param array|object $attributeArguments
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return mixed
     */
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

    /**
     * Normalizes an array.
     *
     * @param array        $array
     * @param string       $attributeArgumentsPath
     * @param array|object $attributeArguments
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return array
     */
    private function normalizeArray(
        array $array,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): array {
        $result = [];

        foreach ($array as $key => $item) {
            $itemAttributeArgumentsPath = AttributeArgumentsUtility::resolveArrayItemAttributeArgumentsPath(
                $item,
                $attributeArgumentsPath,
                $attributeArguments
            );

            $result[$key] = $this->normalizeValue($item, $itemAttributeArgumentsPath, $attributeArguments);
        }

        return $result;
    }
}
