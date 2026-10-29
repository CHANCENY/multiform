<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\form;

require_once __DIR__ . '/../field/FieldTypeInterface.php';

use InvalidArgumentException;
use FieldTypeInterface;

/**
 * Collects form fields and renders them inside an HTML form element.
 */
class Form
{
    private $fields = [];
    private $attributes = [];

    /**
     * Creates a form with optional HTML attributes.
     *
     * @param array $attributes Initial form attributes keyed by name.
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * Adds a field to the form.
     *
     * @param FieldTypeInterface $field The field to add.
     * @param string|null        $key   Optional unique form key for the field.
     *
     * @return $this
     */
    public function addField(FieldTypeInterface $field, ?string $key = null)
    {
        $key = $key ?? $field->getName();
        if ($key === '') {
            throw new InvalidArgumentException('A form field key cannot be empty.');
        }
        if (array_key_exists($key, $this->fields)) {
            throw new InvalidArgumentException(sprintf('A form field with key "%s" already exists.', $key));
        }

        $this->fields[$key] = $field;

        return $this;
    }

    /**
     * Removes a field by its form key.
     *
     * @param string $key The field key.
     *
     * @return $this
     */
    public function removeField(string $key)
    {
        unset($this->fields[$key]);

        return $this;
    }

    /**
     * Gets a field by its form key.
     *
     * @param string $key The field key.
     *
     * @return FieldTypeInterface|null The field, or null if not found.
     */
    public function getField(string $key)
    {
        return $this->fields[$key] ?? null;
    }

    /**
     * Gets all fields in insertion order.
     *
     * @return FieldTypeInterface[] The fields keyed by their form keys.
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Removes all fields from the form.
     *
     * @return $this
     */
    public function clearFields()
    {
        $this->fields = [];

        return $this;
    }

    /**
     * Sets the form submission action.
     *
     * @param string $action The action URL.
     *
     * @return $this
     */
    public function setAction(string $action)
    {
        return $this->setAttribute('action', $action);
    }

    /**
     * Gets the form submission action.
     *
     * @return string|null The action URL, or null if unset.
     */
    public function getAction()
    {
        return $this->getAttribute('action');
    }

    /**
     * Sets the form submission method.
     *
     * @param string $method The method (get, post, or dialog).
     *
     * @return $this
     */
    public function setMethod(string $method)
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post', 'dialog'], true)) {
            throw new InvalidArgumentException('Form method must be get, post, or dialog.');
        }

        return $this->setAttribute('method', $method);
    }

    /**
     * Gets the form submission method.
     *
     * @return string|null The method, or null if unset.
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * Sets the encoding type used to submit the form.
     *
     * @param string $enctype The form encoding type.
     *
     * @return $this
     */
    public function setEnctype(string $enctype)
    {
        return $this->setAttribute('enctype', $enctype);
    }

    /**
     * Sets an HTML attribute on the form element.
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The attribute value.
     *
     * @return $this
     */
    public function setAttribute(string $name, $value)
    {
        if (!preg_match('/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', $name)) {
            throw new InvalidArgumentException(sprintf('Invalid HTML attribute name "%s".', $name));
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Sets several HTML attributes on the form element.
     *
     * @param array $attributes Attributes keyed by name.
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute((string) $name, $value);
        }

        return $this;
    }

    /**
     * Gets an HTML attribute from the form element.
     *
     * @param string $name The attribute name.
     *
     * @return mixed The attribute value, or null if unset.
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Gets all HTML attributes on the form element.
     *
     * @return array Attributes keyed by name.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Removes an HTML attribute from the form element.
     *
     * @param string $name The attribute name.
     *
     * @return $this
     */
    public function removeAttribute(string $name)
    {
        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * Creates the form markup and renders each field using the supplied builder.
     *
     * The builder is passed to each field's create() method. A builder can
     * inspect a field and return its desired markup.
     *
     * @param array    $submit_data    Submitted form data keyed by field name.
     * @param callable $custom_builder Receives each field and returns markup.
     *
     * @return string The complete form markup.
     */
    public function create(array $submit_data, callable $custom_builder): string
    {
        $markup = '<form' . $this->renderAttributes() . '>';

        foreach ($this->fields as $field) {
            $markup .= $field->create($submit_data, $custom_builder);
        }

        return $markup . '</form>';
    }

    /**
     * Renders form attributes as escaped HTML.
     *
     * @return string The serialized attributes, including leading spaces.
     */
    private function renderAttributes()
    {
        $markup = '';
        foreach ($this->attributes as $name => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (in_array(strtolower($name), ['novalidate'], true)) {
                if ($value) {
                    $markup .= ' ' . $name;
                }
                continue;
            }
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
                throw new InvalidArgumentException(sprintf('Value for form attribute "%s" must be scalar or stringable.', $name));
            }

            $markup .= ' ' . $name . '="' . htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"';
        }

        return $markup;
    }
}
