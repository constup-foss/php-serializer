<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\IndividualCase;

use ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes\ServiceLeafClass;

readonly class NestedServiceClass
{
    public function __construct(
        public ?ServiceLeafClass $serviceLeafClass,
    ) {
    }
}
