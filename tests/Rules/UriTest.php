<?php

namespace Rules;

use AbuseIO\Rules\Uri;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

class UriTest extends TestCase
{
    #[group('unit')]
    public function testUriValidationPassesForValidUri(): void
    {
        $attribute = [
            'uri' => 'http://valid-uri.com/',
        ];

        $validator = Validator::make($attribute, ['uri' => new Uri()]);

        $this->assertFalse($validator->fails());
    }

    #[group('unit')]
    public function testUriValidationFailsForInvalidUri(): void
    {
        $attribute = [
            'uri' => 'ht!tp://invalid-uri.com/',
        ];

        $validator = Validator::make($attribute, ['uri' => new Uri()]);

        $this->assertTrue($validator->fails());
    }
}
