<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_issued_documents_table extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->table_exists('issued_documents')) {
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
                'document_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 256,
                ],
                'content' => [
                    'type' => 'LONGTEXT',
                ],
                'extra_fields' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'id_users_provider' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'create_datetime' => [
                    'type' => 'DATETIME',
                ],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('id_documentation_entry');

            $this->dbforge->create_table('issued_documents', true, ['engine' => 'InnoDB']);
        }
    }

    public function down(): void
    {
        if ($this->db->table_exists('issued_documents')) {
            $this->dbforge->drop_table('issued_documents');
        }
    }
}
