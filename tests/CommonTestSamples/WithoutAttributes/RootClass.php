<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\CommonTestSamples\WithoutAttributes;

readonly class RootClass
{
    public function __construct(
        public string $stringAttribute,
        public int $intAttribute,
        public bool $boolAttribute,
        public float $floatAttribute,
        public array $scalarArrayAttribute,
        public ?string $nullableStringAttribute,
        public ?int $nullableIntAttribute,
        public ?bool $nullableBoolAttribute,
        public ?float $nullableFloatAttribute,
        public ?array $nullableScalarArrayAttribute,
        public ServiceClass $serviceClass,
        public SerializableSubClass $serializableSubClass
    ) {
    }
}
