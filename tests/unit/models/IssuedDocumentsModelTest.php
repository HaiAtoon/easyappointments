<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'core/EA_Model.php';
require_once APPPATH . 'models/Issued_documents_model.php';

/**
 * Tests for Issued_documents_model validation and business logic.
 */
class IssuedDocumentsModelTest extends TestCase
{
    /**
     * Test $casts array has correct field types.
     */
    public function test_casts_array_has_correct_types(): void
    {
        $class = new \ReflectionClass(\Issued_documents_model::class);
        $casts = $class->getProperty('casts');
        $casts->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();
        $actual = $casts->getValue($instance);

        $this->assertEquals('integer', $actual['id']);
        $this->assertEquals('integer', $actual['id_documentation_entry']);
        $this->assertEquals('integer', $actual['id_users_provider']);
    }

    /**
     * Test validation requires documentation_entry_id.
     */
    public function test_validate_requires_entry_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('documentation entry ID is required');

        $model = $this->createInstance();
        $model->validate([
            'document_type' => 'general_letter',
            'id_users_provider' => 1,
        ]);
    }

    /**
     * Test validation requires provider_id.
     */
    public function test_validate_requires_provider_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('provider ID is required');

        $model = $this->createInstance(1); // entry exists
        $model->validate([
            'id_documentation_entry' => 1,
            'document_type' => 'general_letter',
        ]);
    }

    /**
     * Test validation requires document_type.
     */
    public function test_validate_requires_document_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document type is required');

        $model = $this->createInstance(1);
        $model->validate([
            'id_documentation_entry' => 1,
            'id_users_provider' => 1,
        ]);
    }

    /**
     * Test validation checks entry exists in DB.
     */
    public function test_validate_checks_entry_exists(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('documentation entry ID does not exist');

        $model = $this->createInstance(0); // entry NOT found
        $model->validate([
            'id_documentation_entry' => 999,
            'document_type' => 'general_letter',
            'id_users_provider' => 1,
        ]);
    }

    /**
     * Test validation passes with all required fields.
     */
    public function test_validate_passes_with_valid_data(): void
    {
        $model = $this->createInstance(1);
        $model->validate([
            'id_documentation_entry' => 1,
            'document_type' => 'referral',
            'id_users_provider' => 1,
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test that HTML in extra_fields values is sanitized.
     */
    public function test_extra_fields_html_sanitization(): void
    {
        $malicious_value = '<script>alert("xss")</script><p>Safe</p>';
        $sanitized = pure_html($malicious_value);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringContainsString('Safe', $sanitized);
    }

    /**
     * Test that extra_fields array is JSON-encoded.
     */
    public function test_extra_fields_json_encoding(): void
    {
        $fields = ['destination' => 'Hospital', 'notes' => 'Urgent'];
        $encoded = json_encode($fields);

        $this->assertJson($encoded);
        $this->assertEquals($fields, json_decode($encoded, true));
    }

    /**
     * Test that model has no update or delete methods (immutability).
     */
    public function test_model_is_immutable(): void
    {
        $class = new \ReflectionClass(\Issued_documents_model::class);

        $this->assertFalse($class->hasMethod('update'), 'Model should not have public update method');
        $this->assertFalse($class->hasMethod('delete'), 'Model should not have public delete method');
    }

    private function createInstance(int $entryExists = 0): \Issued_documents_model
    {
        $class = new \ReflectionClass(\Issued_documents_model::class);
        $instance = $class->newInstanceWithoutConstructor();

        $dbProp = $class->getParentClass()->getProperty('db');
        $dbProp->setAccessible(true);
        $dbProp->setValue($instance, new \MockDb($entryExists));

        return $instance;
    }
}
