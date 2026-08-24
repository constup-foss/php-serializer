<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Serializer\DataProvider\ToJson;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes\EmptyAfterSerialization;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes\SerializableChildClass;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes\SimpleMixedAttributesClass;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\NonSerializableClass;

/**
 * @see \ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\MixedAttributesDataProvider
 */
readonly class MixedAttributesDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'MixedAttributes - simple - direct property.' => [
                'data' => new SimpleMixedAttributesClass(
                    noAttributes: 'no attributes',
                    doNotSerialize: 42,
                    changedName: 'changed name',
                    changedValue: 'changed value',
                    changedNameAndValue: 'changed name and value',
                    doNotSerialize2: 'do not serialize 2',
                    nonSerializableClass: new NonSerializableClass(),
                    nonSerializableClass2: new NonSerializableClass(),
                    nonSerializableClass3: new NonSerializableClass(),
                    serializableChildClass: new SerializableChildClass(
                        changedNameAndValue: 'changed name and value',
                    ),
                    emptyAfterSerialization: new EmptyAfterSerialization(
                        doNotSerialize: 'do not serialize',
                    ),
                    nullableSerializableChildClass: null,
                    nullAfterValueTransformation: new SerializableChildClass(
                        changedNameAndValue: 'changed name and value',
                    ),
                    transformationInsideTransformedName: new SerializableChildClass(
                        changedNameAndValue: 'changed name and value',
                    ),
                ),
                'attributeArguments' => [
                    'root' => (object)[
                        'changedName' => (object)[
                            TransformPropertyName::class => (object)[
                                'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                            ],
                        ],
                        'changedValue' => (object)[
                            TransformPropertyValue::class => (object)[
                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                            ],
                        ],
                        'changedNameAndValue' => (object)[
                            TransformPropertyName::class => (object)[
                                'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                            ],
                            TransformPropertyValue::class => (object)[
                                'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                            ],
                        ],
                        'serializableChildClass' => (object)[
                            'changedNameAndValue' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                ],
                                TransformPropertyValue::class => (object)[
                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                ],
                            ],
                        ],
                        'nullAfterValueTransformation' => (object)[
                            TransformPropertyValue::class => (object)[
                                'attributeArguments' => ['dummy param content'],
                            ],
                            'changedNameAndValue' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                ],
                                TransformPropertyValue::class => (object)[
                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                ],
                            ],
                        ],
                        'transformationInsideTransformedName' => (object)[
                            TransformPropertyName::class => (object)[
                                'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                            ],
                            'changedNameAndValue' => (object)[
                                TransformPropertyName::class => (object)[
                                    'attributeArguments' => ['TEST_FROM_CONTEXT_'],
                                ],
                                TransformPropertyValue::class => (object)[
                                    'attributeArguments' => ['_TEST_FROM_CONTEXT'],
                                ],
                            ],
                        ],
                    ],
                ],
                'jsonFlags' => JSON_PRETTY_PRINT,
                'jsonDepth' => 512,
                'expected' => '{
    "noAttributes": "no attributes",
    "TEST_FROM_CONTEXT_changedName": "changed name",
    "changedValue": "changed value_TEST_FROM_CONTEXT",
    "TEST_FROM_CONTEXT_changedNameAndValue": "changed name and value_TEST_FROM_CONTEXT",
    "serializableChildClass": {
        "TEST_FROM_CONTEXT_changedNameAndValue": "changed name and value_TEST_FROM_CONTEXT"
    },
    "nullableSerializableChildClass": null,
    "nullAfterValueTransformation": null,
    "TEST_FROM_CONTEXT_transformationInsideTransformedName": {
        "TEST_FROM_CONTEXT_changedNameAndValue": "changed name and value_TEST_FROM_CONTEXT"
    }
}',
            ],
        ];
    }
}
