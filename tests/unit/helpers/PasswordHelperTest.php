<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class PasswordHelperTest extends TestCase
{
    public function test_hash_password_returns_non_empty_string(): void
    {
        $hash = hash_password('salt123', 'password');

        $this->assertNotEmpty($hash);
        $this->assertIsString($hash);
    }

    public function test_hash_password_is_deterministic(): void
    {
        $hash1 = hash_password('same_salt', 'same_password');
        $hash2 = hash_password('same_salt', 'same_password');

        $this->assertEquals($hash1, $hash2);
    }

    public function test_hash_password_differs_with_different_salt(): void
    {
        $hash1 = hash_password('salt_a', 'password');
        $hash2 = hash_password('salt_b', 'password');

        $this->assertNotEquals($hash1, $hash2);
    }

    public function test_hash_password_differs_with_different_password(): void
    {
        $hash1 = hash_password('salt', 'password1');
        $hash2 = hash_password('salt', 'password2');

        $this->assertNotEquals($hash1, $hash2);
    }

    public function test_generate_salt_returns_non_empty_string(): void
    {
        $salt = generate_salt();

        $this->assertNotEmpty($salt);
        $this->assertIsString($salt);
    }

    public function test_generate_salt_is_unique(): void
    {
        $salt1 = generate_salt();
        $salt2 = generate_salt();

        $this->assertNotEquals($salt1, $salt2);
    }

    public function test_generate_salt_has_minimum_length(): void
    {
        $salt = generate_salt();

        $this->assertGreaterThanOrEqual(16, strlen($salt));
    }
}
