<?php

namespace Aegisora\Rules\Tests\Unit;

use Aegisora\RuleContract\RuleInterface;
use Aegisora\Rules\JsonRule;
use PHPUnit\Framework\TestCase;

class JsonRuleTest extends TestCase
{
    public function testCreate(): void
    {
        self::assertInstanceOf(RuleInterface::class, JsonRule::create());
    }
}
