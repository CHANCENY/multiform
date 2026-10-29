<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a multiple-selection dropdown field.
 */
class MultiSelect extends ElementField
{
    public function __construct()
    {
        parent::__construct('select');
        $this->multiple = true;
    }
}
