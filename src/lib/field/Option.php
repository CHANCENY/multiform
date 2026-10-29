<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents an option within a select or datalist element.
 */
class Option extends ElementField
{
    public function __construct()
    {
        parent::__construct('option');
    }
}
