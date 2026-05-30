<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples;

use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerialize;

#[DoNotSerialize]
readonly class NonSerializableClass
{
    public function __construct(
        public string $thisShouldNotBeInTheResult = 'ERROR',
    ) {}
}