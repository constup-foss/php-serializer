<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Constup\PhpAttributes\Common\IsAttributePresent;
use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerializeProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyNameProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValueProcessor;
use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

readonly class AttributeProcessor implements AttributeProcessorInterface
{
    /**
     * @param ReflectionProperty $reflectionProperty
     * @param object             $object
     * @param string             $attributeArgumentsPath
     * @param array|stdClass     $attributeArguments
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return Property|null
     */
    public function processAttributes(
        ReflectionProperty $reflectionProperty,
        object             $object,
        string             $attributeArgumentsPath,
        array|stdClass     $attributeArguments,
    ): ?Property {
        if (DoNotSerializeProcessor::detectForReflectionProperty($reflectionProperty)) {
            return null;
        }

        $propertyName = $reflectionProperty->getName();
        if (gettype($object->{$propertyName}) === 'object') {
            $reflectionClass = new ReflectionClass($object->{$propertyName});
            if (DoNotSerializeProcessor::detectForReflectionClass($reflectionClass)) {
                return null;
            }
        }

        $attributeArgumentsPath = $attributeArgumentsPath . '->' . $reflectionProperty->getName();

        $convertedPropertyName = $propertyName;
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyName::class)) {
            $convertedPropertyName = TransformPropertyNameProcessor::transform(
                reflectionProperty: $reflectionProperty,
                context: AttributeArgumentsUtility::getByPath(
                    $attributeArguments,
                    $attributeArgumentsPath . '->' . TransformPropertyName::class . '->attributeArguments'
                ),
            );
        }

        $convertedPropertyValue = $reflectionProperty->getValue($object);
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyValue::class)) {
            $convertedPropertyValue = TransformPropertyValueProcessor::transform(
                object: $object,
                reflectionProperty: $reflectionProperty,
                context: AttributeArgumentsUtility::getByPath(
                    $attributeArguments,
                    $attributeArgumentsPath . '->' . TransformPropertyValue::class . '->attributeArguments'
                ),
            );
        }

        return PropertyFactory::produce($convertedPropertyName, $convertedPropertyValue, $attributeArgumentsPath);
    }
}
