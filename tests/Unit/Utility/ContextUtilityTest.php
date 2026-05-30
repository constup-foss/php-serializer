<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Unit\Utility;

use ConstupFoss\PhpSerializer\Tests\Unit\Utility\DataProvider\ContextUtility\GetByPathDataProvider;
use ConstupFoss\PhpSerializer\Tests\Unit\Utility\DataProvider\ContextUtility\HasPathDataProvider;
use ConstupFoss\PhpSerializer\Utility\ContextUtility;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContextUtilityTest extends TestCase
{
    #[DataProviderExternal(
        GetByPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_getByPath_HappyFlow(
        array|stdClass $context,
        string         $path,
        mixed          $expected
    ): void {
        $class = new ContextUtility();
        $result = $class->getByPath($context, $path);

        $this->assertEquals($expected, $result);
    }

    #[DataProviderExternal(
        GetByPathDataProvider::class,
        'provide_ErrorFlow'
    )]
    public function test_getByPath_ErrorFlow(
        array|stdClass $context,
        string         $path,
        string         $expectedException,
        int            $expectedExceptionCode
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $class = new ContextUtility();
        $class->getByPath($context, $path);
    }

    #[DataProviderExternal(
        HasPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_hasPath_HappyFlow(
        array|stdClass $context,
        string         $path,
        bool           $expected
    ): void {
        $class = new ContextUtility();
        $result = $class->hasPath($context, $path);

        $this->assertEquals($expected, $result);
    }
}
