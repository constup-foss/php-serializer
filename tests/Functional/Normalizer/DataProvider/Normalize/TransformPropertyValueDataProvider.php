<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\ArrayContainingChild;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\ArrayTransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\Child01;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\Child02;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\ChildObjectTransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\ChildWithArray;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\ContextAwareTransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\DoubleArray;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\NestedArrayTransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue\SimpleTransformPropertyValue;

readonly class TransformPropertyValueDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'TransformPropertyValue - isolated - simple - direct property.' => [
                'object' => new SimpleTransformPropertyValue(
                    noAttributes: 'noAttributes',
                    simpleName: 'simple value',
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'simpleName' => (object)[
                            TransformPropertyValue::class => (object)[
                                'attributeArguments' => [],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'noAttributes' => 'noAttributes',
                    'simpleName' => 'simple value_SimpleValueModifier',
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - direct property.' => [
                'object' => new ContextAwareTransformPropertyValue(
                    noAttributes: 'noAttributes',
                    contextAwareName: 'context aware value',
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'contextAwareName' => (object)[
                            TransformPropertyValue::class => (object)[
                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'noAttributes' => 'noAttributes',
                    'contextAwareName' => 'context aware value_TEST_FROM_CONTEXT',
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - nested property.' => [
                'object' => new ChildObjectTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    child: new Child01(
                        noAttributes: 'noAttributes',
                        contextAwareName: 'context aware value',
                    ),
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'child' => (object)[
                            'contextAwareName' => (object)[
                                TransformPropertyValue::class => (object)[
                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'child' => [
                        'noAttributes' => 'noAttributes',
                        'contextAwareName' => 'context aware value_TEST_FROM_CONTEXT',
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - direct property - object within list array - uniform array element types.' => [
                'object' => new ArrayTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    children: [
                        new Child01(
                            noAttributes: 'element 1 - noAttributes',
                            contextAwareName: 'element 1 - context aware value',
                        ),
                        new Child01(
                            noAttributes: 'element 2 - noAttributes',
                            contextAwareName: 'element 2 - context aware value',
                        ),
                        null,
                        9,
                        new Child01(
                            noAttributes: 'element 3 - noAttributes',
                            contextAwareName: 'element 3 - context aware value',
                        ),
                    ]
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'children' => (object)[
                            '__types' => (object)[
                                Child01::class => (object)[
                                    'contextAwareName' => (object)[
                                        TransformPropertyValue::class => (object)[
                                            'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'children' => [
                        [
                            'noAttributes' => 'element 1 - noAttributes',
                            'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        [
                            'noAttributes' => 'element 2 - noAttributes',
                            'contextAwareName' => 'element 2 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        null,
                        9,
                        [
                            'noAttributes' => 'element 3 - noAttributes',
                            'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                        ],
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - direct property - object within array - variable array element types.' => [
                'object' => new ArrayTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    children: [
                        new Child01(
                            noAttributes: 'element 1 - noAttributes',
                            contextAwareName: 'element 1 - context aware value',
                        ),
                        new Child01(
                            noAttributes: 'element 2 - noAttributes',
                            contextAwareName: 'element 2 - context aware value',
                        ),
                        null,
                        new Child02(
                            intProperty: 42,
                            stringProperty: 'child 02 1 string value',
                        ),
                        9,
                        new Child01(
                            noAttributes: 'element 3 - noAttributes',
                            contextAwareName: 'element 3 - context aware value',
                        ),
                        new Child02(
                            intProperty: 7,
                            stringProperty: 'child 02 2 string value',
                        ),
                    ]
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'children' => (object)[
                            '__types' => (object)[
                                Child01::class => (object)[
                                    'contextAwareName' => (object)[
                                        TransformPropertyValue::class => (object)[
                                            'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                        ],
                                    ],
                                ],
                                Child02::class => (object)[
                                    'stringProperty' => (object)[
                                        TransformPropertyValue::class => (object)[
                                            'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'children' => [
                        [
                            'noAttributes' => 'element 1 - noAttributes',
                            'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        [
                            'noAttributes' => 'element 2 - noAttributes',
                            'contextAwareName' => 'element 2 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        null,
                        [
                            'intProperty' => 42,
                            'stringProperty' => 'child 02 1 string value_TEST_FROM_CONTEXT_Alternative',
                        ],
                        9,
                        [
                            'noAttributes' => 'element 3 - noAttributes',
                            'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        [
                            'intProperty' => 7,
                            'stringProperty' => 'child 02 2 string value_TEST_FROM_CONTEXT_Alternative',
                        ],
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - direct property - object within associative array - uniform array element types.' => [
                'object' => new ArrayTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    children: [
                        'element01' => new Child01(
                            noAttributes: 'element 1 - noAttributes',
                            contextAwareName: 'element 1 - context aware value',
                        ),
                        'element02' => new Child01(
                            noAttributes: 'element 2 - noAttributes',
                            contextAwareName: 'element 2 - context aware value',
                        ),
                        'element03' => null,
                        'element04' => 9,
                        'element05' => new Child01(
                            noAttributes: 'element 3 - noAttributes',
                            contextAwareName: 'element 3 - context aware value',
                        ),
                    ]
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'children' => (object)[
                            '__types' => (object)[
                                Child01::class => (object)[
                                    'contextAwareName' => (object)[
                                        TransformPropertyValue::class => (object)[
                                            'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'children' => [
                        'element01' => [
                            'noAttributes' => 'element 1 - noAttributes',
                            'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        'element02' => [
                            'noAttributes' => 'element 2 - noAttributes',
                            'contextAwareName' => 'element 2 - context aware value_TEST_FROM_CONTEXT',
                        ],
                        'element03' => null,
                        'element04' => 9,
                        'element05' => [
                            'noAttributes' => 'element 3 - noAttributes',
                            'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                        ],
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - nested property - object within array - uniform array element types.' => [
                'object' => new NestedArrayTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    child: new ArrayContainingChild(
                        children: [
                            new Child01(
                                noAttributes: 'element 1 - noAttributes',
                                contextAwareName: 'element 1 - context aware value',
                            ),
                            new Child01(
                                noAttributes: 'element 2 - noAttributes',
                                contextAwareName: 'element 2 - context aware value',
                            ),
                            null,
                            9,
                            new Child01(
                                noAttributes: 'element 3 - noAttributes',
                                contextAwareName: 'element 3 - context aware value',
                            ),
                        ]
                    )
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'child' => (object)[
                            'children' => (object)[
                                '__types' => (object)[
                                    Child01::class => (object)[
                                        'contextAwareName' => (object)[
                                            TransformPropertyValue::class => (object)[
                                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'child' => [
                        'children' => [
                            [
                                'noAttributes' => 'element 1 - noAttributes',
                                'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                            ],
                            [
                                'noAttributes' => 'element 2 - noAttributes',
                                'contextAwareName' => 'element 2 - context aware value_TEST_FROM_CONTEXT',
                            ],
                            null,
                            9,
                            [
                                'noAttributes' => 'element 3 - noAttributes',
                                'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                            ],
                        ],
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - nested property - object within array - variable array element types.' => [
                'object' => new NestedArrayTransformPropertyValue(
                    parentNoAttribute: 'parent no attribute',
                    child: new ArrayContainingChild(
                        children: [
                            new Child01(
                                noAttributes: 'element 1 - noAttributes',
                                contextAwareName: 'element 1 - context aware value',
                            ),
                            new Child01(
                                noAttributes: 'element 2 - noAttributes',
                                contextAwareName: 'element 2 - context aware value',
                            ),
                            null,
                            new Child02(
                                intProperty: 42,
                                stringProperty: 'child 02 1 string value',
                            ),
                            9,
                            new Child01(
                                noAttributes: 'element 3 - noAttributes',
                                contextAwareName: 'element 3 - context aware value',
                            ),
                            new Child02(
                                intProperty: 7,
                                stringProperty: 'child 02 2 string value',
                            ),
                        ]
                    )
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'child' => (object)[
                            'children' => (object)[
                                '__types' => (object)[
                                    Child01::class => (object)[
                                        'contextAwareName' => (object)[
                                            TransformPropertyValue::class => (object)[
                                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                            ],
                                        ],
                                    ],
                                    Child02::class => (object)[
                                        'stringProperty' => (object)[
                                            TransformPropertyValue::class => (object)[
                                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'child' => [
                        'children' => [
                            [
                                'noAttributes' => 'element 1 - noAttributes',
                                'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                            ],
                            [
                                'noAttributes' => 'element 2 - noAttributes',
                                'contextAwareName' => 'element 2 - context aware value_TEST_FROM_CONTEXT',
                            ],
                            null,
                            [
                                'intProperty' => 42,
                                'stringProperty' => 'child 02 1 string value_TEST_FROM_CONTEXT_Alternative',
                            ],
                            9,
                            [
                                'noAttributes' => 'element 3 - noAttributes',
                                'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                            ],
                            [
                                'intProperty' => 7,
                                'stringProperty' => 'child 02 2 string value_TEST_FROM_CONTEXT_Alternative',
                            ],
                        ],
                    ],
                ],
            ],
            'TransformPropertyValue - isolated - context-aware - objects in nested arrays' => [
                'object' => new DoubleArray(
                    parentNoAttribute: 'parent no attribute',
                    childrenContainingArrays: [
                        new ChildWithArray(
                            childWithArray: [
                                [
                                    new ArrayContainingChild(
                                        children: [
                                            new Child01(
                                                noAttributes: 'element 1 - noAttributes',
                                                contextAwareName: 'element 1 - context aware value',
                                            ),
                                            new Child02(
                                                intProperty: 42,
                                                stringProperty: 'child 02 1 string value',
                                            ),
                                            9,
                                        ]
                                    ),
                                ],
                                [
                                    new ArrayContainingChild(
                                        children: [
                                            new Child02(
                                                intProperty: 42,
                                                stringProperty: 'child 02 1 string value',
                                            ),
                                            7,
                                            new Child01(
                                                noAttributes: 'element 3 - noAttributes',
                                                contextAwareName: 'element 3 - context aware value',
                                            ),
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ]
                ),
                'attributeArguments' => (object)[
                    'root' => (object)[
                        'childrenContainingArrays' => (object)[
                            '__types' => (object)[
                                ChildWithArray::class => (object)[
                                    'childWithArray' => (object)[
                                        '__types' => (object)[
                                            ArrayContainingChild::class => (object)[
                                                'children' => (object)[
                                                    '__types' => (object)[
                                                        Child01::class => (object)[
                                                            'contextAwareName' => (object)[
                                                                TransformPropertyValue::class => (object)[
                                                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                                                ],
                                                            ],
                                                        ],
                                                        Child02::class => (object)[
                                                            'stringProperty' => (object)[
                                                                TransformPropertyValue::class => (object)[
                                                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'expected' => [
                    'parentNoAttribute' => 'parent no attribute',
                    'childrenContainingArrays' => [
                        [
                            'childWithArray' => [
                                [
                                    [
                                        'children' => [
                                            [
                                                'noAttributes' => 'element 1 - noAttributes',
                                                'contextAwareName' => 'element 1 - context aware value_TEST_FROM_CONTEXT',
                                            ],
                                            [
                                                'intProperty' => 42,
                                                'stringProperty' => 'child 02 1 string value_TEST_FROM_CONTEXT_Alternative',
                                            ],
                                            9,
                                        ],
                                    ],
                                ],
                                [
                                    [
                                        'children' => [
                                            [
                                                'intProperty' => 42,
                                                'stringProperty' => 'child 02 1 string value_TEST_FROM_CONTEXT_Alternative',
                                            ],
                                            7,
                                            [
                                                'noAttributes' => 'element 3 - noAttributes',
                                                'contextAwareName' => 'element 3 - context aware value_TEST_FROM_CONTEXT',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function provide_ErrorFlow(): array
    {
        return [
            'TransformPropertyValue - Empty context for context aware attribute.' => [
                'object' => new SimpleTransformPropertyValue(
                    noAttributes: 'noAttributes',
                    simpleName: 'simple value',
                ),
                'attributeArguments' => [],
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1003,
            ],
        ];
    }
}
