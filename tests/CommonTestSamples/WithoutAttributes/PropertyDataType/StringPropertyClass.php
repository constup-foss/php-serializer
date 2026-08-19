<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\PropertyDataType;

readonly class StringPropertyClass
{
    public function __construct(
        public ?string $stringProperty,
    ) {
    }
}
