<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature\Types;

use Sevavietl\OverloadedFunction\Signature\Types\Type;
use Sevavietl\OverloadedFunction\Signature\Types\OptionalType;

class OptionalTypeTest extends \TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($typeString, $param, $result)
    {
        $this->assertEquals($result, (new OptionalType(new Type($typeString)))->match($param));
    }

    public function matchDataProvider()
    {
        return [
            ['string', null, true],
            ['integer',  null, true],
        ];
    }
}