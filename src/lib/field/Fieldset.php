<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a fieldset that groups related form controls.
 */
class Fieldset extends ElementField
{
    public function __construct()
    {
        parent::__construct('fieldset');
    }
}
