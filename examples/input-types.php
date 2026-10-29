<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\Button;
use Simp\Multiform\lib\field\Checkbox;
use Simp\Multiform\lib\field\Color;
use Simp\Multiform\lib\field\Date;
use Simp\Multiform\lib\field\DateTimeLocal;
use Simp\Multiform\lib\field\Email;
use Simp\Multiform\lib\field\File;
use Simp\Multiform\lib\field\Hidden;
use Simp\Multiform\lib\field\Image;
use Simp\Multiform\lib\field\Month;
use Simp\Multiform\lib\field\Number;
use Simp\Multiform\lib\field\Password;
use Simp\Multiform\lib\field\Radio;
use Simp\Multiform\lib\field\Range;
use Simp\Multiform\lib\field\Reset;
use Simp\Multiform\lib\field\Search;
use Simp\Multiform\lib\field\Submit;
use Simp\Multiform\lib\field\Tel;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\field\Time;
use Simp\Multiform\lib\field\Url;
use Simp\Multiform\lib\field\Week;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$fields = [
    new Text(),
    new Password(),
    new Email(),
    new Number(),
    new Tel(),
    new Url(),
    new Search(),
    new Hidden(),
    new Checkbox(),
    new Radio(),
    new File(),
    new Submit(),
    new Reset(),
    new Button(),
    new Image(),
    new Date(),
    new DateTimeLocal(),
    new Month(),
    new Week(),
    new Time(),
    new Range(),
    new Color(),
];

foreach ($fields as $field) {
    $field->setLabel(ucfirst($field->getType()));
    $field->setHtmlId($field->getName());
}

$fields[7]->setValue('hidden-example');
$fields[8]->setValue('yes');
$fields[8]->setAttribute('checked', true);
$fields[9]->setValue('standard');
$fields[9]->setAttribute('checked', true);
$fields[11]->setValue('Submit');
$fields[12]->setValue('Reset');
$fields[13]->setValue('Click me');
$fields[14]->setAttribute('src', '/submit-image.png');
$fields[14]->setAttribute('alt', 'Submit');
$fields[20]->setValue(50);
$fields[20]->setAttribute('min', 0);
$fields[20]->setAttribute('max', 100);
$fields[21]->setValue('#336699');

$form = new Form();
$form->setMethod('post');
$form->setEnctype('multipart/form-data');
foreach ($fields as $field) {
    $form->addField($field);
}

echo $form->create([], static function ($field): string {
    return '<div class="field">'
        . Helper::renderField($field)
        . '</div>';
});
