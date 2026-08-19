<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Serializer;

use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
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
     * @param object       $data
     * @param array|object $attributeArguments
     * @param int          $jsonFlags
     * @param int          $jsonDepth
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return false|string
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
     * @param object       $data
     * @param array|object $attributeArguments
     * @param int          $yamlEncoding
     * @param int          $yamlLineBreak
     * @param array        $callbacks
     *
     * @throws AttributeArgumentsException
     * @throws ReflectionException
     *
     * @return string
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
