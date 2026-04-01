<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

class FieldEncryptionTest extends TestCase
{
    public function test_encrypted_fields_registry_contains_users(): void
    {
        $fields = \Field_encryption::get_encrypted_fields('users');

        $this->assertContains('id_number', $fields);
    }

    public function test_encrypted_fields_registry_contains_documentation_entries(): void
    {
        $fields = \Field_encryption::get_encrypted_fields('documentation_entries');

        $this->assertContains('session_summary', $fields);
    }

    public function test_encrypted_fields_returns_empty_for_unknown_table(): void
    {
        $fields = \Field_encryption::get_encrypted_fields('nonexistent_table');

        $this->assertEmpty($fields);
    }

    public function test_encrypt_record_does_nothing_for_unknown_table(): void
    {
        $record = ['some_field' => 'value'];
        \Field_encryption::encrypt_record('nonexistent_table', $record);

        $this->assertSame('value', $record['some_field']);
    }

    public function test_decrypt_record_does_nothing_for_unknown_table(): void
    {
        $record = ['some_field' => 'enc:something'];
        \Field_encryption::decrypt_record('nonexistent_table', $record);

        $this->assertSame('enc:something', $record['some_field']);
    }

    public function test_decrypt_records_batch(): void
    {
        $original1 = 'value-1';
        $original2 = 'value-2';

        $records = [
            ['id_number' => field_encrypt($original1)],
            ['id_number' => field_encrypt($original2)],
        ];

        \Field_encryption::decrypt_records('users', $records);

        $this->assertSame($original1, $records[0]['id_number']);
        $this->assertSame($original2, $records[1]['id_number']);
    }

    public function test_hash_for_lookup_is_deterministic(): void
    {
        $h1 = \Field_encryption::hash_for_lookup('test-value');
        $h2 = \Field_encryption::hash_for_lookup('test-value');

        $this->assertSame($h1, $h2);
    }

    public function test_hash_for_lookup_differs_for_different_values(): void
    {
        $h1 = \Field_encryption::hash_for_lookup('value-a');
        $h2 = \Field_encryption::hash_for_lookup('value-b');

        $this->assertNotSame($h1, $h2);
    }

    public function test_encrypt_then_decrypt_roundtrip_users(): void
    {
        $record = ['id_number' => '123456789', 'first_name' => 'Test'];
        \Field_encryption::encrypt_record('users', $record);

        $this->assertStringStartsWith('enc:', $record['id_number']);
        $this->assertSame('Test', $record['first_name']);

        \Field_encryption::decrypt_record('users', $record);

        $this->assertSame('123456789', $record['id_number']);
        $this->assertSame('Test', $record['first_name']);
    }

    public function test_encrypt_then_decrypt_roundtrip_documentation(): void
    {
        $record = ['session_summary' => '<p>Notes</p>', 'id' => 1];
        \Field_encryption::encrypt_record('documentation_entries', $record);

        $this->assertStringStartsWith('enc:', $record['session_summary']);
        $this->assertSame(1, $record['id']);

        \Field_encryption::decrypt_record('documentation_entries', $record);

        $this->assertSame('<p>Notes</p>', $record['session_summary']);
    }

    public function test_internal_fields_stripped_on_decrypt(): void
    {
        $record = ['id_number' => 'enc:test', 'id_number_hash' => 'somehash'];
        \Field_encryption::decrypt_record('users', $record);

        $this->assertArrayNotHasKey('id_number_hash', $record);
    }

    public function test_hashed_fields_registry(): void
    {
        $hashed = \Field_encryption::get_hashed_fields('users');

        $this->assertContains('id_number', $hashed);
    }

    public function test_documentation_entries_has_no_hashed_fields(): void
    {
        $hashed = \Field_encryption::get_hashed_fields('documentation_entries');

        $this->assertEmpty($hashed);
    }

    public function test_only_registered_fields_are_encrypted(): void
    {
        $record = [
            'id_number' => '123',
            'first_name' => 'John',
            'email' => 'john@test.com',
        ];

        \Field_encryption::encrypt_record('users', $record);

        $this->assertStringStartsWith('enc:', $record['id_number']);
        $this->assertSame('John', $record['first_name']);
        $this->assertSame('john@test.com', $record['email']);
    }
}
