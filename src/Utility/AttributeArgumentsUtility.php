<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Utility;

use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use stdClass;

class AttributeArgumentsUtility
{
    public const string SEPARATOR = '->';

    /**
     * Returns the value at the given path in attribute arguments.
     *
     * Path nodes are separated by `->`.
     *
     * @param array|stdClass $attributeArguments
     * @param string         $path
     *
     * @throws AttributeArgumentsException when path has invalid format or is not found in attribute arguments.
     *
     * @return mixed
     */
    public static function getByPath(array|stdClass $attributeArguments, string $path): mixed
    {
        if (trim($path) === '') {
            throw new AttributeArgumentsException()->pathIsEmpty();
        }

        if (empty($attributeArguments)) {
            throw new AttributeArgumentsException()->attributeArgumentsAreEmpty();
        }

        $keys = explode(self::SEPARATOR, $path);

        if (in_array('', $keys, true)) {
            throw new AttributeArgumentsException()->pathContainsEmptySegment();
        }

        $sentinel = new class() {};

        $value = array_reduce(
            $keys,
            function (mixed $carry, string $key) use ($sentinel): mixed {
                // path contains a missing segment, value will be equal to $sentinel, path not found exception should be thrown
                if ($carry === $sentinel) {
                    return $sentinel;
                }

                if (is_array($carry)) {
                    return array_key_exists($key, $carry)
                        ? $carry[$key]
                        : $sentinel;
                }

                if ($carry instanceof stdClass) {
                    return property_exists($carry, $key)
                        ? $carry->$key
                        : $sentinel;
                }

                return $sentinel; // Tried to descend into a scalar
            },
            (static fn (): mixed => $attributeArguments)()
        );

        if ($value === $sentinel) {
            throw new AttributeArgumentsException()->pathNotFound($path);
        }

        return $value;
    }

    /**
     * Determines if the given path exists in attribute arguments.
     *
     * @param array|object $attributeArguments
     * @param string       $path
     *
     * @return bool
     */
    public static function hasPath(array|object $attributeArguments, string $path): bool
    {
        try {
            self::getByPath($attributeArguments, $path);

            return true;
        } catch (AttributeArgumentsException) {
            return false;
        }
    }
}
