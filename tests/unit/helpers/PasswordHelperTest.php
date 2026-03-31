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

    public function test_hash_password_produces_argon2_or_bcrypt_hash(): void
    {
        $hash = hash_password('salt', 'password');

        $this->assertTrue(
            str_starts_with($hash, '$argon2') || str_starts_with($hash, '$2y$'),
            'Hash should use Argon2id or bcrypt',
        );
    }

    public function test_verify_password_validates_correct_password(): void
    {
        $hash = hash_password('salt', 'my_password');

        $this->assertTrue(verify_password('my_password', $hash));
    }

    public function test_verify_password_rejects_wrong_password(): void
    {
        $hash = hash_password('salt', 'my_password');

        $this->assertFalse(verify_password('wrong_password', $hash));
    }

    public function test_verify_password_works_with_legacy_hash(): void
    {
        $salt = 'testsalt1234567890abcdef';
        $legacy_hash = hash_password_legacy($salt, 'legacy_pass');

        $this->assertTrue(verify_password('legacy_pass', $legacy_hash, $salt));
        $this->assertFalse(verify_password('wrong', $legacy_hash, $salt));
    }

    public function test_ea_password_needs_rehash_for_legacy(): void
    {
        $legacy_hash = hash('sha256', 'test');

        $this->assertTrue(ea_password_needs_rehash($legacy_hash));
    }

    public function test_ea_password_needs_rehash_detects_modern_hash(): void
    {
        $modern_hash = hash_password('salt', 'password');

        // Modern hashes start with $argon2 or $2y$ — the function recognizes them
        $this->assertTrue(
            str_starts_with($modern_hash, '$argon2') || str_starts_with($modern_hash, '$2y$'),
        );
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

        $this->assertGreaterThanOrEqual(32, strlen($salt));
    }
}
