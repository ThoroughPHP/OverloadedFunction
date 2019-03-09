<?php

namespace ThoroughPHP\OverloadedFunction\Tests\Unit;

use ThoroughPHP\OverloadedFunction\Signature;
use ThoroughPHP\OverloadedFunction\Signature\Types\Type;
use ThoroughPHP\OverloadedFunction\Signature\Types\ArrayType;
use ThoroughPHP\OverloadedFunction\Signature\Types\OptionalType;

final class SignatureTest extends \TestCase
{
    public function testParseStringRepresentation()
    {
        // Arrange
        $stringRepresentation = 'string,integer[],?boolean';

        // Act
        $signature = new Signature($stringRepresentation);

        // Assert
        $this->assertAttributeCount(3, 'guards', $signature);
    }

    public function testParseEmptyStringRepresentation()
    {
        // Arrange
        $stringRepresentation = '';

        // Act
        $signature = new Signature($stringRepresentation);

        // Assert
        $this->assertAttributeEmpty('guards', $signature);
    }

    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($stringRepresentation, $params, $result)
    {
        $this->assertEquals($result, (new Signature($stringRepresentation))->match($params));
    }

    public function matchDataProvider()
    {
        return [
            ['string', ['foo'], true],
            ['string|integer', ['foo'], true],
            ['string&integer', ['foo'], false],
            ['string', [1], false],
            ['string, integer', ['foo', 1], true],
            ['string, integer, boolean', ['foo', 1], false],
            ['string, integer, ?boolean', ['foo', 1], true],
            ['NULL', [], true],
            ['string, ArrayAccess, integer', ['foo', new \ArrayIterator, 1], true],
            ['string[]', [['foo', 'bar']], true],
            ['string[]', ['foo'], false],
            ['string[]', [['1', '2', 3]], false],
            ['string[]', [[1, 2, 3]], false],
        ];
    }
}