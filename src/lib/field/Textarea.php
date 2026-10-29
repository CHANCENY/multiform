<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a multiline textarea field.
 */
class Textarea extends ElementField
{
    public function __construct()
    {
        parent::__construct('textarea');
    }
}
