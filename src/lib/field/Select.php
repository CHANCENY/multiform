<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a single-selection dropdown field.
 */
class Select extends ElementField
{
    public function __construct()
    {
        parent::__construct('select');
    }
}
