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
 * Documentation entries model.
 *
 * Handles database operations for session notes recorded by providers during client meetings.
 * Each entry belongs to a customer, is authored by a provider, and may optionally link to an appointment.
 * Entries are immutable (no delete) to preserve the audit trail. Edits are tracked in the edit log table.
 *
 * @package Models
 */
class Documentation_entries_model extends EA_Model
{
    protected array $casts = [
        'id' => 'integer',
        'id_users_customer' => 'integer',
        'id_appointments' => 'integer',
        'id_users_provider' => 'integer',
        'is_edited' => 'boolean',
    ];

    protected array $api_resource = [
        'id' => 'id',
        'customerId' => 'id_users_customer',
        'appointmentId' => 'id_appointments',
        'providerId' => 'id_users_provider',
        'sessionSummary' => 'session_summary',
        'isEdited' => 'is_edited',
        'createDatetime' => 'create_datetime',
        'updateDatetime' => 'update_datetime',
    ];

    public function save(array $entry): int
    {
        $this->validate($entry);

        if (empty($entry['id'])) {
            return $this->insert($entry);
        } else {
            return $this->update($entry);
        }
    }

    public function validate(array $entry): void
    {
        if (!empty($entry['id'])) {
            $count = $this->db->get_where('documentation_entries', ['id' => $entry['id']])->num_rows();

            if (!$count) {
                throw new InvalidArgumentException(
                    'The provided documentation entry ID does not exist in the database: ' . $entry['id'],
                );
            }
        }

        if (empty($entry['id'])) {
            if (empty($entry['id_users_customer'])) {
                throw new InvalidArgumentException('The customer ID is required for a documentation entry.');
            }

            if (empty($entry['id_users_provider'])) {
                throw new InvalidArgumentException('The provider ID is required for a documentation entry.');
            }

            if (empty($entry['session_summary'])) {
                throw new InvalidArgumentException('The session summary is required for a documentation entry.');
            }
        }

        if (!empty($entry['id_appointments'])) {
            $count = $this->db->get_where('appointments', ['id' => $entry['id_appointments']])->num_rows();

            if (!$count) {
                throw new InvalidArgumentException(
                    'The provided appointment ID does not exist in the database: ' . $entry['id_appointments'],
                );
            }
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

        $entries = $this->db->get('documentation_entries', $limit, $offset)->result_array();

        foreach ($entries as &$entry) {
            $this->cast($entry);
        }

        return $entries;
    }

    public function find(int $entry_id): array
    {
        $entry = $this->db->get_where('documentation_entries', ['id' => $entry_id])->row_array();

        if (!$entry) {
            throw new InvalidArgumentException(
                'The provided documentation entry ID was not found in the database: ' . $entry_id,
            );
        }

        $this->cast($entry);

        return $entry;
    }

    public function value(int $entry_id, string $field): mixed
    {
        if (empty($field)) {
            throw new InvalidArgumentException('The field argument cannot be empty.');
        }

        if (empty($entry_id)) {
            throw new InvalidArgumentException('The entry ID argument cannot be empty.');
        }

        $query = $this->db->get_where('documentation_entries', ['id' => $entry_id]);

        if (!$query->num_rows()) {
            throw new InvalidArgumentException(
                'The provided documentation entry ID was not found in the database: ' . $entry_id,
            );
        }

        $entry = $query->row_array();

        $this->cast($entry);

        if (!array_key_exists($field, $entry)) {
            throw new InvalidArgumentException('The requested field was not found in the entry data: ' . $field);
        }

        return $entry[$field];
    }

    protected function insert(array $entry): int
    {
        $now = date('Y-m-d H:i:s');

        $entry['create_datetime'] = $now;
        $entry['update_datetime'] = $now;
        $entry['is_edited'] = 0;

        if (!$this->db->insert('documentation_entries', $entry)) {
            throw new RuntimeException('Could not insert documentation entry.');
        }

        return $this->db->insert_id();
    }

    protected function update(array $entry): int
    {
        $entry['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->update('documentation_entries', $entry, ['id' => $entry['id']])) {
            throw new RuntimeException('Could not update documentation entry.');
        }

        return $entry['id'];
    }

    public function search(string $keyword, ?int $limit = null, ?int $offset = null, ?string $order_by = null): array
    {
        $entries = $this->db
            ->select()
            ->from('documentation_entries')
            ->group_start()
            ->like('session_summary', $keyword)
            ->group_end()
            ->limit($limit)
            ->offset($offset)
            ->order_by($order_by !== null ? $this->quote_order_by($order_by) : 'create_datetime DESC')
            ->get()
            ->result_array();

        foreach ($entries as &$entry) {
            $this->cast($entry);
        }

        return $entries;
    }

    public function query(): CI_DB_query_builder
    {
        return $this->db->from('documentation_entries');
    }

    public function log_edit(int $entry_id, int $editor_id, string $field, ?string $old_value, ?string $new_value): void
    {
        $this->db->insert('documentation_edit_log', [
            'id_documentation_entry' => $entry_id,
            'id_users_editor' => $editor_id,
            'field_name' => $field,
            'old_value' => $old_value,
            'new_value' => $new_value,
            'edit_datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_edit_log(int $entry_id): array
    {
        return $this->db
            ->select('documentation_edit_log.*, users.first_name, users.last_name')
            ->from('documentation_edit_log')
            ->join('users', 'users.id = documentation_edit_log.id_users_editor', 'left')
            ->where('id_documentation_entry', $entry_id)
            ->order_by('edit_datetime', 'DESC')
            ->get()
            ->result_array();
    }

    public function load(array &$entry, array $resources): void
    {
        if (in_array('appointment', $resources) && !empty($entry['id_appointments'])) {
            $entry['appointment'] = $this->db
                ->get_where('appointments', ['id' => $entry['id_appointments']])
                ->row_array();
        }

        if (in_array('provider', $resources)) {
            $entry['provider'] = $this->db
                ->get_where('users', ['id' => $entry['id_users_provider']])
                ->row_array();
        }

        if (in_array('customer', $resources)) {
            $entry['customer'] = $this->db
                ->get_where('users', ['id' => $entry['id_users_customer']])
                ->row_array();
        }
    }
}
