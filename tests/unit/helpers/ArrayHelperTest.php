<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function test_is_assoc_returns_true_for_associative_array(): void
    {
        $this->assertTrue(is_assoc(['key' => 'value', 'foo' => 'bar']));
    }

    public function test_is_assoc_returns_false_for_indexed_array(): void
    {
        $this->assertFalse(is_assoc(['a', 'b', 'c']));
    }

    public function test_is_assoc_returns_false_for_empty_array(): void
    {
        $this->assertFalse(is_assoc([]));
    }

    public function test_is_assoc_returns_true_for_mixed_keys(): void
    {
        $this->assertTrue(is_assoc([0 => 'a', 'key' => 'b']));
    }

    public function test_array_find_returns_first_match(): void
    {
        $result = array_find([1, 2, 3, 4], fn($v) => $v > 2);

        $this->assertEquals(3, $result);
    }

    public function test_array_find_returns_null_when_no_match(): void
    {
        $result = array_find([1, 2, 3], fn($v) => $v > 10);

        $this->assertNull($result);
    }

    public function test_array_fields_filters_to_specified_keys(): void
    {
        $input = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
        $result = array_fields($input, ['a', 'c']);

        $this->assertEquals(['a' => 1, 'c' => 3], $result);
    }

    public function test_array_fields_ignores_missing_keys(): void
    {
        $input = ['a' => 1, 'b' => 2];
        $result = array_fields($input, ['a', 'x', 'y']);

        $this->assertEquals(['a' => 1], $result);
    }

    public function test_array_fields_returns_empty_for_no_matches(): void
    {
        $input = ['a' => 1];
        $result = array_fields($input, ['x', 'y']);

        $this->assertEquals([], $result);
    }
}
