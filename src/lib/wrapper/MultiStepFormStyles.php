<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\wrapper;

use InvalidArgumentException;

/**
 * Provides layouts, CSS classes, and self-contained CSS for multi-step forms.
 */
class MultiStepFormStyles
{
    private const PRESETS = [
        'plain' => [
            'layout' => 'basic',
            'classes' => [
                'form' => 'multistep-form',
                'step' => 'multistep-form__step',
                'title' => 'multistep-form__title',
                'progress' => 'multistep-form__progress',
                'navigation' => 'multistep-form__navigation',
                'button' => 'multistep-form__button',
                'back' => 'multistep-form__button multistep-form__button--back',
                'next' => 'multistep-form__button multistep-form__button--next',
                'submit' => 'multistep-form__button multistep-form__button--submit',
            ],
        ],
        'bootstrap' => [
            'layout' => 'basic',
            'classes' => [
                'form' => 'multistep-form',
                'step' => 'card card-body mb-3',
                'title' => 'h2 card-title',
                'progress' => 'progress mb-3',
                'navigation' => 'd-flex justify-content-between mt-3',
                'button' => 'btn',
                'back' => 'btn btn-secondary',
                'next' => 'btn btn-primary',
                'submit' => 'btn btn-success',
            ],
        ],
        'tailwind' => [
            'layout' => 'basic',
            'classes' => [
                'form' => 'space-y-6',
                'step' => 'rounded-lg border border-gray-200 p-6 space-y-4',
                'title' => 'text-xl font-semibold',
                'progress' => 'w-full h-2 rounded bg-gray-200',
                'navigation' => 'flex justify-between gap-4',
                'button' => 'rounded px-4 py-2 font-medium',
                'back' => 'rounded bg-gray-200 px-4 py-2 font-medium',
                'next' => 'rounded bg-blue-600 px-4 py-2 font-medium text-white',
                'submit' => 'rounded bg-green-600 px-4 py-2 font-medium text-white',
            ],
        ],
        'numbered-stepper' => [
            'layout' => 'numbered-stepper',
            'classes' => [
                'form' => 'msf msf--stepper',
                'step' => 'msf__panel',
                'title' => 'msf__title',
                'progress' => 'msf__progress-text',
                'navigation' => 'msf__navigation',
                'button' => 'msf__button',
                'back' => 'msf__button msf__button--back',
                'next' => 'msf__button msf__button--next',
                'submit' => 'msf__button msf__button--submit',
                'step-list' => 'msf-stepper',
                'step-item' => 'msf-stepper__item',
                'step-number' => 'msf-stepper__number',
                'step-label' => 'msf-stepper__label',
            ],
        ],
        'one-thing-at-a-time' => [
            'layout' => 'one-thing-at-a-time',
            'classes' => [
                'form' => 'msf msf--focused',
                'step' => 'msf__panel msf__panel--focused',
                'title' => 'msf__title',
                'progress' => 'msf__progress-text',
                'navigation' => 'msf__navigation msf__navigation--focused',
                'button' => 'msf__button',
                'back' => 'msf__button msf__button--back',
                'next' => 'msf__button msf__button--next',
                'submit' => 'msf__button msf__button--submit',
            ],
        ],
        'vertical-sidebar' => [
            'layout' => 'vertical-sidebar',
            'classes' => [
                'form' => 'msf msf--sidebar',
                'step' => 'msf__panel',
                'title' => 'msf__title',
                'progress' => 'msf__progress-text',
                'navigation' => 'msf__navigation',
                'button' => 'msf__button',
                'back' => 'msf__button msf__button--back',
                'next' => 'msf__button msf__button--next',
                'submit' => 'msf__button msf__button--submit',
                'step-list' => 'msf-sidebar',
                'step-item' => 'msf-sidebar__item',
                'step-number' => 'msf-sidebar__number',
                'step-label' => 'msf-sidebar__label',
            ],
        ],
        'icon-grid-cards' => [
            'layout' => 'icon-grid-cards',
            'classes' => [
                'form' => 'msf msf--cards',
                'step' => 'msf__panel',
                'title' => 'msf__title',
                'progress' => 'msf__progress-text',
                'navigation' => 'msf__navigation',
                'button' => 'msf__button',
                'back' => 'msf__button msf__button--back',
                'next' => 'msf__button msf__button--next',
                'submit' => 'msf__button msf__button--submit',
                'step-list' => 'msf-cards',
                'step-item' => 'msf-cards__item',
                'step-icon' => 'msf-cards__icon',
                'step-label' => 'msf-cards__label',
            ],
        ],
    ];

