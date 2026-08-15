<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier\AlternativeCotextAwareValueModifier;

class Child02
{
    public function __construct(
        public int $intProperty,
        #[TransformPropertyValue(
            AlternativeCotextAwareValueModifier::transform(...)
        )]
        public string $stringProperty,
    ) {
    }
}
