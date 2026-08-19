<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\ArrayPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\BoolPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\FloatPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\IntPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\StringPropertyClass;

readonly class ArrayPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Empty array' => [
                'object' => new ArrayPropertyClass([]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [],
                ],
            ],
            'Null value' => [
                'object' => new ArrayPropertyClass(null),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => null,
                ],
            ],
            'Scalar values of the same type' => [
                'object' => new ArrayPropertyClass([1, 2, 3]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [1, 2, 3],
                ],
            ],
            'Scalar values of different types' => [
                'object' => new ArrayPropertyClass([1, 'string value', true, null, 12.13]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [1, 'string value', true, null, 12.13],
                ],
            ],
            'Array of objects of the same type, null included' => [
                'object' => new ArrayPropertyClass([
                    new IntPropertyClass(12),
                    null,
                    new IntPropertyClass(23),
                ]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [
                        ['intProperty' => 12],
                        null,
                        ['intProperty' => 23],
                    ],
                ],
            ],
            'Array of objects of different types, null included' => [
                'object' => new ArrayPropertyClass([
                    new IntPropertyClass(12),
                    null,
                    new StringPropertyClass('sample value'),
                    new BoolPropertyClass(true),
                    new FloatPropertyClass(12.13),
                ]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [
                        ['intProperty' => 12],
                        null,
                        ['stringProperty' => 'sample value'],
                        ['boolProperty' => true],
                        ['floatProperty' => 12.13],
                    ],
                ],
            ],
            'Array of arrays of scalar values' => [
                'object' => new ArrayPropertyClass([
                    [12, null, 'sample value', true, 11.13],
                    [13, null, 'sample value', true, 22.13],
                    [14, null, 'sample value', true, 33.13],
                ]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [
                        [12, null, 'sample value', true, 11.13],
                        [13, null, 'sample value', true, 22.13],
                        [14, null, 'sample value', true, 33.13],
                    ],
                ],
            ],
            'Array of arrays of object values' => [
                'object' => new ArrayPropertyClass([
                    [
                        new IntPropertyClass(12),
                        new StringPropertyClass('sample value'),
                        null,
                        new BoolPropertyClass(true),
                        new FloatPropertyClass(11.13),
                    ],
                    [
                        new IntPropertyClass(13),
                        new StringPropertyClass('sample value'),
                        null,
                        new BoolPropertyClass(true),
                        new FloatPropertyClass(22.13),
                    ],
                ]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [
                        [
                            ['intProperty' => 12],
                            ['stringProperty' => 'sample value'],
                            null,
                            ['boolProperty' => true],
                            ['floatProperty' => 11.13],
                        ],
                        [
                            ['intProperty' => 13],
                            ['stringProperty' => 'sample value'],
                            null,
                            ['boolProperty' => true],
                            ['floatProperty' => 22.13],
                        ],
                    ],
                ],
            ],
            'Array of array objects' => [
                'object' => new ArrayPropertyClass([
                    new ArrayPropertyClass([
                        11,
                        null,
                        false,
                        new IntPropertyClass(111),
                        new FloatPropertyClass(111.11),
                        new StringPropertyClass('sample value 1'),
                        new BoolPropertyClass(true),
                    ]),
                    new ArrayPropertyClass([
                        22,
                        true,
                        new StringPropertyClass('sample value 2'),
                    ]),
                ]),
                'attributeArguments' => [],
                'expected' => [
                    'arrayProperty' => [
                        ['arrayProperty' => [
                            11,
                            null,
                            false,
                            ['intProperty' => 111],
                            ['floatProperty' => 111.11],
                            ['stringProperty' => 'sample value 1'],
                            ['boolProperty' => true],
                        ]],
                        ['arrayProperty' => [
                            22,
                            true,
                            ['stringProperty' => 'sample value 2'],
                        ]],
                    ],
                ],
            ],
        ];
    }
}
