<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class ValidationHelperTest extends TestCase
{
    public function test_validate_datetime_accepts_valid_format(): void
    {
        $this->assertTrue(validate_datetime('2026-03-15 14:30:00'));
    }

    public function test_validate_datetime_accepts_midnight(): void
    {
        $this->assertTrue(validate_datetime('2026-01-01 00:00:00'));
    }

    public function test_validate_datetime_rejects_invalid_format(): void
    {
        $this->assertFalse(validate_datetime('15/03/2026'));
    }

    public function test_validate_datetime_rejects_date_only(): void
    {
        $this->assertFalse(validate_datetime('2026-03-15'));
    }

    public function test_validate_datetime_rejects_empty_string(): void
    {
        $this->assertFalse(validate_datetime(''));
    }

    public function test_validate_datetime_rejects_wrong_format(): void
    {
        $this->assertFalse(validate_datetime('03-15-2026 14:30:00'));
    }

    public function test_validate_datetime_rejects_garbage(): void
    {
        $this->assertFalse(validate_datetime('not-a-date'));
    }
}
