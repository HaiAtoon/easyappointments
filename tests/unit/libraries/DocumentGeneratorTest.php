<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

class DocumentGeneratorTest extends TestCase
{
    /**
     * Test resolve_values with free_text fields.
     */
    public function test_resolve_values_free_text(): void
    {
        $field_mappings = [
            ['label' => 'destination', 'name' => 'Destination', 'type' => 'free_text', 'user_display' => true],
        ];

        $extra_fields = ['destination' => 'Hospital A'];
        $context = [];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertEquals(['destination' => 'Hospital A'], $result);
    }

    /**
     * Test resolve_values with system variables.
     */
    public function test_resolve_values_system_variable(): void
    {
        $field_mappings = [
            ['label' => 'customerName', 'name' => 'Name', 'type' => 'customer_name', 'user_display' => false],
        ];

        $extra_fields = [];
        $context = ['customer_name' => 'John Doe'];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertEquals(['customerName' => 'John Doe'], $result);
    }

    /**
     * Test resolve_values with user override of system variable.
     */
    public function test_resolve_values_user_override(): void
    {
        $field_mappings = [
            ['label' => 'customerName', 'name' => 'Name', 'type' => 'customer_name', 'user_display' => true],
        ];

        $extra_fields = ['customerName' => 'Custom Name'];
        $context = ['customer_name' => 'John Doe'];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertEquals(['customerName' => 'Custom Name'], $result);
    }

    /**
     * Test resolve_values strips braces from labels.
     */
    public function test_resolve_values_strips_braces(): void
    {
        $field_mappings = [
            ['label' => '{customerName}', 'name' => 'Name', 'type' => 'customer_name', 'user_display' => false],
        ];

        $extra_fields = [];
        $context = ['customer_name' => 'Jane'];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertEquals(['customerName' => 'Jane'], $result);
    }

    /**
     * Test resolve_values skips empty labels.
     */
    public function test_resolve_values_skips_empty_labels(): void
    {
        $field_mappings = [
            ['label' => '', 'name' => 'Empty', 'type' => 'free_text', 'user_display' => true],
            ['label' => 'valid', 'name' => 'Valid', 'type' => 'free_text', 'user_display' => true],
        ];

        $extra_fields = ['valid' => 'value'];
        $context = [];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertArrayNotHasKey('', $result);
        $this->assertEquals('value', $result['valid']);
    }

    /**
     * Test resolve_values with free_textarea type.
     */
    public function test_resolve_values_free_textarea(): void
    {
        $field_mappings = [
            ['label' => 'notes', 'name' => 'Notes', 'type' => 'free_textarea', 'user_display' => true],
        ];

        $extra_fields = ['notes' => '<p>Rich <strong>text</strong></p>'];
        $context = [];

        $result = $this->callResolveValues($field_mappings, $extra_fields, $context);

        $this->assertEquals('<p>Rich <strong>text</strong></p>', $result['notes']);
    }

    /**
     * Test html_to_plain_text conversion.
     */
    public function test_html_to_plain_text_preserves_line_breaks(): void
    {
        $html = '<p>Line one</p><p>Line two</p>';
        $result = $this->callHtmlToPlainText($html);

        $this->assertStringContainsString("Line one", $result);
        $this->assertStringContainsString("Line two", $result);
        $this->assertStringNotContainsString('<p>', $result);
    }

    public function test_html_to_plain_text_handles_br_tags(): void
    {
        $html = 'First<br>Second<br/>Third';
        $result = $this->callHtmlToPlainText($html);

        $this->assertStringContainsString("First\n", $result);
        $this->assertStringContainsString("Second\n", $result);
    }

    public function test_html_to_plain_text_handles_lists(): void
    {
        $html = '<ul><li>Item A</li><li>Item B</li></ul>';
        $result = $this->callHtmlToPlainText($html);

        $this->assertStringContainsString('Item A', $result);
        $this->assertStringContainsString('Item B', $result);
    }

    public function test_html_to_plain_text_returns_empty_for_empty_input(): void
    {
        $this->assertEquals('', $this->callHtmlToPlainText(''));
        $this->assertEquals('', $this->callHtmlToPlainText('   '));
    }

    // --- build_context tests ---

    public function test_build_context_returns_all_expected_keys(): void
    {
        $customer = ['first_name' => 'John', 'last_name' => 'Doe', 'id_number' => '123', 'phone_number' => '050',
            'email' => 'j@d.com', 'address' => '1 St', 'city' => 'TLV', 'zip_code' => '12345'];
        $provider = ['first_name' => 'Dr', 'last_name' => 'Smith', 'custom_field_1' => 'MD', 'custom_field_2' => 'L123',
            'email' => 'dr@s.com', 'phone_number' => '051'];
        $entry = ['session_summary' => '<p>Notes</p>'];

        $result = $this->callBuildContext($customer, $provider, null, null, $entry);

        $this->assertEquals('John Doe', $result['customer_name']);
        $this->assertEquals('123', $result['customer_id_number']);
        $this->assertEquals('Dr Smith', $result['provider_name']);
        $this->assertEquals('MD', $result['provider_title']);
        $this->assertEquals('L123', $result['provider_license']);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('time', $result);
        $this->assertArrayHasKey('company_name', $result);
        $this->assertEquals('<p>Notes</p>', $result['session_summary']);
    }

