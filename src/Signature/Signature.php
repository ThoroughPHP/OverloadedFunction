<?php

namespace Sevavietl\OverloadedFunction\Signature;

use Sevavietl\OverloadedFunction\Signature\Types\Type;
use Sevavietl\OverloadedFunction\Signature\Types\UnionType;
use Sevavietl\OverloadedFunction\Signature\Types\IntersectionType;
use Sevavietl\OverloadedFunction\Signature\Types\ArrayType;
use Sevavietl\OverloadedFunction\Signature\Types\OptionalType;

class Signature
{
    const UNION_TYPE_SEPARATOR = '|';
    const INTERSECTION_TYPE_SEPARATOR = '&';

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
            
            if ($array = $this->isArray($paramType)) {
                $paramType = substr($paramType, 0, -2);
            }

            if ($this->isUnion($paramType)) {
                $type = new UnionType(explode(self::UNION_TYPE_SEPARATOR, $paramType));
            } elseif ($this->isIntersection($paramType)) {
                $type = new IntersectionType(explode(self::INTERSECTION_TYPE_SEPARATOR, $paramType));
            } else {
                $type = new Type($paramType);
            }

            $type = $array ? new ArrayType($type) : $type;

            return $optional ? new OptionalType($type) : $type;
        }, $paramTypes);
    }

    private function isOptional($paramType)
    {
        return strpos($paramType, '?') === 0;
    }

    private function isArray($paramType)
    {
        return preg_match('/(?:\[\])$/', $paramType);
    }

    private function isUnion($paramType)
    {
        return strpos($paramType, self::UNION_TYPE_SEPARATOR) !== false;
    }

    private function isIntersection($paramType)
    {
        return strpos($paramType, self::INTERSECTION_TYPE_SEPARATOR) !== false;
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
