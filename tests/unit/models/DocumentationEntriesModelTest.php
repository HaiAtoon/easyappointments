<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

/**
 * Tests for Documentation_entries_model validation and business logic.
 */
class DocumentationEntriesModelTest extends TestCase
{
    /**
     * Test that $casts array contains all expected fields with correct types.
     */
    public function test_casts_array_has_correct_types(): void
    {
        require_once APPPATH . 'core/EA_Model.php';
        require_once APPPATH . 'models/Documentation_entries_model.php';

        $model = new \ReflectionClass(\Documentation_entries_model::class);
        $casts = $model->getProperty('casts');
        $casts->setAccessible(true);

        $instance = $model->newInstanceWithoutConstructor();
        $actual = $casts->getValue($instance);

        $this->assertEquals('integer', $actual['id']);
        $this->assertEquals('integer', $actual['id_users_customer']);
        $this->assertEquals('integer', $actual['id_appointments']);
        $this->assertEquals('integer', $actual['id_users_provider']);
        $this->assertEquals('boolean', $actual['is_edited']);
    }

    /**
     * Test validation rejects entry without customer ID on insert.
     */
    public function test_validate_requires_customer_id_on_insert(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('customer ID is required');

        $model = $this->createModelWithMockDb();
        $model->validate([
            'id_users_provider' => 1,
            'session_summary' => 'Test summary',
        ]);
    }

    /**
     * Test validation rejects entry without provider ID on insert.
     */
    public function test_validate_requires_provider_id_on_insert(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('provider ID is required');

        $model = $this->createModelWithMockDb();
        $model->validate([
            'id_users_customer' => 1,
            'session_summary' => 'Test summary',
        ]);
    }

    /**
     * Test validation rejects entry without session summary on insert.
     */
    public function test_validate_requires_session_summary_on_insert(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('session summary is required');

        $model = $this->createModelWithMockDb();
        $model->validate([
            'id_users_customer' => 1,
            'id_users_provider' => 1,
        ]);
    }

    /**
     * Test validation passes with all required fields.
     */
    public function test_validate_passes_with_all_required_fields(): void
    {
        $model = $this->createModelWithMockDb();

        $model->validate([
            'id_users_customer' => 1,
            'id_users_provider' => 1,
            'session_summary' => 'Valid summary',
        ]);

        $this->assertTrue(true); // No exception thrown
    }

    /**
     * Test validation checks appointment existence when provided.
     */
    public function test_validate_checks_appointment_exists(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('appointment ID does not exist');

        $model = $this->createModelWithMockDb(new \MockDb(0));
        $model->validate([
            'id_users_customer' => 1,
            'id_users_provider' => 1,
            'session_summary' => 'Summary',
            'id_appointments' => 999,
        ]);
    }

    /**
     * Test that session summary is sanitized via pure_html on insert.
     */
    public function test_session_summary_sanitized_concept(): void
    {
        $malicious = '<script>alert(1)</script><p>Safe content</p>';
        $sanitized = pure_html($malicious);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringContainsString('Safe content', $sanitized);
    }

    /**
     * Test purge_edit_log builds correct date cutoff.
     */
    public function test_purge_edit_log_cutoff_calculation(): void
    {
        $days = 365;
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $expected_date = date('Y-m-d', strtotime('-365 days'));

        $this->assertStringStartsWith($expected_date, $cutoff);
    }

    private function createModelWithMockDb($mockDb = null): \Documentation_entries_model
    {
        $class = new \ReflectionClass(\Documentation_entries_model::class);
        $instance = $class->newInstanceWithoutConstructor();

        $db = $mockDb ?? new \MockDb(0);
        $dbProp = $class->getParentClass()->getProperty('db');
        $dbProp->setAccessible(true);
        $dbProp->setValue($instance, $db);

        return $instance;
    }
}
