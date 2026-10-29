<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\field;

require_once __DIR__ . '/FieldTypeInterface.php';

use FieldTypeInterface;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Provides shared configuration and HTML element helpers for form fields.
 *
 * Subclasses provide their own rendering implementation through create().
 */
abstract class FieldBase implements FieldTypeInterface
{
    protected $value;
    protected $label = '';
    protected $description = '';
    protected $required = false;
    protected $default;
    protected $weight = 0;
    protected $title = '';
    protected $htmlId = '';
    protected $htmlClass = [];
    protected $styles = [];
    protected $lang = '';
    protected $tabindex;
    protected $type = 'text';
    protected $element = 'input';
    protected $hidden = false;
    protected $contenteditable = false;
    protected $dataAttribute = [];
    protected $ariaAttributes = [];
    protected $placeholder = '';
    protected $disabled = false;
    protected $readonly = false;
    protected $autofocus = false;
    protected $autocomplete = false;
    protected $size = 0;
    protected $maxlength = 0;
    protected $maxlengthWidth = 0;
    protected $minlengthHeight = 0;
    protected $steps = 0;
    protected $pattern = '';
    protected $multiple = false;
    protected $list = [];
    protected $events = [];
    protected $attributes = [];

    /**
     * Gets the configured HTML element.
     *
     * @return string The element name.
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Sets an additional HTML attribute.
     *
     * Use this for attributes not covered by the dedicated field settings,
     * including form, validation, input-specific, and submit-override attributes.
     *
     * @param string $name The attribute name.
     * @param mixed  $value The attribute value.
     *
     * @return $this
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Sets additional HTML attributes.
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
     * Gets an additional HTML attribute.
     *
     * @param string $name The attribute name.
     *
     * @return mixed The attribute value, or null if it is not set.
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Gets all additional HTML attributes.
     *
     * @return array Attributes keyed by name.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Removes an additional HTML attribute.
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
     * Gets this field's name from its concrete class name.
     *
     * @return string The lower-case class name.
     */
    public function getName()
    {
        return strtolower((new ReflectionClass($this))->getShortName());
    }

    /**
     * Gets the configured HTML input type.
     *
     * @return string The input type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the current field value.
     *
     * @return mixed The current value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the current field value.
     *
     * @param mixed $value The value to assign.
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the field label.
     *
     * @return string The label.
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the field label.
     *
     * @param string $label The label to assign.
     *
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Gets the field description.
     *
     * @return string The description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the field description.
     *
     * @param string $description The description to assign.
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Determines whether the field is required.
     *
     * @return bool True if the field is required.
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Sets whether the field is required.
     *
     * @param bool $required Whether the field is required.
     *
     * @return void
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Gets the field's default value.
     *
     * @return mixed The default value.
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets the field's default value.
     *
     * @param mixed $default The default value.
     *
     * @return void
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * Sets the field weight.
     *
     * @param int $weight The weight.
     *
     * @return void
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
    }

    /**
     * Gets the field weight.
     *
     * @return int The field weight.
     */
    public function getWeighting()
    {
        return $this->weight;
    }

    /**
     * Sets the field title.
     *
     * @param string $title The title.
     *
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Gets the field title.
     *
     * @return string The title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the HTML id.
     *
     * @param string $htmlId The id.
     *
     * @return void
     */
    public function setHtmlId(string $htmlId)
    {
        $this->htmlId = $htmlId;
    }

    /**
     * Gets the HTML id.
     *
     * @return string The id.
     */
    public function getHtmlId()
    {
        return $this->htmlId;
    }

    /**
     * Sets the HTML classes.
     *
     * @param array $htmlClass The classes.
     *
     * @return void
     */
    public function setHtmlClass(array $htmlClass)
    {
        $this->htmlClass = $htmlClass;
    }

    /**
     * Gets the HTML classes.
     *
     * @return array The classes.
     */
    public function getHtmlClass()
    {
        return $this->htmlClass;
    }

    /**
     * Sets the inline styles.
     *
     * @param array $styles The styles.
     *
     * @return void
     */
    public function setStyles(array $styles)
    {
        $this->styles = $styles;
    }

