<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'libraries/Timezones.php';

class TimezonesTest extends TestCase
{
    private \Timezones $timezones;

    protected function setUp(): void
    {
        $class = new \ReflectionClass(\Timezones::class);
        $this->timezones = $class->newInstanceWithoutConstructor();
    }

    public function test_to_array_returns_non_empty_array(): void
    {
        $result = $this->timezones->to_array();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    public function test_to_array_contains_utc(): void
    {
        $result = $this->timezones->to_array();

        $this->assertArrayHasKey('UTC', $result);
    }

    public function test_to_array_contains_common_timezones(): void
    {
        $result = $this->timezones->to_array();

        $this->assertArrayHasKey('America/New_York', $result);
        $this->assertArrayHasKey('Europe/London', $result);
        $this->assertArrayHasKey('Asia/Jerusalem', $result);
    }

    public function test_to_grouped_array_returns_groups(): void
    {
        $result = $this->timezones->to_grouped_array();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('UTC', $result);
        $this->assertArrayHasKey('America', $result);
        $this->assertArrayHasKey('Europe', $result);
        $this->assertArrayHasKey('Asia', $result);
    }

    public function test_get_timezone_name_returns_name_for_valid_timezone(): void
    {
        $result = $this->timezones->get_timezone_name('UTC');

        $this->assertNotNull($result);
        $this->assertEquals('UTC', $result);
    }

    public function test_get_timezone_name_returns_null_for_invalid(): void
    {
        $result = $this->timezones->get_timezone_name('Invalid/Timezone');

        $this->assertNull($result);
    }

    public function test_convert_returns_same_value_for_same_timezone(): void
    {
        $result = $this->timezones->convert('2026-03-15 14:30:00', 'UTC', 'UTC');

        $this->assertEquals('2026-03-15 14:30:00', $result);
    }

    public function test_convert_returns_same_value_for_empty_target(): void
    {
        $result = $this->timezones->convert('2026-03-15 14:30:00', 'UTC', '');

        $this->assertEquals('2026-03-15 14:30:00', $result);
    }

    public function test_convert_changes_time_between_timezones(): void
    {
        $result = $this->timezones->convert('2026-03-15 12:00:00', 'UTC', 'Asia/Jerusalem');

        $this->assertNotEquals('2026-03-15 12:00:00', $result);
        $this->assertStringContainsString('2026-03-15', $result);
    }

    public function test_convert_returns_valid_datetime_format(): void
    {
        $result = $this->timezones->convert('2026-06-15 10:00:00', 'UTC', 'America/New_York');

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result);
    }

    public function test_get_default_timezone_returns_string(): void
    {
        $result = $this->timezones->get_default_timezone();

        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
}
