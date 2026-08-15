<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples;

use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerialize;

readonly class RootClass
{
    public function __construct(
        public string $noAttributesProperty,
        #[DoNotSerialize]
        public string $doNotSerializeProperty,
    ) {
    }
}
