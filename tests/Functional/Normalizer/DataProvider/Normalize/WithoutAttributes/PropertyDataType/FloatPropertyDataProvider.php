<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\FloatPropertyClass;

readonly class FloatPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Float value in floatProperty' => [
                'object' => new FloatPropertyClass(42.42),
                'attributeArguments' => [],
                'expected' => [
                    'floatProperty' => 42.42,
                ],
            ],
            'Null value in floatProperty' => [
                'object' => new FloatPropertyClass(null),
                'attributeArguments' => [],
                'expected' => [
                    'floatProperty' => null,
                ],
            ],
        ];
    }
}
