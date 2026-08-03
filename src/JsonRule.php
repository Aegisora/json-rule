<?php

namespace Aegisora\Rules;

use Aegisora\RuleContract\Exceptions\InvalidRuleContextException;
use Aegisora\RuleContract\Models\Context;
use Aegisora\RuleContract\Models\Result;
use Aegisora\RuleContract\Rule;

class JsonRule extends Rule
{
    public static function create(): self
    {
        return new self();
    }

    protected function executeValidate(Context $context): Result
    {
        json_decode($this->getContextValue($context));

        return (json_last_error() === JSON_ERROR_NONE) ?
            $this->getDefaultValidResult() :
            $this->getDefaultInvalidResult();
    }

    /**
     * @throws InvalidRuleContextException
     */
    private function getContextValue(Context $context): string
    {
        $value = $context->getValue();

        if (!is_string($value)) {
            throw new InvalidRuleContextException();
        }

        return $value;
    }
}
