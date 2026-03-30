<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer_portal extends EA_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('customers_model');
        $this->load->model('customer_otps_model');
        $this->load->model('customer_logins_model');
        $this->load->model('appointments_model');
        $this->load->model('services_model');
        $this->load->model('providers_model');
        $this->load->library('email_messages');
    }

    public function index(): void
    {
        if (session('customer_id')) {
            redirect('customer/dashboard');
            return;
        }

        $company_name = setting('company_name');

        html_vars([
            'page_title' => lang('customer_area'),
            'company_name' => $company_name,
        ]);

        $this->load->view('pages/customer_login');
    }

    public function dashboard(): void
    {
        $customer_id = session('customer_id');

        if (!$customer_id) {
            redirect('customer');
            return;
        }

        $customer = $this->customers_model->find($customer_id);

        script_vars([
            'date_format' => setting('date_format'),
            'time_format' => setting('time_format'),
            'customer_id' => (int) $customer_id,
        ]);

        html_vars([
            'page_title' => lang('customer_dashboard'),
            'company_name' => setting('company_name'),
            'customer' => $customer,
        ]);

        $this->load->view('pages/customer_dashboard');
    }

    public function send_otp(): void
    {
        try {
            $id_number = request('id_number');

            if (empty($id_number)) {
                throw new InvalidArgumentException('No ID number provided.');
            }

            $customer = $this->customers_model->find_by_id_number($id_number);

            if (!$customer) {
                json_response(['found' => false]);
                return;
            }

            $otp_code = $this->customer_otps_model->generate((int) $customer['id'], $customer['email']);

            $company_color = setting('company_color');

            $settings = [
                'company_name' => setting('company_name'),
                'company_link' => setting('company_link'),
                'company_email' => setting('company_email'),
                'company_color' => !empty($company_color) && $company_color != DEFAULT_COMPANY_COLOR ? $company_color : null,
            ];

            $this->email_messages->send_customer_otp($customer, $settings, $otp_code);

            $email = $customer['email'];
            $masked = substr($email, 0, 2) . str_repeat('*', strpos($email, '@') - 2) . substr($email, strpos($email, '@'));

            json_response([
                'found' => true,
                'customer_id' => (int) $customer['id'],
                'masked_email' => $masked,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function verify_otp(): void
    {
        try {
            $customer_id = (int) request('customer_id');
            $otp_code = request('otp_code');

            if (empty($customer_id) || empty($otp_code)) {
                throw new InvalidArgumentException('Customer ID and OTP code are required.');
            }

            $valid = $this->customer_otps_model->verify($customer_id, $otp_code);

            if ($valid) {
                $customer = $this->customers_model->find($customer_id);

                session([
                    'customer_id' => (int) $customer['id'],
                    'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
                ]);

                $this->session->sess_regenerate();

                $this->customer_logins_model->log($customer_id, 'login', $this->input->ip_address());

                json_response(['valid' => true]);
            } else {
                json_response(['valid' => false]);
            }
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    public function logout(): void
    {
        $customer_id = session('customer_id');

        if ($customer_id) {
            $this->customer_logins_model->log((int) $customer_id, 'logout', $this->input->ip_address());
        }

        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('customer_name');

        html_vars([
            'page_title' => lang('customer_logged_out'),
            'company_name' => setting('company_name'),
        ]);

        $this->load->view('pages/customer_logout');
    }

    public function get_appointments(): void
    {
        try {
            $customer_id = session('customer_id');

            if (!$customer_id) {
                throw new RuntimeException('Not authenticated.');
            }

            $now = date('Y-m-d H:i:s');

            $prefix = $this->db->dbprefix;

            $upcoming = $this->db
                ->select("{$prefix}appointments.*, {$prefix}services.name as service_name, CONCAT({$prefix}users.first_name, ' ', {$prefix}users.last_name) as provider_name", false)
                ->from('appointments')
                ->join('services', 'services.id = appointments.id_services', 'left')
                ->join('users', 'users.id = appointments.id_users_provider', 'left')
                ->where('appointments.id_users_customer', $customer_id)
                ->where('appointments.start_datetime >=', $now)
                ->where('appointments.is_unavailability', 0)
                ->where("(appointments.status IS NULL OR appointments.status != 'Cancelled')")
                ->order_by('appointments.start_datetime', 'ASC')
                ->get()
                ->result_array();

            $past = $this->db
                ->select("{$prefix}appointments.*, {$prefix}services.name as service_name, CONCAT({$prefix}users.first_name, ' ', {$prefix}users.last_name) as provider_name", false)
                ->from('appointments')
                ->join('services', 'services.id = appointments.id_services', 'left')
                ->join('users', 'users.id = appointments.id_users_provider', 'left')
                ->where('appointments.id_users_customer', $customer_id)
                ->where('appointments.is_unavailability', 0)
                ->group_start()
                    ->where('appointments.start_datetime <', $now)
                    ->or_where('appointments.status', 'Cancelled')
                ->group_end()
                ->order_by('appointments.start_datetime', 'DESC')
                ->limit(50)
                ->get()
                ->result_array();

            json_response([
                'upcoming' => $upcoming,
                'past' => $past,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }
}
