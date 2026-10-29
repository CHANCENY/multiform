<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib;

use InvalidArgumentException;
use Simp\Multiform\lib\field\FieldBase;
use FieldTypeInterface;

/**
 * Provides starter HTML rendering helpers for form fields.
 *
 * Applications can use these helpers directly or replace the renderer passed
 * to Form::create() with their own implementation.
 */
class Helper
{
    /**
     * Escapes a scalar value for safe use in HTML text or an attribute.
     *
     * @param mixed $value The value to escape.
     *
     * @return string The escaped value.
     */
    public static function escape($value): string
    {
        if ($value === null) {
            return '';
        }
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new InvalidArgumentException('HTML values must be scalar or stringable.');
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Serializes an associative array of HTML attributes.
     *
     * Boolean HTML attributes are emitted without a value. Other boolean
     * values, including ARIA values, are serialized as "true" or "false".
     *
     * @param array $attributes Attribute names mapped to values.
     *
     * @return string Serialized attributes with leading spaces.
     */
    public static function renderAttributes(array $attributes): string
    {
        $markup = '';
        foreach ($attributes as $name => $value) {
            $name = (string) $name;
            if (!preg_match('/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', $name)) {
                throw new InvalidArgumentException(sprintf('Invalid HTML attribute name "%s".', $name));
            }
            if ($value === null || $value === '') {
                continue;
            }
            if (self::isBooleanAttribute($name)) {
                if ($value) {
                    $markup .= ' ' . $name;
                }
                continue;
            }
            $markup .= ' ' . $name . '="' . self::escape($value) . '"';
        }

        return $markup;
    }

    /**
     * Renders option elements for select or datalist controls.
     *
     * Options may be supplied as value => label pairs or as a list of labels.
     * Selected values are compared as strings.
     *
     * @param array $options  Option values and labels.
     * @param mixed $selected The selected value or values.
     *
     * @return string The rendered option elements.
     */
    public static function renderOptions(array $options, $selected = null): string
    {
        $selectedValues = is_array($selected) ? $selected : [$selected];
        $selectedValues = array_map('strval', $selectedValues);
        $isList = $options === [] || array_keys($options) === range(0, count($options) - 1);
        $markup = '';

        foreach ($options as $value => $label) {
            if ($isList) {
                $value = $label;
            }
            $isSelected = in_array((string) $value, $selectedValues, true);
            $markup .= '<option' . self::renderAttributes([
                'value' => $value,
                'selected' => $isSelected,
            ]) . '>' . self::escape($label) . '</option>';
        }

        return $markup;
    }

    /**
     * Renders a field as HTML using its configuration.
     *
     * Container fields such as fieldsets may receive child fields as the third
     * argument. Unsupported custom field implementations should use their own
     * renderer in the callback passed to Form::create().
     *
     * @param FieldTypeInterface   $field      The field to render.
     * @param array                $submitData Submitted form values.
     * @param FieldTypeInterface[] $children   Child fields for containers.
     *
     * @return string The rendered field markup.
     */
    public static function renderField(
        FieldTypeInterface $field,
        array $submitData = [],
        array $children = []
    ): string {
        if (!$field instanceof FieldBase) {
            throw new InvalidArgumentException('The default renderer requires a FieldBase field.');
        }

        $element = $field->getElement();
        $attributes = self::fieldAttributes($field);
        $name = $field->getName();
        if ($field->getHtmlId() === '' && in_array($element, ['input', 'textarea', 'select'], true)) {
            $attributes['id'] = $name;
        }
        $value = array_key_exists($name, $submitData)
            ? $submitData[$name]
            : ($field->getValue() !== null ? $field->getValue() : $field->getDefault());

        if ($element === 'input') {
            $attributes['type'] = $field->getType();
            $attributes['name'] = $name;
            if ($field->getType() === 'file') {
                unset($attributes['value']);
            } else {
                $attributes['value'] = $value;
            }
            $attributes['placeholder'] = $field->getPlaceholder();
            $attributes['required'] = $field->isRequired();
            $attributes['disabled'] = $field->isDisabled();
            $attributes['readonly'] = $field->isReadonly();
            $attributes['autofocus'] = $field->getAutofocus();
            $attributes['autocomplete'] = $field->getAutocomplete() ? 'on' : 'off';
            $attributes['multiple'] = $field->getMultiple();
            $attributes['size'] = $field->getSize() ?: null;
            $attributes['maxlength'] = $field->getMaxlength() ?: null;
            $attributes['minlength'] = $field->getMinlengthHeight() ?: null;
            $attributes['step'] = $field->getSteps() ?: null;
            $attributes['pattern'] = $field->getPattern();
            if ($field->getList() && !array_key_exists('list', $attributes)) {
                $attributes['list'] = implode(' ', $field->getList());
            }

            return self::renderLabel($field)
                . '<input' . self::renderAttributes($attributes) . ' />';
        }

        if ($element === 'textarea') {
            $attributes['name'] = $name;
            $attributes['placeholder'] = $field->getPlaceholder();
            $attributes['required'] = $field->isRequired();
            $attributes['disabled'] = $field->isDisabled();
            $attributes['readonly'] = $field->isReadonly();
            $attributes['autofocus'] = $field->getAutofocus();

            return self::renderLabel($field)
                . '<textarea' . self::renderAttributes($attributes) . '>'
                . self::escape($value)
                . '</textarea>';
        }

        if ($element === 'select') {
            $attributes['name'] = $name;
            $attributes['required'] = $field->isRequired();
            $attributes['disabled'] = $field->isDisabled();
            $attributes['autofocus'] = $field->getAutofocus();
            $attributes['multiple'] = $field->getMultiple();
            $attributes['size'] = $field->getSize() ?: null;

            return self::renderLabel($field)
                . '<select' . self::renderAttributes($attributes) . '>'
                . self::renderOptions($field->getList(), $value)
                . '</select>';
        }

        if ($element === 'option') {
            $optionAttributes = $attributes;
            $optionAttributes['value'] = $value;
            $optionAttributes['selected'] = $field->getAttribute('selected') === true;
            $optionAttributes['disabled'] = $field->isDisabled();

            return '<option' . self::renderAttributes($optionAttributes) . '>'
                . self::escape($field->getLabel() !== '' ? $field->getLabel() : $value)
                . '</option>';
        }

        if ($element === 'datalist') {
            $content = self::renderOptions($field->getList());
            foreach ($children as $child) {
                if (!$child instanceof FieldTypeInterface) {
                    throw new InvalidArgumentException('Datalist children must implement FieldTypeInterface.');
                }
                $content .= self::renderField($child, $submitData);
            }

            return '<datalist' . self::renderAttributes($attributes) . '>' . $content . '</datalist>';
        }

        if ($element === 'fieldset') {
            $content = '';
            if ($field->getLabel() !== '') {
                $content .= '<legend>' . self::escape($field->getLabel()) . '</legend>';
            }
            foreach ($children as $child) {
                if (!$child instanceof FieldTypeInterface) {
                    throw new InvalidArgumentException('Fieldset children must implement FieldTypeInterface.');
                }
                $content .= self::renderField($child, $submitData);
            }

            return '<fieldset' . self::renderAttributes($attributes) . '>' . $content . '</fieldset>';
        }

        if (in_array($element, ['output', 'progress', 'meter'], true)) {
            if ($element === 'output') {
                $attributes['name'] = $name;
            } else {
                $attributes['value'] = $value;
            }

            return '<' . $element . self::renderAttributes($attributes) . '>'
                . self::escape($value)
                . '</' . $element . '>';
        }

        throw new InvalidArgumentException(sprintf('No default renderer is defined for "%s".', $element));
    }

    /**
     * Builds common attributes from a field's settings.
     *
     * @param FieldBase $field The field.
     *
     * @return array Attributes ready for HTML serialization.
     */
    private static function fieldAttributes(FieldBase $field): array
    {
        $attributes = $field->getAttributes();
        $attributes['id'] = $field->getHtmlId() !== '' ? $field->getHtmlId() : null;
        $attributes['class'] = $field->getHtmlClass() ? implode(' ', $field->getHtmlClass()) : null;
        $attributes['title'] = $field->getTitle();
        $attributes['lang'] = $field->getLang();
        $attributes['tabindex'] = $field->getTabindex() ?: null;
        $attributes['hidden'] = $field->isHidden();
        $attributes['contenteditable'] = $field->isContenteditable() ? 'true' : null;

        foreach ($field->getDataAttributes() as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'data-') === 0 ? $name : 'data-' . $name] = $value;
        }
        foreach ($field->getAriaAttribtues() as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'aria-') === 0 ? $name : 'aria-' . $name] = $value;
        }
        foreach ($field->getEvents() as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'on') === 0 ? $name : 'on' . ucfirst($name)] = $value;
        }

        return $attributes;
    }

    /**
     * Renders a label associated with a field when it has a label.
     *
     * @param FieldBase $field The field.
     *
     * @return string The label markup, or an empty string.
     */
    private static function renderLabel(FieldBase $field): string
    {
        if ($field->getLabel() === '') {
            return '';
        }

        $id = $field->getHtmlId() !== '' ? $field->getHtmlId() : $field->getName();

        return '<label for="' . self::escape($id) . '">'
            . self::escape($field->getLabel())
            . '</label>';
    }

    /**
     * Determines whether an attribute uses HTML boolean syntax.
     *
     * @param string $name The attribute name.
     *
     * @return bool True when the attribute is boolean.
     */
    private static function isBooleanAttribute(string $name): bool
    {
        return in_array(strtolower($name), [
            'allowfullscreen',
            'async',
            'autofocus',
            'autoplay',
            'checked',
            'controls',
            'default',
            'defer',
            'disabled',
            'formnovalidate',
            'hidden',
            'ismap',
            'loop',
            'multiple',
            'muted',
            'novalidate',
            'open',
            'readonly',
            'required',
            'reversed',
            'selected',
        ], true);
    }
}