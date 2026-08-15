<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier\AlternativeContextAwareNameModifier;

class Child02
{
    public function __construct(
        public int $intProperty,
        #[TransformPropertyName(
            AlternativeContextAwareNameModifier::transform(...)
        )]
        public string $stringProperty,
    ) {
    }
}
