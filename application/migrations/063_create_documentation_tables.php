<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_documentation_tables extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->table_exists('documentation_entries')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'id_users_customer' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'id_appointments' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'id_users_provider' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'session_summary' => [
                    'type' => 'LONGTEXT',
                ],
                'is_edited' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
                'create_datetime' => [
                    'type' => 'DATETIME',
                ],
                'update_datetime' => [
                    'type' => 'DATETIME',
                ],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('id_users_customer');
            $this->dbforge->add_key('id_appointments');

            $this->dbforge->create_table('documentation_entries', true, ['engine' => 'InnoDB']);
        }

        if (!$this->db->table_exists('documentation_edit_log')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'id_documentation_entry' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'id_users_editor' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'field_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'old_value' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
                'new_value' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
                'edit_datetime' => [
                    'type' => 'DATETIME',
                ],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('id_documentation_entry');

            $this->dbforge->create_table('documentation_edit_log', true, ['engine' => 'InnoDB']);
        }
    }

    public function down(): void
    {
        if ($this->db->table_exists('documentation_edit_log')) {
            $this->dbforge->drop_table('documentation_edit_log');
        }

        if ($this->db->table_exists('documentation_entries')) {
            $this->dbforge->drop_table('documentation_entries');
        }
    }
}
