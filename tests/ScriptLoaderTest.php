<?php

use PHPUnit\Framework\TestCase;
use Simp\Multiform\lib\wrapper\ScriptLoader;

final class ScriptLoaderTest extends TestCase
{
    public function testRendersScriptWithDefaultAndCustomParameterNames(): void
    {
        $script = ScriptLoader::renderMultiStepFormScript();
        self::assertStringContainsString('"_multistep_step"', $script);
        self::assertStringContainsString('"_multistep_action"', $script);
        self::assertStringContainsString('button.value === "back"', $script);

        $customScript = ScriptLoader::loadMultiStepForm('step<name', 'action"name');
        self::assertStringContainsString('step\\u003Cname', $customScript);
        self::assertStringContainsString('action\\u0022name', $customScript);
    }

    public function testRejectsEmptyOrDuplicateParameterNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ScriptLoader::renderMultiStepFormScript('step', 'step');
    }
}
