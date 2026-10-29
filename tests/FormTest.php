<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\form\Form;

final class FormTest extends TestCase
{
    public function testCollectsFieldsAndRendersUsingCustomBuilder(): void
    {
        $field = new Text();
        $form = new Form(['id' => 'contact']);
        $form->setMethod('POST')->setAction('/submit')->addField($field);

        $markup = $form->create([], static function ($renderedField): string {
            return '<input name="' . $renderedField->getName() . '">';
        });

        self::assertSame('<form id="contact" method="post" action="/submit"><input name="text"></form>', $markup);
        self::assertSame($field, $form->getField('text'));
        self::assertSame(['text' => $field], $form->getFields());
    }

    public function testRejectsDuplicateFieldKeysAndInvalidMethods(): void
    {
        $form = new Form();
        $form->addField(new Text(), 'identity');

        try {
            $form->addField(new Text(), 'identity');
            self::fail('Expected duplicate field keys to be rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('already exists', $exception->getMessage());
        }

        $this->expectException(InvalidArgumentException::class);
        $form->setMethod('patch');
    }

    public function testFieldCanBeRemovedAndFormAttributesEscaped(): void
    {
        $form = new Form(['title' => '"quoted"']);
        $form->addField(new Text(), 'name')->removeField('name');

        self::assertSame([], $form->getFields());
        self::assertSame('<form title="&quot;quoted&quot;"></form>', $form->create([], static fn (): string => ''));
    }
}
