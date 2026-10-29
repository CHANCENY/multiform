<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents an email input field.
 */
class Email extends InputField
{
    public function __construct()
    {
        parent::__construct('email');
    }
}
