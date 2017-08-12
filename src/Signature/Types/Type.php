<?php

namespace Sevavietl\OverloadedFunction\Signature\Types;

class Type implements IType
{
    private $typeString;

    public function __construct($typeString)
    {
        $this->typeString = $typeString; 
    }

    public function match($param)
    {
        $type = gettype($param);
        
        return $type === 'object' 
            ? $param instanceof $this->typeString 
            : $type === $this->typeString;
    }
}
