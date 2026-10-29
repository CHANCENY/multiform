# Simp Multiform

A PHP library for building and rendering HTML forms, including multi-step form
flows with application-controlled state storage and built-in visual presets.

## Requirements

- PHP
- Composer

## Installation

Install the package with Composer:

```sh
composer require simp/multiform
```

If you are working from a local checkout, install its dependencies and load
Composer's autoloader:

```sh
composer install
```

```php
require __DIR__ . '/vendor/autoload.php';
```

## Build a basic form

Create field objects, configure them, add them to a `Form`, and provide a
renderer when creating the HTML:

```php
use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\Submit;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$name = new Text();
$name->setLabel('Name');
$name->setHtmlId('name');
$name->setRequired(true);

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('email');
$email->setRequired(true);

$submit = new Submit();
$submit->setValue('Send');

$form = new Form();
$form->setMethod('post');
$form->addField($name);
$form->addField($email);
$form->addField($submit);

echo $form->create($_POST, static function ($field): string {
    return Helper::renderField($field, $_POST);
});
```

`Helper::renderField()` is the default renderer for built-in fields. Use
`Helper::escape()` when inserting submitted values or other untrusted text into
your own HTML. The library renders fields; your application remains responsible
for validating submitted data on the server.

### Fields and attributes

The `Simp\Multiform\lib\field` namespace includes classes for the standard HTML
input types:

`Button`, `Checkbox`, `Color`, `Date`, `DateTimeLocal`, `Email`, `File`,
`Hidden`, `Image`, `Month`, `Number`, `Password`, `Radio`, `Range`, `Reset`,
`Search`, `Submit`, `Tel`, `Text`, `Time`, `Url`, and `Week`.

Additional element fields include `Textarea`, `Select`, `MultiSelect`, `Option`,
`Fieldset`, `Datalist`, `Output`, `Progress`, and `Meter`.

Fields share configuration methods such as `setLabel()`, `setValue()`,
`setDefault()`, `setRequired()`, `setHtmlId()`, `setHtmlClass()`,
`setPlaceholder()`, `setDisabled()`, `setReadonly()`, and `setAttribute()`.
Use `setList()` for select choices and datalist suggestions. For example:

```php
use Simp\Multiform\lib\field\Select;

$country = new Select();
$country->setLabel('Country');
$country->setList([
    'mw' => 'Malawi',
    'zm' => 'Zambia',
]);
```

See [`examples/input-types.php`](examples/input-types.php) and
[`examples/selection-controls.php`](examples/selection-controls.php) for more
field examples.

### Form state

`FormState` can keep submitted values, validation errors, a submitted flag, and
application-specific state together. It does not impose validation rules:

```php
use Simp\Multiform\lib\form\FormState;

$state = new FormState();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $state->setSubmittedValues($_POST);

    $email = $state->getValue('email');
    if (!is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $state->addError('email', 'Enter a valid email address.');
    }
}
```

Read values with `getValue()`/`getValues()` and errors with `getErrors()` or
`getAllErrors()`. Validate according to your application's requirements.

## Multi-step forms

`MultiStepFormWrapper` needs a `FormStateSessionStore`. Implement the interface
using the storage mechanism appropriate for your application. This example
uses the PHP session:

```php
use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\wrapper\FormStateSessionStore;
use Simp\Multiform\lib\wrapper\MultiStepFormStyles;
use Simp\Multiform\lib\wrapper\MultiStepFormWrapper;

if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
    throw new RuntimeException('Unable to start the PHP session.');
}

$store = new class implements FormStateSessionStore {
    public function load(string $key): array
    {
        return $_SESSION['multiform'][$key] ?? [];
    }

    public function save(string $key, array $values): void
    {
        $_SESSION['multiform'][$key] = $values;
    }

    public function clear(string $key): void
    {
        unset($_SESSION['multiform'][$key]);
    }
};

$name = new Text();
$name->setLabel('Name');

$email = new Email();
$email->setLabel('Email');

$wizard = new MultiStepFormWrapper(
    $store,
    new MultiStepFormStyles('numbered-stepper')
);
$wizard->addStep('About you', [$name]);
$wizard->addStep('Contact', [$email]);

$flowKey = 'registration';
$completedValues = null;
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $wizard->processSubmission($flowKey, $_POST);

    if ($wizard->isComplete($flowKey)) {
        $completedValues = $wizard->getValues($flowKey);
        $wizard->clear($flowKey);
    }
}

if ($completedValues !== null) {
    // Validate and process $completedValues for your application.
    echo 'Thank you, ' . \Simp\Multiform\lib\Helper::escape($completedValues['text'] ?? '') . '.';
} else {
    echo $wizard->render($flowKey, $wizard->getCurrentStep($flowKey));
}
```

Call `processSubmission()` for each POST request. It saves submitted values and
updates the active step. The final step's Submit button marks the flow complete;
read values with `getValues()` and clear saved state with `clear()` when
appropriate. The wrapper's internal state keys are excluded from `getValues()`.

The wrapper supports `back`, `next`, `finish`, and `goto:N` navigation actions.
Jump navigation is limited to previously visited steps. Browser-side required
field checks are not a substitute for server-side validation.

### Style presets

Pass one of these names to `MultiStepFormStyles`:

| Preset | Description |
| --- | --- |
| `plain` | Basic layout with library class names |
| `bootstrap` | Bootstrap-oriented utility classes; load Bootstrap CSS in your page |
| `tailwind` | Tailwind utility classes; load Tailwind CSS in your page |
| `numbered-stepper` | Horizontal numbered progress stepper |
| `one-thing-at-a-time` | Focused, conversational presentation |
| `vertical-sidebar` | Vertical progress sidebar |
| `icon-grid-cards` | Icon-based step cards |

The four visual presets include responsive CSS in the rendered form. The
Bootstrap and Tailwind presets rely on the corresponding framework CSS being
loaded by your application. Customize class slots with `set()`:

```php
$styles = (new MultiStepFormStyles('plain'))
    ->set('form', 'contact-wizard')
    ->set('next', 'button button--primary');
```

## Examples

Runnable examples are in [`examples/`](examples/):

- [`basic-form.php`](examples/basic-form.php) — form fields, state, and
  application-level validation
- [`input-types.php`](examples/input-types.php) — input controls
- [`selection-controls.php`](examples/selection-controls.php) — options,
  selects, and datalists
- [`grouped-elements.php`](examples/grouped-elements.php) — grouped fields
- [`custom-renderer.php`](examples/custom-renderer.php) — custom field markup
- [`file-upload.php`](examples/file-upload.php) — file input configuration
- [`multi-step-form.php`](examples/multi-step-form.php) — multi-step form flow
- [`multi-step-style-plain.php`](examples/multi-step-style-plain.php) and the
  other [`multi-step-style-*.php`](examples/) files — one style preset per
  example

Run an example through a PHP-enabled web server. The multi-step examples use
PHP session storage and must be requested through a server that preserves the
session cookie between requests.

## Development

Run the PHPUnit suite:

```sh
./vendor/bin/phpunit
```

Run Rector in dry-run mode or apply its configured refactors:

```sh
composer rector:check
composer rector
```

Rector is configured in [`rector.php`](rector.php). PHPUnit discovers tests in
[`tests/`](tests/) using [`phpunit.xml`](phpunit.xml).
