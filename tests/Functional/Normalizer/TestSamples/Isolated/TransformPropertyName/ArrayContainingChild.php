<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

readonly class ArrayContainingChild
{
    /**
     * @param array $children
     */
    public function __construct(
        public array $children,
    ) {
    }
}
