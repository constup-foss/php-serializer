<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier\SimpleNameModifier;

readonly class SimpleTransformPropertyName
{
    public function __construct(
        public string $noAttributes,
        #[TransformPropertyName(SimpleNameModifier::modify(...))]
        public string $simpleName,
    ) {}
}