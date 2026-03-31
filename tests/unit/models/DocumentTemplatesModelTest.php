<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'core/EA_Model.php';
require_once APPPATH . 'models/Document_templates_model.php';

/**
 * Tests for Document_templates_model validation and JSON handling.
 */
class DocumentTemplatesModelTest extends TestCase
{
    /**
     * Test $casts array has correct types.
     */
    public function test_casts_array(): void
    {
        $instance = $this->getInstance();
        $casts = $this->getProperty($instance, 'casts');

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('boolean', $casts['is_active']);
        $this->assertEquals('integer', $casts['sort_order']);
    }

    /**
     * Test validation requires name on insert.
     */
    public function test_validate_requires_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $model = $this->getInstance();
        $model->validate(['slug' => 'test']);
    }

    /**
     * Test validation requires slug on insert.
     */
    public function test_validate_requires_slug(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('slug is required');

        $model = $this->getInstance();
        $model->validate(['name' => 'Test']);
    }

    /**
     * Test validation passes with valid data.
     */
    public function test_validate_passes(): void
    {
        $model = $this->getInstance();
        $model->validate(['name' => 'Referral', 'slug' => 'referral']);

        $this->assertTrue(true);
    }

    /**
     * Test validation checks record exists for update.
     */
    public function test_validate_checks_record_exists_on_update(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('template ID does not exist');

        $model = $this->getInstanceWithDb(0);
        $model->validate(['id' => 999]);
    }

    /**
     * Test field_mappings JSON encoding.
     */
    public function test_encode_fields_converts_array_to_json(): void
    {
        $instance = $this->getInstance();
        $method = new \ReflectionMethod($instance, 'encode_fields');
        $method->setAccessible(true);

        $template = ['field_mappings' => [['label' => 'name', 'type' => 'free_text']]];
        $method->invokeArgs($instance, [&$template]);

        $this->assertIsString($template['field_mappings']);
        $this->assertJson($template['field_mappings']);
    }

    /**
     * Test field_mappings JSON decoding.
     */
    public function test_decode_fields_converts_json_to_array(): void
    {
        $instance = $this->getInstance();
        $method = new \ReflectionMethod($instance, 'decode_fields');
        $method->setAccessible(true);

        $template = ['field_mappings' => '[{"label":"name","type":"free_text"}]'];
        $method->invokeArgs($instance, [&$template]);

        $this->assertIsArray($template['field_mappings']);
        $this->assertCount(1, $template['field_mappings']);
        $this->assertEquals('name', $template['field_mappings'][0]['label']);
    }

    /**
     * Test decode_fields handles empty/null.
     */
    public function test_decode_fields_handles_empty(): void
    {
        $instance = $this->getInstance();
        $method = new \ReflectionMethod($instance, 'decode_fields');
        $method->setAccessible(true);

        $template = ['field_mappings' => null];
        $method->invokeArgs($instance, [&$template]);
        $this->assertEquals([], $template['field_mappings']);

        $template = ['field_mappings' => ''];
        $method->invokeArgs($instance, [&$template]);
        $this->assertEquals([], $template['field_mappings']);
    }

    /**
     * Test decode_fields handles corrupted JSON gracefully.
     */
    public function test_decode_fields_handles_invalid_json(): void
    {
        $instance = $this->getInstance();
        $method = new \ReflectionMethod($instance, 'decode_fields');
        $method->setAccessible(true);

        $template = ['field_mappings' => '{broken json'];
        $method->invokeArgs($instance, [&$template]);

        $this->assertEquals([], $template['field_mappings']);
    }

    /**
     * Test soft delete sets is_active to 0 (not actual delete).
     */
    public function test_delete_is_soft(): void
    {
        $class = new \ReflectionClass(\Document_templates_model::class);
        $method = $class->getMethod('delete');

        // Verify the method signature — it exists and is public
        $this->assertTrue($method->isPublic());

        // Read source to confirm it uses update, not delete
        $source = file_get_contents(APPPATH . 'models/Document_templates_model.php');
        $this->assertStringContainsString("'is_active' => 0", $source);
        $this->assertStringNotContainsString("->delete('document_templates'", $source);
    }

    private function getInstance(): \Document_templates_model
    {
        $class = new \ReflectionClass(\Document_templates_model::class);
        return $class->newInstanceWithoutConstructor();
    }

    private function getInstanceWithDb(int $numRows): \Document_templates_model
    {
        $instance = $this->getInstance();

        $class = new \ReflectionClass(\Document_templates_model::class);
        $dbProp = $class->getParentClass()->getProperty('db');
        $dbProp->setAccessible(true);
        $dbProp->setValue($instance, new \MockDb($numRows));

        return $instance;
    }

    private function getProperty($instance, string $name)
    {
        $class = new \ReflectionClass($instance);
        $prop = $class->getProperty($name);
        $prop->setAccessible(true);
        return $prop->getValue($instance);
    }
}
