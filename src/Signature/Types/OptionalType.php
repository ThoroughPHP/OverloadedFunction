<?php

namespace Sevavietl\OverloadedFunction\Signature\Types;

class OptionalType implements IType
{
    private $type;

    public function __construct(IType $type)
    {
        $this->type = $type; 
    }

    public function match($param)
    {
        $type = gettype($param);

        if ($type === 'NULL') {
            return true;
        }

        return $this->type->match($param);
    }
}