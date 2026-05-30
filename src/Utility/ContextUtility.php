<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Utility;

use ConstupFoss\PhpSerializer\Exceptions\ContextException;
use stdClass;

class ContextUtility
{
    public const string SEPARATOR = '->';

    /**
     * Returns the value at the given path in the context.
     *
     * Path nodes are separated by `->`.
     *
     * @param array|stdClass $context
     * @param string         $path
     *
     * @throws ContextException when path has invalid format or is not found in context.
     *
     * @return mixed
     */
    public static function getByPath(array|stdClass $context, string $path): mixed
    {
        if (trim($path) === '') {
            throw new ContextException()->pathIsEmpty();
        }

        if (empty($context)) {
            throw new ContextException()->contextIsEmpty();
        }

        $keys = explode(self::SEPARATOR, $path);

        if (in_array('', $keys, true)) {
            throw new ContextException()->pathContainsEmptySegment();
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
            (static fn (): mixed => $context)()
        );

        if ($value === $sentinel) {
            throw new ContextException()->contextPathNotFound($path);
        }

        return $value;
    }

    /**
     * Determines if the given path exists in the context.
     *
     * @param array|object $context
     * @param string       $path
     *
     * @return bool
     */
    public static function hasPath(array|object $context, string $path): bool
    {
        try {
            self::getByPath($context, $path);

            return true;
        } catch (ContextException) {
            return false;
        }
    }
}
