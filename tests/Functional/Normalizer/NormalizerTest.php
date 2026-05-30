<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer;

use ConstupFoss\PhpSerializer\Normalizer\Normalizer;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalizer\DoNotSerializeDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalizer\MixedAttributesDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalizer\TransformPropertyNameDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalizer\TransformPropertyValueDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{
    #[DataProviderExternal(DoNotSerializeDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(TransformPropertyNameDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(TransformPropertyValueDataProvider::class, 'provide_HappyFlow')]
    #[DataProviderExternal(MixedAttributesDataProvider::class, 'provide_HappyFlow')]
    public function test_normalize_HappyFlow(
        object $object,
        array|object $context,
        array $expected
    ): void {
        $class = new Normalizer();
        $result = $class->normalize($object, $context);

        $this->assertEquals($expected, $result);
    }

    #[DataProviderExternal(TransformPropertyNameDataProvider::class, 'provide_ErrorFlow')]
    #[DataProviderExternal(TransformPropertyValueDataProvider::class, 'provide_ErrorFlow')]
    public function test_normalize_ErrorFlow(
        object $object,
        array|object $context,
        string $expectedException,
        int $expectedExceptionCode,
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);

        $class = new Normalizer();
        $class->normalize($object, $context);
    }
}