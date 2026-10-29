<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\form;

use InvalidArgumentException;

/**
 * Stores submitted values, validation errors, and processing state for a form.
 */
class FormState
{
    private $values = [];
    private $errors = [];
    private $storage = [];
    private $submitted = false;

    /**
     * Initializes the state with optional values.
     *
     * @param array $values Initial form values keyed by field name.
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * Sets a value for a form field.
     *
     * @param string $name  The field name.
     * @param mixed  $value The field value.
     *
     * @return $this
     */
    public function setValue(string $name, $value)
    {
        $this->assertKey($name, 'Field name');
        $this->values[$name] = $value;

        return $this;
    }

    /**
     * Gets a form field value.
     *
     * @param string $name    The field name.
     * @param mixed  $default Value to return when the field is not set.
     *
     * @return mixed The field value, or the default.
     */
    public function getValue(string $name, $default = null)
    {
        return array_key_exists($name, $this->values) ? $this->values[$name] : $default;
    }

    /**
     * Determines whether a value has been set for a form field.
     *
     * @param string $name The field name.
     *
     * @return bool True if a value exists for the field.
     */
    public function hasValue(string $name)
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * Gets all form values.
     *
     * @return array Values keyed by field name.
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Replaces all form values.
     *
     * @param array $values Values keyed by field name.
     *
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Sets submitted data and marks the form as submitted.
     *
     * @param array $values Submitted values keyed by field name.
     *
     * @return $this
     */
    public function setSubmittedValues(array $values)
    {
        $this->values = $values;
        $this->submitted = true;

        return $this;
    }

    /**
     * Determines whether the form has been submitted.
     *
     * @return bool True if the form has been submitted.
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Sets whether the form has been submitted.
     *
     * @param bool $submitted Whether the form has been submitted.
     *
     * @return $this
     */
    public function setSubmitted(bool $submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Adds a validation error to a field.
     *
     * @param string $name    The field name.
     * @param string $message The error message.
     *
     * @return $this
     */
    public function addError(string $name, string $message)
    {
        $this->assertKey($name, 'Field name');
        if ($message === '') {
            throw new InvalidArgumentException('An error message cannot be empty.');
        }

        if (!isset($this->errors[$name])) {
            $this->errors[$name] = [];
        }
        $this->errors[$name][] = $message;

        return $this;
    }

    /**
     * Sets all validation errors for a field.
     *
     * @param string   $name     The field name.
     * @param string[] $messages Error messages.
     *
     * @return $this
     */
    public function setErrors(string $name, array $messages)
    {
        $this->assertKey($name, 'Field name');

        foreach ($messages as $message) {
            if (!is_string($message) || $message === '') {
                throw new InvalidArgumentException('Each validation error must be a non-empty string.');
            }
        }

        if ($messages) {
            $this->errors[$name] = array_values($messages);
        } else {
            unset($this->errors[$name]);
        }

        return $this;
    }

    /**
     * Gets validation errors for a field.
     *
     * @param string $name The field name.
     *
     * @return string[] The field's error messages.
     */
    public function getErrors(string $name)
    {
        return $this->errors[$name] ?? [];
    }

    /**
     * Gets all validation errors grouped by field.
     *
     * @return array<string, string[]> The errors keyed by field name.
     */
    public function getAllErrors()
    {
        return $this->errors;
    }

    /**
     * Determines whether a field has validation errors.
     *
     * @param string|null $name The field name, or null to check any field.
     *
     * @return bool True if the field or form has errors.
     */
    public function hasErrors(?string $name = null)
    {
        if ($name !== null) {
            return !empty($this->errors[$name]);
        }

        return !empty($this->errors);
    }

    /**
     * Determines whether the form state has no validation errors.
     *
     * @return bool True if no errors have been recorded.
     */
    public function isValid()
    {
        return !$this->hasErrors();
    }

    /**
     * Removes errors for a field or clears all errors.
     *
     * @param string|null $name The field name, or null to clear all errors.
     *
     * @return $this
     */
    public function clearErrors(?string $name = null)
    {
        if ($name === null) {
            $this->errors = [];
        } else {
            unset($this->errors[$name]);
        }

        return $this;
    }

    /**
     * Stores an application-specific value in the form state.
     *
     * @param string $key   The storage key.
     * @param mixed  $value The value to store.
     *
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->assertKey($key, 'Storage key');
        $this->storage[$key] = $value;

        return $this;
    }

    /**
     * Gets an application-specific value from the form state.
     *
     * @param string $key     The storage key.
     * @param mixed  $default Value to return when the key is not set.
     *
     * @return mixed The stored value, or the default.
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->storage) ? $this->storage[$key] : $default;
    }

    /**
     * Determines whether an application-specific key exists.
     *
     * @param string $key The storage key.
     *
     * @return bool True if the key exists.
     */
    public function has(string $key)
    {
        return array_key_exists($key, $this->storage);
    }

    /**
     * Gets all application-specific stored values.
     *
     * @return array The stored values.
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Removes an application-specific value.
     *
     * @param string $key The storage key.
     *
     * @return $this
     */
    public function remove(string $key)
    {
        unset($this->storage[$key]);

        return $this;
    }

    /**
     * Clears all values, errors, storage, and submission status.
     *
     * @return $this
     */
    public function clear()
    {
        $this->values = [];
        $this->errors = [];
        $this->storage = [];
        $this->submitted = false;

        return $this;
    }

    /**
     * Ensures a field or storage key is non-empty.
     *
     * @param string $key  The key to validate.
     * @param string $kind The kind of key for the exception message.
     *
     * @return void
     */
    private function assertKey(string $key, string $kind)
    {
        if ($key === '') {
            throw new InvalidArgumentException($kind . ' cannot be empty.');
        }
    }
}
