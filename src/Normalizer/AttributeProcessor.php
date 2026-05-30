<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Constup\PhpAttributes\Common\IsAttributePresent;
use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerializeProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyNameProcessor;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValueProcessor;
use ConstupFoss\PhpSerializer\Exceptions\ContextException;
use ConstupFoss\PhpSerializer\Utility\ContextUtility;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

readonly class AttributeProcessor implements AttributeProcessorInterface
{
    /**
     * @param ReflectionProperty $reflectionProperty
     * @param object $object
     * @param string $contextPath
     * @param array|stdClass $context
     * @return Property|null
     * @throws ContextException
     * @throws ReflectionException
     */
    public function processAttributes(
        ReflectionProperty $reflectionProperty,
        object             $object,
        string             $contextPath,
        array|stdClass     $context,
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

        $contextPath = $contextPath . '->' . $reflectionProperty->getName();

        $convertedPropertyName = $propertyName;
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyName::class)) {
            $convertedPropertyName = TransformPropertyNameProcessor::transform(
                reflectionProperty: $reflectionProperty,
                context: ContextUtility::getByPath(
                    $context,
                    $contextPath . '->' . TransformPropertyName::class . '->context'
                ),
            );
        }

        $convertedPropertyValue = $reflectionProperty->getValue($object);
        if (IsAttributePresent::detectForReflectionProperty($reflectionProperty, TransformPropertyValue::class)) {
            $convertedPropertyValue = TransformPropertyValueProcessor::transform(
                object: $object,
                reflectionProperty: $reflectionProperty,
                context: ContextUtility::getByPath(
                    $context,
                    $contextPath . '->' . TransformPropertyValue::class . '->context'
                ),
            );
        }

        return PropertyFactory::produce($convertedPropertyName, $convertedPropertyValue, $contextPath);
    }
}
