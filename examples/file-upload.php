<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Simp\Multiform\lib\field\File;
use Simp\Multiform\lib\field\Submit;
use Simp\Multiform\lib\form\Form;
use Simp\Multiform\lib\Helper;

$attachment = new File();
$attachment->setLabel('Choose an image');
$attachment->setHtmlId('attachment');
$attachment->setAttribute('accept', 'image/png,image/jpeg');
$attachment->setMultiple(true);

$submit = new Submit();
$submit->setValue('Upload');

$form = new Form();
$form->setMethod('post');
$form->setEnctype('multipart/form-data');
$form->addField($attachment);
$form->addField($submit);

echo '<p>Handle uploaded files from <code>$_FILES</code> on the server. '
    . 'Validate file size and content before storing them.</p>';
echo $form->create([], static function ($field): string {
    return '<div class="upload-field">' . Helper::renderField($field) . '</div>';
});
