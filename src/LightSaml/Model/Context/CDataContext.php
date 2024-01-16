<?php

/*
 * This file is part of the LightSAML-Core package.
 *
 * (c) Daniel Knapke <d.knapke@brain-scc.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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