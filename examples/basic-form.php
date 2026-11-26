<?php

error_reporting(E_ALL);


require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\Submit;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\field\Textarea;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\form\FormState;
use Simp\Multiform\lib\Helper;

$state = new FormState();
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $state->setSubmittedValues($_POST);

    foreach (['text' => 'Name', 'textarea' => 'Message'] as $name => $label) {
        $value = $state->getValue($name);
        if (!is_string($value) || trim($value) === '') {
            $state->addError($name, $label . ' is required.');
        }
    }

    $email = $state->getValue('email');
    if (!is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $state->addError('email', 'Enter a valid email address.');
    }
}

$name = new Text();
$name->setLabel('Name');
$name->setHtmlId('name');
$name->setRequired(true);
$name->setValue($state->getValue('text', ''));

$name1 = new Text();
$name1->setLabel('Title');
$name1->setHtmlId('Title');
$name1->setName('title');
$name1->setRequired(true);
$name1->setValue($state->getValue('title', ''));

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('email');
$email->setRequired(true);
$email->setValue($state->getValue('email', ''));

$message = new Textarea();
$message->setLabel('Message');
$message->setHtmlId('message');
$message->setRequired(true);
$message->setValue($state->getValue('textarea', ''));

$send = new Submit();
$send->setValue('Send message');

$form = new Form();
$form->setMethod('post');
$form->addField($name1);
$form->addField($name);
$form->addField($email);
$form->addField($message);
$form->addField($send);

if ($state->isSubmitted() && $state->isValid()) {
    echo '<p>Thank you. The form was submitted successfully.</p>';
}

foreach ($state->getAllErrors() as $fieldErrors) {
    foreach ($fieldErrors as $error) {
        echo '<p class="form-error">' . Helper::escape($error) . '</p>';
    }
}

echo $form->create($state->getValues(), static function ($field) use ($state): string {
    return Helper::renderField($field, $state->getValues());
});
