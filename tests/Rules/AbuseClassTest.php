<?php

namespace Rules;

use AbuseIO\Rules\AbuseClass;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

/**
 * @note The abuse classes are defined in the bootstrap/cache/config.php file
 */
class AbuseClassTest extends TestCase
{

    #[group('unit')]
    public function testAbuseClassRuleShouldPassWithValidAbuseClass(): void
    {
        $attribute = [
            'attribute' => 'MALWARE_INFECTION',
        ];

        $validator = Validator::make($attribute, ['attribute' => new AbuseClass()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testAbuseClassRuleShouldFailWithInvalidAbuseClass(): void
    {
        $attribute = [
            'attribute' => 'TEST_CLASS',
        ];

        $validator = Validator::make($attribute, ['attribute' => new AbuseClass()]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testAbuseClassRuleShouldFailWithNullAttribute(): void
    {
        $attribute = [
            'attribute' => null,
        ];

        $validator = Validator::make($attribute, ['attribute' => new AbuseClass()]);

        $this->assertTrue($validator->fails());
    }
}
