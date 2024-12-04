<?php

namespace Tests\Unit;

use App\Services\TokenValidator;
use PHPUnit\Framework\TestCase;

class TokenValidatorTest extends TestCase
{
    protected TokenValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TokenValidator();
    }

    public function testValidTokens()
    {
        $tokens = [
            '{}', '[]', '()',
            '{()}', '{[]}',
            '({})', '([])',
            '[()]', '[{}]',
            '{([])}', '{[()]}',
            '({[]})', '([{}])',
            '[{()}]', '[({})]',
            '()[]{}',
            '()[]{}{()}{([])}[{()}][({})]{()}'
        ];

        foreach ($tokens as $token) {
            $this->assertTrue($this->validator->validate($token));
        }
    }

    public function testInvalidTokens()
    {
        $tokens = [
            '{', '(', '[',
            '}', ')', ']',
            '{)', '{]',
            '[)', '[}',
            '(}', '(]',
            '([)', '(])', '({)', '(})',
            '{(}', '{)}', '{[}', '{]}',
            '[(]', '[)]', '[{]', '[}]',
            '([{])', '([}])', '({[})', '({]})',
            '{([)}', '{(])}', '{[(]}', '{[)]}',
            '[({)]', '[(})]', '[{(}]', '[{)}]',
            '[{]}'
        ];

        foreach ($tokens as $token) {
            $this->assertFalse($this->validator->validate($token));
        }
    }
}
