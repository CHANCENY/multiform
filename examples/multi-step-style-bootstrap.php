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
$name->setHtmlId('bootstrap-name');

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('bootstrap-email');

$message = new Textarea();
$message->setLabel('What would you like help with?');
$message->setHtmlId('bootstrap-message');

$contactMethod = new Select();
$contactMethod->setLabel('Preferred contact method');
$contactMethod->setHtmlId('bootstrap-contact-method');
$contactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);

$reviewName = new Text();
$reviewName->setLabel('Name');
$reviewName->setHtmlId('bootstrap-review-name');
$reviewName->setReadonly(true);

$reviewEmail = new Email();
$reviewEmail->setLabel('Email');
$reviewEmail->setHtmlId('bootstrap-review-email');
$reviewEmail->setReadonly(true);

$reviewMessage = new Textarea();
$reviewMessage->setLabel('What would you like help with?');
$reviewMessage->setHtmlId('bootstrap-review-message');
$reviewMessage->setReadonly(true);

$reviewContactMethod = new Select();
$reviewContactMethod->setLabel('Preferred contact method');
$reviewContactMethod->setHtmlId('bootstrap-review-contact-method');
$reviewContactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);
$reviewContactMethod->setDisabled(true);

$wizard = new MultiStepFormWrapper($store, new MultiStepFormStyles('bootstrap'));
$wizard->setAttribute('id', 'bootstrap-example');
$wizard->addStep('About you', [$name, $email]);
$wizard->addStep('Your preferences', [$message, $contactMethod]);
$wizard->addStep('Review', [$reviewName, $reviewEmail, $reviewMessage, $reviewContactMethod]);

$flowKey = 'bootstrap-style-flow';
$completedValues = null;
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $wizard->processSubmission($flowKey, $_POST);

    if ($wizard->isComplete($flowKey)) {
        $completedValues = $wizard->getValues($flowKey);
        $wizard->clear($flowKey);
    }
}

echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Bootstrap multi-step form</title><body>';
echo '<h1>Bootstrap preset</h1>';
echo '<p>Load Bootstrap CSS in your page to apply the preset styling.</p>';
if ($completedValues !== null) {
    echo '<p>Thank you, ' . Helper::escape($completedValues['text'] ?? '') . '.</p>';
} else {
    echo $wizard->render($flowKey, $wizard->getCurrentStep($flowKey));
}
echo '</body></html>';
