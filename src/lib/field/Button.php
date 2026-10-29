<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a general-purpose button input field.
 */
class Button extends InputField
{
    public function __construct()
    {
        parent::__construct('button');
    }
}
