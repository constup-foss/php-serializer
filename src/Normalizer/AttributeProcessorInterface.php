<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use ConstupFoss\PhpSerializer\Exceptions\ContextException;
use ReflectionException;
use ReflectionProperty;
use stdClass;

interface AttributeProcessorInterface
{
    /**
     * Process applicable attributes on the given property.
     *
     * Uses the normalization `$context` to pass context data to attribute processors. Applicable context is defined by
     * `$contextPath`.
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
    ): ?Property;
}