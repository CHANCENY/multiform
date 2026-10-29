<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a meter element for a scalar measurement.
 */
class Meter extends ElementField
{
    public function __construct()
    {
        parent::__construct('meter');
    }
}
