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

readonly class AttributeProcessor
{
    /**
     * Process applicable attributes on the given property.
     *
     * Supported attributes (from the `constup-foss/php-attributes` package):
     *
     *  - `DoNotSerialize`
     *  - `TransformPropertyName`
     *  - `TransformPropertyValue`
     *
     * @see https://packagist.org/packages/constup-foss/php-attributes
     *
     * @param ReflectionProperty $reflectionProperty Reflection property to process.
     * @param object             $object             Object to normalize.
     * @param string             $contextPath        Normalization context path.
     * @param array|stdClass     $context            Normalization context.
     *
     * @throws ContextException
     * @throws ReflectionException
     *
     * @return Property|null
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
