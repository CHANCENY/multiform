<?php

namespace Simp\Multiform\lib\field;

/**
 * Represents a local date and time input field.
 */
class DateTimeLocal extends InputField
{
    public function __construct()
    {
        parent::__construct('datetime-local');
    }

    /**
     * Gets this field's HTML name.
     *
     * @return string The input type name.
     */
    public function getName()
    {
        return 'datetime-local';
    }
}
