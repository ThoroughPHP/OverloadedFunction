<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\UnionType;

class DisjoinedTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeStrings, $param, $result)
    {
        $this->assertEquals($result, (new UnionType($typeStrings))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            [['ArrayAccess', 'Countable'], new \ArrayIterator, true],
            [['Countable', 'RecursiveIterator'], new \ArrayIterator, true],
        ];
    }
}