    public function test_build_context_handles_null_appointment(): void
    {
        $result = $this->callBuildContext(
            ['first_name' => 'A', 'last_name' => 'B'],
            ['first_name' => 'C', 'last_name' => 'D'],
            null, null,
            ['session_summary' => 'test'],
        );

        $this->assertEquals('', $result['appointment_date']);
        $this->assertEquals('', $result['appointment_time']);
        $this->assertEquals('', $result['appointment_service']);
    }

    public function test_build_context_with_appointment_and_service(): void
    {
        $appointment = ['start_datetime' => '2026-03-15 10:00:00', 'id_services' => 1];
        $service = ['name' => 'Consultation', 'duration' => '30', 'price' => '200', 'category' => 'Medical'];

        $result = $this->callBuildContext(
            ['first_name' => 'A', 'last_name' => 'B'],
            ['first_name' => 'C', 'last_name' => 'D'],
            $appointment, $service,
            ['session_summary' => 'test'],
        );

        $this->assertEquals('15/03/2026', $result['appointment_date']);
        $this->assertEquals('10:00', $result['appointment_time']);
        $this->assertEquals('Consultation', $result['service_name']);
        $this->assertEquals('30', $result['service_duration']);
    }

    // --- clean_split_placeholders tests ---

    public function test_clean_split_placeholders_merges_split_label(): void
    {
        // Simulate Word splitting {name} across 3 w:t nodes
        $xml = '<w:r><w:t>Hello: {</w:t></w:r><w:r><w:t>name</w:t></w:r><w:r><w:t>}</w:t></w:r>';

        $result = $this->callCleanSplitPlaceholders($xml, ['name']);

        $this->assertStringContainsString('{name}', $result);
    }

    public function test_clean_split_placeholders_skips_intact_labels(): void
    {
        $xml = '<w:r><w:t>{name}</w:t></w:r>';

        $result = $this->callCleanSplitPlaceholders($xml, ['name']);

        // Should remain unchanged
        $this->assertEquals($xml, $result);
    }

    public function test_clean_split_placeholders_handles_multiple_labels(): void
    {
        $xml = '<w:r><w:t>{</w:t></w:r><w:r><w:t>first</w:t></w:r><w:r><w:t>}</w:t></w:r>' .
               '<w:r><w:t>{second}</w:t></w:r>';

        $result = $this->callCleanSplitPlaceholders($xml, ['first', 'second']);

        $this->assertStringContainsString('{first}', $result);
        $this->assertStringContainsString('{second}', $result);
    }

    // --- get_system_variables tests ---

    public function test_get_system_variables_has_all_categories(): void
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('get_system_variables');
        $method->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();

        $vars = $method->invoke($instance);

        $this->assertArrayHasKey('customer', $vars);
        $this->assertArrayHasKey('provider', $vars);
        $this->assertArrayHasKey('service', $vars);
        $this->assertArrayHasKey('session', $vars);
        $this->assertArrayHasKey('company', $vars);
        $this->assertArrayHasKey('document', $vars);
    }

    public function test_system_variables_categories_have_label_and_variables(): void
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('get_system_variables');
        $method->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();

        $vars = $method->invoke($instance);

        foreach ($vars as $key => $category) {
            $this->assertArrayHasKey('label', $category, "Category {$key} missing 'label'");
            $this->assertArrayHasKey('variables', $category, "Category {$key} missing 'variables'");
            $this->assertIsArray($category['variables']);
            $this->assertNotEmpty($category['variables'], "Category {$key} has no variables");
        }
    }

    public function test_customer_variables_include_expected_slugs(): void
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('get_system_variables');
        $method->setAccessible(true);
        $instance = $class->newInstanceWithoutConstructor();

        $vars = $method->invoke($instance);
        $customer_vars = array_keys($vars['customer']['variables']);

        $this->assertContains('customer_name', $customer_vars);
        $this->assertContains('customer_id_number', $customer_vars);
        $this->assertContains('customer_phone', $customer_vars);
        $this->assertContains('customer_email', $customer_vars);
    }

    /**
     * Call the private resolve_values method via reflection.
     */
    private function callResolveValues(array $mappings, array $extra, array $context): array
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('resolve_values');
        $method->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();

        return $method->invoke($instance, $mappings, $extra, $context);
    }

    /**
     * Call the private html_to_plain_text method via reflection.
     */
    private function callHtmlToPlainText(string $html): string
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('html_to_plain_text');
        $method->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();

        return $method->invoke($instance, $html);
    }

    private function callBuildContext(array $customer, array $provider, ?array $appointment, ?array $service, array $entry): array
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('build_context');
        $method->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();

        return $method->invoke($instance, $customer, $provider, $appointment, $service, $entry);
    }

    private function callCleanSplitPlaceholders(string $xml, array $labels): string
    {
        $class = new \ReflectionClass(\Document_generator::class);
        $method = $class->getMethod('clean_split_placeholders');
        $method->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();

        return $method->invoke($instance, $xml, $labels);
    }
}
