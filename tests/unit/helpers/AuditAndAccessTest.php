<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class AuditAndAccessTest extends TestCase
{
    private string $auditHelperSource;
    private string $rateLimitHelperSource;
    private string $documentationEntriesSource;
    private string $accountsSource;
    private string $customerPortalSource;
    private string $customerLoginsModelSource;

    protected function setUp(): void
    {
        $this->auditHelperSource = file_get_contents(APPPATH . 'helpers/audit_helper.php');
        $this->rateLimitHelperSource = file_get_contents(APPPATH . 'helpers/rate_limit_helper.php');
        $this->documentationEntriesSource = file_get_contents(APPPATH . 'controllers/Documentation_entries.php');
        $this->accountsSource = file_get_contents(APPPATH . 'libraries/Accounts.php');
        $this->customerPortalSource = file_get_contents(APPPATH . 'controllers/Customer_portal.php');
        $this->customerLoginsModelSource = file_get_contents(APPPATH . 'models/Customer_logins_model.php');
    }

    // --- Audit logging coverage ---

    public function test_audit_log_function_exists_and_is_callable(): void
    {
        $this->assertStringContainsString('function audit_log(', $this->auditHelperSource);
    }

    public function test_documentation_entries_calls_audit_log_for_view_documentation(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]VIEW_DOCUMENTATION[\'"]/',
            $this->documentationEntriesSource,
        );
    }

    public function test_documentation_entries_calls_audit_log_for_view_entry_pdf(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]VIEW_ENTRY_PDF[\'"]/',
            $this->documentationEntriesSource,
        );
    }

    public function test_documentation_entries_calls_audit_log_for_send_entry_pdf(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]SEND_ENTRY_PDF[\'"]/',
            $this->documentationEntriesSource,
        );
    }

    public function test_documentation_entries_calls_audit_log_for_send_document_pdf(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]SEND_DOCUMENT_PDF[\'"]/',
            $this->documentationEntriesSource,
        );
    }

    public function test_accounts_calls_audit_log_for_login_success(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]LOGIN_SUCCESS[\'"]/',
            $this->accountsSource,
        );
    }

    public function test_accounts_calls_audit_log_for_login_failed(): void
    {
        $this->assertMatchesRegularExpression(
            '/audit_log\s*\(\s*[\'"]LOGIN_FAILED[\'"]/',
            $this->accountsSource,
        );
    }

    public function test_audit_log_includes_patient_id_parameter(): void
    {
        $this->assertMatchesRegularExpression(
            '/function\s+audit_log\s*\([^)]*\$patient_id/',
            $this->auditHelperSource,
        );
    }

    // --- Rate limiting ---

    public function test_rate_limit_auth_function_exists(): void
    {
        $this->assertStringContainsString('function rate_limit_auth(', $this->rateLimitHelperSource);
    }

    public function test_rate_limit_auth_enforces_max_5_attempts(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$attempts\s*>=\s*5/',
            $this->rateLimitHelperSource,
        );
    }

    public function test_rate_limit_auth_has_lockout_after_10_attempts(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$attempts\s*>=\s*10/',
            $this->rateLimitHelperSource,
        );
    }

    public function test_rate_limit_auth_called_in_login_controller(): void
    {
        $loginSource = file_get_contents(APPPATH . 'controllers/Login.php');

        $this->assertStringContainsString(
            'rate_limit_auth',
            $loginSource,
            'Login controller must call rate_limit_auth() before authentication attempts'
        );
    }

    public function test_rate_limit_auth_called_in_otp_flow(): void
    {
        $this->assertStringContainsString(
            'rate_limit_auth',
            $this->customerPortalSource,
            'Customer_portal must call rate_limit_auth() for OTP send/verify endpoints'
        );
    }

    // --- Provider access scoping ---

    public function test_documentation_entries_search_filters_by_provider_id(): void
    {
        $this->assertStringContainsString('DB_SLUG_PROVIDER', $this->documentationEntriesSource);
        $this->assertStringContainsString('id_users_provider', $this->documentationEntriesSource);

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*session\s*\(\s*[\'"]role_slug[\'"]\s*\)\s*===\s*DB_SLUG_PROVIDER\s*\)/',
            $this->documentationEntriesSource,
        );
    }

    public function test_documentation_entries_store_restricts_provider_to_own_entries(): void
    {
        preg_match('/function\s+store\s*\(\).*?^    \}/ms', $this->documentationEntriesSource, $matches);
        $this->assertNotEmpty($matches, 'store() method should exist');

        $storeBody = $matches[0];

        $this->assertStringContainsString('DB_SLUG_ADMIN', $storeBody);
        $this->assertMatchesRegularExpression(
            '/\$entry\s*\[\s*[\'"]id_users_provider[\'"]\s*\]\s*=\s*\$user_id/',
            $storeBody,
        );
    }

    // --- Login attempt logging ---

    public function test_customer_logins_model_exists_and_has_log_method(): void
    {
        $this->assertStringContainsString('class Customer_logins_model', $this->customerLoginsModelSource);
        $this->assertMatchesRegularExpression(
            '/function\s+log\s*\(/',
            $this->customerLoginsModelSource,
        );
    }

    public function test_customer_portal_calls_customer_logins_model_log(): void
    {
        $this->assertMatchesRegularExpression(
            '/customer_logins_model\s*->\s*log\s*\(/',
            $this->customerPortalSource,
        );
    }
}
