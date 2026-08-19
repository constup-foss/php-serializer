<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

interface NormalizerInterface
{
    /**
     * Converts an `$object` to an array.
     *
     * The normalization process can use First Class Callables as attribute arguments. If that is the case, you can
     * provide the attribute arguments tree with `$attributeArguments`.
     *
     * @param object       $object             Objetc to be normalized.
     * @param array|object $attributeArguments Attribute arguments tree, if any attributes that are using First Class
     *                                         Callables are used during normalization.
     *
     * @return array
     */
    public function normalize(
        object $object,
        array|object $attributeArguments = [],
    ): array;
}
