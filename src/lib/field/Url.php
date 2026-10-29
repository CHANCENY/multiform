<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a URL input field.
 */
class Url extends InputField
{
    public function __construct()
    {
        parent::__construct('url');
    }
}
