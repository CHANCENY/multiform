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
$name->setHtmlId('sidebar-name');

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('sidebar-email');

$message = new Textarea();
$message->setLabel('What would you like help with?');
$message->setHtmlId('sidebar-message');

$contactMethod = new Select();
$contactMethod->setLabel('Preferred contact method');
$contactMethod->setHtmlId('sidebar-contact-method');
$contactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);

$reviewName = new Text();
$reviewName->setLabel('Name');
$reviewName->setHtmlId('sidebar-review-name');
$reviewName->setReadonly(true);

$reviewEmail = new Email();
$reviewEmail->setLabel('Email');
$reviewEmail->setHtmlId('sidebar-review-email');
$reviewEmail->setReadonly(true);

$reviewMessage = new Textarea();
$reviewMessage->setLabel('What would you like help with?');
$reviewMessage->setHtmlId('sidebar-review-message');
$reviewMessage->setReadonly(true);

$reviewContactMethod = new Select();
$reviewContactMethod->setLabel('Preferred contact method');
$reviewContactMethod->setHtmlId('sidebar-review-contact-method');
$reviewContactMethod->setList([
    'email' => 'Email',
    'phone' => 'Phone',
]);
$reviewContactMethod->setDisabled(true);

$wizard = new MultiStepFormWrapper($store, new MultiStepFormStyles('vertical-sidebar'));
$wizard->setAttribute('id', 'vertical-sidebar-example');
$wizard->addStep('About you', [$name, $email]);
$wizard->addStep('Your preferences', [$message, $contactMethod]);
$wizard->addStep('Review', [$reviewName, $reviewEmail, $reviewMessage, $reviewContactMethod]);

$flowKey = 'vertical-sidebar-flow';
$completedValues = null;
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $wizard->processSubmission($flowKey, $_POST);

    if ($wizard->isComplete($flowKey)) {
        $completedValues = $wizard->getValues($flowKey);
        $wizard->clear($flowKey);
    }
}

echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Vertical sidebar</title><body>';
echo '<h1>Vertical sidebar</h1>';
if ($completedValues !== null) {
    echo '<p>Thank you, ' . Helper::escape($completedValues['text'] ?? '') . '.</p>';
} else {
    echo $wizard->render($flowKey, $wizard->getCurrentStep($flowKey));
}
echo '</body></html>';
