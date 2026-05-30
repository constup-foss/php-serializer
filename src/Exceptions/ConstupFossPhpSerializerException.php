<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Exceptions;

use ConstupFoss\PhpExerr\Library\LibraryException;

abstract class ConstupFossPhpSerializerException extends LibraryException
{
    protected string $libraryName = 'constup-foss/php-serializer';
}
