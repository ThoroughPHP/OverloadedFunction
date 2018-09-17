<?php

namespace Sevavietl\OverloadedFunction;

use TypeGuard\Guard;

final class OverloadedFunction
{
    /** @var callable */
    private $func;

    public function __construct(array $cases)
    {
        if (empty($cases)) {
            throw new FunctionHasNoCasesException;
        }

        $this->prepareFunction($this->prepareCases($cases));
    }

    private function prepareCases(array $cases): \SplObjectStorage
    {
        $_cases = new \SplObjectStorage;

        foreach ($cases as $signatureString => $func) {
            $_cases[new Signature($signatureString)] = $func;
        }

        return $_cases;
    }

    private function prepareFunction(\SplObjectStorage $cases): void
    {
        $this->func = function (...$args) use ($cases) {
            /** @var Guard $signature */
            foreach ($cases as $signature) {
                if ($signature->match($args)) {
                    return $cases[$signature](...$args);
                }
            }

            throw new UnknownSignatureException('There is no function case for provided parameters.');
        };   
    }

    /**
     * @param mixed ...$argv
     * @return mixed
     */
    public function __invoke(...$argv)
    {
        return ($this->func)(...$argv);
    }
}
