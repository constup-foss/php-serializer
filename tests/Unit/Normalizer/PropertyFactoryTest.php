<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Unit\Normalizer;

use ConstupFoss\PhpSerializer\Normalizer\PropertyFactory;
use ConstupFoss\PhpSerializer\Tests\Unit\Normalizer\DataProvider\PropertyFactory\ProduceDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class PropertyFactoryTest extends TestCase
{
    #[DataProviderExternal(
        ProduceDataProvider::class,
        'provide_HappyFlow'
    )]
    public function testProduceProperty(
        string $name,
        mixed $value,
        string $contextPath,
    ): void {
        $property = PropertyFactory::produce($name, $value, $contextPath);

        $this->assertEquals($name, $property->name);
        $this->assertEquals($value, $property->value);
        $this->assertEquals($contextPath, $property->contextPath);
    }
}
