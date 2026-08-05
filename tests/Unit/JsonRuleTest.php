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
            $this->jsonRule->validate($context),
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
            'context value - not empty array' => [
                'context' => Context::create('[1, 2, 3, "foo", true, false, 0, -1, 0.1, -0.1, null, ""]'),
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
            'context value - not object string wrapped with double quotes' => [
                'context' => Context::create("\"Hello\""),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - not empty object' => [
                'context' => Context::create(
                    '
                    {
                      "system_config": {
                        "version": "4.2.0",
                        "environment": "production",
                        "maintenance_mode": false,
                        "cluster_settings": {
                          "nodes_active": 4,
                          "nodes_max": 10,
                          "timeout_ms": 5000
                        }
                      },
                      "company_data": {
                        "company_name": "TechCorp \\n Solutions",
                        "headquarters": {
                          "city": "Барселона",
                          "country": "Испания",
                          "coordinates": {
                            "latitude": 41.3851,
                            "longitude": 2.1734
                          }
                        },
                        "departments": [
                          {
                            "id": "dep_eng",
                            "name": "Engineering",
                            "manager_id": 1024,
                            "budget": 1250000.50,
                            "active_projects": ["Project_Alpha", "Project_Beta"]
                          },
                          {
                            "id": "dep_mkt",
                            "name": "Marketing",
                            "manager_id": 2048,
                            "budget": 850000.00,
                            "active_projects": ["Campaign_Summer"]
                          }
                        ]
                      },
                      "employee_directory": [
                        {
                          "employee_id": 1024,
                          "first_name": "Алексей",
                          "last_name": "Смирнов",
                          "role": "Lead Architect",
                          "contact": {
                            "email": "a.smirnov@techcorp.com",
                            "phone": "+34 600 000 000"
                          },
                          "skills": ["Java", "Kubernetes", "System Design"],
                          "is_remote": true,
                          "termination_date": null
                        },
                        {
                          "employee_id": 1025,
                          "first_name": "Мария",
                          "last_name": "Гонсалес",
                          "role": "DevOps Engineer",
                          "contact": {
                            "email": "m.gonzalez@techcorp.com",
                            "phone": "+34 600 111 111"
                          },
                          "skills": ["AWS", "Docker", "Python"],
                          "is_remote": false,
                          "termination_date": null
                        }
                      ],
                      "permissions_matrix": {
                        "admin": ["read", "write", "execute", "delete"],
                        "editor": ["read", "write"],
                        "viewer": ["read"]
                      }
                    }
                    '
                ),
                'expectedResult' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'context value - invalid object with single quotes' => [
                'context' => Context::create("{'key': 'value'}"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid object without quotes on keys' => [
                'context' => Context::create("{key: \"value\"}"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid object with trailing comma' => [
                'context' => Context::create("{\"a\": 1, \"b\": 2,}"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid array with trailing comma' => [
                'context' => Context::create("[1, 2, 3,]"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid true string register' => [
                'context' => Context::create("TRUE"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid false string register' => [
                'context' => Context::create("FALSE"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid null string register' => [
                'context' => Context::create("NULL"),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid object with skipped string value quotes' => [
                'context' => Context::create('{\"a\": 1, \"b\": success"}'),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - invalid object with special character without shielding' => [
                'context' => Context::create('{\"a\": 1, \"b\": "\"Текст с \n переносом\""}'),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
                ],
            ],
            'context value - empty string' => [
                'context' => Context::create(''),
                'expectedResult' => [
                    'isValid' => false,
                    'failedRuleCode' => 'json_rule',
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
