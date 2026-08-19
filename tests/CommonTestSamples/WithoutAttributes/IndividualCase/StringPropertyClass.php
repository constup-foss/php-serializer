<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase;

readonly class StringPropertyClass
{
    public function __construct(
        public ?string $stringProperty,
    ) {}
}