<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType\ObjectProperty;

readonly class NestedServiceClass
{
    public function __construct(
        public ?ServiceLeafClass $serviceLeafClass,
    ) {
    }
}
