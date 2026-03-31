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
 * Issued documents model.
 *
 * Handles database operations for formal documents (referrals, certificates, letters) generated
 * from documentation entries using .docx templates. Documents are immutable — once created they
 * cannot be updated or deleted, preserving the professional record trail.
 *
 * @package Models
 */
class Issued_documents_model extends EA_Model
{
    protected array $casts = [
        'id' => 'integer',
        'id_documentation_entry' => 'integer',
        'id_users_provider' => 'integer',
    ];

    protected array $api_resource = [
        'id' => 'id',
        'documentationEntryId' => 'id_documentation_entry',
        'documentType' => 'document_type',
        'title' => 'title',
        'content' => 'content',
        'extraFields' => 'extra_fields',
        'providerId' => 'id_users_provider',
        'createDatetime' => 'create_datetime',
    ];

    public function save(array $document): int
    {
        $this->validate($document);

        return $this->insert($document);
    }

    public function validate(array $document): void
    {
        if (empty($document['id_documentation_entry'])) {
            throw new InvalidArgumentException('The documentation entry ID is required.');
        }

        $count = $this->db->get_where('documentation_entries', ['id' => $document['id_documentation_entry']])->num_rows();

        if (!$count) {
            throw new InvalidArgumentException(
                'The provided documentation entry ID does not exist: ' . $document['id_documentation_entry'],
            );
        }

        if (empty($document['document_type'])) {
            throw new InvalidArgumentException('The document type is required.');
        }

        if (empty($document['id_users_provider'])) {
            throw new InvalidArgumentException('The provider ID is required.');
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
            $this->db->order_by('create_datetime', 'DESC');
        }

        $documents = $this->db->get('issued_documents', $limit, $offset)->result_array();

        foreach ($documents as &$document) {
            $this->cast($document);
        }

        return $documents;
    }

    public function find(int $document_id): array
    {
        $document = $this->db->get_where('issued_documents', ['id' => $document_id])->row_array();

        if (!$document) {
            throw new InvalidArgumentException(
                'The provided issued document ID was not found: ' . $document_id,
            );
        }

        $this->cast($document);

        return $document;
    }

    protected function insert(array $document): int
    {
        $document['create_datetime'] = date('Y-m-d H:i:s');

        if (!empty($document['extra_fields']) && is_array($document['extra_fields'])) {
            foreach ($document['extra_fields'] as $key => &$value) {
                if (is_string($value) && $value !== strip_tags($value)) {
                    $value = pure_html($value);
                }
            }
            unset($value);

            $document['extra_fields'] = json_encode($document['extra_fields']);
        }

        if (!empty($document['content']) && $document['content'] !== strip_tags($document['content'])) {
            $document['content'] = pure_html($document['content']);
        }

        if (!$this->db->insert('issued_documents', $document)) {
            throw new RuntimeException('Could not insert issued document.');
        }

        return $this->db->insert_id();
    }

    public function query(): CI_DB_query_builder
    {
        return $this->db->from('issued_documents');
    }

    public function load(array &$document, array $resources): void
    {
        if (in_array('provider', $resources)) {
            $document['provider'] = $this->db
                ->get_where('users', ['id' => $document['id_users_provider']])
                ->row_array();
        }

        if (in_array('entry', $resources)) {
            $document['entry'] = $this->db
                ->get_where('documentation_entries', ['id' => $document['id_documentation_entry']])
                ->row_array();
        }
    }
}
