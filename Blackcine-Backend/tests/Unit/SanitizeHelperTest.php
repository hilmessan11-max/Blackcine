<?php

namespace Tests\Unit;

use App\Helpers\SanitizeHelper;
use PHPUnit\Framework\TestCase;

class SanitizeHelperTest extends TestCase
{
    public function test_escape_like_escapes_percent_underscore_and_backslash(): void
    {
        $this->assertSame(
            '100\\% Cacao\\_x\\\\y',
            SanitizeHelper::escapeLike('100% Cacao_x\\y')
        );
    }

    public function test_escape_like_leaves_plain_text_untouched(): void
    {
        $this->assertSame('Film Test', SanitizeHelper::escapeLike('Film Test'));
    }
}
