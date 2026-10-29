<?php

// This is file is part of simp/multiform php library.

/**
 * Defines the configuration and rendering contract for a form field.
 */
interface FieldTypeInterface
{
    /**
     * Gets the field's name.
     *
     * @return string The field name.
     */
    public function getName();

    /**
     * Gets the field's type.
     *
     * @return string The field type.
     */
    public function getType();

    /**
     * Gets the field's current value.
     *
     * @return mixed The current value.
     */
    public function getValue();

    /**
     * Sets the field's current value.
     *
     * @param mixed $value The value to assign.
     *
     * @return void
     */
    public function setValue($value);

    /**
     * Gets the field's label.
     *
     * @return string The field label.
     */
    public function getLabel();

    /**
     * Sets the field's label.
     *
     * @param string $label The label to assign.
     *
     * @return void
     */
    public function setLabel($label);

    /**
     * Gets the field's description.
     *
     * @return string The field description.
     */
    public function getDescription();

    /**
     * Sets the field's description.
     *
     * @param string $description The description to assign.
     *
     * @return void
     */
    public function setDescription($description);

    /**
     * Determines whether the field is required.
     *
     * @return bool True if the field is required.
     */
    public function isRequired();

    /**
     * Sets whether the field is required.
     *
     * @param bool $required Whether the field is required.
     *
     * @return void
     */
    public function setRequired($required);

    /**
     * Gets the field's default value.
     *
     * @return mixed The default value.
     */
    public function getDefault();

    /**
     * Sets the field's default value.
     *
     * @param mixed $default The default value to assign.
     *
     * @return void
     */
    public function setDefault($default);

    /**
     * Sets the field's weight.
     *
     * @param int $weight The weight to assign.
     *
     * @return void
     */
    public function setWeight(int $weight);

    /**
     * Gets the field's weighting.
     *
     * @return int The field weighting.
     */
    public function getWeighting();

    /**
     * Sets the field's title.
     *
     * @param string $title The title to assign.
     *
     * @return void
     */
    public function setTitle(string $title);

    /**
     * Gets the field's title.
     *
     * @return string The field title.
     */
    public function getTitle();

    /**
     * Sets the field's HTML id.
     *
     * @param string $htmlId The HTML id to assign.
     *
     * @return void
     */
    public function setHtmlId(string $htmlId);

    /**
     * Gets the field's HTML id.
     *
     * @return string The HTML id.
     */
    public function getHtmlId();

    /**
     * Sets the field's HTML classes.
     *
     * @param string[] $htmlClass The HTML classes to assign.
     *
     * @return void
     */
    public function setHtmlClass(array $htmlClass);

    /**
     * Gets the field's HTML classes.
     *
     * @return string[] The HTML classes.
     */
    public function getHtmlClass();

    /**
     * Sets the field's inline styles.
     *
     * @param array $styles The styles to assign.
     *
     * @return void
     */
    public function setStyles(array $styles);

    /**
     * Sets the field's language.
     *
     * @param string $lang The language code to assign.
     *
     * @return void
     */
    public function setLang(string $lang);

    /**
     * Gets the field's language.
     *
     * @return string The language code.
     */
    public function getLang();

    /**
     * Sets the field's tab order.
     *
     * @param int $tabindex The tab order to assign.
     *
     * @return void
     */
    public function setTabindex(int $tabindex);

    /**
     * Gets the field's tab order.
     *
     * @return int The tab order.
     */
    public function getTabindex();

    /**
     * Sets the field's type.
     *
     * @param string $type The type to assign.
     *
     * @return void
     */
    public function setType(string $type);

    /**
     * Sets whether the field is hidden.
     *
     * @param bool $hidden Whether the field is hidden.
     *
     * @return void
     */
    public function setHidden(bool $hidden);

    /**
     * Determines whether the field is hidden.
     *
     * @return bool True if the field is hidden.
     */
    public function isHidden();

    /**
     * Sets whether the field is editable in the browser.
     *
     * @param bool $contenteditable Whether the field is editable.
     *
     * @return void
     */
    public function setContenteditable(bool $contenteditable);

    /**
     * Determines whether the field is editable in the browser.
     *
     * @return bool True if the field is editable.
     */
    public function isContenteditable();

    /**
     * Sets the field's data attributes.
     *
     * @param array $dataAttribute The data attributes to assign.
     *
     * @return void
     */
    public function setDataAttribute(array $dataAttribute);

    /**
     * Gets the value of a data attribute.
     *
     * @param string $name The attribute name.
     *
     * @return mixed The attribute value.
     */
    public function getDataAttribute(string $name);

    /**
     * Gets all of the field's data attributes.
     *
     * @return array The data attributes.
     */
    public function getDataAttributes();

