<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Unit\Utility\DataProvider\ContextUtility;

use ConstupFoss\PhpSerializer\Exceptions\ContextException;

readonly class GetByPathDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Object context. Valid object path.' => [
                'context' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => (object)[
                    'bar' => 'baz',
                ],
            ],
            'Object context. Valid array path.' => [
                'context' => (object)[
                    'root' => (object)[
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => [
                    'bar' => 'baz',
                ],
            ],
            'Object context. Deeply nested path.' => [
                'context' => (object)[
                    'root' => (object)[
                        'foo' => (object)[
                            'bar' => [
                                'baz' => (object)[
                                    'fee' => 'fie',
                                ],
                            ],
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->baz->fee',
                'expected' => 'fie',
            ],
            'Array context. Valid path.' => [
                'context' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo',
                'expected' => [
                    'bar' => 'baz',
                ],
            ],
            'Array context. Deeply nested path.' => [
                'context' => [
                    'root' => [
                        'foo' => [
                            'bar' => [
                                'baz' => [
                                    'fee' => 'fie',
                                ],
                            ],
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->baz->fee',
                'expected' => 'fie',
            ],
        ];
    }

    public static function provide_ErrorFlow(): array
    {
        return [
            'Path is empty' => [
                'context' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => '',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1001,
            ],
            'Path contains an empty segment' => [
                'context' => [
                    'foo' => [
                        'bar' => 'baz',
                    ],
                ],
                'path' => 'foo->->bar',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1002,
            ],
            'Path not found in object context. Object path.' => [
                'context' => (object)[
                    'root' => (object)[
                        'foo' => (object)[],
                    ],
                ],
                'path' => 'root->baz',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path not found in object context. Array path.' => [
                'context' => (object)[
                    'root' => (object)[
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->foo->bar->fee',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path not found in array context.' => [
                'context' => [
                    'root' => [
                        'foo' => [],
                    ],
                ],
                'path' => 'root->baz',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Path contains a missing segment.' => [
                'context' => [
                    'root' => [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
                'path' => 'root->fee->bar',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1000,
            ],
            'Context is empty' => [
                'context' => [],
                'path' => 'root->foo->bar',
                'expectedException' => ContextException::class,
                'expectedExceptionCode' => 1003,
            ],
        ];
    }
}
