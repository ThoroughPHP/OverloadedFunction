<?php

namespace Sevavietl\OverloadedFunction\Signature;

use Sevavietl\OverloadedFunction\Signature\Types\Type;
use Sevavietl\OverloadedFunction\Signature\Types\DisjoinedType;
use Sevavietl\OverloadedFunction\Signature\Types\ConjoinedType;
use Sevavietl\OverloadedFunction\Signature\Types\OptionalType;

class Signature
{
    private $types;

    public function __construct($stringRepresentation)
    {
        $this->parseStringRepresentation($stringRepresentation);
    }

    private function parseStringRepresentation($stringRepresentation)
    {
        $paramTypes = array_filter(array_map('trim', explode(',', $stringRepresentation)));

        $this->types = array_map(function ($paramType) {
            if (($optional = $this->isOptional($paramType))) {
                $paramType = substr($paramType, 1);
            }

            if ($this->isDisjoined($paramType)) {
                $type = new DisjoinedType(explode('|', $paramType));
            } elseif ($this->isConjoined($paramType)) {
                $type = new ConjoinedType(explode('&', $paramType));
            } else {
                $type = new Type($paramType);
            }

            return $optional ? new OptionalType($type) : $type;
        }, $paramTypes);
    }

    private function isOptional($paramType)
    {
        return strpos($paramType, '?') === 0;
    }

    private function isDisjoined($paramType)
    {
        return strpos($paramType, '|') !== false;
    }

    private function isConjoined($paramType)
    {
        return strpos($paramType, '&') !== false;
    }

    public function match(array $params)
    {
        return array_reduce(
            array_map(function ($type, $param) {
                if (is_null($type)) {
                    return false;
                }

                return $type->match($param);
            }, $this->types, $params),
            function ($carry, $result) {
                return $carry && $result;
            },
            true
        );
    }
}
