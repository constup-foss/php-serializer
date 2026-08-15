<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\Isolated\MixedAttributes;

use Constup\PhpAttributes\Serialization\DoNotSerialize\DoNotSerialize;
use Constup\PhpAttributes\Serialization\TransformPropertyName\TransformPropertyName;
use Constup\PhpAttributes\Serialization\TransformPropertyValue\TransformPropertyValue;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\NonSerializableClass;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyNameModifier\ContextAwareNameModifier;
use ConstupFoss\PhpSerializer\Tests\Functional\Normalizer\TestSamples\PropertyValueModifier\ContextAwareValueModifier;

readonly class SimpleMixedAttributesClass
{
    public function __construct(
        public string $noAttributes,
        #[DoNotSerialize]
        public int $doNotSerialize,
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        public string $changedName,
        #[TransformPropertyValue(ContextAwareValueModifier::transform(...))]
        public string $changedValue,
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        #[TransformPropertyValue(ContextAwareValueModifier::transform(...))]
        public string $changedNameAndValue,
        #[DoNotSerialize]
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        #[TransformPropertyValue(ContextAwareValueModifier::transform(...))]
        public string $doNotSerialize2,
        public NonSerializableClass $nonSerializableClass,
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        #[TransformPropertyValue(ContextAwareValueModifier::transform(...))]
        public NonSerializableClass    $nonSerializableClass2,
        #[DoNotSerialize]
        public NonSerializableClass    $nonSerializableClass3,
        public SerializableChildClass  $serializableChildClass,
        public EmptyAfterSerialization $emptyAfterSerialization,
        public ?SerializableChildClass $nullableSerializableChildClass,
        #[TransformPropertyValue(ContextAwareValueModifier::nullTheValue(...))]
        public ?SerializableChildClass $nullAfterValueTransformation,
        #[TransformPropertyName(ContextAwareNameModifier::transform(...))]
        public SerializableChildClass  $transformationInsideTransformedName,
    ) {
    }

}
