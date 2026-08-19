<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Serializer\DataProvider\Serializer;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\ArrayPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\IntPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\NestedServiceClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase\StringPropertyClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\RootClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\SerializableSubClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\ServiceClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\ServiceLeafClass;

readonly class ToJsonDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Without attributes. Individual case. Nested Service Class.' => [
                'data' => new NestedServiceClass(
                    serviceLeafClass: new ServiceLeafClass(),
                ),
                'expected' => '[]',
            ],
            'Without attributes. Individual case. Empty array.' => [
                'data' => new ArrayPropertyClass(
                    arrayProperty: [],
                ),
                'expected' => '{"arrayProperty":[]}',
            ],
            'Class without properties.' => [
                'data' => new ServiceLeafClass(),
                'expected' => '[]',
            ],
            'Without attributes. Combined cases.' => [
                'data' => new RootClass(
                    stringAttribute: 'string attribute',
                    intAttribute: 42,
                    boolAttribute: true,
                    floatAttribute: 3.14,
                    scalarArrayAttribute: [1, 2, 3],
                    nullableStringAttribute: null,
                    nullableIntAttribute: null,
                    nullableBoolAttribute: null,
                    nullableFloatAttribute: null,
                    nullableScalarArrayAttribute: null,
                    serviceClass: new ServiceClass(
                        serviceLeafClass: new ServiceLeafClass(),
                    ),
                    serializableSubClass: new SerializableSubClass(
                        serializableAttribute: 'serializable attribute',
                    ),
                ),
                'expected' => '{"stringAttribute":"string attribute","intAttribute":42,"boolAttribute":true,"floatAttribute":3.14,"scalarArrayAttribute":[1,2,3],"nullableStringAttribute":null,"nullableIntAttribute":null,"nullableBoolAttribute":null,"nullableFloatAttribute":null,"nullableScalarArrayAttribute":null,"serializableSubClass":{"serializableAttribute":"serializable attribute"}}',
            ],
        ];
    }
}
