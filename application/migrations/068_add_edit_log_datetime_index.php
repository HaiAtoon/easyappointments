<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_edit_log_datetime_index extends EA_Migration
{
    public function up(): void
    {
        $this->db->query(
            'ALTER TABLE `' . $this->db->dbprefix('documentation_edit_log') . '` ADD INDEX `idx_edit_datetime` (`edit_datetime`)',
        );
    }

    public function down(): void
    {
        $this->db->query(
            'ALTER TABLE `' . $this->db->dbprefix('documentation_edit_log') . '` DROP INDEX `idx_edit_datetime`',
        );
    }
}
