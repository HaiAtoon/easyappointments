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

class Documentation_entries extends EA_Controller
{
    public array $allowed_entry_fields = [
        'id',
        'id_users_customer',
        'id_appointments',
        'id_users_provider',
        'session_summary',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('documentation_entries_model');
        $this->load->model('issued_documents_model');
        $this->load->model('document_templates_model');
        $this->load->model('appointments_model');
        $this->load->model('customers_model');
        $this->load->model('providers_model');
        $this->load->library('permissions');
    }

    public function search(): void
    {
        try {
            $this->require_admin_or_provider();

            $customer_id = (int) request('customer_id');

            if (!$customer_id) {
                throw new InvalidArgumentException('Customer ID is required.');
            }

            $prefix = $this->db->dbprefix;

            $this->db
                ->select("
                    {$prefix}documentation_entries.*,
                    CONCAT(provider.first_name, ' ', provider.last_name) AS provider_name,
                    CONCAT({$prefix}services.name, ' - ', {$prefix}appointments.start_datetime) AS appointment_summary
                ", false)
                ->from('documentation_entries')
                ->join('users AS provider', 'provider.id = documentation_entries.id_users_provider', 'left')
                ->join('appointments', 'appointments.id = documentation_entries.id_appointments', 'left')
                ->join('services', 'services.id = appointments.id_services', 'left')
                ->where('documentation_entries.id_users_customer', $customer_id);

            // Providers can only see their own entries; admins see all
            if (session('role_slug') === DB_SLUG_PROVIDER) {
                $this->db->where('documentation_entries.id_users_provider', session('user_id'));
            }

            $entries = $this->db
                ->order_by('documentation_entries.create_datetime', 'DESC')
                ->get()
                ->result_array();

            foreach ($entries as &$entry) {
                $this->documentation_entries_model->cast($entry);
                Field_encryption::decrypt_record('documentation_entries', $entry);
            }

            audit_log('VIEW_DOCUMENTATION', 'documentation_entries', null, $customer_id);

            json_response($entries);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function store(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry = request('documentation_entry');

            $this->documentation_entries_model->only($entry, $this->allowed_entry_fields);

            $role_slug = session('role_slug');
            $user_id = session('user_id');

            if ($role_slug !== DB_SLUG_ADMIN) {
                $entry['id_users_provider'] = $user_id;
            }

            if (empty($entry['id_users_provider'])) {
                $entry['id_users_provider'] = $user_id;
            }

            unset($entry['id']);

            $entry_id = $this->documentation_entries_model->save($entry);

            $saved_entry = $this->documentation_entries_model->find($entry_id);

            json_response([
                'success' => true,
                'id' => $entry_id,
                'entry' => $saved_entry,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function update(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry = request('documentation_entry');

            if (empty($entry['id'])) {
                throw new InvalidArgumentException('Documentation entry ID is required for update.');
            }

            $original = $this->documentation_entries_model->find($entry['id']);

            $user_id = session('user_id');
            $role_slug = session('role_slug');

            $editable_fields = ['session_summary', 'id_appointments'];

            if ($role_slug === DB_SLUG_ADMIN) {
                $editable_fields[] = 'id_users_provider';
            }

            $update_data = ['id' => $entry['id']];

            foreach ($editable_fields as $field) {
                if (array_key_exists($field, $entry)) {
                    $update_data[$field] = $entry[$field];
                }
            }

            foreach ($editable_fields as $field) {
                if (!array_key_exists($field, $entry)) {
                    continue;
                }

                $old_val = $original[$field] ?? null;
                $new_val = $entry[$field] ?? null;

                if ((string) $old_val !== (string) $new_val) {
                    $this->documentation_entries_model->log_edit(
                        $entry['id'],
                        $user_id,
                        $field,
                        (string) $old_val,
                        (string) $new_val,
                    );
                }
            }

            $update_data['is_edited'] = 1;

            $this->documentation_entries_model->save($update_data);

            $updated_entry = $this->documentation_entries_model->find($entry['id']);

            json_response([
                'success' => true,
                'id' => $entry['id'],
                'entry' => $updated_entry,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function edit_log(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry_id = (int) request('documentation_entry_id');

            if (!$entry_id) {
                throw new InvalidArgumentException('Documentation entry ID is required.');
            }

            $log = $this->documentation_entries_model->get_edit_log($entry_id);

            json_response($log);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function view_entry_pdf(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry_id = (int) request('documentation_entry_id');

            if (!$entry_id) {
                throw new InvalidArgumentException('Documentation entry ID is required.');
            }

            $this->load->library('pdf_generator');

            $pdf_string = $this->pdf_generator->generate_from_entry($entry_id);

            audit_log('VIEW_ENTRY_PDF', 'documentation_entries', $entry_id);

            $this->output
                ->set_content_type('application/pdf')
                ->set_header('Content-Disposition: inline; filename="entry_' . $entry_id . '.pdf"')
                ->set_output($pdf_string);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function send_entry_pdf(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry_id = (int) request('documentation_entry_id');

            if (!$entry_id) {
                throw new InvalidArgumentException('Documentation entry ID is required.');
            }

            $this->load->library('pdf_generator');
            if (!class_exists('Pdf_utils', false)) {
                require_once APPPATH . 'libraries/Pdf_utils.php';
            }
            $this->load->library('email_messages');

            $password = Pdf_utils::generate_password();

            $pdf_string = $this->pdf_generator->generate_from_entry($entry_id, $password);

            $entry = $this->documentation_entries_model->find($entry_id);
            $customer = $this->customers_model->find($entry['id_users_customer']);

            if (empty($customer['email'])) {
                throw new RuntimeException(lang('no_client_email'));
            }

            $this->email_messages->send_documentation_pdf($customer, $pdf_string, 'session_summary', $password);

            audit_log('SEND_ENTRY_PDF', 'documentation_entries', $entry_id, $customer['id']);

            json_response(['success' => true]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function store_document(): void
    {
        try {
            $this->require_admin_or_provider();

            $document = request('issued_document');

            if (empty($document['id_documentation_entry'])) {
                throw new InvalidArgumentException('Documentation entry ID is required.');
            }

            $role_slug = session('role_slug');
            $user_id = session('user_id');

            if ($role_slug !== DB_SLUG_ADMIN) {
                $document['id_users_provider'] = $user_id;
            }

            if (empty($document['id_users_provider'])) {
                $document['id_users_provider'] = $user_id;
            }

            $document_id = $this->issued_documents_model->save($document);

            $saved_document = $this->issued_documents_model->find($document_id);

            json_response([
                'success' => true,
                'id' => $document_id,
                'document' => $saved_document,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function get_documents(): void
    {
        try {
            $this->require_admin_or_provider();

            $entry_id = (int) request('documentation_entry_id');

            if (!$entry_id) {
                throw new InvalidArgumentException('Documentation entry ID is required.');
            }

            $documents = $this->db
                ->select('
                    issued_documents.*,
                    CONCAT(provider.first_name, " ", provider.last_name) AS provider_name
                ')
                ->from('issued_documents')
                ->join('users AS provider', 'provider.id = issued_documents.id_users_provider', 'left')
                ->where('issued_documents.id_documentation_entry', $entry_id)
                ->order_by('issued_documents.create_datetime', 'DESC')
                ->get()
                ->result_array();

            foreach ($documents as &$doc) {
                $this->issued_documents_model->cast($doc);
            }

            json_response($documents);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function view_document_pdf(): void
    {
        try {
            $this->require_admin_or_provider();

            $document_id = (int) request('document_id');

            if (!$document_id) {
                throw new InvalidArgumentException('Document ID is required.');
            }

            $this->load->library('pdf_generator');

            $pdf_string = $this->pdf_generator->generate_from_document($document_id);

            $this->output
                ->set_content_type('application/pdf')
                ->set_header('Content-Disposition: inline; filename="document_' . $document_id . '.pdf"')
                ->set_output($pdf_string);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function send_document_pdf(): void
    {
        try {
            $this->require_admin_or_provider();

            $document_id = (int) request('document_id');

            if (!$document_id) {
                throw new InvalidArgumentException('Document ID is required.');
            }

            $this->load->library('pdf_generator');
            if (!class_exists('Pdf_utils', false)) {
                require_once APPPATH . 'libraries/Pdf_utils.php';
            }
            $this->load->library('email_messages');

            $document = $this->issued_documents_model->find($document_id);
            $this->issued_documents_model->load($document, ['entry']);

            $entry = $document['entry'];
            $customer = $this->customers_model->find($entry['id_users_customer']);

            if (empty($customer['email'])) {
                throw new RuntimeException(lang('no_client_email'));
            }

            $password = Pdf_utils::generate_password();

            $pdf_string = $this->pdf_generator->generate_from_document($document_id, $password);

            $this->email_messages->send_documentation_pdf($customer, $pdf_string, $document['document_type'], $password);

            audit_log('SEND_DOCUMENT_PDF', 'issued_documents', $document_id, $customer['id']);

            json_response(['success' => true]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function document_types(): void
    {
        try {
            $this->require_admin_or_provider();

            $this->load->library('pdf_generator');

            json_response($this->pdf_generator->get_document_types());
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function system_variables(): void
    {
        try {
            $this->require_admin_or_provider();

            $this->load->library('document_generator');

            json_response($this->document_generator->get_system_variables());
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function template_mappings(): void
    {
        try {
            $this->require_admin_or_provider();

            $template_slug = request('template_slug');

            if (!$template_slug) {
                throw new InvalidArgumentException('Template slug is required.');
            }

            $template = $this->document_templates_model->find_by_slug($template_slug);

            if (!$template) {
                throw new InvalidArgumentException('Template not found: ' . $template_slug);
            }

            json_response([
                'field_mappings' => $template['field_mappings'] ?? [],
                'has_file' => !empty($template['file_path']),
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    private function require_admin_or_provider(): void
    {
        $role_slug = session('role_slug');

        if ($role_slug !== DB_SLUG_ADMIN && $role_slug !== DB_SLUG_PROVIDER) {
            abort(403, 'Forbidden');
        }
    }
}
