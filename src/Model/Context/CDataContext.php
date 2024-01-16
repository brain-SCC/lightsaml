<?php

namespace LightSaml\Model\Context;

class CDataContext
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
    
    public function __toString()
    {
        return $this->value;
    }
}