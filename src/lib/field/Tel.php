<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a telephone input field.
 */
class Tel extends InputField
{
    public function __construct()
    {
        parent::__construct('tel');
    }
}
