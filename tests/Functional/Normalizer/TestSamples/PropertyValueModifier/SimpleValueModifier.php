<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier;

readonly class SimpleValueModifier
{
    public static function modify(string $propertyValue): string
    {
        return $propertyValue . '_SimpleValueModifier';
    }
}