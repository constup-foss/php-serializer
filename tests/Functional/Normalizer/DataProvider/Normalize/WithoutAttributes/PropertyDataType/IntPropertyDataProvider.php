<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\IntPropertyClass;

readonly class IntPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Integer value in intProperty' => [
                'object' => new IntPropertyClass(42),
                'attributeArguments' => [],
                'expected' => [
                    'intProperty' => 42,
                ],
            ],
            'Null value in intProperty' => [
                'object' => new IntPropertyClass(null),
                'attributeArguments' => [],
                'expected' => [
                    'intProperty' => null,
                ],
            ],
        ];
    }
}
