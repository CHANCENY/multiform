<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\field;

/**
 * Renders a text field using the shared field configuration.
 */
class Text extends InputField
{
    /**
     * Creates a text input field.
     */
    public function __construct()
    {
        parent::__construct('text');
    }
}
