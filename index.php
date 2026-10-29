<?php

require __DIR__ . '/vendor/autoload.php';

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

$nameField = new Text();
$nameField->setLabel('Name');
$nameField->setHtmlId('name');
$nameField->setPlaceholder('Your name');
$nameField->setRequired(true);
$nameField->setValue($state->getValue('text', ''));

$emailField = new Email();
$emailField->setLabel('Email');
$emailField->setHtmlId('email');
$emailField->setPlaceholder('you@example.com');
$emailField->setRequired(true);
$emailField->setValue($state->getValue('email', ''));

$messageField = new Textarea();
$messageField->setLabel('Message');
$messageField->setHtmlId('message');
$messageField->setPlaceholder('How can we help?');
$messageField->setRequired(true);
$messageField->setValue($state->getValue('textarea', ''));

$submitField = new Submit();
$submitField->setValue('Send message');

$form = new Form();
$form
    ->setAction('')
    ->setMethod('post')
    ->setAttribute('accept-charset', 'UTF-8')
    ->addField($nameField)
    ->addField($emailField)
    ->addField($messageField)
    ->addField($submitField);

if ($state->isSubmitted() && $state->isValid()) {
    echo '<p>Thanks! Your form was submitted.</p>';
    dump($state->getValues());
}

if ($state->hasErrors('email')) {
    foreach ($state->getErrors('email') as $error) {
        echo '<p class="form-error">' . Helper::escape($error) . '</p>';
    }
}

echo $form->create(
    $state->getValues(),
    static function (\FieldTypeInterface $field) use ($state): string {
        return Helper::renderField($field, $state->getValues());
    }
);