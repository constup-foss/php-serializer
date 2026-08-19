<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\DataProvider\Normalize\WithoutAttributes\PropertyDataType;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\ObjectProperty\NestedServiceClass;
use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\ObjectProperty\ServiceLeafClass;

readonly class ObjectPropertyDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Service leaf class' => [
                'object' => new ServiceLeafClass(),
                'attributeArguments' => [],
                'expected' => [],
            ],
            'Nested service class' => [
                'object' => new NestedServiceClass(
                    new ServiceLeafClass()
                ),
                'attributeArguments' => [],
                'expected' => [],
            ],
            // Note: Even though this is a nested service class, this is an intended behavior. If you want this to
            // return `[]`, use the `DoNotSerialize` attribute. Automatic detection of service classes is not possible
            // because class properties can have interfaces as their types.
            'Nested service null' => [
                'object' => new NestedServiceClass(null),
                'attributeArguments' => [],
                'expected' => ['serviceLeafClass' => null],
            ],
        ];
    }
}
