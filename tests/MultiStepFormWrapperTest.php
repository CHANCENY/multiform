<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\field\Text;
use Simp\Multiform\lib\wrapper\FormStateSessionStore;
use Simp\Multiform\lib\wrapper\MultiStepFormStyles;
use Simp\Multiform\lib\wrapper\MultiStepFormWrapper;

final class MultiStepFormWrapperTest extends TestCase
{
    private $store;

    protected function setUp(): void
    {
        $this->store = new class implements FormStateSessionStore {
            public $values = [];

            public function load(string $key): array
            {
                return $this->values[$key] ?? [];
            }

            public function save(string $key, array $values): void
            {
                $this->values[$key] = $values;
            }

            public function clear(string $key): void
            {
                unset($this->values[$key]);
            }
        };
    }

    public function testNavigationPersistsValuesUntilCompletion(): void
    {
        $name = new Text();
        $name->setLabel('Name');
        $email = new Text();
        $email->setLabel('Email');
        $wizard = new MultiStepFormWrapper($this->store);
        $wizard->addStep('About you', [$name]);
        $wizard->addStep('Contact', [$email]);

        self::assertSame(1, $wizard->processSubmission('signup', [
            '_multistep_step' => '0',
            '_multistep_action' => 'next',
            'text' => 'Ada',
        ]));
        self::assertSame(['text' => 'Ada'], $wizard->getValues('signup'));
        self::assertSame(1, $wizard->getCurrentStep('signup'));
        self::assertStringContainsString('value="Ada"', $wizard->render('signup', 1));

        $wizard->processSubmission('signup', [
            '_multistep_step' => '1',
            '_multistep_action' => 'finish',
            'text' => 'ada@example.com',
        ]);
        self::assertTrue($wizard->isComplete('signup'));
        self::assertSame(['text' => 'ada@example.com'], $wizard->getValues('signup'));

        $wizard->clear('signup');
        self::assertFalse($wizard->isComplete('signup'));
        self::assertSame([], $wizard->getValues('signup'));
    }

    public function testBackNavigationAndReturnToPreviouslyVisitedStep(): void
    {
        $wizard = new MultiStepFormWrapper($this->store);
        $wizard->addStep('First', [new Text()]);
        $wizard->addStep('Second', [new Text()]);
        $wizard->addStep('Third', [new Text()]);

        $wizard->processSubmission('flow', ['_multistep_step' => 0, '_multistep_action' => 'next']);
        $wizard->processSubmission('flow', ['_multistep_step' => 1, '_multistep_action' => 'next']);
        self::assertSame(2, $wizard->getCurrentStep('flow'));

        self::assertSame(0, $wizard->processSubmission('flow', [
            '_multistep_step' => 2,
            '_multistep_action' => 'goto:0',
        ]));
        self::assertSame(0, $wizard->processSubmission('flow', [
            '_multistep_step' => 0,
            '_multistep_action' => 'back',
        ]));
    }

    public function testRendersPresetMarkupAndCustomFieldRenderer(): void
    {
        $field = new Text();
        $field->setLabel('Name');
        $styles = new MultiStepFormStyles('numbered-stepper');
        $wizard = new MultiStepFormWrapper($this->store, $styles);
        $wizard->setAttribute('id', 'wizard');
        $wizard->addStep('About', [$field]);
        $wizard->addStep('Review', []);

        $markup = $wizard->render('flow');
        self::assertStringContainsString('class="msf msf--stepper"', $markup);
        self::assertStringContainsString('msf-stepper__item', $markup);
        self::assertStringContainsString('<style>', $markup);

        $customMarkup = $wizard->render('flow', 0, static fn (): string => '<p>Custom field</p>');
        self::assertStringContainsString('<p>Custom field</p>', $customMarkup);
    }

    public function testRejectsInvalidSubmissionAndMissingSteps(): void
    {
        $wizard = new MultiStepFormWrapper($this->store);
        $wizard->addStep('First', [new Text()]);

        $this->expectException(InvalidArgumentException::class);
        $wizard->processSubmission('flow', [
            '_multistep_step' => 0,
            '_multistep_action' => 'goto:1',
        ]);
    }

    public function testRejectsInvalidMethodAndParameterNames(): void
    {
        $wizard = new MultiStepFormWrapper($this->store);
        try {
            $wizard->setMethod('patch');
            self::fail('Expected unsupported methods to be rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('support get or post', $exception->getMessage());
        }

        $this->expectException(InvalidArgumentException::class);
        $wizard->setParameterNames('same', 'same');
    }
}
