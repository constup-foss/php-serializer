<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ConstupFoss\PhpSerializer\Normalizer\AttributeArgumentsBuilder;

readonly class AttributeArgumentsBuilderDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'simple property attribute context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('simpleName', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeArguments(TransformPropertyName::class, []);
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'simpleName' => (object)[
                            TransformPropertyName::class => (object)[
                                'attributeArguments' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'nested property attribute context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('child', function (AttributeArgumentsBuilder $builder): void {
                        $builder->property('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->attributeArguments(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                        });
                    }),
                'expected' => (object)[
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
            ],
            'typed array context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->property('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                $builder->attributeArguments(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                            });
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'children' => (object)[
                            '__types' => (object)[
                                SampleClass01::class => (object)[
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
            'mixed type array context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->property('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                $builder->attributeArguments(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                            });
                        });

                        $builder->attributeInArray(SampleClass02::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->property('stringProperty', function (AttributeArgumentsBuilder $builder): void {
                                $builder->attributeArguments(TransformPropertyName::class, ['ALT_']);
                            });
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'children' => (object)[
                            '__types' => (object)[
                                SampleClass01::class => (object)[
                                    'contextAwareName' => (object)[
                                        TransformPropertyName::class => (object)[
                                            'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                        ],
                                    ],
                                ],
                                SampleClass02::class => (object)[
                                    'stringProperty' => (object)[
                                        TransformPropertyName::class => (object)[
                                            'attributeArguments' => ['ALT_'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'deeply nested context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('childrenContainingArrays', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray(SampleNestedClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->property('childWithArray', function (AttributeArgumentsBuilder $builder): void {
                                $builder->attributeInArray(SampleNestedClass02::class, function (AttributeArgumentsBuilder $builder): void {
                                    $builder->property('children', function (AttributeArgumentsBuilder $builder): void {
                                        $builder->attributeInArray(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                                            $builder->property('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                                $builder->attributeArguments(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                                            });
                                        });

                                        $builder->attributeInArray(SampleClass02::class, function (AttributeArgumentsBuilder $builder): void {
                                            $builder->property('stringProperty', function (AttributeArgumentsBuilder $builder): void {
                                                $builder->attributeArguments(TransformPropertyName::class, ['ALT_']);
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'childrenContainingArrays' => (object)[
                            '__types' => (object)[
                                SampleNestedClass01::class => (object)[
                                    'childWithArray' => (object)[
                                        '__types' => (object)[
                                            SampleNestedClass02::class => (object)[
                                                'children' => (object)[
                                                    '__types' => (object)[
                                                        SampleClass01::class => (object)[
                                                            'contextAwareName' => (object)[
                                                                TransformPropertyName::class => (object)[
                                                                    'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                                                ],
                                                            ],
                                                        ],
                                                        SampleClass02::class => (object)[
                                                            'stringProperty' => (object)[
                                                                TransformPropertyName::class => (object)[
                                                                    'attributeArguments' => ['ALT_'],
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
            ],
            'multiple sibling properties in same scope' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('rootChild', function (AttributeArgumentsBuilder $builder): void {
                        $builder->property('firstName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->attributeArguments(TransformPropertyName::class, ['FIRST_']);
                        });

                        $builder->property('lastName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->attributeArguments(TransformPropertyName::class, ['LAST_']);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'rootChild' => (object)[
                            'firstName' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['FIRST_'],
                                ],
                            ],
                            'lastName' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['LAST_'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'scope is restored after nested closure' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('parent', function (AttributeArgumentsBuilder $builder): void {
                        $builder->property('child', function (AttributeArgumentsBuilder $builder): void {
                            $builder->property('grandChild', function (AttributeArgumentsBuilder $builder): void {
                                $builder->attributeArguments(TransformPropertyName::class, ['GRAND_']);
                            });
                        });

                        $builder->property('sibling', function (AttributeArgumentsBuilder $builder): void {
                            $builder->attributeArguments(TransformPropertyName::class, ['SIBLING_']);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'parent' => (object)[
                            'child' => (object)[
                                'grandChild' => (object)[
                                    TransformPropertyName::class => (object)[
                                        'attributeArguments' => ['GRAND_'],
                                    ],
                                ],
                            ],
                            'sibling' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['SIBLING_'],
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
            'Creating property node with an empty name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property(''),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating property node with a context path separator in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('root->child'),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating property node with whitespace in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('root child'),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating array node with an empty name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray('');
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating array node with a context path separator in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray('child->name');
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating array node with whitespace in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeInArray('child name');
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating attribute node with an empty name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('simpleName', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeArguments('', []);
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating attribute node with a context path separator in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('simpleName', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeArguments('child->name', []);
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating attribute node with whitespace in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->property('simpleName', function (AttributeArgumentsBuilder $builder): void {
                        $builder->attributeArguments('child name ', []);
                    }),
                'expectedException' => AttributeArgumentsException::class,
                'expectedExceptionCode' => 1005,
            ],
        ];
    }
}

final class SampleClass01
{
}

final class SampleClass02
{
}

final class SampleNestedClass01
{
}

final class SampleNestedClass02
{
}
