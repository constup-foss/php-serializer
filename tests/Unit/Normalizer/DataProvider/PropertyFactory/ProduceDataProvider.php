<?php

declare(strict_types = 1);

namespace ConstupFoss\PhpSerializer\Tests\Unit\Normalizer\DataProvider\PropertyFactory;

readonly class ProduceDataProvider
{
    public static function provide_HappyFlow(): array
    {
        return [
            'Happy flow' => [
                'name' => 'foo',
                'value' => 'bar',
                'contextPath' => 'root->fee->fie',
            ],
        ];
    }
}
