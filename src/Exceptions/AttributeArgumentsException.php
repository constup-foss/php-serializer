<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Exceptions;

class AttributeArgumentsException extends ConstupFossPhpSerializerException
{
    /**
     * The attribute arguments path was not found in attribute arguments.
     *
     * @param string $attributeArgumentsPath
     *
     * @return $this
     */
    public function pathNotFound(string $attributeArgumentsPath): self
    {
        $this->message = 'Path not found in attribute arguments.';
        $this->debugMessage = 'Path: ' . $attributeArgumentsPath . ' not found in attribute arguments.';
        $this->code = 1000;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the attribute arguments path is empty.
     *
     * @return $this
     */
    public function pathIsEmpty(): self
    {
        $this->message = 'Attribute arguments path is empty.';
        $this->debugMessage = 'Attribute arguments path is empty.';
        $this->code = 1001;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the attribute arguments path contains an empty segment. ('foo->->bar')
     *
     * @return $this
     */
    public function pathContainsEmptySegment(): self
    {
        $this->message = 'Attribute arguments path contains an empty segment.';
        $this->debugMessage = 'Attribute arguments path contains an empty segment.';
        $this->code = 1002;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the attribute arguments are empty.
     *
     * @return $this
     */
    public function attributeArgumentsAreEmpty(): self
    {
        $this->message = 'Attribute arguments are empty.';
        $this->debugMessage = 'Attribute arguments are empty.';
        $this->code = 1003;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to add an empty node when building attribute arguments.
     *
     * @return $this
     */
    public function emptyAttributeArgumentsNodeName(): self
    {
        $this->message = 'Attribute arguments node name is empty.';
        $this->debugMessage = 'Attribute arguments node name is empty.';
        $this->code = 1004;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to create an attribute arguments node that contains a path separator (`->`).
     * Thrown when trying to create an attribute arguments node that contains whitespace (` `).
     *
     * @return $this
     */
    public function invalidNodeName(): self
    {
        $this->message = 'Invalid attribute arguments node name.';
        $this->debugMessage = 'Invalid attribute arguments node name.';
        $this->code = 1005;
        $this->recoverable = false;

        return $this;
    }
}
