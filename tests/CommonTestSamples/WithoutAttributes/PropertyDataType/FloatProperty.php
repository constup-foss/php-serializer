<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType;

readonly class FloatProperty
{
    public function __construct(
        public ?float $floatProperty
    ) {
    }
}
