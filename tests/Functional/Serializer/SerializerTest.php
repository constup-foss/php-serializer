<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Serializer;

use ConstupFoss\PhpSerializer\Serializer\Serializer;
use ConstupFoss\PhpSerializer\Tests\Functional\Serializer\DataProvider\ToJson\MixedAttributesDataProvider as JsonMixedAttributesDataProvider;
use ConstupFoss\PhpSerializer\Tests\Functional\Serializer\DataProvider\ToYaml\MixedAttributesDataProvider as YamlMixedAttributesDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    #[DataProviderExternal(JsonMixedAttributesDataProvider::class, 'provide_HappyFlow')]
    public function test_toJson_HappyFlow(
        object $data,
        array $attributeArguments,
        int $jsonFlags,
        int $jsonDepth,
        string $expected
    ): void {
        $result = new Serializer()->toJson($data, $attributeArguments, $jsonFlags, $jsonDepth);

        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    #[DataProviderExternal(YamlMixedAttributesDataProvider::class, 'provide_HappyFlow')]
    public function test_Yaml_HappyFlow(
        object $data,
        array $attributeArguments,
        int $yamlEncoding,
        int $yamlLineBreak,
        array $callbacks,
        string $expected
    ): void {
        $result = new Serializer()->toYaml($data, $attributeArguments, $yamlEncoding, $yamlLineBreak, $callbacks);

        $this->assertEquals($expected, $result);
    }
}
