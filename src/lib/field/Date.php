<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a date input field.
 */
class Date extends InputField
{
    public function __construct()
    {
        parent::__construct('date');
    }
}
