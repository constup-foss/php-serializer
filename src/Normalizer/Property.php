<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

/*
 * Property DTO used in the normalization process.
 */
readonly class Property
{
    /**
     * @param string $name
     * @param mixed  $value
     * @param string $contextPath
     */
    public function __construct(
        public string $name,
        public mixed $value,
        public string $contextPath,
    ) {
    }
}
