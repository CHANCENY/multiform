<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a file upload input field.
 */
class File extends InputField
{
    public function __construct()
    {
        parent::__construct('file');
    }
}
