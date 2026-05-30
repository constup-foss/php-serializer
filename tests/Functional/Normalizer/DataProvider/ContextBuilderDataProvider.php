<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpSerializer\Exceptions\ContextException;
use ConstupFoss\PhpSerializer\Normalizer\ContextBuilder;

readonly class ContextBuilderDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'simple property attribute context' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('simpleName', function (ContextBuilder $builder): void {
                        $builder->attributeContext(TransformPropertyName::class, []);
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'simpleName' => (object)[
                            TransformPropertyName::class => (object)[
                                'context' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'nested property attribute context' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('child', function (ContextBuilder $builder): void {
                        $builder->property('contextAwareName', function (ContextBuilder $builder): void {
                            $builder->attributeContext(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'child' => (object)[
                            'contextAwareName' => (object)[
                                TransformPropertyName::class => (object)[
                                    'context' => ['TEST_FROM_CONTEXT_'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'typed array context' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('children', function (ContextBuilder $builder): void {
                        $builder->attributeInArray(SampleClass01::class, function (ContextBuilder $builder): void {
                            $builder->property('contextAwareName', function (ContextBuilder $builder): void {
                                $builder->attributeContext(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
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
                                            'context' => ['TEST_FROM_CONTEXT_'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'mixed type array context' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('children', function (ContextBuilder $builder): void {
                        $builder->attributeInArray(SampleClass01::class, function (ContextBuilder $builder): void {
                            $builder->property('contextAwareName', function (ContextBuilder $builder): void {
                                $builder->attributeContext(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                            });
                        });

                        $builder->attributeInArray(SampleClass02::class, function (ContextBuilder $builder): void {
                            $builder->property('stringProperty', function (ContextBuilder $builder): void {
                                $builder->attributeContext(TransformPropertyName::class, ['ALT_']);
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
                                            'context' => ['TEST_FROM_CONTEXT_'],
                                        ],
                                    ],
                                ],
                                SampleClass02::class => (object)[
                                    'stringProperty' => (object)[
                                        TransformPropertyName::class => (object)[
                                            'context' => ['ALT_'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'deeply nested context' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('childrenContainingArrays', function (ContextBuilder $builder): void {
                        $builder->attributeInArray(SampleNestedClass01::class, function (ContextBuilder $builder): void {
                            $builder->property('childWithArray', function (ContextBuilder $builder): void {
                                $builder->attributeInArray(SampleNestedClass02::class, function (ContextBuilder $builder): void {
                                    $builder->property('children', function (ContextBuilder $builder): void {
                                        $builder->attributeInArray(SampleClass01::class, function (ContextBuilder $builder): void {
                                            $builder->property('contextAwareName', function (ContextBuilder $builder): void {
                                                $builder->attributeContext(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                                            });
                                        });

                                        $builder->attributeInArray(SampleClass02::class, function (ContextBuilder $builder): void {
                                            $builder->property('stringProperty', function (ContextBuilder $builder): void {
                                                $builder->attributeContext(TransformPropertyName::class, ['ALT_']);
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
                                                                    'context' => ['TEST_FROM_CONTEXT_'],
                                                                ],
                                                            ],
                                                        ],
                                                        SampleClass02::class => (object)[
                                                            'stringProperty' => (object)[
                                                                TransformPropertyName::class => (object)[
                                                                    'context' => ['ALT_'],
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
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('rootChild', function (ContextBuilder $builder): void {
                        $builder->property('firstName', function (ContextBuilder $builder): void {
                            $builder->attributeContext(TransformPropertyName::class, ['FIRST_']);
                        });

                        $builder->property('lastName', function (ContextBuilder $builder): void {
                            $builder->attributeContext(TransformPropertyName::class, ['LAST_']);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'rootChild' => (object)[
                            'firstName' => (object)[
                                TransformPropertyName::class => (object)[
                                    'context' => ['FIRST_'],
                                ],
                            ],
                            'lastName' => (object)[
                                TransformPropertyName::class => (object)[
                                    'context' => ['LAST_'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'scope is restored after nested closure' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('parent', function (ContextBuilder $builder): void {
                        $builder->property('child', function (ContextBuilder $builder): void {
                            $builder->property('grandChild', function (ContextBuilder $builder): void {
                                $builder->attributeContext(TransformPropertyName::class, ['GRAND_']);
                            });
                        });

                        $builder->property('sibling', function (ContextBuilder $builder): void {
                            $builder->attributeContext(TransformPropertyName::class, ['SIBLING_']);
                        });
                    }),
                'expected' => (object)[
                    'root' => (object)[
                        'parent' => (object)[
                            'child' => (object)[
                                'grandChild' => (object)[
                                    TransformPropertyName::class => (object)[
                                        'context' => ['GRAND_'],
                                    ],
                                ],
                            ],
                            'sibling' => (object)[
                                TransformPropertyName::class => (object)[
                                    'context' => ['SIBLING_'],
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
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property(''),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating property node with a context path separator in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('root->child'),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating property node with whitespace in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('root child'),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating array node with an empty name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('children', function (ContextBuilder $builder): void {
                        $builder->attributeInArray('');
                    }),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating array node with a context path separator in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('children', function (ContextBuilder $builder): void {
                        $builder->attributeInArray('child->name');
                    }),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating array node with whitespace in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('children', function (ContextBuilder $builder): void {
                        $builder->attributeInArray('child name');
                    }),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating attribute node with an empty name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('simpleName', function (ContextBuilder $builder): void {
                        $builder->attributeContext('', []);
                    }),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating attribute node with a context path separator in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('simpleName', function (ContextBuilder $builder): void {
                        $builder->attributeContext('child->name', []);
                    }),
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating attribute node with whitespace in its name.' => [
                'builderFactory' => static fn (): ContextBuilder => new ContextBuilder()
                    ->property('simpleName', function (ContextBuilder $builder): void {
                        $builder->attributeContext('child name ', []);
                    }),
                'expectedException' => ContextException::class,
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
