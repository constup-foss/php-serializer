<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType;

readonly class BoolPropertyClass
{
    public function __construct(
        public ?bool $boolProperty,
    ) {
    }
}
