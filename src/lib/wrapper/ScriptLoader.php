<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\wrapper;

use InvalidArgumentException;

/**
 * Generates optional inline JavaScript enhancements for multi-step forms.
 */
class ScriptLoader
{
    /**
     * Renders an inline script that allows navigating back past browser
     * constraint validation.
     *
     * Browsers validate required fields before submitting a form. When the
     * user clicks the wrapper's Back button, that validation should not prevent
     * the request from saving the current step and returning to the previous
     * one. Server-side validation remains the application's responsibility.
     *
     * @param string $stepParameter   Name of the wrapper's hidden step field.
     * @param string $actionParameter Name of the wrapper's navigation buttons.
     *
     * @return string An inline script element.
     */
    public static function renderMultiStepFormScript(
        string $stepParameter = '_multistep_step',
        string $actionParameter = '_multistep_action'
    ): string {
        if ($stepParameter === '' || $actionParameter === '' || $stepParameter === $actionParameter) {
            throw new InvalidArgumentException('Step and action parameter names must be distinct and non-empty.');
        }

        $config = json_encode(
            [
                'stepParameter' => $stepParameter,
                'actionParameter' => $actionParameter,
            ],
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );

        if ($config === false) {
            throw new InvalidArgumentException('Unable to encode multi-step script configuration.');
        }

        return '<script>(function(config) {'
            . 'document.querySelectorAll("form").forEach(function(form) {'
            . 'if (form.dataset.multistepEnhanced === "true") { return; }'
            . 'var stepField = Array.prototype.find.call(form.elements, function(element) {'
            . 'return element.name === config.stepParameter;'
            . '});'
            . 'if (!stepField) { return; }'
            . 'form.dataset.multistepEnhanced = "true";'
            . 'form.addEventListener("click", function(event) {'
            . 'var button = event.target.closest("button");'
            . 'if (button && button.form === form && button.name === config.actionParameter && button.value === "back") {'
            . 'form.noValidate = true;'
            . '}'
            . '});'
            . '});'
            . '})(' . $config . ');</script>';
    }

    /**
     * Alias for renderMultiStepFormScript().
     *
     * @param string $stepParameter   Name of the wrapper's hidden step field.
     * @param string $actionParameter Name of the wrapper's navigation buttons.
     *
     * @return string An inline script element.
     */
    public static function loadMultiStepForm(
        string $stepParameter = '_multistep_step',
        string $actionParameter = '_multistep_action'
    ): string {
        return self::renderMultiStepFormScript($stepParameter, $actionParameter);
    }
}
