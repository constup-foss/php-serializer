<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase;

readonly class IntPropertyClass
{
    public function __construct(
        public ?int $intProperty,
    ) {}
}