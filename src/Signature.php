<?php

namespace ThoroughPHP\OverloadedFunction;

use ThoroughPHP\TypeGuard\TypeGuard;

final class Signature
{
    /** @var TypeGuard[] */
    private $guards;

    public function __construct(string $stringRepresentation)
    {
        $this->parseStringRepresentation($stringRepresentation);
    }

    private function parseStringRepresentation(string $stringRepresentation): void
    {
        $paramTypes = array_filter(array_map('trim', explode(',', $stringRepresentation)));

        $this->guards = array_map(function (string $paramType): TypeGuard {
            return new TypeGuard($paramType);
        }, $paramTypes);
    }

    public function match(array $params)
    {
        return array_reduce(
            array_map(function (?TypeGuard $guard, $param): bool {
                if (null === $guard) {
                    return false;
                }

                return $guard->match($param);
            }, $this->guards, $params),
            function ($carry, $result) {
                return $carry && $result;
            },
            true
        );
    }
}
