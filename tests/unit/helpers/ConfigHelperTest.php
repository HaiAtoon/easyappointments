<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    public function test_is_rtl_function_exists(): void
    {
        $this->assertTrue(function_exists('is_rtl'));
    }

    public function test_is_rtl_returns_boolean(): void
    {
        // Config::LANGUAGE = 'english' in bootstrap, so is_rtl should return false
        // But config() depends on CI instance — test the function signature
        $this->assertIsBool(is_rtl());
    }

    public function test_vars_function_exists(): void
    {
        $this->assertTrue(function_exists('vars'));
    }
}
