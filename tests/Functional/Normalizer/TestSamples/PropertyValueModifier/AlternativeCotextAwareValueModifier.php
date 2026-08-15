<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\PropertyValueFccTransformerInterface;

readonly class AlternativeCotextAwareValueModifier implements PropertyValueFccTransformerInterface
{
    public static function transform(mixed $propertyValue, array $context = []): string
    {
        return $propertyValue . $context[0] . '_Alternative';
    }
}
