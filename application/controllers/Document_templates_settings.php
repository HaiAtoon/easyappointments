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

class Document_templates_settings extends EA_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('document_templates_model');
        $this->load->library('accounts');
    }

    public function index(): void
    {
        session(['dest_url' => site_url('document_templates_settings')]);

        $user_id = session('user_id');

        if (cannot('view', PRIV_SYSTEM_SETTINGS)) {
            if ($user_id) {
                abort(403, 'Forbidden');
            }

            redirect('login');

            return;
        }

        $role_slug = session('role_slug');

        $templates = $this->document_templates_model->get();

        $this->load->library('document_generator');
        $system_variables = $this->document_generator->get_system_variables();

        script_vars([
            'user_id' => $user_id,
            'role_slug' => $role_slug,
            'templates' => $templates,
            'system_variables' => $system_variables,
        ]);

        html_vars([
            'page_title' => lang('document_templates'),
            'active_menu' => PRIV_SYSTEM_SETTINGS,
            'user_display_name' => $this->accounts->get_user_display_name($user_id),
        ]);

        $this->load->view('pages/document_templates_settings');
    }

    public function store(): void
    {
        try {
            if (cannot('edit', PRIV_SYSTEM_SETTINGS)) {
                throw new RuntimeException('You do not have the required permissions.');
            }

            $template = $this->parse_template_request();

            $template_id = $this->document_templates_model->save($template);

            $this->handle_file_upload($template_id);

            $saved_template = $this->document_templates_model->find($template_id);

            json_response([
                'success' => true,
                'id' => $template_id,
                'template' => $saved_template,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function update(): void
    {
        try {
            if (cannot('edit', PRIV_SYSTEM_SETTINGS)) {
                throw new RuntimeException('You do not have the required permissions.');
            }

            $template = $this->parse_template_request();

            if (empty($template['id'])) {
                throw new InvalidArgumentException('Template ID is required.');
            }

            $template_id = $this->document_templates_model->save($template);

            $this->handle_file_upload($template_id);

            $saved_template = $this->document_templates_model->find($template_id);

            json_response([
                'success' => true,
                'id' => $template_id,
                'template' => $saved_template,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function destroy(): void
    {
        try {
            if (cannot('edit', PRIV_SYSTEM_SETTINGS)) {
                throw new RuntimeException('You do not have the required permissions.');
            }

            $template_id = (int) request('template_id');

            if (!$template_id) {
                throw new InvalidArgumentException('Template ID is required.');
            }

            $this->document_templates_model->delete($template_id);

            json_response(['success' => true]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    private function parse_template_request(): array
    {
        $template = [];

        $id = $_POST['id'] ?? $this->input->post('id');

        if (!empty($id)) {
            $template['id'] = (int) $id;
        }

        $template['name'] = $_POST['name'] ?? $this->input->post('name', true) ?: '';
        $template['slug'] = $_POST['slug'] ?? $this->input->post('slug', true) ?: '';
        $template['is_active'] = (int) ($_POST['is_active'] ?? $this->input->post('is_active') ?? 1);

        $field_mappings = $_POST['field_mappings'] ?? $this->input->post('field_mappings');

        if ($field_mappings) {
            $template['field_mappings'] = is_string($field_mappings)
                ? json_decode($field_mappings, true)
                : $field_mappings;
        } else {
            $template['field_mappings'] = [];
        }

        return $template;
    }

    private function handle_file_upload(int $template_id): void
    {
        if (empty($_FILES['template_file']['name'])) {
            return;
        }

        $upload_dir = FCPATH . 'storage/document-templates/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0775, true);
        }

        $template = $this->document_templates_model->find($template_id);
        $filename = $template_id . '_' . $template['slug'] . '.docx';
        $dest = $upload_dir . $filename;

        if (!move_uploaded_file($_FILES['template_file']['tmp_name'], $dest)) {
            throw new RuntimeException('Failed to upload template file.');
        }

        $this->document_templates_model->save([
            'id' => $template_id,
            'file_path' => 'storage/document-templates/' . $filename,
        ]);
    }
}
