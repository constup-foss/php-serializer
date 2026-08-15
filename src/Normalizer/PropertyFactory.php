<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

readonly class PropertyFactory
{
    /**
     * @param string $name
     * @param mixed  $value
     * @param string $attributeArgumentsPath
     *
     * @return Property
     */
    public static function produce(
        string $name,
        mixed $value,
        string $attributeArgumentsPath,
    ): Property {
        return new Property($name, $value, $attributeArgumentsPath);
    }
}
