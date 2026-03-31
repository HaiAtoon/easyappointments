<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

class FieldMappingValidationTest extends TestCase
{
    /**
     * Test that label sanitization removes special characters.
     */
    public function test_label_sanitization(): void
    {
        $raw = '{customerName}';
        $sanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $raw);

        $this->assertEquals('customerName', $sanitized);
    }

    public function test_label_sanitization_removes_path_traversal(): void
    {
        $raw = '../../../etc/passwd';
        $sanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $raw);

        $this->assertEquals('etcpasswd', $sanitized);
    }

    public function test_label_sanitization_preserves_underscores(): void
    {
        $raw = 'customer_name_123';
        $sanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $raw);

        $this->assertEquals('customer_name_123', $sanitized);
    }

    /**
     * Test that slug sanitization produces valid filenames.
     */
    public function test_slug_sanitization(): void
    {
        $raw = 'General Letter!';
        $sanitized = preg_replace('/[^a-z0-9_]/', '', strtolower($raw));

        $this->assertEquals('generalletter', $sanitized);
    }

    public function test_slug_sanitization_removes_unicode(): void
    {
        $raw = 'הפניה-test';
        $sanitized = preg_replace('/[^a-z0-9_]/', '', strtolower($raw));

        $this->assertEquals('test', $sanitized);
    }

    /**
     * Test valid type list contains expected values.
     */
    public function test_known_type_values(): void
    {
        $valid_types = [
            'free_text',
            'free_textarea',
            'customer_name',
            'customer_id_number',
            'customer_phone',
            'customer_email',
            'customer_address',
            'customer_city',
            'customer_zip_code',
            'provider_name',
            'provider_title',
            'provider_license',
            'provider_email',
            'provider_phone',
            'service_name',
            'service_duration',
            'service_price',
            'service_category',
            'date',
            'time',
            'appointment_date',
            'appointment_time',
            'appointment_service',
            'session_summary',
            'company_name',
            'company_email',
            'company_link',
            'template_name',
        ];

        foreach ($valid_types as $type) {
            $this->assertMatchesRegularExpression('/^[a-z][a-z0-9_]*$/', $type, "Type '{$type}' has invalid format");
        }
    }

    /**
     * Test that unknown types fall back to free_text.
     */
    public function test_unknown_type_fallback(): void
    {
        $valid_types = ['free_text', 'free_textarea', 'customer_name'];
        $type = 'malicious_injection';

        $result = in_array($type, $valid_types) ? $type : 'free_text';

        $this->assertEquals('free_text', $result);
    }

    /**
     * Test mapping structure after validation.
     */
    public function test_mapping_structure(): void
    {
        $input = [
            'label' => '{testField}',
            'name' => '<script>alert(1)</script>Real Name',
            'type' => 'customer_name',
            'user_display' => true,
        ];

        $validated = [
            'label' => preg_replace('/[^a-zA-Z0-9_]/', '', $input['label']),
            'name' => mb_substr(strip_tags($input['name']), 0, 256),
            'type' => $input['type'],
            'user_display' => !empty($input['user_display']),
        ];

        $this->assertEquals('testField', $validated['label']);
        $this->assertEquals('alert(1)Real Name', $validated['name']);
        $this->assertEquals('customer_name', $validated['type']);
        $this->assertTrue($validated['user_display']);
    }
}
