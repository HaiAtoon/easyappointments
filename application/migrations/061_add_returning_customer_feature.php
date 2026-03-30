<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_returning_customer_feature extends EA_Migration
{
    public function up(): void
    {
        // Add returning_customer setting
        $this->db->query("
            INSERT IGNORE INTO settings (`name`, `value`, `create_datetime`, `update_datetime`)
            VALUES ('returning_customer', '0', NOW(), NOW())
        ");

        // Create customer OTPs table
        if (!$this->db->table_exists('customer_otps')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'customer_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'otp_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 6,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 256,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
                'expires_at' => [
                    'type' => 'DATETIME',
                ],
                'used' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key(['customer_id', 'otp_code', 'used']);
            $this->dbforge->create_table('customer_otps', true, ['ENGINE' => 'InnoDB']);
        }

        // Create customer logins table
        if (!$this->db->table_exists('customer_logins')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'customer_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'action' => [
                    'type' => 'ENUM',
                    'constraint' => ['login', 'logout'],
                ],
                'ip_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('customer_id');
            $this->dbforge->create_table('customer_logins', true, ['ENGINE' => 'InnoDB']);
        }
    }

    public function down(): void
    {
        $this->db->query("DELETE FROM settings WHERE `name` = 'returning_customer'");

        if ($this->db->table_exists('customer_otps')) {
            $this->dbforge->drop_table('customer_otps');
        }

        if ($this->db->table_exists('customer_logins')) {
            $this->dbforge->drop_table('customer_logins');
        }
    }
}
