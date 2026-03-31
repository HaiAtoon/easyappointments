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
}
