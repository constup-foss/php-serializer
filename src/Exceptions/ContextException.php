<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Exceptions;

class ContextException extends ConstupFossPhpSerializerException
{
    /**
     * The context path was not found in the context.
     *
     * @param string $contextPath
     *
     * @return $this
     */
    public function contextPathNotFound(string $contextPath): self
    {
        $this->message = 'Path not found in context.';
        $this->debugMessage = 'Path: ' . $contextPath . ' not found in context.';
        $this->code = 1000;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the context path is empty.
     *
     * @return $this
     */
    public function pathIsEmpty(): self
    {
        $this->message = 'Context path is empty.';
        $this->debugMessage = 'Context path is empty.';
        $this->code = 1001;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the context path contains an empty segment. ('foo->->bar')
     *
     * @return $this
     */
    public function pathContainsEmptySegment(): self
    {
        $this->message = 'Context path contains an empty segment.';
        $this->debugMessage = 'Context path contains an empty segment.';
        $this->code = 1002;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when the context is empty.
     *
     * @return $this
     */
    public function contextIsEmpty(): self
    {
        $this->message = 'Context is empty.';
        $this->debugMessage = 'Context is empty.';
        $this->code = 1003;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to add an empty node when building a context.
     *
     * @return $this
     */
    public function emptyContextNodeName(): self
    {
        $this->message = 'Context node name is empty.';
        $this->debugMessage = 'Context node name is empty.';
        $this->code = 1004;
        $this->recoverable = false;

        return $this;
    }

    /**
     * Thrown when trying to create a context node that contains a path separator (`->`).
     * Thrown when trying to create a context node that contains whitespace (` `).
     *
     * @return $this
     */
    public function invalidNodeName(): self
    {
        $this->message = 'Invalid context node name.';
        $this->debugMessage = 'Invalid context node name.';
        $this->code = 1005;
        $this->recoverable = false;

        return $this;
    }
}
