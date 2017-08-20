<?php

namespace Sevavietl\OverloadedFunction\Signature\Types;

class IntersectionType implements IType
{
    private $types;

    public function __construct(array $typeStrings)
    {
        $this->types = array_map(function ($typeString) {
            return new Type($typeString);
        }, $typeStrings); 
    }

    public function match($param)
    {
        return array_reduce($this->types, function ($carry, $type) use ($param) {
            return $carry && $type->match($param);
        }, true);
    }
}
