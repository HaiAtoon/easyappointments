<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

/**
 * Test that all expected constants are defined and have correct values.
 */
class ConstantsTest extends TestCase
{
    public function test_privilege_action_constants(): void
    {
        $this->assertEquals(1, PRIV_VIEW);
        $this->assertEquals(2, PRIV_ADD);
        $this->assertEquals(4, PRIV_EDIT);
        $this->assertEquals(8, PRIV_DELETE);
    }

    public function test_privilege_resource_constants(): void
    {
        $this->assertEquals('appointments', PRIV_APPOINTMENTS);
        $this->assertEquals('customers', PRIV_CUSTOMERS);
        $this->assertEquals('services', PRIV_SERVICES);
        $this->assertEquals('users', PRIV_USERS);
        $this->assertEquals('system_settings', PRIV_SYSTEM_SETTINGS);
        $this->assertEquals('user_settings', PRIV_USER_SETTINGS);
        $this->assertEquals('webhooks', PRIV_WEBHOOKS);
        $this->assertEquals('blocked_periods', PRIV_BLOCKED_PERIODS);
    }

    public function test_role_slug_constants(): void
    {
        $this->assertEquals('admin', DB_SLUG_ADMIN);
        $this->assertEquals('provider', DB_SLUG_PROVIDER);
        $this->assertEquals('secretary', DB_SLUG_SECRETARY);
        $this->assertEquals('customer', DB_SLUG_CUSTOMER);
    }

    public function test_webhook_event_constants(): void
    {
        $this->assertEquals('appointment_save', WEBHOOK_APPOINTMENT_SAVE);
        $this->assertEquals('appointment_delete', WEBHOOK_APPOINTMENT_DELETE);
        $this->assertEquals('customer_save', WEBHOOK_CUSTOMER_SAVE);
        $this->assertEquals('customer_delete', WEBHOOK_CUSTOMER_DELETE);
        $this->assertEquals('provider_save', WEBHOOK_PROVIDER_SAVE);
        $this->assertEquals('provider_delete', WEBHOOK_PROVIDER_DELETE);
    }

    public function test_storage_path_constants(): void
    {
        $this->assertEquals('storage/cache/mpdf/', STORAGE_MPDF_TEMP);
        $this->assertEquals('storage/document-templates/', STORAGE_DOCUMENT_TEMPLATES);
    }

    public function test_password_max_length_constant(): void
    {
        $this->assertTrue(defined('MAX_PASSWORD_LENGTH'));
        $this->assertIsInt(MAX_PASSWORD_LENGTH);
        $this->assertGreaterThan(0, MAX_PASSWORD_LENGTH);
    }
}
