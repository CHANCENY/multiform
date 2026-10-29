<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a hidden input field.
 */
class Hidden extends InputField
{
    public function __construct()
    {
        parent::__construct('hidden');
    }
}
