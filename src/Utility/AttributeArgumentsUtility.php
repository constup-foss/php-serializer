<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Utility;

use ConstupFoss\PhpPropertyMetadata\MetadataService;

readonly class AttributeArgumentsUtility extends MetadataService
{
    /**
     * Resolves the attribute arguments path for an array item.
     *
     * @param mixed        $item
     * @param string       $attributeArgumentsPath
     * @param array|object $attributeArguments
     *
     * @return string
     */
    public static function resolveArrayItemAttributeArgumentsPath(
        mixed $item,
        string $attributeArgumentsPath,
        array|object $attributeArguments,
    ): string {
        if (!is_object($item)) {
            return $attributeArgumentsPath;
        }

        $candidatePath = $attributeArgumentsPath . '->__types->' . get_class($item);

        if (self::hasPath($attributeArguments, $candidatePath)) {
            return $candidatePath;
        }

        return $attributeArgumentsPath;
    }
}
