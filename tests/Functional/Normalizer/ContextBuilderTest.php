<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer;

use ConstupFoss\PhpSerializer\Normalizer\ContextBuilder;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\ContextBuilderDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ContextBuilderTest extends TestCase
{
    #[DataProviderExternal(
        ContextBuilderDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_build_HappyFlow(
        callable $builderFactory,
        object $expected,
    ): void {
        $builder = $builderFactory();

        $this->assertInstanceOf(ContextBuilder::class, $builder);
        $this->assertEquals($expected, $builder->build());
    }

    #[DataProviderExternal(
        ContextBuilderDataProvider::class,
        'provide_ErrorFlow'
    )]
    public function test_build_ErrorFlow(
        callable $builderFactory,
        string $expectedException,
        int $expectedExceptionCode,
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $builderFactory();
    }
}
