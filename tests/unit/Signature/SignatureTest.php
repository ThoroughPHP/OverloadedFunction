<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit\Signature;

use Sevavietl\OverloadedFunction\Signature\Signature;
use Sevavietl\OverloadedFunction\Signature\Types\Type;
use Sevavietl\OverloadedFunction\Signature\Types\DisjoinedType;
use Sevavietl\OverloadedFunction\Signature\Types\ConjoinedType;
use Sevavietl\OverloadedFunction\Signature\Types\OptionalType;

class SignatureTest extends \TestCase
{
    public function testParseStringRepresentation()
    {
        // Arrange
        $stringRepresentation = 'string,integer,?boolean';

        // Act
        $signature = new Signature($stringRepresentation);

        // Assert
        $types = $this->getAttribute($signature, 'types');

        $stringType = $types[0];
        $integerType = $types[1];
        $booleanType = $types[2];

        $this->assertInstanceOf(Type::class, $stringType);
        $this->assertAttributeEquals('string', 'typeString', $stringType);
        
        $this->assertInstanceOf(Type::class, $integerType);
        $this->assertAttributeEquals('integer', 'typeString', $integerType);
        
        $this->assertInstanceOf(OptionalType::class, $booleanType);
        $this->assertAttributeInstanceOf(Type::class, 'type', $booleanType);
    }

    public function testParseEmptyStringRepresentation()
    {
        // Arrange
        $stringRepresentation = '';

        // Act
        $signature = new Signature($stringRepresentation);

        // Assert
        $this->assertAttributeEmpty('types', $signature);
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
            ['string, ArrayAccess, integer', ['foo', new \ArrayIterator, 1], true]
        ];
    }
}