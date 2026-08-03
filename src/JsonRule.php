<?php

namespace Aegisora\Rules;

use Aegisora\RuleContract\Exceptions\InvalidRuleContextException;

class JsonRule
{
    public static function create(): self
    {
        return new self();
    }

    /**
     * @param mixed $value
     * @throws InvalidRuleContextException
     */
    private function validateValue($value): void
    {
        if (!is_string($value)) {
            throw new InvalidRuleContextException();
        }
    }
}
