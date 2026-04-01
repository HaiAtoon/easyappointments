<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class EncryptionHelperTest extends TestCase
{
    public function test_get_encryption_key_returns_valid_key(): void
    {
        $key = get_encryption_key();

        $this->assertNotEmpty($key);
        $this->assertGreaterThanOrEqual(64, strlen($key));
    }

    public function test_field_encrypt_returns_enc_prefix(): void
    {
        $encrypted = field_encrypt('123456789');

        $this->assertStringStartsWith('enc:', $encrypted);
    }

    public function test_field_encrypt_returns_empty_for_empty_input(): void
    {
        $this->assertNull(field_encrypt(null));
        $this->assertSame('', field_encrypt(''));
    }

    public function test_field_decrypt_reverses_field_encrypt(): void
    {
        $original = '123456789';
        $encrypted = field_encrypt($original);
        $decrypted = field_decrypt($encrypted);

        $this->assertSame($original, $decrypted);
    }

    public function test_field_decrypt_handles_plaintext_passthrough(): void
    {
        $plaintext = 'not encrypted';

        $this->assertSame($plaintext, field_decrypt($plaintext));
    }

    public function test_field_decrypt_handles_null(): void
    {
        $this->assertNull(field_decrypt(null));
    }

    public function test_field_decrypt_handles_empty_string(): void
    {
        $this->assertSame('', field_decrypt(''));
    }

    public function test_field_encrypt_produces_different_ciphertext_each_time(): void
    {
        $value = 'same-input';
        $enc1 = field_encrypt($value);
        $enc2 = field_encrypt($value);

        $this->assertNotSame($enc1, $enc2);
    }

    public function test_field_decrypt_with_truncated_data_returns_original(): void
    {
        $truncated = 'enc:CBmybm5RrlcWz8ZR';

        $this->assertSame($truncated, field_decrypt($truncated));
    }

    public function test_field_hash_returns_consistent_value(): void
    {
        $hash1 = field_hash('123456789');
        $hash2 = field_hash('123456789');

        $this->assertSame($hash1, $hash2);
    }

    public function test_field_hash_returns_different_value_for_different_input(): void
    {
        $hash1 = field_hash('123456789');
        $hash2 = field_hash('987654321');

        $this->assertNotSame($hash1, $hash2);
    }

    public function test_field_hash_returns_null_for_empty_input(): void
    {
        $this->assertNull(field_hash(null));
        $this->assertSame('', field_hash(''));
    }

    public function test_field_hash_returns_64_char_hex_string(): void
    {
        $hash = field_hash('test');

        $this->assertSame(64, strlen($hash));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $hash);
    }

    public function test_encrypted_value_is_not_plaintext(): void
    {
        $original = '123456789';
        $encrypted = field_encrypt($original);

        $this->assertStringNotContainsString($original, $encrypted);
    }

    public function test_roundtrip_with_unicode_content(): void
    {
        $original = '<p>רשומת בדיקה עם תוכן בעברית</p>';
        $encrypted = field_encrypt($original);
        $decrypted = field_decrypt($encrypted);

        $this->assertSame($original, $decrypted);
    }

    public function test_roundtrip_with_long_html_content(): void
    {
        $original = '<div>' . str_repeat('<p>Session notes paragraph. </p>', 100) . '</div>';
        $encrypted = field_encrypt($original);
        $decrypted = field_decrypt($encrypted);

        $this->assertSame($original, $decrypted);
    }
}
