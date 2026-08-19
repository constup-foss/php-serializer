<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\BoolPropertyClass;

readonly class BoolPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Bool value in boolProperty' => [
                'object' => new BoolPropertyClass(true),
                'attributeArguments' => [],
                'expected' => [
                    'boolProperty' => true,
                ],
            ],
            'Null value in boolProperty' => [
                'object' => new BoolPropertyClass(null),
                'attributeArguments' => [],
                'expected' => [
                    'boolProperty' => null,
                ],
            ],
        ];
    }
}
