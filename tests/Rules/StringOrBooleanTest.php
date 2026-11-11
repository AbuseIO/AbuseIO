<?php

namespace Rules;

use AbuseIO\Rules\StringOrBoolean;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

class StringOrBooleanTest extends TestCase
{

    #[group('unit')]
    public function testStringOrBooleanRuleShouldPassWithString(): void
    {
        $attribute = [
            'value' => 'This is a string',
        ];

        $validator = Validator::make($attribute, ['value' => new StringOrBoolean()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testStringOrBooleanRuleShouldPassWithBooleanTrue(): void
    {
        $attribute = [
            'value' => true,
        ];

        $validator = Validator::make($attribute, ['value' => new StringOrBoolean()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testStringOrBooleanRuleShouldShouldFailWithIntegerGreaterThanOne(): void
    {
        $attribute = [
            'value' => 2,
        ];

        $validator = Validator::make($attribute, ['value' => new StringOrBoolean()]);

        $this->assertTrue($validator->fails());
    }
}
