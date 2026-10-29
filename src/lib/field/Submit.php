<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a submit input field.
 */
class Submit extends InputField
{
    public function __construct()
    {
        parent::__construct('submit');
    }
}
