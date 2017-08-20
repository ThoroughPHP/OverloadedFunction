<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\IntersectionType;

class IntersectionTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeStrings, $param, $result)
    {
        $this->assertEquals($result, (new IntersectionType($typeStrings))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            [['ArrayAccess', 'Countable'], new \ArrayIterator, true],
            [['Countable', 'RecursiveIterator'], new \ArrayIterator, false],
        ];
    }
}
