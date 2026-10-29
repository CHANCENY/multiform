<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a password input field.
 */
class Password extends InputField
{
    public function __construct()
    {
        parent::__construct('password');
    }
}
