<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/Field_encryption.php';

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

if (!function_exists('get_encryption_key')) {
    function get_encryption_key(): string
    {
        if (!class_exists('Config', false)) {
            $config_path = dirname(__DIR__, 2) . '/config.php';

            if (file_exists($config_path)) {
                require_once $config_path;
            }
        }

        if (!class_exists('Config', false) || !defined('Config::ENCRYPTION_KEY')) {
            return '';
        }

        $key = Config::ENCRYPTION_KEY;

        return (!empty($key) && strlen($key) >= 64) ? $key : '';
    }
}

if (!function_exists('field_encrypt')) {
    function field_encrypt(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        $key = get_encryption_key();

        if (empty($key)) {
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

if (!function_exists('field_hash')) {
    function field_hash(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        $key = get_encryption_key();

        if (empty($key)) {
            return hash('sha256', $value);
        }

        return hash_hmac('sha256', $value, $key);
    }
}

if (!function_exists('field_decrypt')) {
    function field_decrypt(?string $value): ?string
    {
        if (empty($value) || !str_starts_with($value, 'enc:')) {
            return $value;
        }

        $key = get_encryption_key();

        if (empty($key)) {
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
