<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Fieldset;
use Simp\Multiform\lib\field\Option;
use Simp\Multiform\lib\field\Output;
use Simp\Multiform\lib\field\Progress;
use Simp\Multiform\lib\field\Select;
use Simp\Multiform\lib\field\Textarea;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$biography = new Textarea();
$biography->setLabel('Biography');
$biography->setHtmlId('biography');
$biography->setAttribute('rows', 4);
$biography->setAttribute('cols', 40);
$biography->setValue('A short introduction.');

$country = new Select();
$country->setLabel('Country');
$country->setHtmlId('country');
$country->setList([
    'mw' => 'Malawi',
    'zm' => 'Zambia',
]);

$details = new Fieldset();
$details->setLabel('Personal details');
$details->setHtmlId('details');

$result = new Output();
$result->setLabel('Calculated result');
$result->setHtmlId('result');
$result->setValue(42);

$progress = new Progress();
$progress->setHtmlId('progress');
$progress->setValue(3);
$progress->setAttribute('max', 5);

$form = new Form();
$form->addField($details, 'details-group');
$form->addField($result);
$form->addField($progress);

$children = [$biography, $country];
echo $form->create([], static function ($field) use ($details, $children): string {
    return $field === $details
        ? Helper::renderField($field, [], $children)
        : Helper::renderField($field);
});
