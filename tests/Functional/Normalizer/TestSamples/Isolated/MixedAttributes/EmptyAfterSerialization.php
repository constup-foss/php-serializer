<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes;

use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerialize;

readonly class EmptyAfterSerialization
{
    public function __construct(
        #[DoNotSerialize]
        public string $doNotSerialize,
    ) {}
}