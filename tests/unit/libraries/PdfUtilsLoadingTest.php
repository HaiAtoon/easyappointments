<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

class PdfUtilsLoadingTest extends TestCase
{
    public function test_pdf_utils_class_is_loadable(): void
    {
        $path = APPPATH . 'libraries/Pdf_utils.php';

        $this->assertFileExists($path);
        $this->assertTrue(class_exists('Pdf_utils', false));
    }

    public function test_pdf_generator_requires_pdf_utils(): void
    {
        $source = file_get_contents(APPPATH . 'libraries/Pdf_generator.php');

        $this->assertStringContainsString('Pdf_utils.php', $source,
            'Pdf_generator must require Pdf_utils.php to avoid "Class not found" errors');
    }

    public function test_document_generator_requires_pdf_utils(): void
    {
        $source = file_get_contents(APPPATH . 'libraries/Document_generator.php');

        $this->assertStringContainsString('Pdf_utils.php', $source,
            'Document_generator must require Pdf_utils.php to avoid "Class not found" errors');
    }

    public function test_pdf_utils_has_required_static_methods(): void
    {
        $this->assertTrue(method_exists('Pdf_utils', 'create_mpdf'));
        $this->assertTrue(method_exists('Pdf_utils', 'generate_password'));
        $this->assertTrue(method_exists('Pdf_utils', 'get_customer_identifier'));
        $this->assertTrue(method_exists('Pdf_utils', 'get_password_field'));
    }

    public function test_generate_password_returns_hex_string(): void
    {
        $password = \Pdf_utils::generate_password();

        $this->assertSame(16, strlen($password));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{16}$/', $password);
    }

    public function test_generate_password_is_unique_each_call(): void
    {
        $p1 = \Pdf_utils::generate_password();
        $p2 = \Pdf_utils::generate_password();

        $this->assertNotSame($p1, $p2);
    }

    public function test_pdf_utils_receives_decrypted_customer_identifier(): void
    {
        $original = '123456789';
        $customer = ['id_number' => $original, 'phone_number' => '0501234567'];

        \Field_encryption::encrypt_record('users', $customer);
        \Field_encryption::decrypt_record('users', $customer);

        $this->assertSame($original, \Pdf_utils::get_customer_identifier($customer));
    }

    public function test_all_documentation_controller_pdf_methods_load_dependencies(): void
    {
        $source = file_get_contents(APPPATH . 'controllers/Documentation_entries.php');

        $methods_using_pdf_utils = [];
        preg_match_all('/public function (\w+)\(.*?\{(.*?)\n    \}/s', $source, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            if (str_contains($matches[2][$i], 'Pdf_utils::')) {
                $methods_using_pdf_utils[$matches[1][$i]] = $matches[2][$i];
            }
        }

        $this->assertNotEmpty($methods_using_pdf_utils, 'Should find methods that use Pdf_utils');

        foreach ($methods_using_pdf_utils as $method => $body) {
            $loads_pdf_utils = str_contains($body, 'Pdf_utils.php') || str_contains($body, "load->library('pdf_utils')");
            $loads_pdf_generator = str_contains($body, "load->library('pdf_generator')");

            $this->assertTrue(
                $loads_pdf_utils || $loads_pdf_generator,
                "Method {$method}() uses Pdf_utils but does not load it or pdf_generator (which requires it)"
            );
        }
    }

    public function test_load_methods_decrypt_user_records(): void
    {
        $doc_entries_source = file_get_contents(APPPATH . 'models/Documentation_entries_model.php');
        $issued_docs_source = file_get_contents(APPPATH . 'models/Issued_documents_model.php');

        $this->assertStringContainsString(
            "Field_encryption::decrypt_record('users'",
            $doc_entries_source,
            'Documentation_entries_model::load() must decrypt user records'
        );

        $this->assertStringContainsString(
            "Field_encryption::decrypt_record('users'",
            $issued_docs_source,
            'Issued_documents_model::load() must decrypt user records'
        );
    }

    public function test_load_methods_decrypt_documentation_entry_records(): void
    {
        $issued_docs_source = file_get_contents(APPPATH . 'models/Issued_documents_model.php');

        $this->assertStringContainsString(
            "Field_encryption::decrypt_record('documentation_entries'",
            $issued_docs_source,
            'Issued_documents_model::load() must decrypt documentation entry records'
        );
    }
}
