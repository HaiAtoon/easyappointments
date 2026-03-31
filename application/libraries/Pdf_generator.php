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

/**
 * PDF generator library.
 *
 * Generates PDF documents from documentation entries (session summaries) using an HTML template rendered by mPDF.
 * For issued documents based on .docx templates, delegates to Document_generator.
 *
 * @package Libraries
 */
class Pdf_generator
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('documentation_entries_model');
        $this->CI->load->model('issued_documents_model');
        $this->CI->load->model('document_templates_model');
        $this->CI->load->model('customers_model');
        $this->CI->load->model('providers_model');
        $this->CI->load->model('appointments_model');
    }

    /**
     * Generate a PDF from a documentation entry's session summary.
     *
     * @param int $entry_id Documentation entry ID.
     * @param string|null $password If set, encrypt the PDF with this password.
     *
     * @return string PDF binary string.
     */
    public function generate_from_entry(int $entry_id, ?string $password = null): string
    {
        $entry = $this->CI->documentation_entries_model->find($entry_id);
        $this->CI->documentation_entries_model->load($entry, ['customer', 'provider', 'appointment']);

        $customer = $entry['customer'];
        $provider = $entry['provider'];
        $appointment = $entry['appointment'] ?? null;

        $session_date = !empty($appointment)
            ? date('d/m/Y', strtotime($appointment['start_datetime']))
            : date('d/m/Y', strtotime($entry['create_datetime']));

        return $this->render_pdf([
            'content' => $entry['session_summary'],
            'type_label' => lang('session_summary'),
            'document_type' => 'session_summary',
            'extra_fields' => [],
            'customer' => $customer,
            'provider' => $provider,
            'appointment' => $appointment,
            'session_date' => $session_date,
        ], $password);
    }

    /**
     * Generate a PDF from an issued document.
     *
     * If the document's template has a .docx file, delegates to Document_generator for
     * DOCX-based generation. Otherwise falls back to HTML template rendering.
     *
     * @param int $document_id Issued document ID.
     * @param string|null $password If set, encrypt the PDF with this password.
     *
     * @return string PDF binary string.
     */
    public function generate_from_document(int $document_id, ?string $password = null): string
    {
        $template = $this->CI->document_templates_model->find_by_slug(
            $this->CI->issued_documents_model->find($document_id)['document_type'],
        );

        if ($template && !empty($template['file_path'])) {
            $this->CI->load->library('document_generator');

            return $this->CI->document_generator->generate($document_id, $password);
        }

        $document = $this->CI->issued_documents_model->find($document_id);
        $this->CI->issued_documents_model->load($document, ['provider', 'entry']);

        $entry = $document['entry'];
        $this->CI->documentation_entries_model->load($entry, ['customer', 'appointment']);

        $customer = $entry['customer'];
        $provider = $document['provider'];
        $appointment = $entry['appointment'] ?? null;

        $extra_fields = !empty($document['extra_fields'])
            ? json_decode($document['extra_fields'], true) ?? []
            : [];

        $types = $this->get_document_types();
        $type_label = $document['title'] ?: ($types[$document['document_type']]['label'] ?? $document['document_type']);

        $session_date = !empty($appointment)
            ? date('d/m/Y', strtotime($appointment['start_datetime']))
            : date('d/m/Y', strtotime($entry['create_datetime']));

        return $this->render_pdf([
            'content' => $document['content'],
            'type_label' => $type_label,
            'document_type' => $document['document_type'],
            'extra_fields' => $extra_fields,
            'customer' => $customer,
            'provider' => $provider,
            'appointment' => $appointment,
            'session_date' => $session_date,
        ], $password);
    }

    /**
     * Get all active document types from the database.
     *
     * Results are cached for the duration of the request to avoid repeated DB queries.
     *
     * @return array Associative array keyed by slug with id and label.
     */
    public function get_document_types(): array
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $templates = $this->CI->document_templates_model->get(['is_active' => 1]);

        $types = [];

        foreach ($templates as $template) {
            $types[$template['slug']] = [
                'id' => $template['id'],
                'label' => $template['name'],
            ];
        }

        $cached = $types;

        return $types;
    }

    /**
     * Render a PDF from the HTML template using mPDF.
     *
     * @param array $data Template data (content, customer, provider, etc.).
     * @param string|null $password If set, encrypt PDF with this password.
     *
     * @return string PDF binary string.
     */
    private function render_pdf(array $data, ?string $password = null): string
    {
        $html = $this->CI->load->view('pdfs/documentation_pdf', [
            'content' => $data['content'],
            'customer' => $data['customer'],
            'provider' => $data['provider'],
            'appointment' => $data['appointment'],
            'document_type' => $data['document_type'],
            'type_label' => $data['type_label'],
            'extra_fields' => $data['extra_fields'],
            'session_date' => $data['session_date'],
            'is_rtl' => is_rtl(),
            'company_name' => setting('company_name'),
            'company_logo' => setting('company_logo'),
            'company_color' => setting('company_color') ?: '#429a82',
        ], true);

        $mpdf = Pdf_utils::create_mpdf();

        if ($password) {
            Pdf_utils::encrypt_pdf($mpdf, $password);
        }

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
