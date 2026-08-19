<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes;

readonly class SerializableSubClass
{
    public function __construct(
        public string $serializableAttribute,
    ) {
    }
}
