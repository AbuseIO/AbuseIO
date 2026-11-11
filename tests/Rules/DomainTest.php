<?php

namespace Rules;

use AbuseIO\Rules\Domain;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

class DomainTest extends TestCase
{

    #[group('unit')]
    public function testDomainRuleShouldPassWithValidDomain(): void
    {
        $attribute = [
            'domain' => 'example.com',
        ];

        $validator = Validator::make($attribute, ['domain' => new Domain]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testDomainRuleShouldFailWithInvalidDomain(): void
    {
        $attribute = [
            'domain' => 'exampledotcom',
        ];

        $validator = Validator::make($attribute, ['domain' => new Domain]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testDomainRuleShouldFailWithNullValue(): void
    {
        $attribute = [
            'domain' => null,
        ];

        $validator = Validator::make($attribute, ['domain' => new Domain]);

        $this->assertTrue($validator->fails());
    }
}
