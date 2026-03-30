<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_cancellation_fields_to_appointments extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->field_exists('cancellation_reason', 'appointments')) {
            $fields = [
                'cancellation_reason' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'status',
                ],
                'cancelled_by' => [
                    'type' => 'VARCHAR',
                    'constraint' => 256,
                    'null' => true,
                    'after' => 'cancellation_reason',
                ],
                'cancelled_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'cancelled_by',
                ],
            ];

            $this->dbforge->add_column('appointments', $fields);
        }
    }

    public function down(): void
    {
        foreach (['cancellation_reason', 'cancelled_by', 'cancelled_at'] as $field) {
            if ($this->db->field_exists($field, 'appointments')) {
                $this->dbforge->drop_column('appointments', $field);
            }
        }
    }
}
