<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a time input field.
 */
class Time extends InputField
{
    public function __construct()
    {
        parent::__construct('time');
    }
}
