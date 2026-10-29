<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a reset input field.
 */
class Reset extends InputField
{
    public function __construct()
    {
        parent::__construct('reset');
    }
}
