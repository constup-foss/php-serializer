<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Closure;
use ConstupFoss\PhpSerializer\Exceptions\AttributeArgumentsException;
use ConstupFoss\PhpSerializer\Utility\AttributeArgumentsUtility;
use stdClass;

class AttributeArgumentsBuilder
{
    private stdClass $attributeArguments;
    private array $path = [];

    public function __construct()
    {
        $this->attributeArguments = (object)[
            'root' => (object)[],
        ];

        $this->path[] = $this->attributeArguments->root;
    }

    /**
     * Create a new property node.
     *
     * @param string       $propertyName
     * @param Closure|null $children
     *
     * @throws AttributeArgumentsException
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
     * @throws AttributeArgumentsException
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
     * Create attribute arguments for an attribute.
     *
     * @param string $attributeFqn
     * @param array  $attributeArguments
     *
     * @throws AttributeArgumentsException
     *
     * @return $this
     */
    public function attributeArguments(string $attributeFqn, array $attributeArguments): self
    {
        $this->validateNodeName($attributeFqn);
        $currentNode = $this->getCurrentNode();

        $currentNode->{$attributeFqn} = (object)[
            'attributeArguments' => $attributeArguments,
        ];

        return $this;
    }

    /**
     * Return built attribute arguments.
     *
     * @return object
     */
    public function build(): object
    {
        return $this->attributeArguments;
    }

    /**
     * Validate the name of a node before creation.
     *
     * @param string $name
     *
     * @throws AttributeArgumentsException
     *
     * @return void
     */
    private function validateNodeName(string $name): void
    {
        if ($name === '') {
            throw new AttributeArgumentsException()->emptyContextNodeName();
        }

        if (
            str_contains($name, AttributeArgumentsUtility::SEPARATOR) ||
            str_contains($name, ' ')
        ) {
            throw new AttributeArgumentsException()->invalidNodeName();
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