    private const CSS = <<<'CSS'
.msf{--msf-ink:#172033;--msf-muted:#6b7280;--msf-line:#d8deea;--msf-accent:#5b5ce2;--msf-accent-soft:#eeefff;--msf-success:#16845b;color:var(--msf-ink);font:inherit;max-width:860px;margin:2rem auto;padding:clamp(1rem,3vw,2rem);background:#fff;border:1px solid #e8ebf2;border-radius:20px;box-shadow:0 18px 55px rgba(27,39,71,.09)}
.msf *{box-sizing:border-box}.msf__title{font-size:clamp(1.35rem,3vw,2rem);line-height:1.2;margin:0 0 1rem}.msf__progress-text{color:var(--msf-muted);font-size:.875rem;margin:.75rem 0 1rem}.msf__panel{padding:clamp(1rem,3vw,1.75rem);border:1px solid #e8ebf2;border-radius:16px;background:linear-gradient(145deg,#fff,#fafbff);animation:msf-enter .28s ease both}
.msf__panel label{display:block;font-weight:600;margin:.9rem 0 .4rem}.msf__panel input:not([type=checkbox]):not([type=radio]),.msf__panel textarea,.msf__panel select{width:100%;max-width:100%;padding:.75rem .85rem;border:1px solid #cbd2e1;border-radius:10px;font:inherit;color:inherit;background:#fff}.msf__panel input:focus,.msf__panel textarea:focus,.msf__panel select:focus{outline:3px solid rgba(91,92,226,.18);border-color:var(--msf-accent)}
.msf__navigation{display:flex;justify-content:space-between;align-items:center;gap:.75rem;margin-top:1.25rem}.msf__button{border:0;border-radius:10px;padding:.72rem 1.15rem;font:inherit;font-weight:650;cursor:pointer;transition:transform .15s,filter .15s}.msf__button:hover{transform:translateY(-1px);filter:brightness(.97)}.msf__button--back{background:#f0f2f7;color:#374151}.msf__button--next,.msf__button--submit{background:var(--msf-accent);color:#fff}.msf__button--submit{background:var(--msf-success)}
.msf-stepper{display:flex;list-style:none;padding:0;margin:0 0 1.5rem;counter-reset:step}.msf-stepper__item{position:relative;display:flex;align-items:center;gap:.55rem;flex:1;min-width:0;color:var(--msf-muted);font-size:.8rem}.msf-stepper__item:not(:last-child):after{content:"";height:2px;background:var(--msf-line);flex:1;margin:0 .6rem}.msf-stepper__item.is-complete:not(:last-child):after{background:var(--msf-success)}.msf-stepper__number,.msf-sidebar__number{display:grid;place-items:center;flex:0 0 2rem;width:2rem;height:2rem;border:2px solid var(--msf-line);border-radius:50%;font-weight:700;background:white}.msf-stepper__item.is-current,.msf-stepper__item.is-complete{color:var(--msf-ink)}.msf-stepper__item.is-current .msf-stepper__number{border-color:var(--msf-accent);background:var(--msf-accent);color:white;box-shadow:0 0 0 4px var(--msf-accent-soft)}.msf-stepper__item.is-complete .msf-stepper__number{border-color:var(--msf-success);background:var(--msf-success);color:white}.msf-stepper__label{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.msf--focused{max-width:680px}.msf__panel--focused{padding:clamp(1.5rem,5vw,3rem);min-height:220px}.msf--focused .msf__title{font-size:clamp(1.6rem,4vw,2.4rem)}.msf__navigation--focused{padding-top:.5rem}.msf--focused .msf__progress-text:after{content:"";display:block;height:4px;margin-top:.7rem;border-radius:9px;background:linear-gradient(to right,var(--msf-accent) var(--msf-progress,0%),#eceef4 var(--msf-progress,0%))}
.msf--sidebar{max-width:1100px;display:grid;grid-template-columns:minmax(190px,260px) minmax(0,1fr);gap:clamp(1.25rem,4vw,3rem);align-items:start}.msf--sidebar .msf__sidebar{grid-column:1;grid-row:1}.msf-sidebar{list-style:none;padding:0;margin:0}.msf-sidebar__item{display:flex;align-items:center;gap:.8rem;padding:.7rem .75rem;margin:.2rem 0;color:var(--msf-muted);border-radius:12px}.msf-sidebar__item.is-current{background:var(--msf-accent-soft);color:var(--msf-accent);font-weight:700}.msf-sidebar__item.is-complete .msf-sidebar__number{border-color:var(--msf-success);color:var(--msf-success)}.msf-sidebar__item.is-current .msf-sidebar__number{border-color:var(--msf-accent);background:var(--msf-accent);color:white}.msf--sidebar .msf__main{grid-column:2;grid-row:1;min-width:0}.msf--sidebar .msf__title{margin:0}.msf--sidebar .msf__progress-text{margin:.75rem 0 1rem}
.msf-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(125px,1fr));gap:.65rem;list-style:none;padding:0;margin:0 0 1.5rem}.msf-cards__item{display:flex;align-items:center;gap:.65rem;min-height:66px;padding:.7rem;border:1px solid #e7eaf1;border-radius:14px;background:#fff;color:var(--msf-muted);font:inherit;text-align:left}.msf-cards__item.is-current{border-color:var(--msf-accent);background:var(--msf-accent-soft);color:var(--msf-accent);box-shadow:0 5px 16px rgba(91,92,226,.12)}.msf-cards__item.is-complete{border-color:#b9e5d4;color:var(--msf-success)}.msf-cards__item:disabled{opacity:1}.msf-cards__icon{display:grid;place-items:center;flex:0 0 2rem;width:2rem;height:2rem;border-radius:10px;background:#f2f3f8;font-size:1.1rem}.msf-cards__item.is-current .msf-cards__icon{background:#fff}.msf-cards__label{font-size:.82rem;font-weight:650}
@keyframes msf-enter{from{opacity:0;transform:translateY(7px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:640px){.msf-stepper__item{justify-content:center}.msf-stepper__label{display:none}.msf-stepper__item:not(:last-child):after{margin:0 .35rem}.msf--sidebar{display:block}.msf-sidebar{display:flex;overflow-x:auto;margin:0 0 1.25rem}.msf-sidebar__item{flex:0 0 auto}.msf-sidebar__label{display:none}.msf-cards{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(prefers-reduced-motion:reduce){.msf__panel{animation:none}.msf__button{transition:none}}
CSS;

    private const ICONS = ['✦', '◎', '◇', '↗', '♡', '✚', '⌂', '☼'];

    private $preset;
    private $classes;

    /**
     * Creates a style set from a named preset.
     *
     * Presets: plain, bootstrap, tailwind, numbered-stepper,
     * one-thing-at-a-time, vertical-sidebar, or icon-grid-cards.
     *
     * @param string $preset Preset name.
     */
    public function __construct(string $preset = 'plain')
    {
        if (!isset(self::PRESETS[$preset])) {
            throw new InvalidArgumentException(sprintf('Unknown multi-step form style preset "%s".', $preset));
        }

        $this->preset = $preset;
        $this->classes = self::PRESETS[$preset]['classes'];
    }

    /**
     * Gets the selected preset name.
     *
     * @return string The preset name.
     */
    public function getPreset(): string
    {
        return $this->preset;
    }

    /**
     * Gets the visual layout type used by the wrapper.
     *
     * @return string The layout identifier.
     */
    public function getLayout(): string
    {
        return self::PRESETS[$this->preset]['layout'];
    }

    /**
     * Gets a CSS class value.
     *
     * @param string $element Style slot such as form, step, back, or next.
     *
     * @return string The configured CSS classes, or an empty string.
     */
    public function get(string $element): string
    {
        return $this->classes[$element] ?? '';
    }

    /**
     * Gets all configured CSS class slots.
     *
     * @return array<string, string> CSS classes keyed by slot.
     */
    public function all(): array
    {
        return $this->classes;
    }

    /**
     * Sets or overrides a CSS class slot.
     *
     * @param string $element Style slot name.
     * @param string $class   CSS classes for the slot.
     *
     * @return $this
     */
    public function set(string $element, string $class)
    {
        if ($element === '') {
            throw new InvalidArgumentException('A style slot name cannot be empty.');
        }

        $this->classes[$element] = $class;

        return $this;
    }

    /**
     * Gets an icon for a step index.
     *
     * @param int $index Zero-based step index.
     *
     * @return string The icon glyph.
     */
    public function getStepIcon(int $index): string
    {
        return self::ICONS[$index % count(self::ICONS)];
    }

    /**
     * Returns the CSS needed by the built-in visual presets.
     *
     * Framework presets rely on their framework's own CSS. The new design
     * presets share the self-contained .msf stylesheet.
     *
     * @return string CSS source.
     */
    public function getCss(): string
    {
        return in_array($this->preset, [
            'numbered-stepper',
            'one-thing-at-a-time',
            'vertical-sidebar',
            'icon-grid-cards',
        ], true) ? self::CSS : '';
    }
}
