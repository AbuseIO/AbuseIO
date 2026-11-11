<?php

namespace Rules;

use AbuseIO\Rules\Email;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

class EmailTest extends TestCase
{
    #[group('unit')]
    public function testEmailRuleShouldPassWithValidEmail(): void
    {
        $attribute = [
          'email' => 'test@example.com',
        ];

        $validator = Validator::make($attribute, ['email' => new Email()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testEmailRuleShouldFailWithInvalidEmail(): void
    {
        $attribute = [
          'email' => 'test-example.com',
        ];

        $validator = Validator::make($attribute, ['email' => new Email()]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testEmailRuleShouldFailWithInvalidNullValue(): void
    {
        $attribute = [
          'email' => null,
        ];

        $validator = Validator::make($attribute, ['email' => new Email()]);

        $this->assertTrue($validator->fails());
    }
}
