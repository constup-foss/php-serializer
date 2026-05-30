<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Closure;
use ConstupFoss\PhpSerializer\Exceptions\ContextException;
use ConstupFoss\PhpSerializer\Utility\ContextUtility;
use stdClass;

class ContextBuilder
{
    private stdClass $context;
    private array $path = [];

    public function __construct()
    {
        $this->context = (object)[
            'root' => (object)[],
        ];

        $this->path[] = $this->context->root;
    }

    /**
     * Create a new property node.
     *
     * @param string       $propertyName
     * @param Closure|null $children
     *
     * @throws ContextException
     *
     * @return self
     */
    public function property(
        string $propertyName,
        ?Closure $children = null
    ): self {
        $this->validateNodeName($propertyName);

        $currentNode = $this->getCurrentNode();

        if (!property_exists($currentNode, $propertyName) || !is_object($currentNode->{$propertyName})) {
            $currentNode->{$propertyName} = new stdClass();
        }

        return $this->descendInto($currentNode->{$propertyName}, $children);
    }

    /**
     * Create a new attribute node for an object inside an array.
     *
     * @param string       $attributeFqn
     * @param Closure|null $children
     *
     * @throws ContextException
     *
     * @return self
     */
    public function attributeInArray(string $attributeFqn, ?Closure $children = null): self
    {
        $this->validateNodeName($attributeFqn);
        $currentNode = $this->getCurrentNode();

        if (!property_exists($currentNode, '__types') || !is_object($currentNode->__types)) {
            $currentNode->__types = new stdClass();
        }

        if (!property_exists($currentNode->__types, $attributeFqn) || !is_object($currentNode->__types->{$attributeFqn})) {
            $currentNode->__types->{$attributeFqn} = new stdClass();
        }

        return $this->descendInto($currentNode->__types->{$attributeFqn}, $children);
    }

    /**
     * Create attribute context for an attribute.
     *
     * @param string $attributeFqn
     * @param array  $context
     *
     * @throws ContextException
     *
     * @return $this
     */
    public function attributeContext(string $attributeFqn, array $context): self
    {
        $this->validateNodeName($attributeFqn);
        $currentNode = $this->getCurrentNode();

        $currentNode->{$attributeFqn} = (object)[
            'context' => $context,
        ];

        return $this;
    }

    /**
     * Return the built context.
     *
     * @return object
     */
    public function build(): object
    {
        return $this->context;
    }

    /**
     * Validate the name of a node before creation.
     *
     * @param string $name
     *
     * @throws ContextException
     *
     * @return void
     */
    private function validateNodeName(string $name): void
    {
        if ($name === '') {
            throw new ContextException()->emptyContextNodeName();
        }

        if (
            str_contains($name, ContextUtility::SEPARATOR) ||
            str_contains($name, ' ')
        ) {
            throw new ContextException()->invalidNodeName();
        }
    }

    /**
     * Descend into a child node.
     *
     * @param object       $node
     * @param Closure|null $children
     *
     * @return self
     */
    private function descendInto(object $node, ?Closure $children): self
    {
        if ($children === null) {
            return $this;
        }

        $this->path[] = $node;

        try {
            $children($this);
        } finally {
            array_pop($this->path);
        }

        return $this;
    }

    /**
     * Get the current node.
     *
     * @return object
     */
    private function getCurrentNode(): object
    {
        return $this->path[array_key_last($this->path)];
    }
}
