<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize;

use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\DoNotSerialize\DoNotSerializeClass;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\NonSerializableClass;

readonly class DoNotSerializeDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'DoNotSerialize - isolated. Only serializes applicable properties. Non-serializable object is not serialized.' => [
                'object' => new DoNotSerializeClass(
                    noAttributes: 'noAttributes',
                    doNotSerialize: 'doNotSerialize',
                    nonSerializableObject: new NonSerializableClass(),
                ),
                'attributeArguments' => [],
                'expected' => [
                    'noAttributes' => 'noAttributes',
                ],
            ],
        ];
    }
}
