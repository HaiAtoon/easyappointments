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
 * Shared configuration and helper methods used by both Pdf_generator and Document_generator
 * to avoid duplication of mPDF setup, encryption logic, and temp directory management.
 *
 * @package Libraries
 */
class Pdf_utils
{
    const TEMP_DIR = 'storage/cache/mpdf/';

    /**
     * Create a configured mPDF instance with UTF-8, A4 format, and optional RTL support.
     *
     * @return Mpdf
     */
    public static function create_mpdf(): Mpdf
    {
        $temp_dir = FCPATH . self::TEMP_DIR;

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
     * Apply password encryption to an mPDF instance using the customer's ID number or phone.
     *
     * @param Mpdf $mpdf The mPDF instance to protect.
     * @param array $customer Customer record with id_number and/or phone_number.
     *
     * @return void
     */
    public static function encrypt_for_customer(Mpdf $mpdf, array $customer): void
    {
        $password = self::get_pdf_password($customer);

        if ($password) {
            $mpdf->SetProtection(['copy', 'print'], $password, $password);
        }
    }

    /**
     * Determine the PDF password for a customer (ID number, fallback to phone).
     *
     * @param array $customer Customer record.
     *
     * @return string Password string, or empty if no identifier available.
     */
    public static function get_pdf_password(array $customer): string
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
     * Determine which field is used as the PDF password.
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
