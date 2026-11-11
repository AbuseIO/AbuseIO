<?php

namespace Rules;

use AbuseIO\Rules\AbuseType;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

/**
 * @note The abuse types are INFO, ABUSE and ESCALATION.
 */
class AbuseTypeTest extends TestCase
{

    #[group('unit')]
    public function testAbuseTypeShouldPassWithValidInfoAbuseType(): void
    {
        $attribute = [
            'value' => 'INFO',
        ];

        $validator = Validator::make($attribute, ['value' => new AbuseType()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testAbuseTypeShouldPassWithValidAbuseAbuseType(): void
    {
        $attribute = [
            'value' => 'ABUSE',
        ];

        $validator = Validator::make($attribute, ['value' => new AbuseType()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testAbuseTypeShouldPassWithValidEscalationAbuseType(): void
    {
        $attribute = [
            'value' => 'ESCALATION',
        ];

        $validator = Validator::make($attribute, ['value' => new AbuseType()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testAbuseTypeShouldFailWithInvalidAbuseType(): void
    {
        $attribute = [
            'value' => 'Test Type',
        ];

        $validator = Validator::make($attribute, ['value' => new AbuseType()]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testAbuseTypeShouldFailWithNullValue(): void
    {
        $attribute = [
            'value' => null,
        ];

        $validator = Validator::make($attribute, ['value' => new AbuseType()]);

        $this->assertTrue($validator->fails());
    }
}
