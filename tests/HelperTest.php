<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\Helper;
use Simp\Multiform\lib\field\File;
use Simp\Multiform\lib\field\Select;
use Simp\Multiform\lib\field\Textarea;
use Simp\Multiform\lib\field\Text;

final class HelperTest extends TestCase
{
    public function testEscapeHandlesNullBooleansAndHtmlSpecialCharacters(): void
    {
        self::assertSame('', Helper::escape(null));
        self::assertSame('true', Helper::escape(true));
        self::assertSame('&lt;Hi &amp; &quot;you&quot;&#039;&gt;', Helper::escape('<Hi & "you"\'>'));
    }

    public function testEscapeRejectsNonStringableValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Helper::escape(['unsafe']);
    }

    public function testRenderAttributesEscapesValuesAndUsesBooleanAttributeSyntax(): void
    {
        self::assertSame(
            ' disabled aria-disabled="false" title="A &amp; B"',
            Helper::renderAttributes([
                'disabled' => true,
                'aria-disabled' => false,
                'title' => 'A & B',
            ])
        );
    }

    public function testRenderAttributesRejectsInvalidNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Helper::renderAttributes(['bad name' => 'value']);
    }

    public function testRenderOptionsSupportsAssociativeAndListOptions(): void
    {
        self::assertSame(
            '<option value="mw">Malawi</option><option value="zm" selected>Zambia</option>',
            Helper::renderOptions(['mw' => 'Malawi', 'zm' => 'Zambia'], 'zm')
        );
        self::assertSame(
            '<option value="Red">Red</option><option value="Blue" selected>Blue</option>',
            Helper::renderOptions(['Red', 'Blue'], 'Blue')
        );
    }

    public function testRenderFieldEscapesTextAndPreservesExplicitDatalistListAttribute(): void
    {
        $field = new Text();
        $field->setLabel('Search');
        $field->setHtmlId('search');
        $field->setAttribute('list', 'suggestions');

        self::assertSame(
            '<label for="search">Search</label><input list="suggestions" id="search" type="text" name="text" value="&lt;script&gt;" autocomplete="off" />',
            Helper::renderField($field, ['text' => '<script>'])
        );
    }

    public function testRenderFieldRendersTextareaAndSelectedOptions(): void
    {
        $textarea = new Textarea();
        $textarea->setHtmlId('bio');
        $select = new Select();
        $select->setList(['mw' => 'Malawi', 'zm' => 'Zambia']);

        self::assertStringContainsString('>&lt;hello&gt;</textarea>', Helper::renderField($textarea, ['textarea' => '<hello>']));
        self::assertStringContainsString('<option value="zm" selected>Zambia</option>', Helper::renderField($select, ['select' => 'zm']));
    }

    public function testFileInputDoesNotRenderSubmittedValue(): void
    {
        $field = new File();

        $markup = Helper::renderField($field, ['file' => '/tmp/private.txt']);

        self::assertStringContainsString('type="file"', $markup);
        self::assertStringNotContainsString('/tmp/private.txt', $markup);
    }
}
