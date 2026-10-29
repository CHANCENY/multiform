<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Datalist;
use Simp\Multiform\lib\field\MultiSelect;
use Simp\Multiform\lib\field\Select;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

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
$interests->setAttribute('size', 4);
$interests->setList([
    'php' => 'PHP',
    'html' => 'HTML',
    'css' => 'CSS',
]);
$interests->setValue(['php', 'html']);

$suggestions = new Datalist();
$suggestions->setHtmlId('cities');
$suggestions->setList([
    'Lilongwe' => 'Lilongwe',
    'Blantyre' => 'Blantyre',
    'Mzuzu' => 'Mzuzu',
]);

$city = new Text();
$city->setLabel('City');
$city->setHtmlId('city');
$city->setAttribute('list', 'cities');

$form = new Form();
$form->setMethod('post');
$form->addField($country);
$form->addField($interests);
$form->addField($city);
$form->addField($suggestions);

echo $form->create([], static function ($field): string {
    return Helper::renderField($field);
});
