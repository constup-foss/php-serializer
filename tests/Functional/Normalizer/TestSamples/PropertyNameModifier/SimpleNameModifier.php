<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier;

readonly class SimpleNameModifier
{
    public static function modify(string $propertyName): string
    {
        return 'SimpleNameModifier_' . $propertyName;
    }
}
