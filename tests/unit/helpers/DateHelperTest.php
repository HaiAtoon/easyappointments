<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    public function test_get_date_format_dmy(): void
    {
        // setting('date_format') returns 'DMY' from bootstrap stub
        $this->assertEquals('d/m/Y', get_date_format());
    }

    public function test_get_time_format_regular(): void
    {
        // setting('time_format') returns 'regular' from bootstrap stub
        $this->assertEquals('g:i a', get_time_format());
    }

    public function test_get_date_time_format_combines_both(): void
    {
        $result = get_date_time_format();

        $this->assertStringContainsString('/', $result);
        $this->assertStringContainsString(':', $result);
    }

    public function test_format_date_with_string_input(): void
    {
        $result = format_date('2026-03-15');

        $this->assertNotEmpty($result);
        $this->assertStringContainsString('15', $result);
    }

    public function test_format_date_with_datetime_input(): void
    {
        $dt = new \DateTime('2026-06-20');
        $result = format_date($dt);

        $this->assertStringContainsString('20', $result);
    }

    public function test_format_time_with_string_input(): void
    {
        $result = format_time('2026-03-15 14:30:00');

        $this->assertNotEmpty($result);
    }

    public function test_format_date_time_with_string(): void
    {
        $result = format_date_time('2026-03-15 14:30:00');

        $this->assertNotEmpty($result);
        $this->assertStringContainsString('15', $result);
    }
}
