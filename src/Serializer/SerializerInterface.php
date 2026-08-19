<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Serializer;

interface SerializerInterface
{
    /**
     * Converts an object to JSON using `json_encode()` built-in function.
     *
     * @param object       $data               Object to be serialized.
     * @param array|object $attributeArguments Attribute arguments tree, if any attributes that are using First Class Callables are used during normalization.
     * @param int          $jsonFlags          `json_encode()` argument.
     * @param int          $jsonDepth          `json_encode()` argument.
     *
     * @return false|string Returns the result of `json_encode()` directly.
     *
     * @link https://www.php.net/manual/en/function.json-encode.php
     */
    public function toJson(
        object $data,
        array|object $attributeArguments = [],
        int $jsonFlags = 0,
        int $jsonDepth = 512
    ): false|string;

    /**
     * Converts an object to YAML using `yaml_emit()` function from the `yaml` PHP extension.
     *
     * @param object       $data               Object to be serialized.
     * @param array|object $attributeArguments Attribute arguments tree, if any attributes that are using First Class Callables are used during normalization.
     * @param int          $yamlEncoding       `yaml_emit()` argument.
     * @param int          $yamlLineBreak      `yaml_emit()` argument.
     * @param array        $callbacks          `yaml_emit()` argument.
     *
     * @return string Returns the result of `yaml_emit()` directly.
     *
     * @link https://php.net/manual/en/function.yaml-emit.php
     */
    public function toYaml(
        object $data,
        array|object $attributeArguments = [],
        int $yamlEncoding = YAML_ANY_ENCODING,
        int $yamlLineBreak = YAML_ANY_BREAK,
        array $callbacks = []
    ): string;
}
