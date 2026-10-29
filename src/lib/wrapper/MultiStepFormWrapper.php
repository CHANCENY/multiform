<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\wrapper;

require_once __DIR__ . '/../field/FieldTypeInterface.php';
require_once __DIR__ . '/FormStateSessionStore.php';
require_once __DIR__ . '/MultiStepFormStyles.php';

use InvalidArgumentException;
use FieldTypeInterface;
use Simp\Multiform\lib\Helper;

/**
 * Splits form fields into steps and renders back/continue navigation.
 *
 * Applications provide a FormStateSessionStore implementation so submitted
 * values can be persisted between requests using their preferred storage.
 */
class MultiStepFormWrapper
{
    private $steps = [];
    private $store;
    private $styles;
    private $attributes = ['method' => 'post'];
    private $stepParameter = '_multistep_step';
    private $actionParameter = '_multistep_action';

    /**
     * Creates a multi-step wrapper.
     *
     * @param FormStateSessionStore $store  Application-provided state storage.
     * @param MultiStepFormStyles|null $styles CSS style preset.
     */
    public function __construct(FormStateSessionStore $store, ?MultiStepFormStyles $styles = null)
    {
        $this->store = $store;
        $this->styles = $styles ?? new MultiStepFormStyles();
    }

    /**
     * Adds a step and the fields displayed on it.
     *
     * @param string               $title  Step heading.
     * @param FieldTypeInterface[] $fields Fields belonging to this step.
     * @param string               $icon   Optional icon for the icon-grid preset.
     *
     * @return $this
     */
    public function addStep(string $title, array $fields, string $icon = '')
    {
        foreach ($fields as $field) {
            if (!$field instanceof FieldTypeInterface) {
                throw new InvalidArgumentException('Every step field must implement FieldTypeInterface.');
            }
        }

        $this->steps[] = [
            'title' => $title,
            'fields' => array_values($fields),
            'icon' => $icon,
        ];

        return $this;
    }

    /**
     * Gets the configured steps.
     *
     * @return array<int, array{title: string, fields: FieldTypeInterface[]}>
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * Sets an HTML form attribute.
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
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
     * Sets the form action URL.
     *
     * @param string $action Form submission URL.
     *
     * @return $this
     */
    public function setAction(string $action)
    {
        return $this->setAttribute('action', $action);
    }

