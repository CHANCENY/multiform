<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\FieldBase;
use Simp\Multiform\lib\field\Submit;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$name = new Text();
$name->setLabel('Name');
$name->setHtmlId('name');
$name->setHtmlClass(['control', 'control-text']);
$name->setDataAttribute(['validation' => 'required']);

$email = new Email();
$email->setLabel('Email');
$email->setHtmlId('email');
$email->setAriaAttribtues(['describedby' => 'email-help']);

$submit = new Submit();
$submit->setValue('Save');

$form = new Form(['class' => 'custom-form', 'autocomplete' => 'on']);
$form->addField($name);
$form->addField($email);
$form->addField($submit);

$renderer = static function ($field): string {
    $markup = Helper::renderField($field);

    if ($field instanceof FieldBase && $field->getType() === 'submit') {
        return '<footer class="form-actions">' . $markup . '</footer>';
    }

    return '<div class="form-row">' . $markup . '</div>';
};

echo $form->create([], $renderer);
