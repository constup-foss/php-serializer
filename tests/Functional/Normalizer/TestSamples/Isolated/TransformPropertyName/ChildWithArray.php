<?php

declare(strict_types=1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\TransformPropertyName;

readonly class ChildWithArray
{
    public function __construct(
        public array $childWithArray,
    ) {}
}