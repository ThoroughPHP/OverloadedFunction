<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\Type;
use Sevavietl\OverloadedFunction\Signature\Types\ArrayType;
use Sevavietl\OverloadedFunction\Signature\Types\UnionType;
use Sevavietl\OverloadedFunction\Signature\Types\IntersectionType;

class ArrayTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeStrings, $param, $result)
    {
        $this->assertEquals($result, (new ArrayType($typeStrings))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            [new Type('string'), ['foo', 'bar'], true],
            [new Type('string'), ['foo', 1], false],
            [
                new UnionType(['ArrayAccess', 'Countable']),
                [new \ArrayIterator, new \ArrayIterator],
                true
            ],
            [
                new UnionType(['ArrayAccess', 'Countable']),
                [new \ArrayObject, new \ArrayIterator],
                true
            ],
            [
                new UnionType(['ArrayAccess', 'Countable']),
                [new \StdClass, new \ArrayIterator],
                false
            ],
            [
                new UnionType(['stdClass', 'Countable']),
                [new \ArrayObject, new \ArrayIterator],
                true
            ],
            [
                new UnionType(['stdClass', 'Countable']),
                [new \ArrayObject, new \StdClass],
                true
            ],
            [
                new IntersectionType(['ArrayAccess', 'Countable']),
                [new \ArrayObject, new \ArrayIterator],
                true
            ],
            [
                new IntersectionType(['ArrayAccess', 'Countable']),
                [new \ArrayObject, new \StdClass],
                false
            ],
        ];
    }
}
