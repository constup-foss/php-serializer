<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Serializer;

use ConstupFoss\PhpSerializer\Serializer\Serializer;
use ConstupFoss\PhpSerializer\Tests\Functional\Serializer\DataProvider\Serializer\ToJsonDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    #[DataProviderExternal(ToJsonDataProvider::class, 'provide_HappyFlow')]
    public function test_tpJson_HappyFlow(
        object $data,
        string $expected,
        array|object $attributeArguments = [],
        int $jsonFlags = 0,
        int $jsonDepth = 512,
    ): void {
        $class = new Serializer();

        $result = $class->toJson($data, $attributeArguments, $jsonFlags, $jsonDepth);
        $this->assertEquals($expected, $result);
    }
}
