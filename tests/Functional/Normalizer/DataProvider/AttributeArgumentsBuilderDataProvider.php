<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpSerializer\Normalizer\AttributeArgumentsBuilder;

readonly class AttributeArgumentsBuilderDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'simple property attribute context' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->addPropertyNode('simpleName', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addAttributeArgumentsNode(TransformPropertyName::class, []);
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
                    ->addPropertyNode('child', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addPropertyNode('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
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
                    ->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->addPropertyNode('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
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
                    ->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->addPropertyNode('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                            });
                        });

                        $builder->addArrayElementTypeNode(SampleClass02::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->addPropertyNode('stringProperty', function (AttributeArgumentsBuilder $builder): void {
                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['ALT_']);
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
                    ->addPropertyNode('childrenContainingArrays', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode(SampleNestedClass01::class, function (AttributeArgumentsBuilder $builder): void {
                            $builder->addPropertyNode('childWithArray', function (AttributeArgumentsBuilder $builder): void {
                                $builder->addArrayElementTypeNode(SampleNestedClass02::class, function (AttributeArgumentsBuilder $builder): void {
                                    $builder->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                                        $builder->addArrayElementTypeNode(SampleClass01::class, function (AttributeArgumentsBuilder $builder): void {
                                            $builder->addPropertyNode('contextAwareName', function (AttributeArgumentsBuilder $builder): void {
                                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['TEST_FROM_CONTEXT_']);
                                            });
                                        });

                                        $builder->addArrayElementTypeNode(SampleClass02::class, function (AttributeArgumentsBuilder $builder): void {
                                            $builder->addPropertyNode('stringProperty', function (AttributeArgumentsBuilder $builder): void {
                                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['ALT_']);
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
                    ->addPropertyNode('rootChild', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addPropertyNode('firstName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['FIRST_']);
                        });

                        $builder->addPropertyNode('lastName', function (AttributeArgumentsBuilder $builder): void {
                            $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['LAST_']);
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
                    ->addPropertyNode('parent', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addPropertyNode('child', function (AttributeArgumentsBuilder $builder): void {
                            $builder->addPropertyNode('grandChild', function (AttributeArgumentsBuilder $builder): void {
                                $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['GRAND_']);
                            });
                        });

                        $builder->addPropertyNode('sibling', function (AttributeArgumentsBuilder $builder): void {
                            $builder->addAttributeArgumentsNode(TransformPropertyName::class, ['SIBLING_']);
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
            'Creating array node with an empty name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode('');
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1004,
            ],
            'Creating array node with a context path separator in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode('child->name');
                    }),
                'expectedException' => MetadataTreeException::class,
                'expectedExceptionCode' => 1005,
            ],
            'Creating array node with whitespace in its name.' => [
                'builderFactory' => static fn (): AttributeArgumentsBuilder => new AttributeArgumentsBuilder()
                    ->addPropertyNode('children', function (AttributeArgumentsBuilder $builder): void {
                        $builder->addArrayElementTypeNode('child name');
                    }),
                'expectedException' => MetadataTreeException::class,
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
