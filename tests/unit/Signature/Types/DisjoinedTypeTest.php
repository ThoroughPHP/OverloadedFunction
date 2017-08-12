<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\DisjoinedType;

class DisjoinedTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeStrings, $param, $result)
    {
        $this->assertEquals($result, (new DisjoinedType($typeStrings))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            [['ArrayAccess', 'Countable'], new \ArrayIterator, true],
            [['Countable', 'RecursiveIterator'], new \ArrayIterator, true],
        ];
    }
}