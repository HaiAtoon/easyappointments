<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Documentation_entries_model.php';

class DocumentationEncryptionTest extends TestCase
{
    public function test_encrypt_record_encrypts_session_summary(): void
    {
        $entry = ['session_summary' => '<p>Patient notes.</p>'];
        \Field_encryption::encrypt_record('documentation_entries', $entry);

        $this->assertStringStartsWith('enc:', $entry['session_summary']);
    }

    public function test_decrypt_record_decrypts_session_summary(): void
    {
        $original = '<p>Patient showed improvement in session.</p>';
        $entry = ['session_summary' => $original];
        \Field_encryption::encrypt_record('documentation_entries', $entry);
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame($original, $entry['session_summary']);
    }

    public function test_decrypt_record_passes_through_plaintext(): void
    {
        $plaintext = '<p>Already plain text</p>';
        $entry = ['session_summary' => $plaintext];
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame($plaintext, $entry['session_summary']);
    }

    public function test_decrypt_record_handles_empty_summary(): void
    {
        $entry = ['session_summary' => ''];
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame('', $entry['session_summary']);
    }

    public function test_decrypt_record_handles_null_summary(): void
    {
        $entry = ['session_summary' => null];
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertNull($entry['session_summary']);
    }

    public function test_decrypt_record_handles_truncated_encrypted_value(): void
    {
        $truncated = 'enc:qleFaPMmRd4mMEF+';
        $entry = ['session_summary' => $truncated];
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame($truncated, $entry['session_summary']);
    }

    public function test_roundtrip_with_hebrew_content(): void
    {
        $original = '<p>רשומת בדיקה עם תוכן ארוך בעברית ומספרים 12345</p>';
        $entry = ['session_summary' => $original];
        \Field_encryption::encrypt_record('documentation_entries', $entry);
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame($original, $entry['session_summary']);
    }

    public function test_roundtrip_with_long_html_content(): void
    {
        $original = '<div>' . str_repeat('<p>Session paragraph with notes. </p>', 50) . '</div>';
        $entry = ['session_summary' => $original];
        \Field_encryption::encrypt_record('documentation_entries', $entry);
        \Field_encryption::decrypt_record('documentation_entries', $entry);

        $this->assertSame($original, $entry['session_summary']);
    }

    public function test_encrypted_summary_not_readable_as_plaintext(): void
    {
        $original = 'Sensitive patient information';
        $entry = ['session_summary' => $original];
        \Field_encryption::encrypt_record('documentation_entries', $entry);

        $this->assertStringNotContainsString($original, $entry['session_summary']);
    }
}
