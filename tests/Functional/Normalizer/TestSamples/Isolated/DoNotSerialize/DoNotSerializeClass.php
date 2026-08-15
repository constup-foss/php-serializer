<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\DoNotSerialize;

use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerialize;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\NonSerializableClass;

readonly class DoNotSerializeClass
{
    public function __construct(
        public string $noAttributes,
        #[DoNotSerialize]
        public string $doNotSerialize,
        public NonSerializableClass $nonSerializableObject,
    ) {
    }
}
