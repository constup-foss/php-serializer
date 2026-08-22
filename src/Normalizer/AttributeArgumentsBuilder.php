<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Normalizer;

use Closure;
use ConstupFoss\PhpPropertyMetadata\Exceptions\MetadataTreeException;
use ConstupFoss\PhpPropertyMetadata\ObjectMetadataTreeBuilder;

class AttributeArgumentsBuilder extends ObjectMetadataTreeBuilder
{
    /**
     * Create a new type definition node for an array element.
     * Type definitions are stored inside a `__types` subnode.
     *
     * @param string       $attributeFqn
     * @param Closure|null $children
     *
     * @throws MetadataTreeException
     *
     * @return self
     */
    public function addArrayElementTypeNode(string $attributeFqn, ?Closure $children = null): self
    {
        return $this->addNode('__types', function (AttributeArgumentsBuilder $builder) use ($attributeFqn, $children) {
            $builder->addNode($attributeFqn, $children);
        });
    }
}