    /**
     * Sets the field's ARIA attributes.
     *
     * @param array $ariaAttribtues The ARIA attributes to assign.
     *
     * @return void
     */
    public function setAriaAttribtues(array $ariaAttribtues);

    /**
     * Gets the field's ARIA attributes.
     *
     * @return array The ARIA attributes.
     */
    public function getAriaAttribtues();

    /**
     * Sets the field's placeholder text.
     *
     * @param string $placeholder The placeholder to assign.
     *
     * @return void
     */
    public function setPlaceholder(string $placeholder);

    /**
     * Gets the field's placeholder text.
     *
     * @return string The placeholder.
     */
    public function getPlaceholder();

    /**
     * Sets whether the field is disabled.
     *
     * @param bool $disabled Whether the field is disabled.
     *
     * @return void
     */
    public function setDisabled(bool $disabled);

    /**
     * Determines whether the field is disabled.
     *
     * @return bool True if the field is disabled.
     */
    public function isDisabled();

    /**
     * Sets whether the field is read-only.
     *
     * @param bool $readonly Whether the field is read-only.
     *
     * @return void
     */
    public function setReadonly(bool $readonly);

    /**
     * Determines whether the field is read-only.
     *
     * @return bool True if the field is read-only.
     */
    public function isReadonly();

    /**
     * Sets whether the field receives focus automatically.
     *
     * @param bool $autofocus Whether automatic focus is enabled.
     *
     * @return void
     */
    public function setAutofocus(bool $autofocus);

    /**
     * Gets whether the field receives focus automatically.
     *
     * @return bool True if automatic focus is enabled.
     */
    public function getAutofocus();

    /**
     * Sets whether autocomplete is enabled for the field.
     *
     * @param bool $autocomplete Whether autocomplete is enabled.
     *
     * @return void
     */
    public function setAutocomplete(bool $autocomplete);

    /**
     * Gets whether autocomplete is enabled for the field.
     *
     * @return bool True if autocomplete is enabled.
     */
    public function getAutocomplete();

    /**
     * Sets the field's size.
     *
     * @param int $size The size to assign.
     *
     * @return void
     */
    public function setSize(int $size);

    /**
     * Gets the field's size.
     *
     * @return int The field size.
     */
    public function getSize();

    /**
     * Sets the field's maximum length.
     *
     * @param int $maxlength The maximum length to assign.
     *
     * @return void
     */
    public function setMaxlength(int $maxlength);

    /**
     * Gets the field's maximum length.
     *
     * @return int The maximum length.
     */
    public function getMaxlength();

    /**
     * Sets the field's maximum length width.
     *
     * @param int $maxlengthWidth The maximum length width to assign.
     *
     * @return void
     */
    public function setMaxlengthWidth(int $maxlengthWidth);

    /**
     * Gets the field's maximum length width.
     *
     * @return int The maximum length width.
     */
    public function getMaxlengthWidth();

    /**
     * Sets the field's minimum length height.
     *
     * @param int $maxlengthHeight The minimum length height to assign.
     *
     * @return void
     */
    public function setMinlengthHeight(int $maxlengthHeight);

    /**
     * Gets the field's minimum length height.
     *
     * @return int The minimum length height.
     */
    public function getMinlengthHeight();

    /**
     * Sets the field's step value.
     *
     * @param int $steps The step value to assign.
     *
     * @return void
     */
    public function setSteps(int $steps);

    /**
     * Gets the field's step value.
     *
     * @return int The step value.
     */
    public function getSteps();

    /**
     * Sets the field's validation pattern.
     *
     * @param string $pattern The pattern to assign.
     *
     * @return void
     */
    public function setPattern(string $pattern);

    /**
     * Gets the field's validation pattern.
     *
     * @return string The validation pattern.
     */
    public function getPattern();

    /**
     * Sets whether the field accepts multiple values.
     *
     * @param bool $multiple Whether multiple values are accepted.
     *
     * @return void
     */
    public function setMultiple(bool $multiple);

    /**
     * Gets whether the field accepts multiple values.
     *
     * @return bool True if multiple values are accepted.
     */
    public function getMultiple();

    /**
     * Sets the field's list of options.
     *
     * @param array $list The options to assign.
     *
     * @return void
     */
    public function setList(array $list);

    /**
     * Gets the field's list of options.
     *
     * @return array The field options.
     */
    public function getList();

    /**
     * Sets the field's event handlers.
     *
     * @param array $events The event handlers to assign.
     *
     * @return void
     */
    public function setEvents(array $events);

    /**
     * Gets the field's event handlers.
     *
     * @return array The event handlers.
     */
    public function getEvents();

    /**
     * Builds the field from submitted data.
     *
     * @param array    $submit_data    The submitted form data.
     * @param callable $custom_builder The callback used to customize field
     *                                 construction.
     *
     * @return string The generated field markup.
     */
    public function create(array $submit_data, callable $custom_builder): string;

}