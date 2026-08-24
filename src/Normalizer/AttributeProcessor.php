<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Constup\PhpAttributes\Common\IsAttributePresent;
use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerializeProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyNameProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValueProcessor;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

readonly class AttributeProcessor implements AttributeProcessorInterface
{
    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     * @throws MetadataTreeException
     */
    public function processAttributes(
        ReflectionProperty $reflectionProperty,
        object             $object,
        string             $attributeArgumentsPath,
        array|stdClass     $attributeArguments,
    ): ?Property {
        if ($this->processDoNotSerialize($reflectionProperty, $object)) {
            return null;
        }

        $attributeArgumentsPath = $attributeArgumentsPath . '->' . $reflectionProperty->getName();

        $convertedPropertyName = $this->processTransformPropertyName(
            $reflectionProperty,
            $attributeArgumentsPath,
            $attributeArguments
        );

        $convertedPropertyValue = $this->processTransformPropertyValue(
            $reflectionProperty,
            $object,
            $attributeArgumentsPath,
            $attributeArguments,
        );

        return PropertyFactory::produce($convertedPropertyName, $convertedPropertyValue, $attributeArgumentsPath);
    }

    /**
     * Process `DoNotSerialize` attribute.
     *
     * @param ReflectionProperty $reflectionProperty
     * @param object             $object
     *
     * @throws ReflectionException
     *
     * @return bool `true` if the property should not be serialized, `false` otherwise.
     *
     * @see DoNotSerializeProcessor
     */
    private function processDoNotSerialize(
        ReflectionProperty $reflectionProperty,
        object $object
    ): bool {
        if (DoNotSerializeProcessor::detectForReflectionProperty($reflectionProperty)) {
            return true;
        }

        $propertyName = $reflectionProperty->getName();
        if (gettype($object->{$propertyName}) === 'object') {
            $reflectionClass = new ReflectionClass($object->{$propertyName});
            if (DoNotSerializeProcessor::detectForReflectionClass($reflectionClass)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process `TransformPropertyName` attribute.
     *
     * @param ReflectionProperty $reflectionProperty
     * @param string             $attributeArgumentsPath
     * @param array|stdClass     $attributeArguments
     *
     * @throws MetadataTreeException
     *
     * @return string
     *
     * @see TransformPropertyNameProcessor
     */
    private function processTransformPropertyName(
        ReflectionProperty $reflectionProperty,
        string             $attributeArgumentsPath,
        array|stdClass     $attributeArguments,
    ): string {
        $convertedPropertyName = $reflectionProperty->getName();
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyName::class)) {
            $convertedPropertyName = TransformPropertyNameProcessor::transform(
                reflectionProperty: $reflectionProperty,
                context: AttributeArgumentsUtility::getValueFromPath(
                    $attributeArguments,
                    $attributeArgumentsPath . '->' . TransformPropertyName::class . '->attributeArguments'
                ),
            );
        }

        return $convertedPropertyName;
    }

    /**
     * Process `TransformPropertyValue` attribute.
     *
     * @param ReflectionProperty $reflectionProperty
     * @param object             $object
     * @param string             $attributeArgumentsPath
     * @param array|stdClass     $attributeArguments
     *
     * @throws MetadataTreeException
     *
     * @return mixed
     *
     * @see TransformPropertyValueProcessor
     */
    private function processTransformPropertyValue(
        ReflectionProperty $reflectionProperty,
        object             $object,
        string             $attributeArgumentsPath,
        array|stdClass     $attributeArguments,
    ): mixed {
        $convertedPropertyValue = $reflectionProperty->getValue($object);
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyValue::class)) {
            $convertedPropertyValue = TransformPropertyValueProcessor::transform(
                object: $object,
                reflectionProperty: $reflectionProperty,
                context: AttributeArgumentsUtility::getValueFromPath(
                    $attributeArguments,
                    $attributeArgumentsPath . '->' . TransformPropertyValue::class . '->attributeArguments'
                ),
            );
        }

        return $convertedPropertyValue;
    }
}
