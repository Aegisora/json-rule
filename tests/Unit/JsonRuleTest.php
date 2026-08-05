<?php

namespace Aegisora\Rules\Tests\Unit;

use Aegisora\RuleContract\Models\Context;
use Aegisora\RuleContract\Models\Result;
use Aegisora\RuleContract\RuleInterface;
use Aegisora\Rules\JsonRule;
use PHPUnit\Framework\TestCase;

class JsonRuleTest extends TestCase
{
    private JsonRule $jsonRule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonRule = new JsonRule();
    }

    public function testCreate(): void
    {
        self::assertInstanceOf(RuleInterface::class, JsonRule::create());
    }

    /**
     * @dataProvider getValidateProvidedData
     */
    public function testValidate(
        Context $context,
        array $expectedResult
    ): void {
        self::assertActualResultEqualsExpected(
            JsonRule::create()->validate($context),
            $expectedResult
        );
    }

    public static function getValidateProvidedData(): array
    {
        return [
            'context value - empty object' => [
                'context' => Context::create('{}'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - empty array' => [
                'context' => Context::create('[]'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - null string' => [
                'context' => Context::create('null'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - false string' => [
                'context' => Context::create('false'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - true string' => [
                'context' => Context::create('true'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - float string' => [
                'context' => Context::create('-5.67'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - integer string' => [
                'context' => Context::create('100'),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
        ];
    }

    private static function assertActualResultEqualsExpected(
        Result $result,
        array $expectedResult
    ): void {
        self::assertEquals($expectedResult['isValid'], $result->isValid());
        self::assertEquals($expectedResult['failedRuleCode'], $result->getFailedRuleCode());
    }
}
