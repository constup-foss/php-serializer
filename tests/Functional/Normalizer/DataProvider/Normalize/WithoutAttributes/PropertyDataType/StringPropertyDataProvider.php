<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\StringPropertyClass;

readonly class StringPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'String value in stringProperty' => [
                'object' => new StringPropertyClass('test'),
                'attributeArguments' => [],
                'expected' => [
                    'stringProperty' => 'test',
                ],
            ],
            'Null value in stringProperty' => [
                'object' => new StringPropertyClass(null),
                'attributeArguments' => [],
                'expected' => [
                    'stringProperty' => null,
                ],
            ],
        ];
    }
}
