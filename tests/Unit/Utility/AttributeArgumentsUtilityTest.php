<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Unit\Utility;

use ConstupFoss\PhpSerializer\Tests\Unit\Utility\DataProvider\ContextUtility\GetByPathDataProvider;
use ConstupFoss\PhpSerializer\Tests\Unit\Utility\DataProvider\ContextUtility\HasPathDataProvider;
use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use stdClass;

class AttributeArgumentsUtilityTest extends TestCase
{
    #[DataProviderExternal(
        GetByPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_getByPath_HappyFlow(
        array|stdClass $attributeArguments,
        string         $path,
        mixed          $expected
    ): void {
        $class = new AttributeArgumentsUtility();
        $result = $class->getByPath($attributeArguments, $path);

        $this->assertEquals($expected, $result);
    }

    #[DataProviderExternal(
        GetByPathDataProvider::class,
        'provide_ErrorFlow'
    )]
    public function test_getByPath_ErrorFlow(
        array|stdClass $attributeArguments,
        string         $path,
        string         $expectedException,
        int            $expectedExceptionCode
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $class = new AttributeArgumentsUtility();
        $class->getByPath($attributeArguments, $path);
    }

    #[DataProviderExternal(
        HasPathDataProvider::class,
        'provide_HappyFlow'
    )]
    public function test_hasPath_HappyFlow(
        array|stdClass $attributeArguments,
        string         $path,
        bool           $expected
    ): void {
        $class = new AttributeArgumentsUtility();
        $result = $class->hasPath($attributeArguments, $path);

        $this->assertEquals($expected, $result);
    }
}
