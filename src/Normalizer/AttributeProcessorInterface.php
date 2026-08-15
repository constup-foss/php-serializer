<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ReflectionException;
use ReflectionProperty;
use stdClass;

interface AttributeProcessorInterface
{
    /**
     * Process applicable attributes on the given property.
     *
     * Uses `$attributeArguments` to pass attribute arguments to attribute processors. Applicable attribute arguments
     * are defined at `$attributeArgumentsPath`.
     *
     * Supported attributes (from the `constup-foss/php-attributes` package):
     *
     *  - `DoNotSerialize`
     *  - `TransformPropertyName`
     *  - `TransformPropertyValue`
     *
     * @see https://packagist.org/packages/constup-foss/php-attributes
     *
     * @param ReflectionProperty $reflectionProperty     Reflection property to process.
     * @param object             $object                 Object to normalize.
     * @param string             $attributeArgumentsPath Attribute arguments path.
     * @param array|stdClass     $attributeArguments     Attribute arguments.
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
    ): ?Property;
}
