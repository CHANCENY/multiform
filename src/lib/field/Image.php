<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents an image submit input field.
 */
class Image extends InputField
{
    public function __construct()
    {
        parent::__construct('image');
    }
}
