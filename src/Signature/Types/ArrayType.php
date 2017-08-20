<?php

namespace Sevavietl\OverloadedFunction\Signature\Types;

class ArrayType implements IType
{
    private $type;
    
    public function __construct(IType $type)
    {
        $this->type = $type; 
    }

    public function match($param)
    {
        $type = gettype($param);

        if ($type !== 'array') {
            return false;
        }

        foreach ($param as $item) {
            if (! $this->type->match($item)) {
                return false;
            }
        }

        return true;
    }
}
