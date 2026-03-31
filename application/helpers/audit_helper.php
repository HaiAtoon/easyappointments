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

if (!function_exists('audit_log')) {
    /**
     * Write an entry to the audit log.
     *
     * @param string $action Action performed (e.g., 'LOGIN_SUCCESS', 'VIEW_DOCUMENTATION', 'GENERATE_PDF').
     * @param string|null $resource_type Resource type (e.g., 'documentation_entries', 'issued_documents').
     * @param int|null $resource_id Resource ID.
     * @param int|null $patient_id Patient/customer ID whose data was accessed.
     * @param string $result 'success' or 'failure'.
     * @param string|null $details Additional context.
     */
    function audit_log(
        string $action,
        ?string $resource_type = null,
        ?int $resource_id = null,
        ?int $patient_id = null,
        string $result = 'success',
        ?string $details = null,
    ): void {
        try {
            $CI = &get_instance();

            $CI->db->insert('audit_logs', [
                'user_id' => session('user_id'),
                'action' => $action,
                'resource_type' => $resource_type,
                'resource_id' => $resource_id,
                'patient_id' => $patient_id,
                'ip_address' => $CI->input->ip_address(),
                'details' => $details,
                'result' => $result,
                'create_datetime' => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Audit log failed: ' . $e->getMessage());
        }
    }
}
