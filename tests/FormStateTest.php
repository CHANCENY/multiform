<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\form\FormState;

final class FormStateTest extends TestCase
{
    public function testTracksValuesAndSubmittedStatus(): void
    {
        $state = new FormState(['name' => 'Ada']);

        self::assertTrue($state->hasValue('name'));
        self::assertSame('Ada', $state->getValue('name'));
        self::assertSame('fallback', $state->getValue('missing', 'fallback'));
        self::assertFalse($state->isSubmitted());

        $state->setSubmittedValues(['email' => 'ada@example.com']);

        self::assertTrue($state->isSubmitted());
        self::assertSame(['email' => 'ada@example.com'], $state->getValues());
    }

    public function testTracksAndClearsValidationErrors(): void
    {
        $state = new FormState();
        $state->addError('email', 'Invalid email.');
        $state->setErrors('name', ['Required.', 'Must be at least two characters.']);

        self::assertTrue($state->hasErrors());
        self::assertTrue($state->hasErrors('email'));
        self::assertFalse($state->isValid());
        self::assertSame(['Invalid email.'], $state->getErrors('email'));

        $state->clearErrors('email');
        self::assertFalse($state->hasErrors('email'));
        $state->setErrors('name', []);
        self::assertTrue($state->isValid());
    }

    public function testApplicationStorageAndClear(): void
    {
        $state = new FormState(['name' => 'Ada']);
        $state->set('csrf', 'token')->setSubmitted(true);

        self::assertTrue($state->has('csrf'));
        self::assertSame('token', $state->get('csrf'));
        $state->remove('csrf');
        self::assertFalse($state->has('csrf'));

        $state->addError('name', 'Invalid.');
        $state->clear();

        self::assertSame([], $state->getValues());
        self::assertSame([], $state->getAllErrors());
        self::assertSame([], $state->getStorage());
        self::assertFalse($state->isSubmitted());
    }

    public function testRejectsEmptyKeysAndErrorMessages(): void
    {
        $state = new FormState();
        try {
            $state->setValue('', 'value');
            self::fail('Expected an empty field name to be rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Field name cannot be empty.', $exception->getMessage());
        }

        $this->expectException(InvalidArgumentException::class);
        $state->addError('email', '');
    }
}
