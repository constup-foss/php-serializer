<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier\ContextAwareNameModifier;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier\ContextAwareValueModifier;

class SerializableChildClass
{
    public function __construct(
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        #[TransformPropertyValue(ContextAwareValueModifier::transform(...))]
        public string $changedNameAndValue,
    ) {
    }
}
