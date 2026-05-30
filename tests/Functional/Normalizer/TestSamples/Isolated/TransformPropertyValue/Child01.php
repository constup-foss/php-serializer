<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyValue;

use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier\ContextAwareValueModifier;

readonly class Child01
{
    public function __construct(
        public string $noAttributes,
        #[TransformPropertyValue(
            ContextAwareValueModifier::transform(...)
        )]
        public string $contextAwareName,
    ) {}
}