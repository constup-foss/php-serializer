<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

readonly class ChildObjectTransformPropertyName
{
    public function __construct(
        public string $parentNoAttribute,
        public Child01 $child,
    ) {
    }
}
