<?php

namespace Sevavietl\OverloadedFunction;

use TypeGuard\Guard;

final class Signature
{
    /** @var Guard[] */
    private $guards;

    public function __construct(string $stringRepresentation)
    {
        $this->parseStringRepresentation($stringRepresentation);
    }

    private function parseStringRepresentation(string $stringRepresentation): void
    {
        $paramTypes = array_filter(array_map('trim', explode(',', $stringRepresentation)));

        $this->guards = array_map(function (string $paramType): Guard {
            return new Guard($paramType);
        }, $paramTypes);
    }

    public function match(array $params)
    {
        return array_reduce(
            array_map(function (?Guard $guard, $param): bool {
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
