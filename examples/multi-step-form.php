<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\Select;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\field\Textarea;
use Simp\Multiform\lib\Helper;
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
$name->setLabel('Full name');
$name->setHtmlId('full-name');
$name->setRequired(true);

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('email');
$email->setRequired(true);

$message = new Textarea();
$message->setLabel('Message');
$message->setHtmlId('message');
$message->setRequired(true);
$message->setAttribute('rows', 5);

$country = new Select();
$country->setLabel('Country');
$country->setHtmlId('country');
$country->setList([
    'mw' => 'Malawi',
    'zm' => 'Zambia',
    'za' => 'South Africa',
]);
$country->setRequired(true);

$styles = (new MultiStepFormStyles('plain'))
    ->set('form', 'contact-wizard')
    ->set('step', 'contact-wizard__panel');

$wizard = new MultiStepFormWrapper($store, $styles);
$wizard->setMethod('post');
$wizard->setAttribute('autocomplete', 'on');
$wizard->addStep('Your details', [$name, $email]);
$wizard->addStep('Your message', [$message, $country]);

$flowKey = 'contact-form';
$completedValues = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $wizard->processSubmission($flowKey, $_POST);

    if ($wizard->isComplete($flowKey)) {
        $completedValues = $wizard->getValues($flowKey);
        $wizard->clear($flowKey);
    }
}

echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Multi-step contact form</title><body>';
echo '<!-- Latest compiled and minified CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>';

if ($completedValues !== null) {
    echo '<h1>Thank you, ' . Helper::escape($completedValues['text'] ?? '') . '.</h1>';
    echo '<p>Your message has been received.</p>';
} else {
    echo $wizard->render($flowKey, $wizard->getCurrentStep($flowKey));
}

echo '</body></html>';
