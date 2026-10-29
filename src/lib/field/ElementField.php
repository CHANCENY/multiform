<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\field;

use UnexpectedValueException;

/**
 * Base implementation for concrete non-input form elements.
 */
abstract class ElementField extends FieldBase
{
    /**
     * Initializes the HTML element represented by this field.
     *
     * @param string $element The HTML element name.
     */
    protected function __construct(string $element)
    {
        $this->element = $element;
        $this->type = $element;
    }

    /**
     * Builds this field through the supplied custom builder.
     *
     * @param array    $submit_data    Submitted form data.
     * @param callable $custom_builder Receives this field and returns markup.
     *
     * @return string The generated field markup.
     */
    public function create(array $submit_data, callable $custom_builder): string
    {
        $result = $custom_builder($this);

        if (!is_string($result)) {
            throw new UnexpectedValueException('The custom builder must return a string.');
        }

        return $result;
    }
}
