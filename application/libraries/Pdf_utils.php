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

use Mpdf\Mpdf;

/**
 * PDF utilities library.
 *
 * Shared configuration and helper methods used by both Pdf_generator and Document_generator.
 *
 * @package Libraries
 */
class Pdf_utils
{
    /**
     * Create a configured mPDF instance with UTF-8, A4 format, and optional RTL support.
     *
     * @return Mpdf
     */
    public static function create_mpdf(): Mpdf
    {
        $temp_dir = FCPATH . STORAGE_MPDF_TEMP;

        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0775, true);
        }

        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'tempDir' => $temp_dir,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 15,
        ];

        if (is_rtl()) {
            $config['directionality'] = 'rtl';
        }

        return new Mpdf($config);
    }

    /**
     * Generate a random PDF password (16-character hex string).
     *
     * @return string
     */
    public static function generate_password(): string
    {
        return bin2hex(random_bytes(8));
    }

    /**
     * Apply password encryption to an mPDF instance.
     *
     * @param Mpdf $mpdf The mPDF instance to protect.
     * @param string $password The password to use.
     *
     * @return void
     */
    public static function encrypt_pdf(Mpdf $mpdf, string $password): void
    {
        if ($password) {
            $owner_password = bin2hex(random_bytes(16));
            $mpdf->SetProtection(['copy', 'print'], $password, $owner_password);
        }
    }

    /**
     * Apply password encryption using a customer's identifier (legacy, for entry PDFs).
     *
     * @param Mpdf $mpdf The mPDF instance to protect.
     * @param array $customer Customer record.
     *
     * @return void
     */
    public static function encrypt_for_customer(Mpdf $mpdf, array $customer): void
    {
        $password = self::get_customer_identifier($customer);

        if ($password) {
            self::encrypt_pdf($mpdf, $password);
        }
    }

    /**
     * Get a customer's primary identifier (ID number or phone) for legacy password use.
     *
     * @param array $customer Customer record.
     *
     * @return string Identifier string, or empty if unavailable.
     */
    public static function get_customer_identifier(array $customer): string
    {
        if (!empty($customer['id_number'])) {
            return $customer['id_number'];
        }

        if (!empty($customer['phone_number'])) {
            return $customer['phone_number'];
        }

        return '';
    }

    /**
     * Determine which field is used as the customer identifier.
     *
     * @param array $customer Customer record.
     *
     * @return string 'id_number' or 'phone_number'.
     */
    public static function get_password_field(array $customer): string
    {
        return !empty($customer['id_number']) ? 'id_number' : 'phone_number';
    }
}
