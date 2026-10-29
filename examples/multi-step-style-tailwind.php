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
$name->setLabel('Name');
$name->setHtmlId('tailwind-name');

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('tailwind-email');

$message = new Textarea();
$message->setLabel('What would you like help with?');
$message->setHtmlId('tailwind-message');

$contactMethod = new Select();
$contactMethod->setLabel('Preferred contact method');
$contactMethod->setHtmlId('tailwind-contact-method');
$contactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);

$reviewName = new Text();
$reviewName->setLabel('Name');
$reviewName->setHtmlId('tailwind-review-name');
$reviewName->setReadonly(true);

$reviewEmail = new Email();
$reviewEmail->setLabel('Email');
$reviewEmail->setHtmlId('tailwind-review-email');
$reviewEmail->setReadonly(true);

$reviewMessage = new Textarea();
$reviewMessage->setLabel('What would you like help with?');
$reviewMessage->setHtmlId('tailwind-review-message');
$reviewMessage->setReadonly(true);

$reviewContactMethod = new Select();
$reviewContactMethod->setLabel('Preferred contact method');
$reviewContactMethod->setHtmlId('tailwind-review-contact-method');
$reviewContactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);
$reviewContactMethod->setDisabled(true);

$wizard = new MultiStepFormWrapper($store, new MultiStepFormStyles('tailwind'));
$wizard->setAttribute('id', 'tailwind-example');
$wizard->addStep('About you', [$name, $email]);
$wizard->addStep('Your preferences', [$message, $contactMethod]);
$wizard->addStep('Review', [$reviewName, $reviewEmail, $reviewMessage, $reviewContactMethod]);

$flowKey = 'tailwind-style-flow';
$completedValues = null;
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $wizard->processSubmission($flowKey, $_POST);

    if ($wizard->isComplete($flowKey)) {
        $completedValues = $wizard->getValues($flowKey);
        $wizard->clear($flowKey);
    }
}

echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Tailwind multi-step form</title><body>';
echo '<h1>Tailwind preset</h1>';
echo '<p>Include Tailwind CSS in your page to apply the preset utility classes.</p>';
if ($completedValues !== null) {
    echo '<p>Thank you, ' . Helper::escape($completedValues['text'] ?? '') . '.</p>';
} else {
    echo $wizard->render($flowKey, $wizard->getCurrentStep($flowKey));
}
echo '</body></html>';