    /**
     * Sets the form submission method.
     *
     * @param string $method HTTP method (get or post).
     *
     * @return $this
     */
    public function setMethod(string $method)
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post'], true)) {
            throw new InvalidArgumentException('Multi-step forms support get or post methods.');
        }

        return $this->setAttribute('method', $method);
    }

    /**
     * Sets names for the hidden step and navigation fields.
     *
     * @param string $stepParameter   Name of the current-step field.
     * @param string $actionParameter Name of the navigation action field.
     *
     * @return $this
     */
    public function setParameterNames(string $stepParameter, string $actionParameter)
    {
        if ($stepParameter === '' || $actionParameter === '' || $stepParameter === $actionParameter) {
            throw new InvalidArgumentException('Step and action parameter names must be distinct and non-empty.');
        }

        $this->stepParameter = $stepParameter;
        $this->actionParameter = $actionParameter;

        return $this;
    }

    /**
     * Processes a submitted step, persists values, and returns the next step.
     *
     * The submitted request should include this wrapper's hidden step field and
     * one navigation button value: back, next, finish, or goto:N for a
     * previously visited step.
     *
     * @param string $key              Unique key identifying this form flow.
     * @param array  $submittedValues  Submitted values, typically $_POST.
     *
     * @return int The step index to display after processing the request.
     */
    public function processSubmission(string $key, array $submittedValues): int
    {
        $this->assertReady($key);

        $currentStep = $this->normalizeStep($submittedValues[$this->stepParameter] ?? 0);
        $savedStep = $this->normalizeStep($this->store->load($key)['_multistep_step'] ?? 0);
        if ($currentStep !== $savedStep) {
            throw new InvalidArgumentException('The submitted step does not match the saved form step.');
        }
        $action = $submittedValues[$this->actionParameter] ?? 'next';
        if (!is_string($action)) {
            throw new InvalidArgumentException('Step action must be a string.');
        }
        $jumpStep = null;
        if (strpos($action, 'goto:') === 0) {
            $jumpValue = substr($action, 5);
            if (filter_var($jumpValue, FILTER_VALIDATE_INT) === false) {
                throw new InvalidArgumentException('A step navigation target must be an integer.');
            }
            $jumpStep = (int) $jumpValue;
            if ($jumpStep < 0 || $jumpStep >= $currentStep) {
                throw new InvalidArgumentException('Navigation can only return to an earlier step.');
            }
        } elseif (!in_array($action, ['back', 'next', 'finish'], true)) {
            throw new InvalidArgumentException('Step action must be back, next, or finish.');
        }
        if ($action === 'finish' && $currentStep !== count($this->steps) - 1) {
            throw new InvalidArgumentException('A multi-step form can only be finished from its final step.');
        }

        unset(
            $submittedValues[$this->stepParameter],
            $submittedValues[$this->actionParameter],
            $submittedValues['_multistep_step'],
            $submittedValues['_multistep_complete']
        );
        $values = array_merge($this->store->load($key), $submittedValues);

        if ($action === 'back') {
            $nextStep = max(0, $currentStep - 1);
        } elseif ($jumpStep !== null) {
            $nextStep = $jumpStep;
        } elseif ($action === 'next') {
            $nextStep = min(count($this->steps) - 1, $currentStep + 1);
        } else {
            $nextStep = count($this->steps) - 1;
            $values['_multistep_complete'] = true;
        }

        $values['_multistep_step'] = $nextStep;
        $this->store->save($key, $values);

        return $nextStep;
    }

    /**
     * Gets persisted values for a form flow.
     *
     * Internal navigation state is omitted from the returned array.
     *
     * @param string $key Unique key identifying the form flow.
     *
     * @return array Submitted form values.
     */
    public function getValues(string $key): array
    {
        $values = $this->store->load($key);
        unset($values['_multistep_step'], $values['_multistep_complete']);

        return $values;
    }

    /**
     * Gets the saved step index for a form flow.
     *
     * @param string $key Unique key identifying the form flow.
     *
     * @return int The saved step, or zero if none has been saved.
     */
    public function getCurrentStep(string $key): int
    {
        $this->assertReady($key);

        return $this->normalizeStep($this->store->load($key)['_multistep_step'] ?? 0);
    }

    /**
     * Determines whether the final step has been submitted.
     *
     * @param string $key Unique key identifying the form flow.
     *
     * @return bool True if the form flow is complete.
     */
    public function isComplete(string $key): bool
    {
        $this->assertKey($key);

        return ($this->store->load($key)['_multistep_complete'] ?? false) === true;
    }

    /**
     * Clears persisted state for a form flow.
     *
     * @param string $key Unique key identifying the form flow.
     *
     * @return void
     */
    public function clear(string $key): void
    {
        $this->assertKey($key);
        $this->store->clear($key);
    }

    /**
     * Renders the requested step inside a form.
     *
     * The field renderer receives a field and returns its markup. When omitted,
     * Helper::renderField() is used for built-in fields.
     *
     * @param string        $key          Unique key identifying the form flow.
     * @param int           $step         Zero-based step index.
     * @param callable|null $fieldRenderer Optional field renderer callback.
     *
     * @return string The rendered form step.
     */
    public function render(string $key, int $step = 0, ?callable $fieldRenderer = null): string
    {
        $this->assertReady($key);
        $step = $this->normalizeStep($step);
        $stepData = $this->steps[$step];
        $values = $this->getValues($key);
        $renderer = $fieldRenderer ?? static function (FieldTypeInterface $field) use ($values): string {
            return Helper::renderField($field, $values);
        };

        $attributes = $this->attributes;
        $attributes['class'] = trim(($attributes['class'] ?? '') . ' ' . $this->styles->get('form'));
        $css = $this->styles->getCss();
        $markup = $css !== '' ? '<style>' . $css . '</style>' : '';
        $markup .= '<form' . Helper::renderAttributes($attributes) . '>';
        $markup .= '<input type="hidden" name="' . Helper::escape($this->stepParameter)
            . '" value="' . $step . '" />';

        $layout = $this->styles->getLayout();
        if ($layout === 'vertical-sidebar') {
            $markup .= '<aside class="msf__sidebar">' . $this->renderStepList($step, 'sidebar') . '</aside>';
            $markup .= '<main class="msf__main">';
        } elseif ($layout === 'numbered-stepper') {
            $markup .= $this->renderStepList($step, 'stepper');
        } elseif ($layout === 'icon-grid-cards') {
            $markup .= $this->renderStepList($step, 'cards');
        }

        if ($stepData['title'] !== '') {
            $markup .= '<h2' . Helper::renderAttributes(['class' => $this->styles->get('title')]) . '>'
                . Helper::escape($stepData['title'])
                . '</h2>';
        }

        $progress = $layout === 'one-thing-at-a-time'
            ? sprintf('Question %d of %d', $step + 1, count($this->steps))
            : sprintf('Step %d of %d', $step + 1, count($this->steps));
        $progressAttributes = ['class' => $this->styles->get('progress')];
        if ($layout === 'one-thing-at-a-time') {
            $progressAttributes['style'] = sprintf('--msf-progress:%d%%', (int) round((($step + 1) / count($this->steps)) * 100));
        }
        $markup .= '<p' . Helper::renderAttributes($progressAttributes) . '>'
            . Helper::escape($progress)
            . '</p>';
        $markup .= '<div' . Helper::renderAttributes(['class' => $this->styles->get('step')]) . '>';

        foreach ($stepData['fields'] as $field) {
            $fieldMarkup = $field->create($values, $renderer);
            if (!is_string($fieldMarkup)) {
                throw new \UnexpectedValueException('The field renderer must return a string.');
            }
            $markup .= $fieldMarkup;
        }

        $markup .= '</div><div' . Helper::renderAttributes(['class' => $this->styles->get('navigation')]) . '>';
        if ($step > 0) {
            $markup .= $this->renderNavigationButton('back', 'Back', $this->styles->get('back'));
        }

        if ($step < count($this->steps) - 1) {
            $markup .= $this->renderNavigationButton('next', 'Continue', $this->styles->get('next'));
        } else {
            $markup .= $this->renderNavigationButton('finish', 'Submit', $this->styles->get('submit'));
        }

        $markup .= '</div>';
        if ($layout === 'vertical-sidebar') {
            $markup .= '</main>';
        }

        return $markup . '</form>';
    }

    /**
     * Validates the wrapper and flow key before processing or rendering.
     *
     * @param string $key Storage key.
     *
     * @return void
     */
    private function assertReady(string $key): void
    {
        $this->assertKey($key);
        if (!$this->steps) {
            throw new InvalidArgumentException('Add at least one step before rendering or processing submissions.');
        }
    }

    /**
     * Validates a storage key.
     *
     * @param string $key Storage key.
     *
     * @return void
     */
    private function assertKey(string $key): void
    {
        if ($key === '') {
            throw new InvalidArgumentException('A multi-step form storage key cannot be empty.');
        }
    }

    /**
     * Keeps a submitted step index within configured bounds.
     *
     * @param mixed $step Submitted step index.
     *
     * @return int A valid step index.
     */
    private function normalizeStep($step): int
    {
        if (!is_int($step) && !is_string($step)) {
            throw new InvalidArgumentException('The submitted multi-step form index must be an integer.');
        }
        if (filter_var($step, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('The submitted multi-step form index must be an integer.');
        }

        return max(0, min(count($this->steps) - 1, (int) $step));
    }

    /**
     * Renders a navigation submit button.
     *
     * @param string $action Action submitted by the button.
     * @param string $label  Visible button label.
     * @param string $class CSS classes.
     *
     * @return string Button markup.
     */
    private function renderNavigationButton(string $action, string $label, string $class): string
    {
        return '<button type="submit" name="' . Helper::escape($this->actionParameter)
            . '" value="' . Helper::escape($action) . '"'
            . (in_array($action, ['back'], true) ? ' formnovalidate' : '')
            . Helper::renderAttributes(['class' => trim($this->styles->get('button') . ' ' . $class)])
            . '>' . Helper::escape($label) . '</button>';
    }

    /**
     * Renders the step overview used by progress-driven and card layouts.
     *
     * Only completed steps are interactive, so users can revisit earlier
     * answers without skipping required upcoming steps.
     *
     * @param int    $currentStep Current zero-based step.
     * @param string $layout      Overview layout: stepper, sidebar, or cards.
     *
     * @return string The step navigation markup.
     */
    private function renderStepList(int $currentStep, string $layout): string
    {
        $markup = '<ol' . Helper::renderAttributes([
            'class' => $this->styles->get('step-list'),
            'aria-label' => 'Form steps',
        ]) . '>';

        foreach ($this->steps as $index => $step) {
            $status = $index < $currentStep ? 'is-complete' : ($index === $currentStep ? 'is-current' : 'is-upcoming');
            $itemClass = trim($this->styles->get('step-item') . ' ' . $status);
            $ariaCurrent = $index === $currentStep ? 'step' : null;

            if ($layout === 'cards') {
                $icon = $step['icon'] !== '' ? $step['icon'] : $this->styles->getStepIcon($index);
                $content = '<span' . Helper::renderAttributes(['class' => $this->styles->get('step-icon')]) . '>'
                    . Helper::escape($icon)
                    . '</span><span' . Helper::renderAttributes(['class' => $this->styles->get('step-label')]) . '>'
                    . Helper::escape($step['title'] !== '' ? $step['title'] : sprintf('Step %d', $index + 1))
                    . '</span>';

                if ($index < $currentStep) {
                    $markup .= '<li><button type="submit" name="' . Helper::escape($this->actionParameter)
                        . '" value="goto:' . $index . '"'
                        . ' formnovalidate'
                        . Helper::renderAttributes([
                            'class' => $itemClass,
                            'aria-label' => sprintf('Return to step %d: %s', $index + 1, $step['title']),
                        ]) . '>' . $content . '</button></li>';
                } else {
                    $markup .= '<li><span' . Helper::renderAttributes([
                        'class' => $itemClass,
                        'aria-current' => $ariaCurrent,
                        'aria-disabled' => $index > $currentStep ? 'true' : null,
                    ]) . '>' . $content . '</span></li>';
                }

                continue;
            }

            $number = $layout === 'stepper' ? sprintf('%02d', $index + 1) : (string) ($index + 1);
            $item = '<span' . Helper::renderAttributes(['class' => $this->styles->get('step-number')]) . '>'
                . Helper::escape($number)
                . '</span><span' . Helper::renderAttributes(['class' => $this->styles->get('step-label')]) . '>'
                . Helper::escape($step['title'] !== '' ? $step['title'] : sprintf('Step %d', $index + 1))
                . '</span>';
            $markup .= '<li' . Helper::renderAttributes([
                'class' => $itemClass,
                'aria-current' => $ariaCurrent,
            ]) . '>' . $item . '</li>';
        }

        return $markup . '</ol>';
    }
}
