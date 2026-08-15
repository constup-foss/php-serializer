<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier;

use Constup\PhpAttributes\Serialization\TransformPropertyName\PropertyNameFccTransformerInterface;

readonly class ContextAwareNameModifier implements PropertyNameFccTransformerInterface
{
    public static function transform(string $propertyName, array $context = []): string
    {
        return $context[0] . $propertyName;
    }
}
