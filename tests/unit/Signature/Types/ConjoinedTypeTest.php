<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\ConjoinedType;

class ConjoinedTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeStrings, $param, $result)
    {
        $this->assertEquals($result, (new ConjoinedType($typeStrings))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            [['ArrayAccess', 'Countable'], new \ArrayIterator, true],
            [['Countable', 'RecursiveIterator'], new \ArrayIterator, false],
        ];
    }
}