<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalizer;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\ArrayPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\IntPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\NestedServiceClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\StringPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\ServiceLeafClass;

readonly class WithoutAttributesDataProvider
{
    public static function provide_HappyFlow(): array {
        return [
            'Nested service class.' => [
                'object' => new NestedServiceClass(
                    serviceLeafClass: new ServiceLeafClass()
                ),
                'attributeArguments' => [],
                'expected' => [],
            ],
            'Class without properties.' => [
                'object' => new ServiceLeafClass(),
                'attributeArguments' => [],
                'expected' => [],
            ],
            'Empty array.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: []
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => []],
            ],
            'Scalar array.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: [1, 2, 3]
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => [1, 2, 3]],
            ],
            'Array of the same objects.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: [
                        new IntPropertyClass(23),
                        new IntPropertyClass(34),
                        new IntPropertyClass(45)
                    ]
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => [
                        0 => ['intProperty' => 23],
                        1 => ['intProperty' => 34],
                        2 => ['intProperty' => 45]
                    ]
                ],
            ],
            'Array of the same objects with null included.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: [
                        new IntPropertyClass(23),
                        null,
                        new IntPropertyClass(34),
                        new IntPropertyClass(45),
                    ]
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => [
                        0 => ['intProperty' => 23],
                        1 => null,
                        2 => ['intProperty' => 34],
                        3 => ['intProperty' => 45]
                    ]
                ],
            ],
            'Array of the same objects with null leaf value.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: [
                        new IntPropertyClass(null),
                        null,
                        new IntPropertyClass(23),
                        new IntPropertyClass(34)
                    ]
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => [
                    0 => ['intProperty' => null],
                    1 => null,
                    2 => ['intProperty' => 23],
                    3 => ['intProperty' => 34]
                ]],
            ],
            'Array of different objects.' => [
                'object' => new ArrayPropertyClass(
                    arrayProperty: [
                        new IntPropertyClass(23),
                        new StringPropertyClass('string value'),
                        new IntPropertyClass(34)
                    ]
                ),
                'attributeArguments' => [],
                'expected' => ['arrayProperty' => [
                    0 => ['intProperty' => 23],
                    1 => ['stringProperty' => 'string value'],
                    2 => ['intProperty' => 34]
                ]],
            ],
        ];
    }
}