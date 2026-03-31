<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

if (!function_exists('field_encrypt')) {
    /**
     * Encrypt a value using AES-256-CBC with the application encryption key.
     *
     * Returns the IV + ciphertext as a base64-encoded string prefixed with "enc:".
     * If the value is empty or encryption is not configured, returns the value as-is.
     *
     * @param string|null $value Plaintext value.
     *
     * @return string|null Encrypted value or original if empty.
     */
    function field_encrypt(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        $key = defined('Config::ENCRYPTION_KEY') ? Config::ENCRYPTION_KEY : '';

        if (empty($key) || strlen($key) < 64) {
            return $value;
        }

        $key_bytes = hex2bin($key);
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key_bytes, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            return $value;
        }

        return 'enc:' . base64_encode($iv . $encrypted);
    }
}

if (!function_exists('field_decrypt')) {
    /**
     * Decrypt a value encrypted by field_encrypt().
     *
     * Detects the "enc:" prefix to determine if decryption is needed.
     * Returns the original value if not encrypted or if decryption fails.
     *
     * @param string|null $value Encrypted value (with "enc:" prefix) or plaintext.
     *
     * @return string|null Decrypted plaintext or original value.
     */
    function field_decrypt(?string $value): ?string
    {
        if (empty($value) || !str_starts_with($value, 'enc:')) {
            return $value;
        }

        $key = defined('Config::ENCRYPTION_KEY') ? Config::ENCRYPTION_KEY : '';

        if (empty($key) || strlen($key) < 64) {
            return $value;
        }

        $key_bytes = hex2bin($key);
        $data = base64_decode(substr($value, 4));

        if ($data === false || strlen($data) < 17) {
            return $value;
        }

        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);

        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key_bytes, OPENSSL_RAW_DATA, $iv);

        return $decrypted !== false ? $decrypted : $value;
    }
}
