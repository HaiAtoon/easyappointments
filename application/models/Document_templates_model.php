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
 * Document templates model.
 *
 * Handles database operations for .docx document templates configured by admins.
 * Each template has a .docx file with {label} placeholders and a field_mappings JSON
 * that maps each label to either a system variable or a user-input field.
 * Soft-delete via is_active flag to preserve references from existing issued documents.
 *
 * @package Models
 */
class Document_templates_model extends EA_Model
{
    protected array $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected array $api_resource = [
        'id' => 'id',
        'name' => 'name',
        'slug' => 'slug',
        'fields' => 'fields',
        'defaultContent' => 'default_content',
        'isActive' => 'is_active',
        'sortOrder' => 'sort_order',
    ];

    public function save(array $template): int
    {
        $this->validate($template);

        if (empty($template['id'])) {
            return $this->insert($template);
        } else {
            return $this->update($template);
        }
    }

    public function validate(array $template): void
    {
        if (!empty($template['id'])) {
            $count = $this->db->get_where('document_templates', ['id' => $template['id']])->num_rows();

            if (!$count) {
                throw new InvalidArgumentException(
                    'The provided template ID does not exist: ' . $template['id'],
                );
            }
        }

        if (empty($template['id']) && empty($template['name'])) {
            throw new InvalidArgumentException('The template name is required.');
        }

        if (empty($template['id']) && empty($template['slug'])) {
            throw new InvalidArgumentException('The template slug is required.');
        }
    }

    public function get(
        array|string|null $where = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order_by = null,
    ): array {
        if ($where !== null) {
            $this->db->where($where);
        }

        if ($order_by !== null) {
            $this->db->order_by($this->quote_order_by($order_by));
        } else {
            $this->db->order_by('sort_order', 'ASC');
        }

        $templates = $this->db->get('document_templates', $limit, $offset)->result_array();

        foreach ($templates as &$template) {
            $this->cast($template);
            $this->decode_fields($template);
        }

        return $templates;
    }

    public function find(int $template_id): array
    {
        $template = $this->db->get_where('document_templates', ['id' => $template_id])->row_array();

        if (!$template) {
            throw new InvalidArgumentException(
                'The provided template ID was not found: ' . $template_id,
            );
        }

        $this->cast($template);
        $this->decode_fields($template);

        return $template;
    }

    public function find_by_slug(string $slug): ?array
    {
        $template = $this->db->get_where('document_templates', ['slug' => $slug])->row_array();

        if (!$template) {
            return null;
        }

        $this->cast($template);
        $this->decode_fields($template);

        return $template;
    }

    protected function insert(array $template): int
    {
        $now = date('Y-m-d H:i:s');

        $template['create_datetime'] = $now;
        $template['update_datetime'] = $now;

        $this->encode_fields($template);

        if (!$this->db->insert('document_templates', $template)) {
            throw new RuntimeException('Could not insert document template.');
        }

        return $this->db->insert_id();
    }

    protected function update(array $template): int
    {
        $template['update_datetime'] = date('Y-m-d H:i:s');

        $this->encode_fields($template);

        if (!$this->db->update('document_templates', $template, ['id' => $template['id']])) {
            throw new RuntimeException('Could not update document template.');
        }

        return $template['id'];
    }

    public function delete(int $template_id): void
    {
        $this->db->update('document_templates', ['is_active' => 0], ['id' => $template_id]);
    }

    public function query(): CI_DB_query_builder
    {
        return $this->db->from('document_templates');
    }

    public function load(array &$template, array $resources): void
    {
        // No related resources
    }

    private function encode_fields(array &$template): void
    {
        if (isset($template['field_mappings']) && is_array($template['field_mappings'])) {
            $template['field_mappings'] = json_encode($template['field_mappings']);
        }
    }

    private function decode_fields(array &$template): void
    {
        if (!empty($template['field_mappings']) && is_string($template['field_mappings'])) {
            $template['field_mappings'] = json_decode($template['field_mappings'], true) ?? [];
        } elseif (empty($template['field_mappings'])) {
            $template['field_mappings'] = [];
        }
    }
}
