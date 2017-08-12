<?php

namespace Sevavietl\OverloadedFunction;

use Sevavietl\OverloadedFunction\Signature\Signature;

class OverloadedFunction
{
    private $func;

    public function __construct(array $cases)
    {
        if (empty($cases)) {
            throw new FunctionHasNoCasesException;
        }

        $this->prepareFunction($this->prepareCases($cases));
    }

    private function prepareCases($cases)
    {
        $_cases = new \SplObjectStorage;

        foreach ($cases as $signatureString => $func) {
            $_cases[new Signature($signatureString)] = $func;
        }

        return $_cases;
    }

    private function prepareFunction(\SplObjectStorage $cases)
    {
        $this->func = function (...$args) use ($cases) {
            foreach ($cases as $signature) {
                if ($signature->match($args)) {
                    return $cases[$signature](...$args);
                }
            }

            throw new UnknownSignatureException("There is no function case for provided parameters.");
        };   
    }

    public function __invoke(...$argv)
    {
        return ($this->func)(...$argv);
    }
}
