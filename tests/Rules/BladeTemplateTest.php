<?php

namespace Rules;

use AbuseIO\Rules\BladeTemplate;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

/**
 * @note This test tests if the HTML given or Blade template is valid, with valid Blade syntax:
 * - @if ... @endif
 * - @foreach ... @endforeach
 * - {{ $variable }}
 */
class BladeTemplateTest extends TestCase
{
    #[group('unit')]
    public function testBladeTemplateRuleShouldPassWithValidTemplate(): void
    {
        $attribute = [
            'template' => '<h1>Hi {{ $var }}</h1><p>{{ $content }}</p> @if($condition) <span>Condition met</span> @endif',
        ];

        $validator = Validator::make($attribute, ['template' => new BladeTemplate()]);

        $this->assertTrue($validator->passes());
    }

    #[group('unit')]
    public function testBladeTemplateRuleShouldFailWithInvalidTemplate(): void
    {
        $attribute = [
            'template' => '<h1>Hi {{ $var </h1><p>{{ $content }}</p> @if($condition <span>Condition met</span> endif',
        ];

        $validator = Validator::make($attribute, ['template' => new BladeTemplate()]);

        $this->assertTrue($validator->fails());
    }
}
