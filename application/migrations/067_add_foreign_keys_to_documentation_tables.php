<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_foreign_keys_to_documentation_tables extends EA_Migration
{
    public function up(): void
    {
        $prefix = $this->db->dbprefix;

        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` MODIFY `id_users_customer` INT(11) NOT NULL");
        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` MODIFY `id_users_provider` INT(11) NOT NULL");
        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` MODIFY `id_appointments` INT(11) NULL");
        $this->db->query("ALTER TABLE `{$prefix}documentation_edit_log` MODIFY `id_users_editor` INT(11) NOT NULL");
        $this->db->query("ALTER TABLE `{$prefix}issued_documents` MODIFY `id_users_provider` INT(11) NOT NULL");

        // Clean orphaned references
        $this->db->query("UPDATE `{$prefix}documentation_entries` SET `id_appointments` = NULL WHERE `id_appointments` IS NOT NULL AND `id_appointments` NOT IN (SELECT `id` FROM `{$prefix}appointments`)");
        $this->db->query("DELETE FROM `{$prefix}documentation_edit_log` WHERE `id_documentation_entry` NOT IN (SELECT `id` FROM `{$prefix}documentation_entries`)");
        $this->db->query("DELETE FROM `{$prefix}issued_documents` WHERE `id_documentation_entry` NOT IN (SELECT `id` FROM `{$prefix}documentation_entries`)");

        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` ADD CONSTRAINT `fk_doc_entries_customer` FOREIGN KEY (`id_users_customer`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` ADD CONSTRAINT `fk_doc_entries_provider` FOREIGN KEY (`id_users_provider`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `{$prefix}documentation_entries` ADD CONSTRAINT `fk_doc_entries_appointment` FOREIGN KEY (`id_appointments`) REFERENCES `{$prefix}appointments` (`id`) ON DELETE SET NULL");

        $this->db->query("ALTER TABLE `{$prefix}documentation_edit_log` ADD CONSTRAINT `fk_edit_log_entry` FOREIGN KEY (`id_documentation_entry`) REFERENCES `{$prefix}documentation_entries` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `{$prefix}documentation_edit_log` ADD CONSTRAINT `fk_edit_log_editor` FOREIGN KEY (`id_users_editor`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE");

        $this->db->query("ALTER TABLE `{$prefix}issued_documents` ADD CONSTRAINT `fk_issued_doc_entry` FOREIGN KEY (`id_documentation_entry`) REFERENCES `{$prefix}documentation_entries` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `{$prefix}issued_documents` ADD CONSTRAINT `fk_issued_doc_provider` FOREIGN KEY (`id_users_provider`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE");
    }

    public function down(): void
    {
        $prefix = $this->db->dbprefix;

        $fks = [
            'documentation_entries' => ['fk_doc_entries_customer', 'fk_doc_entries_provider', 'fk_doc_entries_appointment'],
            'documentation_edit_log' => ['fk_edit_log_entry', 'fk_edit_log_editor'],
            'issued_documents' => ['fk_issued_doc_entry', 'fk_issued_doc_provider'],
        ];

        foreach ($fks as $table => $constraints) {
            foreach ($constraints as $fk) {
                $this->db->query("ALTER TABLE `{$prefix}{$table}` DROP FOREIGN KEY `{$fk}`");
            }
        }
    }
}
