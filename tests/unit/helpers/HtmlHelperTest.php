<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class HtmlHelperTest extends TestCase
{
    public function test_e_escapes_html_entities(): void
    {
        $this->assertEquals('&lt;script&gt;', e('<script>'));
    }

    public function test_e_escapes_quotes(): void
    {
        $this->assertEquals('&quot;hello&quot;', e('"hello"'));
    }

    public function test_e_handles_null(): void
    {
        $this->assertEquals('', e(null));
    }

    public function test_e_handles_empty_string(): void
    {
        $this->assertEquals('', e(''));
    }

    public function test_e_preserves_safe_text(): void
    {
        $this->assertEquals('Hello World', e('Hello World'));
    }

    public function test_pure_html_strips_script_tags(): void
    {
        $result = pure_html('<p>Hello</p><script>alert(1)</script>');

        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('Hello', $result);
    }

    public function test_pure_html_preserves_safe_tags(): void
    {
        $result = pure_html('<p>Text with <strong>bold</strong> and <em>italic</em></p>');

        $this->assertStringContainsString('<strong>', $result);
        $this->assertStringContainsString('<em>', $result);
    }

    public function test_pure_html_strips_onclick_attributes(): void
    {
        $result = pure_html('<p onclick="alert(1)">Click</p>');

        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringContainsString('Click', $result);
    }

    public function test_pure_html_strips_iframe(): void
    {
        $result = pure_html('<iframe src="http://evil.com"></iframe><p>Safe</p>');

        $this->assertStringNotContainsString('<iframe', $result);
        $this->assertStringContainsString('Safe', $result);
    }

    public function test_pure_html_handles_empty_input(): void
    {
        $this->assertEquals('', pure_html(''));
    }
}
