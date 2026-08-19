<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\ObjectProperty;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\IntPropertyClass;

readonly class NestedSerializableClass
{
    public function __construct(
        public ?IntPropertyClass $intPropertyClass
    ) {
    }
}
