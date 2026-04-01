<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

class DataProtectionTest extends TestCase
{
    private string $pdfUtilsSource;
    private string $documentationEntriesControllerSource;
    private string $instanceSource;
    private string $documentationEntriesModelSource;
    private string $privacySource;
    private string $customerOtpsModelSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdfUtilsSource = file_get_contents(APPPATH . 'libraries/Pdf_utils.php');
        $this->documentationEntriesControllerSource = file_get_contents(APPPATH . 'controllers/Documentation_entries.php');
        $this->instanceSource = file_get_contents(APPPATH . 'libraries/Instance.php');
        $this->documentationEntriesModelSource = file_get_contents(APPPATH . 'models/Documentation_entries_model.php');
        $this->privacySource = file_get_contents(APPPATH . 'controllers/Privacy.php');
        $this->customerOtpsModelSource = file_get_contents(APPPATH . 'models/Customer_otps_model.php');
    }

    // ---------------------------------------------------------------
    // Finding 5: PDF password security
    // ---------------------------------------------------------------

    public function test_generate_password_returns_16_char_hex_string(): void
    {
        $password = \Pdf_utils::generate_password();

        $this->assertSame(16, strlen($password), 'Password must be exactly 16 characters');
        $this->assertMatchesRegularExpression('/^[0-9a-f]{16}$/', $password, 'Password must be a hex string');
    }

    public function test_generate_password_produces_unique_values(): void
    {
        $passwords = [];

        for ($i = 0; $i < 50; $i++) {
            $passwords[] = \Pdf_utils::generate_password();
        }

        $unique = array_unique($passwords);

        $this->assertCount(50, $unique, 'Each call to generate_password() must return a unique value');
    }

    public function test_send_entry_pdf_uses_generate_password_not_id_number(): void
    {
        $method = $this->extractMethod($this->documentationEntriesControllerSource, 'send_entry_pdf');

        $this->assertStringContainsString(
            'Pdf_utils::generate_password()',
            $method,
            'send_entry_pdf must use Pdf_utils::generate_password() for PDF encryption'
        );

        $this->assertStringNotContainsString(
            'id_number',
            $method,
            'send_entry_pdf must NOT use patient id_number as PDF password'
        );

        $this->assertStringNotContainsString(
            'get_customer_identifier',
            $method,
            'send_entry_pdf must NOT use get_customer_identifier for PDF password'
        );
    }

    public function test_send_document_pdf_uses_generate_password_not_id_number(): void
    {
        $method = $this->extractMethod($this->documentationEntriesControllerSource, 'send_document_pdf');

        $this->assertStringContainsString(
            'Pdf_utils::generate_password()',
            $method,
            'send_document_pdf must use Pdf_utils::generate_password() for PDF encryption'
        );

        $this->assertStringNotContainsString(
            'id_number',
            $method,
            'send_document_pdf must NOT use patient id_number as PDF password'
        );

        $this->assertStringNotContainsString(
            'get_customer_identifier',
            $method,
            'send_document_pdf must NOT use get_customer_identifier for PDF password'
        );
    }

    // ---------------------------------------------------------------
    // Finding 10: Backup encryption
    // ---------------------------------------------------------------

    public function test_backup_uses_aes_256_cbc_encryption(): void
    {
        $method = $this->extractMethod($this->instanceSource, 'backup');

        $this->assertStringContainsString(
            'AES-256-CBC',
            $method,
            'Instance::backup must use AES-256-CBC encryption for database backups'
        );

        $this->assertStringContainsString(
            'openssl_encrypt',
            $method,
            'Instance::backup must call openssl_encrypt'
        );
    }

    public function test_backup_checks_encryption_key_before_encrypting(): void
    {
        $method = $this->extractMethod($this->instanceSource, 'backup');

        $this->assertStringContainsString(
            'ENCRYPTION_KEY',
            $method,
            'Instance::backup must check for ENCRYPTION_KEY before encrypting'
        );
    }

    // ---------------------------------------------------------------
    // Finding 13: Edit log retention (7-year MOH requirement)
    // ---------------------------------------------------------------

    public function test_purge_edit_log_default_retention_meets_seven_year_requirement(): void
    {
        if (!class_exists('Documentation_entries_model', false)) {
            require_once APPPATH . 'models/Documentation_entries_model.php';
        }

        $reflection = new \ReflectionMethod(\Documentation_entries_model::class, 'purge_edit_log');
        $params = $reflection->getParameters();

        $daysParam = $params[0];
        $default = $daysParam->getDefaultValue();

        $this->assertGreaterThanOrEqual(
            2555,
            $default,
            'purge_edit_log default retention must be >= 2555 days (7 years) per MOH regulation. '
            . "Current default is {$default} days, which violates the 7-year medical record retention requirement."
        );
    }

    // ---------------------------------------------------------------
    // Finding 14: Medical record deletion (MOH 7-year retention)
    // ---------------------------------------------------------------

    public function test_privacy_controller_does_not_hard_delete_customer_data(): void
    {
        $method = $this->extractMethod($this->privacySource, 'delete_personal_information');

        $usesAnonymize = str_contains($method, 'anonymize') || str_contains($method, 'soft_delete');
        $callsHardDelete = str_contains($method, '->delete(');

        if ($callsHardDelete && !$usesAnonymize) {
            $this->fail(
                'Privacy::delete_personal_information performs a hard delete of customer data via '
                . 'customers_model->delete(). Per MOH regulation, medical records must be retained for 7 years. '
                . 'This method should anonymize/soft-delete customer data instead of permanently removing it.'
            );
        }

        $this->assertTrue(
            $usesAnonymize,
            'Privacy::delete_personal_information should use anonymization or soft-delete pattern '
            . 'instead of hard delete, per MOH 7-year medical record retention requirement.'
        );
    }

    public function test_customers_model_has_anonymize_method(): void
    {
        require_once APPPATH . 'models/Customers_model.php';

        $this->assertTrue(
            method_exists(\Customers_model::class, 'anonymize'),
            'Customers_model must have an anonymize() method for GDPR-compliant data removal'
        );
    }

    public function test_anonymize_method_does_not_delete_record(): void
    {
        $source = file_get_contents(APPPATH . 'models/Customers_model.php');

        $pattern = '/function\s+anonymize\s*\([^)]*\)[^{]*\{(.*?)\n    \}/s';

        $this->assertMatchesRegularExpression($pattern, $source);

        preg_match($pattern, $source, $matches);
        $body = $matches[1];

        $this->assertStringNotContainsString(
            '->delete(',
            $body,
            'anonymize() must NOT call delete — it should update the record with anonymized data'
        );

        $this->assertStringContainsString(
            '->update(',
            $body,
            'anonymize() must update the record with anonymized values'
        );
    }

    // ---------------------------------------------------------------
    // Finding 15: OTP configuration
    // ---------------------------------------------------------------

    public function test_otp_generates_six_or_more_digit_code(): void
    {
        $this->assertStringContainsString(
            '999999',
            $this->customerOtpsModelSource,
            'OTP generation must use a range that produces at least 6-digit codes'
        );

        $this->assertStringContainsString(
            "str_pad",
            $this->customerOtpsModelSource,
            'OTP must be zero-padded to ensure consistent length'
        );

        $this->assertMatchesRegularExpression(
            "/str_pad.*6.*STR_PAD_LEFT/s",
            $this->customerOtpsModelSource,
            'OTP must be padded to at least 6 digits'
        );
    }

    public function test_otp_has_expiry_logic(): void
    {
        $this->assertStringContainsString(
            'expires_at',
            $this->customerOtpsModelSource,
            'OTP model must set an expires_at value when generating an OTP'
        );

        $generateMethod = $this->extractMethod($this->customerOtpsModelSource, 'generate');

        $this->assertMatchesRegularExpression(
            '/strtotime.*\+\d+\s*minutes/',
            $generateMethod,
            'OTP generation must set a time-limited expiry (e.g. +N minutes)'
        );

        $verifyMethod = $this->extractMethod($this->customerOtpsModelSource, 'verify');

        $this->assertStringContainsString(
            'expires_at',
            $verifyMethod,
            'OTP verification must check the expires_at field to enforce expiry'
        );
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function extractMethod(string $source, string $methodName): string
    {
        $pattern = '/(?:public|protected|private)\s+function\s+' . preg_quote($methodName, '/') . '\s*\([^)]*\)[^{]*\{/';

        if (!preg_match($pattern, $source, $match, PREG_OFFSET_CAPTURE)) {
            $this->fail("Could not find method {$methodName} in source");
        }

        $start = $match[0][1];
        $braceCount = 0;
        $len = strlen($source);
        $methodStart = strpos($source, '{', $start);

        for ($i = $methodStart; $i < $len; $i++) {
            if ($source[$i] === '{') {
                $braceCount++;
            } elseif ($source[$i] === '}') {
                $braceCount--;
                if ($braceCount === 0) {
                    return substr($source, $start, $i - $start + 1);
                }
            }
        }

        return substr($source, $start);
    }
}
