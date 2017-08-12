<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\Type;

class TypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeString, $param, $result)
    {
        $this->assertEquals($result, (new Type($typeString))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            ['string', 'foo', true],
            ['string', 1, false],
            ['integer', 1, true],
            ['integer', '1', false],
            ['NULL', null, true],
            ['ArrayAccess', new \ArrayIterator, true],
            ['Countable', new \ArrayIterator, true],
        ];
    }
}
