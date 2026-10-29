<?php

require __DIR__ . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Datalist;
use Simp\Multiform\lib\field\Fieldset;
use Simp\Multiform\lib\field\Meter;
use Simp\Multiform\lib\field\MultiSelect;
use Simp\Multiform\lib\field\Option;
use Simp\Multiform\lib\field\Output;
use Simp\Multiform\lib\field\Progress;
use Simp\Multiform\lib\field\Select;
use Simp\Multiform\lib\field\Textarea;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$fieldset = new Fieldset();
$fieldset->setLabel('Profile');
$fieldset->setHtmlId('profile-fields');

$biography = new Textarea();
$biography->setLabel('Biography');
$biography->setHtmlId('biography');
$biography->setValue('A short introduction.');
$biography->setAttribute('rows', 4);
$biography->setAttribute('cols', 40);

$country = new Select();
$country->setLabel('Country');
$country->setHtmlId('country');
$country->setList([
    'mw' => 'Malawi',
    'zm' => 'Zambia',
    'za' => 'South Africa',
]);
$country->setValue('mw');

$interests = new MultiSelect();
$interests->setLabel('Interests');
$interests->setHtmlId('interests');
$interests->setList([
    'php' => 'PHP',
    'html' => 'HTML',
    'css' => 'CSS',
]);
$interests->setValue(['php', 'html']);
$interests->setAttribute('size', 3);

$suggestions = new Datalist();
$suggestions->setHtmlId('country-suggestions');
$suggestions->setList([
    'Malawi' => 'Malawi',
    'Zambia' => 'Zambia',
    'South Africa' => 'South Africa',
]);

$sampleOption = new Option();
$sampleOption->setValue('other');
$sampleOption->setLabel('Other');

$calculation = new Output();
$calculation->setHtmlId('calculation');
$calculation->setValue('42');

$uploadProgress = new Progress();
$uploadProgress->setHtmlId('upload-progress');
$uploadProgress->setValue(65);
$uploadProgress->setAttribute('max', 100);

$rating = new Meter();
$rating->setHtmlId('rating');
$rating->setValue(4);
$rating->setAttribute('min', 0);
$rating->setAttribute('max', 5);
$rating->setAttribute('low', 2);
$rating->setAttribute('high', 4);
$rating->setAttribute('optimum', 5);

$fieldsetChildren = [$biography, $country, $interests];
$form = new Form();
$form->setMethod('post');
$form->addField($fieldset, 'profile-group');
$form->addField($suggestions);
$form->addField($calculation);
$form->addField($uploadProgress);
$form->addField($rating);

echo $form->create([], static function ($field) use ($fieldset, $suggestions, $fieldsetChildren, $sampleOption): string {
    if ($field === $fieldset) {
        return Helper::renderField($field, [], $fieldsetChildren);
    }

    if ($field === $suggestions) {
        return Helper::renderField($field, [], [$sampleOption]);
    }

    return Helper::renderField($field);
});
