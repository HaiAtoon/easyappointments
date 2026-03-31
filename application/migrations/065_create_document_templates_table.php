<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_document_templates_table extends EA_Migration
{
    public function up(): void
    {
        if (!$this->db->table_exists('document_templates')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 256,
                ],
                'slug' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'fields' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'default_content' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ],
                'sort_order' => [
                    'type' => 'INT',
                    'constraint' => 11,
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

            $this->dbforge->create_table('document_templates', true, ['engine' => 'InnoDB']);

            $this->db->query('ALTER TABLE `' . $this->db->dbprefix('document_templates') . '` ADD UNIQUE KEY `uk_template_slug` (`slug`)');

            $now = date('Y-m-d H:i:s');

            $this->db->insert('document_templates', [
                'name' => 'General Letter',
                'slug' => 'general_letter',
                'fields' => '[]',
                'is_active' => 1,
                'sort_order' => 1,
                'create_datetime' => $now,
                'update_datetime' => $now,
            ]);

            $this->db->insert('document_templates', [
                'name' => 'Referral',
                'slug' => 'referral',
                'fields' => json_encode([['name' => 'destination', 'label' => 'Destination', 'type' => 'text']]),
                'is_active' => 1,
                'sort_order' => 2,
                'create_datetime' => $now,
                'update_datetime' => $now,
            ]);

            $this->db->insert('document_templates', [
                'name' => 'Certificate',
                'slug' => 'certificate',
                'fields' => json_encode([['name' => 'purpose', 'label' => 'Purpose', 'type' => 'text']]),
                'is_active' => 1,
                'sort_order' => 3,
                'create_datetime' => $now,
                'update_datetime' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->table_exists('document_templates')) {
            $this->dbforge->drop_table('document_templates');
        }
    }
}
