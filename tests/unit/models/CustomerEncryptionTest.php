<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Customers_model.php';

class CustomerEncryptionTest extends TestCase
{
    public function test_encrypt_record_sets_enc_prefix_on_id_number(): void
    {
        $customer = ['id_number' => '123456789'];
        \Field_encryption::encrypt_record('users', $customer);

        $this->assertStringStartsWith('enc:', $customer['id_number']);
    }

    public function test_encrypt_record_generates_hash(): void
    {
        $customer = ['id_number' => '123456789'];
        \Field_encryption::encrypt_record('users', $customer);

        $this->assertArrayHasKey('id_number_hash', $customer);
        $this->assertSame(64, strlen($customer['id_number_hash']));
    }

    public function test_encrypt_record_skips_already_encrypted_value(): void
    {
        $encrypted = field_encrypt('123456789');
        $customer = ['id_number' => $encrypted];
        \Field_encryption::encrypt_record('users', $customer);

        $this->assertSame($encrypted, $customer['id_number']);
    }

    public function test_encrypt_record_skips_empty_id_number(): void
    {
        $customer = ['id_number' => ''];
        \Field_encryption::encrypt_record('users', $customer);

        $this->assertSame('', $customer['id_number']);
    }

    public function test_decrypt_record_reverses_encryption(): void
    {
        $original = '123456789';
        $customer = ['id_number' => $original];
        \Field_encryption::encrypt_record('users', $customer);
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertSame($original, $customer['id_number']);
    }

    public function test_decrypt_record_removes_hash_field(): void
    {
        $customer = ['id_number' => '123456789'];
        \Field_encryption::encrypt_record('users', $customer);
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertArrayNotHasKey('id_number_hash', $customer);
    }

    public function test_decrypt_record_strips_hash_even_when_id_number_empty(): void
    {
        $customer = ['id_number' => '', 'id_number_hash' => 'abc123'];
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertArrayNotHasKey('id_number_hash', $customer);
    }

    public function test_decrypt_record_passes_through_plaintext(): void
    {
        $customer = ['id_number' => 'plain-text-value'];
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertSame('plain-text-value', $customer['id_number']);
    }

    public function test_decrypt_record_returns_truncated_value_as_is(): void
    {
        $customer = ['id_number' => 'enc:CBmybm5RrlcWz8ZR'];
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertSame('enc:CBmybm5RrlcWz8ZR', $customer['id_number']);
    }

    public function test_hash_is_deterministic_for_same_input(): void
    {
        $customer1 = ['id_number' => '123456789'];
        $customer2 = ['id_number' => '123456789'];
        \Field_encryption::encrypt_record('users', $customer1);
        \Field_encryption::encrypt_record('users', $customer2);

        $this->assertSame($customer1['id_number_hash'], $customer2['id_number_hash']);
    }

    public function test_encrypted_values_differ_for_same_input(): void
    {
        $customer1 = ['id_number' => '123456789'];
        $customer2 = ['id_number' => '123456789'];
        \Field_encryption::encrypt_record('users', $customer1);
        \Field_encryption::encrypt_record('users', $customer2);

        $this->assertNotSame($customer1['id_number'], $customer2['id_number']);
    }

    public function test_roundtrip_with_unicode_id_number(): void
    {
        $original = 'תעודת-זהות-123';
        $customer = ['id_number' => $original];
        \Field_encryption::encrypt_record('users', $customer);
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertSame($original, $customer['id_number']);
    }
}
