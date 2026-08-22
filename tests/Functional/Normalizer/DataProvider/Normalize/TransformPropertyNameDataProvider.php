<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\ArrayContainingChild;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\ArrayTransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\Child01;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\Child02;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\ChildObjectTransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\ChildWithArray;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\ContextAwareTransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\DoubleArray;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\NestedArrayTransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName\SimpleTransformPropertyName;

readonly class TransformPropertyNameDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'TransformPropertyName - isolated - simple - direct property.' => [
                 'object' => new SimpleTransformPropertyName(
                     noAttributes: 'noAttributes',
                     simpleName: 'simple value',
                 ),
                 'attributeArguments' => (object)[
                     'root' => (object)[
                         'simpleName' => (object)[
                             TransformPropertyName::class => (object)[
                                 'attributeArguments' => [],
                             ],
                         ],
                     ],
                 ],
                 'expected' => [
                     'noAttributes' => 'noAttributes',
                     'SimpleNameModifier_simpleName' => 'simple value',
                 ],
             ],
             'TransformPropertyName - isolated - context-aware - direct property.' => [
                 'object' => new ContextAwareTransformPropertyName(
                     noAttributes: 'noAttributes',
                     contextAwareName: 'context aware value',
                 ),
                 'attributeArguments' => (object)[
                     'root' => (object)[
                         'contextAwareName' => (object)[
                             TransformPropertyName::class => (object)[
                                 'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                             ],
                         ],
                     ],
                 ],
                 'expected' => [
                     'noAttributes' => 'noAttributes',
                     'TEST_FROM_CONTEXT_contextAwareName' => 'context aware value',
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - nested property.' => [
                 'object' => new ChildObjectTransformPropertyName(
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
                                 TransformPropertyName::class => (object)[
                                     'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                 ],
                             ],
                         ],
                     ],
                 ],
                 'expected' => [
                     'parentNoAttribute' => 'parent no attribute',
                     'child' => [
                         'noAttributes' => 'noAttributes',
                         'TEST_FROM_CONTEXT_contextAwareName' => 'context aware value',
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - direct property - object within list array - uniform array element types.' => [
                 'object' => new ArrayTransformPropertyName(
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
                                         TransformPropertyName::class => (object)[
                                             'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                         ],
                         [
                             'noAttributes' => 'element 2 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 2 - context aware value',
                         ],
                         null,
                         9,
                         [
                             'noAttributes' => 'element 3 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
                         ],
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - direct property - object within array - variable array element types.' => [
                 'object' => new ArrayTransformPropertyName(
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
                                         TransformPropertyName::class => (object)[
                                             'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                         ],
                                     ],
                                 ],
                                 Child02::class => (object)[
                                     'stringProperty' => (object)[
                                         TransformPropertyName::class => (object)[
                                             'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                         ],
                         [
                             'noAttributes' => 'element 2 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 2 - context aware value',
                         ],
                         null,
                         [
                             'intProperty' => 42,
                             'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 1 string value',
                         ],
                         9,
                         [
                             'noAttributes' => 'element 3 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
                         ],
                         [
                             'intProperty' => 7,
                             'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 2 string value',
                         ],
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - direct property - object within associative array - uniform array element types.' => [
                 'object' => new ArrayTransformPropertyName(
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
                                         TransformPropertyName::class => (object)[
                                             'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                             ],
                         'element02' => [
                             'noAttributes' => 'element 2 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 2 - context aware value',
                         ],
                         'element03' => null,
                         'element04' => 9,
                         'element05' => [
                             'noAttributes' => 'element 3 - noAttributes',
                             'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
                         ],
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - nested property - object within array - uniform array element types.' => [
                 'object' => new NestedArrayTransformPropertyName(
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
                                             TransformPropertyName::class => (object)[
                                                 'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                             ],
                             [
                                 'noAttributes' => 'element 2 - noAttributes',
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 2 - context aware value',
                             ],
                             null,
                             9,
                             [
                                 'noAttributes' => 'element 3 - noAttributes',
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
                             ],
                         ],
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - nested property - object within array - variable array element types.' => [
                 'object' => new NestedArrayTransformPropertyName(
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
                                             TransformPropertyName::class => (object)[
                                                 'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                             ],
                                         ],
                                     ],
                                     Child02::class => (object)[
                                         'stringProperty' => (object)[
                                             TransformPropertyName::class => (object)[
                                                 'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                             ],
                             [
                                 'noAttributes' => 'element 2 - noAttributes',
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 2 - context aware value',
                             ],
                             null,
                             [
                                 'intProperty' => 42,
                                 'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 1 string value',
                             ],
                             9,
                             [
                                 'noAttributes' => 'element 3 - noAttributes',
                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
                             ],
                             [
                                 'intProperty' => 7,
                                 'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 2 string value',
                             ],
                         ],
                     ],
                 ],
            ],
             'TransformPropertyName - isolated - context-aware - objects in nested arrays' => [
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
                                                                 TransformPropertyName::class => (object)[
                                                                     'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                                                 ],
                                                             ],
                                                         ],
                                                         Child02::class => (object)[
                                                             'stringProperty' => (object)[
                                                                 TransformPropertyName::class => (object)[
                                                                     'attributeArguments' => ['TEST_FROM_CONTEXT_'],
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
                                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 1 - context aware value',
                                             ],
                                             [
                                                 'intProperty' => 42,
                                                 'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 1 string value',
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
                                                 'Alternative_TEST_FROM_CONTEXT_stringProperty' => 'child 02 1 string value',
                                             ],
                                             7,
                                             [
                                                 'noAttributes' => 'element 3 - noAttributes',
                                                 'TEST_FROM_CONTEXT_contextAwareName' => 'element 3 - context aware value',
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
            'TransformPropertyName - Empty context for context aware attribute.' => [
                'object' => new SimpleTransformPropertyName(
                    noAttributes: 'noAttributes',
                    simpleName: 'simple value',
                ),
                'attributeArguments' => [],
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1003,
            ],
        ];
    }
}