    /**
     * Sets the field language.
     *
     * @param string $lang The language code.
     *
     * @return void
     */
    public function setLang(string $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Gets the field language.
     *
     * @return string The language code.
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets the tab order.
     *
     * @param int $tabindex The tab order.
     *
     * @return void
     */
    public function setTabindex(int $tabindex)
    {
        $this->tabindex = $tabindex;
    }

    /**
     * Gets the tab order.
     *
     * @return int The tab order, or zero when not explicitly set.
     */
    public function getTabindex()
    {
        return $this->tabindex ?? 0;
    }

    /**
     * Sets the HTML input type.
     *
     * @param string $type The input type.
     *
     * @return void
     */
    public function setType(string $type)
    {
        $type = strtolower($type);
        $this->type = in_array($type, [
            'button',
            'checkbox',
            'color',
            'date',
            'datetime-local',
            'email',
            'file',
            'hidden',
            'image',
            'month',
            'number',
            'password',
            'radio',
            'range',
            'reset',
            'search',
            'submit',
            'tel',
            'text',
            'time',
            'url',
            'week',
        ], true) ? $type : 'text';
    }

    /**
     * Sets whether the field is hidden.
     *
     * @param bool $hidden Whether the field is hidden.
     *
     * @return void
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Determines whether the field is hidden.
     *
     * @return bool True if the field is hidden.
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets whether the field is editable in the browser.
     *
     * @param bool $contenteditable Whether the field is editable.
     *
     * @return void
     */
    public function setContenteditable(bool $contenteditable)
    {
        $this->contenteditable = $contenteditable;
    }

    /**
     * Determines whether the field is editable in the browser.
     *
     * @return bool True if the field is editable.
     */
    public function isContenteditable()
    {
        return $this->contenteditable;
    }

    /**
     * Sets the data attributes.
     *
     * @param array $dataAttribute The data attributes.
     *
     * @return void
     */
    public function setDataAttribute(array $dataAttribute)
    {
        $this->dataAttribute = $dataAttribute;
    }

    /**
     * Gets a data attribute value.
     *
     * @param string $name The attribute name.
     *
     * @return mixed The attribute value, or null if not set.
     */
    public function getDataAttribute(string $name)
    {
        return $this->dataAttribute[$name] ?? null;
    }

    /**
     * Gets all data attributes.
     *
     * @return array The data attributes.
     */
    public function getDataAttributes()
    {
        return $this->dataAttribute;
    }

    /**
     * Sets the ARIA attributes.
     *
     * @param array $ariaAttribtues The ARIA attributes.
     *
     * @return void
     */
    public function setAriaAttribtues(array $ariaAttribtues)
    {
        $this->ariaAttributes = $ariaAttribtues;
    }

    /**
     * Gets the ARIA attributes.
     *
     * @return array The ARIA attributes.
     */
    public function getAriaAttribtues()
    {
        return $this->ariaAttributes;
    }

    /**
     * Sets the placeholder text.
     *
     * @param string $placeholder The placeholder.
     *
     * @return void
     */
    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * Gets the placeholder text.
     *
     * @return string The placeholder.
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Sets whether the field is disabled.
     *
     * @param bool $disabled Whether the field is disabled.
     *
     * @return void
     */
    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * Determines whether the field is disabled.
     *
     * @return bool True if the field is disabled.
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * Sets whether the field is read-only.
     *
     * @param bool $readonly Whether the field is read-only.
     *
     * @return void
     */
    public function setReadonly(bool $readonly)
    {
        $this->readonly = $readonly;
    }

    /**
     * Determines whether the field is read-only.
     *
     * @return bool True if the field is read-only.
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    /**
     * Sets whether the field receives focus automatically.
     *
     * @param bool $autofocus Whether autofocus is enabled.
     *
     * @return void
     */
    public function setAutofocus(bool $autofocus)
    {
        $this->autofocus = $autofocus;
    }

    /**
     * Gets whether the field receives focus automatically.
     *
     * @return bool True if autofocus is enabled.
     */
    public function getAutofocus()
    {
        return $this->autofocus;
    }

    /**
     * Sets whether autocomplete is enabled.
     *
     * @param bool $autocomplete Whether autocomplete is enabled.
     *
     * @return void
     */
    public function setAutocomplete(bool $autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    /**
     * Gets whether autocomplete is enabled.
     *
     * @return bool True if autocomplete is enabled.
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    /**
     * Sets the field size.
     *
     * @param int $size The size.
     *
     * @return void
     */
    public function setSize(int $size)
    {
        $this->size = $size;
    }

    /**
     * Gets the field size.
     *
     * @return int The size.
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets the maximum character length.
     *
     * @param int $maxlength The maximum length.
     *
     * @return void
     */
    public function setMaxlength(int $maxlength)
    {
        $this->maxlength = $maxlength;
    }

    /**
     * Gets the maximum character length.
     *
     * @return int The maximum length.
     */
    public function getMaxlength()
    {
        return $this->maxlength;
    }

    /**
     * Sets the maximum length width.
     *
     * @param int $maxlengthWidth The maximum length width.
     *
     * @return void
     */
    public function setMaxlengthWidth(int $maxlengthWidth)
    {
        $this->maxlengthWidth = $maxlengthWidth;
    }

    /**
     * Gets the maximum length width.
     *
     * @return int The maximum length width.
     */
    public function getMaxlengthWidth()
    {
        return $this->maxlengthWidth;
    }

    /**
     * Sets the minimum length height.
     *
     * @param int $maxlengthHeight The minimum length height.
     *
     * @return void
     */
    public function setMinlengthHeight(int $maxlengthHeight)
    {
        $this->minlengthHeight = $maxlengthHeight;
    }

    /**
     * Gets the minimum length height.
     *
     * @return int The minimum length height.
     */
    public function getMinlengthHeight()
    {
        return $this->minlengthHeight;
    }

    /**
     * Sets the step value.
     *
     * @param int $steps The step value.
     *
     * @return void
     */
    public function setSteps(int $steps)
    {
        $this->steps = $steps;
    }

    /**
     * Gets the step value.
     *
     * @return int The step value.
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Sets the validation pattern.
     *
     * @param string $pattern The validation pattern.
     *
     * @return void
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Gets the validation pattern.
     *
     * @return string The validation pattern.
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets whether the field accepts multiple values.
     *
     * @param bool $multiple Whether multiple values are accepted.
     *
     * @return void
     */
    public function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;
    }

    /**
     * Gets whether the field accepts multiple values.
     *
     * @return bool True if multiple values are accepted.
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Sets the field options.
     *
     * @param array $list The options.
     *
     * @return void
     */
    public function setList(array $list)
    {
        $this->list = $list;
    }

    /**
     * Gets the field options.
     *
     * @return array The options.
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Sets the field event handlers.
     *
     * @param array $events The handlers, keyed by event name.
     *
     * @return void
     */
    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    /**
     * Gets the field event handlers.
     *
     * @return array The handlers.
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Configures this field as a button input.
     *
     * @return $this
     */
    public function button()
    {
        return $this->useInputType('button');
    }

    /**
     * Configures this field to render an input element.
     *
     * @return $this
     */
    public function inputElement()
    {
        $this->element = 'input';

        return $this;
    }

    /**
     * Configures this field to render a textarea element.
     *
     * @return $this
     */
    public function textarea()
    {
        return $this->useElement('textarea');
    }

    /**
     * Configures this field to render a select element.
     *
     * @return $this
     */
    public function select()
    {
        $this->multiple = false;

        return $this->useElement('select');
    }

    /**
     * Configures this field to render a select element with multiple selection.
     *
     * @return $this
     */
    public function multiselect()
    {
        $this->multiple = true;

        return $this->useElement('select');
    }

    /**
     * Configures this field to render an option element.
     *
     * @return $this
     */
    public function option()
    {
        return $this->useElement('option');
    }

    /**
     * Configures this field to render an optgroup element.
     *
     * @return $this
     */
    public function optgroup()
    {
        return $this->useElement('optgroup');
    }

    /**
     * Configures this field to render a button element.
     *
     * @return $this
     */
    public function buttonElement()
    {
        return $this->useElement('button');
    }

    /**
     * Configures this field to render a fieldset element.
     *
     * @return $this
     */
    public function fieldset()
    {
        return $this->useElement('fieldset');
    }

    /**
     * Configures this field to render a legend element.
     *
     * @return $this
     */
    public function legend()
    {
        return $this->useElement('legend');
    }

    /**
     * Configures this field to render a datalist element.
     *
     * @return $this
     */
    public function datalist()
    {
        return $this->useElement('datalist');
    }

    /**
     * Configures this field to render an output element.
     *
     * @return $this
     */
    public function output()
    {
        return $this->useElement('output');
    }

    /**
     * Configures this field to render a progress element.
     *
     * @return $this
     */
    public function progress()
    {
        return $this->useElement('progress');
    }

    /**
     * Configures this field to render a meter element.
     *
     * @return $this
     */
    public function meter()
    {
        return $this->useElement('meter');
    }

    /**
     * Configures this field to render a label element.
     *
     * @return $this
     */
    public function labelElement()
    {
        return $this->useElement('label');
    }

    /**
     * Configures this field to render a form element.
     *
     * @return $this
     */
    public function formElement()
    {
        return $this->useElement('form');
    }

    /**
     * Configures this field as a checkbox input.
     *
     * @return $this
     */
    public function checkbox()
    {
        return $this->useInputType('checkbox');
    }

    /**
     * Configures this field as a color input.
     *
     * @return $this
     */
    public function color()
    {
        return $this->useInputType('color');
    }

    /**
     * Configures this field as a date input.
     *
     * @return $this
     */
    public function date()
    {
        return $this->useInputType('date');
    }

    /**
     * Configures this field as a local date and time input.
     *
     * @return $this
     */
    public function datetimeLocal()
    {
        return $this->useInputType('datetime-local');
    }

    /**
     * Configures this field as an email input.
     *
     * @return $this
     */
    public function email()
    {
        return $this->useInputType('email');
    }

    /**
     * Configures this field as a file input.
     *
     * @return $this
     */
    public function file()
    {
        return $this->useInputType('file');
    }

    /**
     * Configures this field as a hidden input.
     *
     * @return $this
     */
    public function hidden()
    {
        return $this->useInputType('hidden');
    }

    /**
     * Configures this field as an image input.
     *
     * @return $this
     */
    public function image()
    {
        return $this->useInputType('image');
    }

    /**
     * Configures this field as a month input.
     *
     * @return $this
     */
    public function month()
    {
        return $this->useInputType('month');
    }

    /**
     * Configures this field as a number input.
     *
     * @return $this
     */
    public function number()
    {
        return $this->useInputType('number');
    }

    /**
     * Configures this field as a password input.
     *
     * @return $this
     */
    public function password()
    {
        return $this->useInputType('password');
    }

    /**
     * Configures this field as a radio input.
     *
     * @return $this
     */
    public function radio()
    {
        return $this->useInputType('radio');
    }

    /**
     * Configures this field as a range input.
     *
     * @return $this
     */
    public function range()
    {
        return $this->useInputType('range');
    }

    /**
     * Configures this field as a reset input.
     *
     * @return $this
     */
    public function reset()
    {
        return $this->useInputType('reset');
    }

    /**
     * Configures this field as a search input.
     *
     * @return $this
     */
    public function search()
    {
        return $this->useInputType('search');
    }

    /**
     * Configures this field as a submit input.
     *
     * @return $this
     */
    public function submit()
    {
        return $this->useInputType('submit');
    }

    /**
     * Configures this field as a telephone input.
     *
     * @return $this
     */
    public function tel()
    {
        return $this->useInputType('tel');
    }

    /**
     * Configures this field as a text input.
     *
     * @return $this
     */
    public function text()
    {
        return $this->useInputType('text');
    }

    /**
     * Configures this field as a time input.
     *
     * @return $this
     */
    public function time()
    {
        return $this->useInputType('time');
    }

    /**
     * Configures this field as a URL input.
     *
     * @return $this
     */
    public function url()
    {
        return $this->useInputType('url');
    }

    /**
     * Configures this field as a week input.
     *
     * @return $this
     */
    public function week()
    {
        return $this->useInputType('week');
    }

    /**
     * Sets the current input type and returns this field for chaining.
     *
     * @param string $type The input type.
     *
     * @return $this
     */
    protected function useInputType(string $type)
    {
        $this->element = 'input';
        $this->type = $type;

        return $this;
    }

    /**
     * Selects a supported HTML element for a concrete renderer.
     *
     * @param string $element The element name.
     *
     * @return $this
     */
    protected function useElement(string $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Resolves a field value from submitted data, current value, then default.
     *
     * @param array $submit_data Submitted values keyed by field name.
     *
     * @return mixed The resolved field value.
     */
    protected function getSubmittedValue(array $submit_data)
    {
        $name = $this->getName();

        return array_key_exists($name, $submit_data)
            ? $submit_data[$name]
            : ($this->value !== null ? $this->value : $this->default);
    }

    /**
     * Builds common input markup for a concrete field's create() method.
     *
     * @param array $submit_data Submitted values keyed by field name.
     *
     * @return string The generated input markup.
     */
    protected function buildInputMarkup(array $submit_data): string
    {
        $name = $this->getName();
        $value = $this->getSubmittedValue($submit_data);

        $attributes = [
            'type' => $this->type,
            'name' => $name,
            'value' => $value,
        ];

        $optionalAttributes = [
            'title' => $this->title,
            'id' => $this->htmlId,
            'class' => $this->htmlClass ? implode(' ', $this->htmlClass) : null,
            'style' => $this->buildStyles(),
            'lang' => $this->lang,
            'tabindex' => $this->tabindex,
            'placeholder' => $this->placeholder,
            'hidden' => $this->hidden,
            'contenteditable' => $this->contenteditable,
            'disabled' => $this->disabled,
            'readonly' => $this->readonly,
            'autofocus' => $this->autofocus,
            'autocomplete' => $this->autocomplete ? 'on' : 'off',
            'required' => $this->required,
            'size' => $this->size ?: null,
            'maxlength' => $this->maxlength ?: null,
            'minlength' => $this->minlengthHeight ?: null,
            'step' => $this->steps ?: null,
            'pattern' => $this->pattern,
            'multiple' => $this->multiple,
            'list' => $this->list ? implode(' ', $this->list) : null,
        ];

        $attributes = array_merge($attributes, $this->attributes);

        foreach ($optionalAttributes as $attribute => $attributeValue) {
            if ($attributeValue !== null && $attributeValue !== '' && $attributeValue !== false) {
                $attributes[$attribute] = $attributeValue;
            }
        }

        foreach ($this->dataAttribute as $attribute => $attributeValue) {
            $attribute = (string) $attribute;
            $attributes[strpos($attribute, 'data-') === 0 ? $attribute : 'data-' . $attribute] = $attributeValue;
        }

        foreach ($this->ariaAttributes as $attribute => $attributeValue) {
            $attribute = (string) $attribute;
            $attributes[strpos($attribute, 'aria-') === 0 ? $attribute : 'aria-' . $attribute] = $attributeValue;
        }

        foreach ($this->events as $event => $handler) {
            $event = (string) $event;
            $attributes[strpos($event, 'on') === 0 ? $event : 'on' . ucfirst($event)] = $handler;
        }

        $markup = '<input';
        foreach ($attributes as $attribute => $attributeValue) {
            if ($attributeValue === null || $attributeValue === '') {
                continue;
            }

            if (!preg_match('/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', (string) $attribute)) {
                throw new InvalidArgumentException(sprintf('Invalid HTML attribute name "%s".', $attribute));
            }

            if ($attributeValue === false && $this->isBooleanAttribute((string) $attribute)) {
                continue;
            }
            if ($attributeValue === true && $this->isBooleanAttribute((string) $attribute)) {
                $markup .= ' ' . $attribute;
                continue;
            }

            $markup .= ' ' . $attribute . '="' . $this->escapeAttribute($attributeValue) . '"';
        }

        return $markup . ' />';
    }

    /**
     * Builds markup for a configured non-input element.
     *
     * Subclasses provide the element's content and may pass element-specific
     * attributes. Common field attributes and custom data/ARIA attributes are
     * added automatically.
     *
     * @param mixed $content The element content, or null for an empty element.
     * @param array $attributes Additional element attributes.
     * @param bool $contentIsHtml Whether content is already trusted HTML markup.
     *
     * @return string The generated element markup.
     */
    protected function buildElementMarkup($content = null, array $attributes = [], bool $contentIsHtml = false): string
    {
        $tag = $this->element;
        if (!preg_match('/^[a-z][a-z0-9-]*$/', $tag)) {
            throw new InvalidArgumentException(sprintf('Invalid HTML element name "%s".', $tag));
        }

        $commonAttributes = [
            'title' => $this->title,
            'id' => $this->htmlId,
            'class' => $this->htmlClass ? implode(' ', $this->htmlClass) : null,
            'style' => $this->buildStyles(),
            'lang' => $this->lang,
            'tabindex' => $this->tabindex,
            'hidden' => $this->hidden,
            'contenteditable' => $this->contenteditable,
        ];

        if (in_array($tag, ['input', 'textarea', 'select', 'button', 'output', 'form'], true)) {
            $commonAttributes['name'] = $this->getName();
        }
        if (in_array($tag, ['input', 'textarea'], true)) {
            $commonAttributes['placeholder'] = $this->placeholder;
            $commonAttributes['readonly'] = $this->readonly;
        }
        if (in_array($tag, ['input', 'textarea', 'select'], true)) {
            $commonAttributes['required'] = $this->required;
        }
        if (in_array($tag, ['input', 'textarea', 'select', 'button', 'fieldset', 'option', 'optgroup'], true)) {
            $commonAttributes['disabled'] = $this->disabled;
        }
        if (in_array($tag, ['input', 'textarea', 'select', 'button'], true)) {
            $commonAttributes['autofocus'] = $this->autofocus;
        }
        if (in_array($tag, ['input', 'textarea', 'select', 'form'], true)) {
            $commonAttributes['autocomplete'] = $this->autocomplete ? 'on' : 'off';
        }
        if (in_array($tag, ['input', 'select'], true)) {
            $commonAttributes['multiple'] = $this->multiple;
            $commonAttributes['size'] = $this->size ?: null;
        }

        foreach ($commonAttributes as $name => $value) {
            if ($value !== null && $value !== '' && $value !== false) {
                $attributes[$name] = $value;
            }
        }

        $attributes = array_merge($attributes, $this->attributes);
        foreach ($this->dataAttribute as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'data-') === 0 ? $name : 'data-' . $name] = $value;
        }
        foreach ($this->ariaAttributes as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'aria-') === 0 ? $name : 'aria-' . $name] = $value;
        }
        foreach ($this->events as $name => $value) {
            $name = (string) $name;
            $attributes[strpos($name, 'on') === 0 ? $name : 'on' . ucfirst($name)] = $value;
        }

        $markup = '<' . $tag;
        foreach ($attributes as $name => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if (!preg_match('/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', (string) $name)) {
                throw new InvalidArgumentException(sprintf('Invalid HTML attribute name "%s".', $name));
            }
            if ($value === false && $this->isBooleanAttribute((string) $name)) {
                continue;
            }
            if ($value === true && $this->isBooleanAttribute((string) $name)) {
                $markup .= ' ' . $name;
                continue;
            }
            $markup .= ' ' . $name . '="' . $this->escapeAttribute($value) . '"';
        }

        $voidElements = ['input', 'img', 'br', 'hr', 'area', 'base', 'col', 'embed', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        if (in_array($tag, $voidElements, true)) {
            return $markup . ' />';
        }

        $body = $contentIsHtml ? (string) $content : $this->escapeContent($content);

        return $markup . '>' . $body . '</' . $tag . '>';
    }

    /**
     * Formats styles as inline CSS declarations.
     *
     * @return string|null The style attribute value, or null when unset.
     */
    private function buildStyles()
    {
        if (!$this->styles) {
            return null;
        }

        $declarations = [];
        foreach ($this->styles as $property => $value) {
            if (!is_string($property) || !is_scalar($value)) {
                throw new InvalidArgumentException('Styles must map CSS property names to scalar values.');
            }

            $declarations[] = $property . ': ' . $value;
        }

        return implode('; ', $declarations);
    }

    /**
     * Escapes a value for use in an HTML attribute.
     *
     * @param mixed $value The value to escape.
     *
     * @return string The escaped value.
     */
    private function escapeAttribute($value)
    {
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new InvalidArgumentException('HTML attribute values must be scalar or stringable.');
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Escapes text content for an HTML element.
     *
     * @param mixed $content The content to escape.
     *
     * @return string The escaped content.
     */
    private function escapeContent($content)
    {
        if ($content === null) {
            return '';
        }
        if (!is_scalar($content) && !(is_object($content) && method_exists($content, '__toString'))) {
            throw new InvalidArgumentException('HTML element content must be scalar or stringable.');
        }

        if (is_bool($content)) {
            $content = $content ? 'true' : 'false';
        }

        return htmlspecialchars((string) $content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Determines whether an HTML attribute has boolean syntax.
     *
     * @param string $name The attribute name.
     *
     * @return bool True if it is a boolean attribute.
     */
    private function isBooleanAttribute(string $name)
    {
        return in_array(strtolower($name), [
            'autofocus',
            'checked',
            'disabled',
            'formnovalidate',
            'hidden',
            'multiple',
            'novalidate',
            'readonly',
            'required',
            'selected',
        ], true);
    }

    /**
     * Creates the concrete field using its configured values and submitted data.
     *
     * @param array    $submit_data    Submitted form data.
     * @param callable $custom_builder Callback used to build the field markup.
     *
     * @return string The generated field markup.
     */
    abstract public function create(array $submit_data, callable $custom_builder): string;
}
