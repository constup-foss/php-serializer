<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\PropertyValueFccTransformerInterface;

readonly class ContextAwareValueModifier implements PropertyValueFccTransformerInterface
{
    public static function transform(mixed $propertyValue, array $context = []): mixed
    {
        return $propertyValue . $context[0];
    }

    public static function nullTheValue(mixed $propertyValue, array $context = []): null
    {
        return null;
    }
}
