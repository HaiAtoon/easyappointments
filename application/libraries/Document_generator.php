<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

use PhpOffice\PhpWord\IOFactory;
use Mpdf\Mpdf;

/**
 * Document generator library.
 *
 * Generates PDFs from issued documents using .docx templates. The flow:
 * 1. Opens the .docx template file (ZipArchive)
 * 2. Replaces {label} placeholders in the XML with resolved values
 * 3. Converts the .docx to HTML via PhpWord
 * 4. Injects rich HTML content (from free_textarea fields and session_summary)
 * 5. Renders the final HTML to PDF via mPDF
 *
 * @package Libraries
 */
class Document_generator
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('issued_documents_model');
        $this->CI->load->model('document_templates_model');
        $this->CI->load->model('documentation_entries_model');
        $this->CI->load->model('customers_model');
        $this->CI->load->model('providers_model');
        $this->CI->load->model('appointments_model');
        $this->CI->load->model('services_model');
    }

    /**
     * Generate a PDF from an issued document using its .docx template.
     *
     * @param int $document_id Issued document ID.
     * @param bool $encrypt Whether to password-protect the PDF.
     *
     * @return string PDF binary string.
     *
     * @throws RuntimeException If template file not found.
     */
    public function generate(int $document_id, bool $encrypt = false): string
    {
        $document = $this->CI->issued_documents_model->find($document_id);
        $this->CI->issued_documents_model->load($document, ['entry', 'provider']);

        $entry = $document['entry'];
        $this->CI->documentation_entries_model->load($entry, ['customer', 'appointment']);

        $customer = $entry['customer'];
        $provider = $document['provider'];
        $appointment = $entry['appointment'] ?? null;
        $service = null;

        if (!empty($appointment['id_services'])) {
            $service = $this->CI->services_model->find($appointment['id_services']);
        }

        $template = $this->CI->document_templates_model->find_by_slug($document['document_type']);

        if (!$template || empty($template['file_path'])) {
            throw new RuntimeException('Template file not found for document type: ' . $document['document_type']);
        }

        $file_path = FCPATH . $template['file_path'];

        if (!file_exists($file_path)) {
            throw new RuntimeException('Template file does not exist: ' . $file_path);
        }

        $field_mappings = $template['field_mappings'] ?? [];
        $extra_fields = !empty($document['extra_fields'])
            ? (is_string($document['extra_fields']) ? json_decode($document['extra_fields'], true) : $document['extra_fields'])
            : [];

        $context = $this->build_context($customer, $provider, $appointment, $service, $entry);
        $context['template_name'] = $template['name'] ?? '';
        $replacements = $this->resolve_values($field_mappings, $extra_fields, $context);

        $html_fields = [];

        foreach ($field_mappings as $mapping) {
            $clean_label = trim($mapping['label'] ?? '', '{}');
            $mapping_type = $mapping['type'] ?? '';

            if ($mapping_type === 'free_textarea' || $mapping_type === 'session_summary') {
                $html_fields[$clean_label] = true;
            }
        }

        $temp_docx = tempnam(sys_get_temp_dir(), 'doc_') . '.docx';

        copy($file_path, $temp_docx);

        $zip = new \ZipArchive();

        if ($zip->open($temp_docx) === true) {
            $doc_xml = $zip->getFromName('word/document.xml');

            if ($doc_xml) {
                $doc_xml = $this->clean_split_placeholders($doc_xml, array_keys($replacements));

                $html_placeholders = [];

                foreach ($replacements as $label => $value) {
                    $search = '{' . $label . '}';

                    if (!empty($html_fields[$label]) && !empty($value)) {
                        $marker = '___HTML_' . strtoupper($label) . '___';
                        $html_placeholders[$marker] = $value;
                        $doc_xml = str_replace($search, $marker, $doc_xml);
                    } else {
                        $safe_value = htmlspecialchars($value ?? '', ENT_XML1, 'UTF-8');
                        $doc_xml = str_replace($search, $safe_value, $doc_xml);
                    }
                }

                $zip->addFromString('word/document.xml', $doc_xml);
            }

            $zip->close();
        }

        $pdf_string = $this->convert_to_pdf($temp_docx, $encrypt ? $customer : null, $html_placeholders ?? []);

        @unlink($temp_docx);

        return $pdf_string;
    }

    /**
     * Get all available system variables organized by category.
     *
     * Used by the template settings page to populate the type dropdown.
     *
     * @return array Nested array: category => [label, variables => [slug => display_name]].
     */
    public function get_system_variables(): array
    {
        return [
            'customer' => [
                'label' => lang('customer'),
                'variables' => [
                    'customer_name' => lang('name'),
                    'customer_id_number' => lang('id_number'),
                    'customer_phone' => lang('phone'),
                    'customer_email' => lang('email'),
                    'customer_address' => lang('address'),
                    'customer_city' => lang('city'),
                    'customer_zip_code' => lang('zip_code'),
                ],
            ],
            'provider' => [
                'label' => lang('provider'),
                'variables' => [
                    'provider_name' => lang('name'),
                    'provider_title' => lang('professional_title'),
                    'provider_license' => lang('license_number'),
                    'provider_email' => lang('email'),
                    'provider_phone' => lang('phone'),
                ],
            ],
            'service' => [
                'label' => lang('service'),
                'variables' => [
                    'service_name' => lang('service'),
                    'service_duration' => lang('duration'),
                    'service_price' => lang('price'),
                    'service_category' => lang('category'),
                ],
            ],
            'session' => [
                'label' => lang('session_summary'),
                'variables' => [
                    'date' => lang('date'),
                    'time' => lang('time'),
                    'appointment_date' => lang('date'),
                    'appointment_time' => lang('time'),
                    'appointment_service' => lang('service'),
                    'session_summary' => lang('session_summary'),
                ],
            ],
            'company' => [
                'label' => lang('company'),
                'variables' => [
                    'company_name' => lang('company_name'),
                    'company_email' => lang('company_email'),
                    'company_link' => lang('company_link'),
                ],
            ],
            'document' => [
                'label' => lang('document'),
                'variables' => [
                    'template_name' => lang('template_name'),
                ],
            ],
        ];
    }

    /**
     * Build the context array of all system variable values for the current document.
     *
     * @return array Flat associative array: variable_slug => resolved_value.
     */
    private function build_context(
        array $customer,
        array $provider,
        ?array $appointment,
        ?array $service,
        array $entry,
    ): array {
        return [
            'customer_name' => trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')),
            'customer_id_number' => $customer['id_number'] ?? '',
            'customer_phone' => $customer['phone_number'] ?? '',
            'customer_email' => $customer['email'] ?? '',
            'customer_address' => $customer['address'] ?? '',
            'customer_city' => $customer['city'] ?? '',
            'customer_zip_code' => $customer['zip_code'] ?? '',

            'provider_name' => trim(($provider['first_name'] ?? '') . ' ' . ($provider['last_name'] ?? '')),
            'provider_title' => $provider['custom_field_1'] ?? '',
            'provider_license' => $provider['custom_field_2'] ?? '',
            'provider_email' => $provider['email'] ?? '',
            'provider_phone' => $provider['phone_number'] ?? '',

            'service_name' => $service['name'] ?? '',
            'service_duration' => $service['duration'] ?? '',
            'service_price' => $service['price'] ?? '',
            'service_category' => $service['category'] ?? '',

            'date' => date('d/m/Y'),
            'time' => date('H:i'),
            'appointment_date' => !empty($appointment) ? date('d/m/Y', strtotime($appointment['start_datetime'])) : '',
            'appointment_time' => !empty($appointment) ? date('H:i', strtotime($appointment['start_datetime'])) : '',
            'appointment_service' => $service['name'] ?? '',
            'session_summary' => $entry['session_summary'] ?? '',

            'company_name' => setting('company_name'),
            'company_email' => setting('company_email'),
            'company_link' => setting('company_link'),
        ];
    }

    /**
     * Resolve placeholder values from field mappings.
     *
     * For free_text/free_textarea types, uses user-submitted values from extra_fields.
     * For system variables, resolves from context (with user override if provided).
     *
     * @param array $field_mappings Template field mappings.
     * @param array $extra_fields User-submitted field values keyed by label.
     * @param array $context System variable values.
     *
     * @return array Associative array: clean_label => resolved_value.
     */
    private function resolve_values(array $field_mappings, array $extra_fields, array $context): array
    {
        $replacements = [];

        foreach ($field_mappings as $mapping) {
            $label = trim($mapping['label'] ?? '', '{}');
            $type = $mapping['type'] ?? 'free_text';

            if (!$label) {
                continue;
            }

            $raw_label = $mapping['label'] ?? '';

            if ($type === 'free_text' || $type === 'free_textarea') {
                $replacements[$label] = $extra_fields[$label] ?? $extra_fields[$raw_label] ?? '';
            } else {
                if (isset($extra_fields[$label]) && $extra_fields[$label] !== '') {
                    $replacements[$label] = $extra_fields[$label];
                } elseif (isset($extra_fields[$raw_label]) && $extra_fields[$raw_label] !== '') {
                    $replacements[$label] = $extra_fields[$raw_label];
                } else {
                    $replacements[$label] = $context[$type] ?? '';
                }
            }
        }

        return $replacements;
    }

    /**
     * Convert a .docx file to PDF via PhpWord (HTML export) and mPDF.
     *
     * Rich HTML placeholders (from free_textarea/session_summary fields) are injected into
     * the HTML after PhpWord conversion but before mPDF rendering, preserving formatting.
     *
     * @param string $docx_path Path to the processed .docx file.
     * @param array|null $encrypt_for_customer If set, encrypt PDF with this customer's ID/phone.
     * @param array $html_placeholders Marker => HTML content pairs for rich text injection.
     *
     * @return string PDF binary string.
     */
    private function convert_to_pdf(string $docx_path, ?array $encrypt_for_customer = null, array $html_placeholders = []): string
    {
        $phpWord = IOFactory::load($docx_path, 'Word2007');

        $temp_html = tempnam(sys_get_temp_dir(), 'html_') . '.html';
        $writer = IOFactory::createWriter($phpWord, 'HTML');
        $writer->save($temp_html);

        $html = file_get_contents($temp_html);
        @unlink($temp_html);

        $is_rtl = in_array(config('language'), ['hebrew', 'arabic', 'persian']);

        if ($is_rtl) {
            $html = preg_replace('/<body([^>]*)>/', '<body$1 dir="rtl" style="direction:rtl; text-align:right;">', $html, 1);
            $html = str_replace('<html', '<html dir="rtl"', $html);
        }

        foreach ($html_placeholders as $marker => $rich_html) {
            $html = str_replace(htmlspecialchars($marker, ENT_QUOTES, 'UTF-8'), $rich_html, $html);
            $html = str_replace($marker, $rich_html, $html);
        }

        $temp_dir = FCPATH . 'storage/cache/mpdf/';

        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0775, true);
        }

        $mpdf_config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'tempDir' => $temp_dir,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 15,
        ];

        if ($is_rtl) {
            $mpdf_config['directionality'] = 'rtl';
        }

        $mpdf = new Mpdf($mpdf_config);

        if ($encrypt_for_customer) {
            $password = '';

            if (!empty($encrypt_for_customer['id_number'])) {
                $password = $encrypt_for_customer['id_number'];
            } elseif (!empty($encrypt_for_customer['phone_number'])) {
                $password = $encrypt_for_customer['phone_number'];
            }

            if ($password) {
                $mpdf->SetProtection(['copy', 'print'], $password, $password);
            }
        }

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }

    /**
     * Reassemble placeholders that Word splits across multiple XML text nodes.
     *
     * Word often stores {customerName} as three separate <w:t> nodes: "{", "customerName", "}".
     * This method detects split placeholders by building a flat text view of all <w:t> nodes,
     * finding the placeholder in the flat text, and merging the content back into the first node.
     *
     * @param string $xml The document.xml content.
     * @param array $labels List of placeholder labels (without braces).
     *
     * @return string The XML with reassembled placeholders.
     */
    private function clean_split_placeholders(string $xml, array $labels): string
    {
        foreach ($labels as $label) {
            $placeholder = '{' . $label . '}';

            if (strpos($xml, $placeholder) !== false) {
                continue;
            }

            // Extract all <w:t> text nodes
            preg_match_all('/(<w:t[^>]*>)([^<]*)(<\/w:t>)/', $xml, $matches, PREG_OFFSET_CAPTURE);

            if (empty($matches[0])) {
                continue;
            }

            // Build flat text and track which node each char belongs to
            $flat = '';
            $char_to_node = [];

            foreach ($matches[2] as $i => $text_match) {
                $text = $text_match[0];

                for ($c = 0; $c < mb_strlen($text, 'UTF-8'); $c++) {
                    $char_to_node[] = $i;
                }

                $flat .= $text;
            }

            $ph_pos = mb_strpos($flat, $placeholder, 0, 'UTF-8');

            if ($ph_pos === false) {
                continue;
            }

            $ph_len = mb_strlen($placeholder, 'UTF-8');
            $start_node = $char_to_node[$ph_pos];
            $end_node = $char_to_node[$ph_pos + $ph_len - 1];

            if ($start_node === $end_node) {
                continue;
            }

            // Merge: put the full placeholder text into the start node's <w:t>,
            // empty out the middle/end nodes' text
            $start_text = $matches[2][$start_node][0];
            $brace_pos = mb_strrpos($start_text, '{', 0, 'UTF-8');
            $new_start_text = mb_substr($start_text, 0, $brace_pos, 'UTF-8') . $placeholder;

            $end_text = $matches[2][$end_node][0];
            $close_pos = mb_strpos($end_text, '}', 0, 'UTF-8');
            $new_end_text = mb_substr($end_text, $close_pos + 1, null, 'UTF-8');

            // Apply changes in reverse order (end first) to preserve offsets
            // Fix end node
            $end_full_offset = $matches[0][$end_node][1];
            $end_full_len = strlen($matches[0][$end_node][0]);
            $end_replacement = $matches[1][$end_node][0] . $new_end_text . $matches[3][$end_node][0];
            $xml = substr_replace($xml, $end_replacement, $end_full_offset, $end_full_len);

            // Remove middle nodes (in reverse)
            for ($n = $end_node - 1; $n > $start_node; $n--) {
                $mid_offset = $matches[0][$n][1];
                $mid_len = strlen($matches[0][$n][0]);
                $mid_replacement = $matches[1][$n][0] . $matches[3][$n][0];
                $xml = substr_replace($xml, $mid_replacement, $mid_offset, $mid_len);
            }

            // Fix start node
            $start_full_offset = $matches[0][$start_node][1];
            $start_full_len = strlen($matches[0][$start_node][0]);
            $start_replacement = $matches[1][$start_node][0] . $new_start_text . $matches[3][$start_node][0];
            $xml = substr_replace($xml, $start_replacement, $start_full_offset, $start_full_len);
        }

        return $xml;
    }

    /**
     * Convert HTML to plain text preserving line breaks and list markers.
     *
     * @param string $html HTML content (from Trumbowyg editor).
     *
     * @return string Plain text with newlines.
     */
    private function html_to_plain_text(string $html): string
    {
        $html = trim($html);

        if (empty($html)) {
            return '';
        }

        $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        $text = preg_replace('/<\/(p|div)>/', "\n", $text);
        $text = preg_replace('/<li[^>]*>/', '• ', $text);
        $text = strip_tags($text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }
}
