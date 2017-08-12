<?php

namespace Sevavietl\OverloadedFunction\Tests\Unit;

use Sevavietl\OverloadedFunction\OverloadedFunction;
use Sevavietl\OverloadedFunction\FunctionHasNoCasesException;
use Sevavietl\OverloadedFunction\UnknownSignatureException;

class OverloadedFunctionTest extends \TestCase
{
    protected $func;

    protected function setUp()
    {
        $this->func = new OverloadedFunction([
            'string' => function () {
                return 'string';
            },
            'integer' => function () {
                return 'integer';
            }
        ]);
    }

    public function testInstatiationThrowsExceptionOnEmptyCases()
    {
        $this->expectException(FunctionHasNoCasesException::class);

        $func = new OverloadedFunction([]);
    }

    public function testPrepareFunction()
    {
        $this->assertAttributeInternalType('callable', 'func', $this->func);
    }

    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke($cases, $args, $result)
    {
        $func = new OverloadedFunction($cases);

        $this->assertEquals($result, $func(...$args));
    }

    public function invokeDataProvider()
    {
        return [
            [
                [
                    'integer' => function ($i) { return $i; },
                    'string' => function ($s) { return $s; }
                ],
                [1],
                1
            ],
            [
                [
                    'integer' => function ($i) { return $i; },
                    'string' => function ($s) { return $s; }
                ],
                ['1'],
                '1'
            ],
            [
                [
                    'integer, integer' => function ($a, $b) { return $a + $b; },
                    'string, string' => function ($a, $b) { return $a . $b; }
                ],
                [1, 1],
                2
            ],
            [
                [
                    'integer, integer' => function ($a, $b) { return $a + $b; },
                    'string, string' => function ($a, $b) { return $a . $b; }
                ],
                ['1', '1'],
                '11'
            ],
            [
                [
                    'integer, integer' => function ($a, $b) { return $a + $b; },
                    'string, string' => function ($a, $b) { return $a . $b; },
                    '' => function () { return null; }
                ],
                [],
                null
            ],
            [
                [
                    'ArrayAccess&Countable' => function ($i) { return true; }
                ],
                [new \ArrayIterator],
                true
            ],
            [
                [
                    'string|integer' => function ($i) { return true; }
                ],
                [1],
                true
            ],
            [
                [
                    'string|integer' => function ($i) { return true; }
                ],
                ['1'],
                true
            ],
            [
                [
                    '?integer' => function ($i = 1) { return true; }
                ],
                [],
                true
            ],
            [
                [
                    '?integer' => function ($i = 1) { return true; }
                ],
                [2],
                true
            ],
        ];
    }

    public function testInvokeWithUnknownSignatureException()
    {
        $this->expectException(UnknownSignatureException::class);

        $func = new OverloadedFunction([
            'integer, integer' => function ($a, $b) { return $a + $b; },
            'string, string' => function ($a, $b) { return $a . $b; }
        ]);

        $func(1);
    }
}
