<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_audit_logs_table extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->table_exists('audit_logs')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'user_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'action' => ['type' => 'VARCHAR', 'constraint' => 50],
                'resource_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'resource_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'patient_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'ip_address' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'details' => ['type' => 'TEXT', 'null' => true],
                'result' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'success'],
                'create_datetime' => ['type' => 'DATETIME'],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('user_id');
            $this->dbforge->add_key('patient_id');
            $this->dbforge->add_key('action');
            $this->dbforge->add_key('create_datetime');

            $this->dbforge->create_table('audit_logs', true, ['engine' => 'InnoDB']);
        }
    }

    public function down(): void
    {
        if ($this->db->table_exists('audit_logs')) {
            $this->dbforge->drop_table('audit_logs');
        }
    }
}
