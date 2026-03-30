<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer_logins_model extends EA_Model
{
    public function log(int $customer_id, string $action, string $ip_address): void
    {
        $this->db->insert('customer_logins', [
            'customer_id' => $customer_id,
            'action' => $action,
            'ip_address' => $ip_address,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_by_customer(int $customer_id, int $limit = 50): array
    {
        return $this->db
            ->select('*')
            ->from('customer_logins')
            ->where('customer_id', $customer_id)
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }
}
