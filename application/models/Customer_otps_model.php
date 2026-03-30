<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer_otps_model extends EA_Model
{
    public function generate(int $customer_id, string $email): string
    {
        $this->db->update('customer_otps', ['used' => 1], [
            'customer_id' => $customer_id,
            'used' => 0,
        ]);

        $otp_code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->db->insert('customer_otps', [
            'customer_id' => $customer_id,
            'otp_code' => $otp_code,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'used' => 0,
        ]);

        return $otp_code;
    }

    public function verify(int $customer_id, string $otp_code): bool
    {
        $record = $this->db
            ->select('id')
            ->from('customer_otps')
            ->where('customer_id', $customer_id)
            ->where('otp_code', $otp_code)
            ->where('used', 0)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->row_array();

        if (empty($record)) {
            return false;
        }

        $this->db->update('customer_otps', ['used' => 1], ['id' => $record['id']]);

        return true;
    }
}
