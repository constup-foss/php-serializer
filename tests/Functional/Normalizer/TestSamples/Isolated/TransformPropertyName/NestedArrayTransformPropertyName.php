<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

readonly class NestedArrayTransformPropertyName
{
    public function __construct(
        public string $parentNoAttribute,
        public ArrayContainingChild $child,
    ) {}
}