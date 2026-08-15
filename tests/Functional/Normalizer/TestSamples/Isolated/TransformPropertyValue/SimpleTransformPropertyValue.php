<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier\SimpleValueModifier;

readonly class SimpleTransformPropertyValue
{
    public function __construct(
        public string $noAttributes,
        #[TransformPropertyValue(SimpleValueModifier::modify(...))]
        public string $simpleName,
    ) {
    }
}
