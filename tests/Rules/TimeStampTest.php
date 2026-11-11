<?php

namespace Rules;

use AbuseIO\Rules\TimeStamp;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

class TimeStampTest extends TestCase
{
    #[group('unit')]
    public function testTimeStampRuleShouldPassValidUnixTimeStamp(): void
    {
        // Sun Oct 08 2023 12:00:00 GMT+0000
        $attribute = [
            'timestamp' => 1696759200,
        ];

        $validator = Validator::make($attribute, ['timestamp' => new TimeStamp()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testTimeStampRuleShouldFailWithInvalidTimeStampString(): void
    {
        $attribute = [
            'timestamp' => '2023-10-08 12:00:00',
        ];

        $validator = Validator::make($attribute, ['timestamp' => new TimeStamp()]);

        $this->assertTrue($validator->fails());
    }
}
