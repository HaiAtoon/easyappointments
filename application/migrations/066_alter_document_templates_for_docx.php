<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_document_templates_for_docx extends EA_Migration
{
    public function up(): void
    {
        if ($this->db->field_exists('fields', 'document_templates')) {
            $this->dbforge->drop_column('document_templates', 'fields');
        }

        if ($this->db->field_exists('default_content', 'document_templates')) {
            $this->dbforge->drop_column('document_templates', 'default_content');
        }

        if (!$this->db->field_exists('file_path', 'document_templates')) {
            $this->dbforge->add_column('document_templates', [
                'file_path' => [
                    'type' => 'VARCHAR',
                    'constraint' => 512,
                    'null' => true,
                    'after' => 'slug',
                ],
            ]);
        }

        if (!$this->db->field_exists('field_mappings', 'document_templates')) {
            $this->dbforge->add_column('document_templates', [
                'field_mappings' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'file_path',
                ],
            ]);
        }

        $this->db->update('document_templates', ['field_mappings' => '[]']);
    }

    public function down(): void
    {
        if ($this->db->field_exists('file_path', 'document_templates')) {
            $this->dbforge->drop_column('document_templates', 'file_path');
        }

        if ($this->db->field_exists('field_mappings', 'document_templates')) {
            $this->dbforge->drop_column('document_templates', 'field_mappings');
        }

        if (!$this->db->field_exists('fields', 'document_templates')) {
            $this->dbforge->add_column('document_templates', [
                'fields' => ['type' => 'TEXT', 'null' => true, 'after' => 'slug'],
            ]);
        }

        if (!$this->db->field_exists('default_content', 'document_templates')) {
            $this->dbforge->add_column('document_templates', [
                'default_content' => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'fields'],
            ]);
        }
    }
}
