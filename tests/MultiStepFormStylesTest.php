<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\wrapper\MultiStepFormStyles;

final class MultiStepFormStylesTest extends TestCase
{
    public function testAllBuiltInPresetsHaveExpectedLayouts(): void
    {
        $expectedLayouts = [
            'plain' => 'basic',
            'bootstrap' => 'basic',
            'tailwind' => 'basic',
            'numbered-stepper' => 'numbered-stepper',
            'one-thing-at-a-time' => 'one-thing-at-a-time',
            'vertical-sidebar' => 'vertical-sidebar',
            'icon-grid-cards' => 'icon-grid-cards',
        ];

        foreach ($expectedLayouts as $preset => $layout) {
            $styles = new MultiStepFormStyles($preset);
            self::assertSame($preset, $styles->getPreset());
            self::assertSame($layout, $styles->getLayout());
            self::assertNotSame('', $styles->get('form'));
            self::assertSame(
                in_array($preset, ['numbered-stepper', 'one-thing-at-a-time', 'vertical-sidebar', 'icon-grid-cards'], true),
                $styles->getCss() !== ''
            );
        }
    }

    public function testStyleClassesCanBeOverriddenAndIconsCycle(): void
    {
        $styles = new MultiStepFormStyles('plain');
        $styles->set('next', 'custom-button');

        self::assertSame('custom-button', $styles->get('next'));
        self::assertSame('✦', $styles->getStepIcon(0));
        self::assertSame($styles->getStepIcon(0), $styles->getStepIcon(8));
    }

    public function testRejectsUnknownPresetAndEmptyStyleSlot(): void
    {
        try {
            new MultiStepFormStyles('unknown');
            self::fail('Expected an unknown preset to be rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('Unknown', $exception->getMessage());
        }

        $this->expectException(InvalidArgumentException::class);
        (new MultiStepFormStyles())->set('', 'class');
    }
}
