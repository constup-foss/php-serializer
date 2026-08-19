<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer;

use ConstupFoss\PhpSerializer\Normalizer\Normalizer;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\DoNotSerializeDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\MixedAttributesDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\TransformPropertyNameDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\TransformPropertyValueDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributesDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{
    #[DataProviderExternal(WithoutAttributesDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(DoNotSerializeDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(TransformPropertyNameDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(TransformPropertyValueDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(MixedAttributesDataProvider::class, 'provide_HappyFlow')]
    public function test_normalize_HappyFlow(
        object       $object,
        array|object $attributeArguments,
        array        $expected
    ): void {
        $class = new Normalizer();
        $result = $class->normalize($object, $attributeArguments);

        $this->assertEquals($expected, $result);
    }

    #[DataProviderExternal(TransformPropertyNameDataProvider::class, 'provide_ErrorFlow')]
    #[DataProviderExternal(TransformPropertyValueDataProvider::class, 'provide_ErrorFlow')]
    public function test_normalize_ErrorFlow(
        object       $object,
        array|object $attributeArguments,
        string       $expectedException,
        int          $expectedExceptionCode,
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $class = new Normalizer();
        $class->normalize($object, $attributeArguments);
    }
}
