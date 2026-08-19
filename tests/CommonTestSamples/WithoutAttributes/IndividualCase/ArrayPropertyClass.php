<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase;

readonly class ArrayPropertyClass
{
    public function __construct(
        public ?array $arrayProperty,
    ) {
    }
}
