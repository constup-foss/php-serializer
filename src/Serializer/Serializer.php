<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Serializer;

use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpSerializer\Normalizer\Normalizer;
use ConstupFoss\PhpSerializer\Normalizer\NormalizerInterface;
use ReflectionException;

readonly class Serializer implements SerializerInterface
{
    private ?NormalizerInterface $normalizer;

    public function __construct(
        ?NormalizerInterface $normalizer = null
    ) {
        if ($normalizer === null) {
            $this->normalizer = new Normalizer();
        } else {
            $this->normalizer = $normalizer;
        }
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     * @throws MetadataTreeException
     */
    public function toJson(
        object $data,
        array|object $attributeArguments = [],
        int $jsonFlags = 0,
        int $jsonDepth = 512
    ): false|string {
        $result = $this->normalizer->normalize($data, $attributeArguments);

        return json_encode($result, $jsonFlags, $jsonDepth);
    }

    /**
     * @inheritDoc
     *
     * @throws MetadataTreeException
     * @throws ReflectionException
     */
    public function toYaml(
        object $data,
        array|object $attributeArguments = [],
        int $yamlEncoding = YAML_ANY_ENCODING,
        int $yamlLineBreak = YAML_ANY_BREAK,
        array $callbacks = []
    ): string {
        $result = $this->normalizer->normalize($data, $attributeArguments);

        return yaml_emit($result, $yamlEncoding, $yamlLineBreak, $callbacks);
    }
}
