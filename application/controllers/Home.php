<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Home extends My_controller
{
    //var $max_attempt = 5; // Maximum login attempt.
    //var $expire = 15; // session expiration after inactivity, In minutes.

    public function __construct()
    {
        parent::__construct();

        $this->load->model('home_mdl');
    }

    public function index()
    {
        $this->is_allowed();
        $data['active_nav'] = 'home';  // This Must Match with tsk_name @ Tbl: tasks
        $data['active_subnav'] = ''; // This Must Match with tsk_name @ Tbl: tasks
        // $data['page_head'] = 'home';
        // $data['icon'] = $this->tasks->get_icon(1);

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('home/index', $data);
    }

    function logout()
    {
        $this->usr_id = $this->login->is_logged_in($this->remember_me);

        $this->session->keep_flashdata('permission_errors');
        $this->session->unset_userdata('user');
        $uri2 = $this->uri->segment(2);
        // Remember me should be deleted.
        if ($uri2 == 'ok') {
            $this->login->forgot_me($this->usr_id, $this->remember_me);
        }

        $this->redirect_me('login');
    }

    function login()
    {
        //Login Method:
        //  1 => Saved Password
        //  2 => OTP
        $login_method = $this->settings->get_settings_value($this->clnt_id, 16);

        if ($login_method == 1)
            $this->login_by_saved_password();
        else
            $this->login_by_otp_step1();
    }

    // Showing Username/email/mobile entering view
    function login_by_otp_step1()
    {
        $data['permission_errors'] = $this->session->flashdata('permission_errors');
        $data['errors'] = '';

        // Deleting previous Username if any
        $this->session->unset_userdata('step1');

        $this->form_validation->set_message('required', 'The %s  field is required.');
        $this->form_validation->set_error_delimiters('<span id="login-error" style="display: block;" class="error invalid-feedback">', '</span>');

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        if (!$this->form_validation->run()) {
            $data['errors'] = validation_errors();
            $this->_render_page("home/login_otp_1", $data);
            return;
        }

        $this->session->step1 =  $this->input->post('username');

        // Redirecting to OTP Section
        redirect("login/otp"); // "login/otp" has been Routed to "home/login_by_otp_step2"
        return;
    }

    // Showing OTP entering view
    // "login/otp" has been Routed to this function
    function login_by_otp_step2()
    {
        $data['permission_errors'] = $this->session->flashdata('permission_errors');
        $data['errors'] = '';

        $this->form_validation->set_message('required', 'The %s  field is required.');
        $this->form_validation->set_error_delimiters('<span id="login-error" style="display: block;" class="error invalid-feedback">', '</span>');

        $this->form_validation->set_rules('otp', 'OTP', 'required|trim|callback_check_user|callback_check_otp');

        if (!$this->form_validation->run()) {

            $data['errors'] = validation_errors();
            $this->_render_page("home/login_otp_2", $data);
            return;
        }
        $unm = $this->session->step1;
        $this->usr_id = $this->login->get_user_id($unm);

        $this->session->unset_userdata('step1');

        // Making the user "Logged In".
        make_logged_in($this->usr_id, $unm, '');

        // Redirecting to home
        redirect();
    }

    function check_otp($val)
    {

        // This should check first before any database query run.
        if ($c = check_db_chars($val)) {
            $this->form_validation->set_message('check_otp', "Invalid Password");
            return FALSE;
        }

        $unm = $this->session->step1;
        $user_id = $this->login->get_user_id($unm);

        //Login Method:
        //  1 => Saved Password
        //  2 => OTP
        $login_method = $this->settings->get_settings_value($this->clnt_id, 16);

        // Matching password
        if (($login_method == 2) && (!$this->login->check_otp($user_id, $this->input->post('otp')))) {
            $this->form_validation->set_message('check_otp', "Incurrect OTP");

            // Incrementing the attempt. 
            $this->login->increment_attempt($user_id);
            return FALSE;
        }
        return true;
    }


    function login_by_saved_password()
    {
        $data['permission_errors'] = $this->session->flashdata('permission_errors');
        $data['errors'] = '';

        $this->form_validation->set_rules('username', 'Username', 'required|trim|callback_check_user');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|callback_check_password');
        $this->form_validation->set_message('required', 'This Field Is Required.');
        $this->form_validation->set_error_delimiters('<span id="login-error" style="display: block;" class="error invalid-feedback">', '</span>');

        if (!$this->form_validation->run()) {
            $data['errors'] = validation_errors();
            $this->_render_page("home/login_pwd", $data);
            return;
        }

        $unm = $this->input->post('username');
        $this->usr_id = $this->login->get_user_id($unm);
        $remember = $this->input->post('remember_me');

        // Making the user "Logged In".
        make_logged_in($this->usr_id, $unm, $remember);

        // Redirecting to home
        redirect();
    }


    function check_user($val = "")
    {
        //Login Method:
        //  1 => Saved Password
        //  2 => OTP
        $login_method = $this->settings->get_settings_value($this->clnt_id, 16);

        if ($login_method == 2) {
            // Reading Username 
            $val  = $this->session->step1;
            if (!$val) {
                redirect("home/login");
                return;
            }
        }
        // This should check first before any database query run.
        if ($c = check_db_chars($val)) {
            $this->form_validation->set_message('check_user', "Invalid User");
            return FALSE;
        }

        $user_id = '';

        // You should check check_db_chars() before checking user in database.
        if (!$user_id = $this->login->get_user_id($val)) {
            $this->form_validation->set_message('check_user', 'Invalid User');
            return FALSE;
        }

        // Is active user
        if (!$this->login->is_active_user($user_id)) {
            $this->form_validation->set_message('check_user', 'Inactive User');
            return FALSE;
        }

        // Is locked. It should check before checking attempt
        if ($lock_time = $this->login->is_locked($user_id)) {
            $this->form_validation->set_message('check_user', "Locked up to " . date('h:i A', strtotime($lock_time)));

            // Incrementing the attempt. 
            $this->login->increment_attempt($user_id);

            return FALSE;
        } else {

            // Reset attempt to 0 iff more than allowed attempts over.
            // And unlock if attempt reseted.
            $this->login->reset_attempt($user_id, '0');
        }


        // Check attempts. It should check after checked Lock.
        if ($this->login->check_attempts($user_id)) {
            $this->form_validation->set_message('check_user', 'Increment login attempt');

            // Increment attempt round
            $this->login->increment_attempt_round($user_id);

            // If maximum rounds of attempts over
            if ($this->login->check_attempts_round($user_id)) {
                // Deactivating the user
                $this->login->deactivate_user($user_id);
            } else {
                // Lock the user for some times;
                $this->login->lock_user($user_id);
            }

            return FALSE;
        }

        // Matching password
        if (($login_method == 1) && (!$this->login->check_password($user_id, $this->input->post('password')))) {
            $this->form_validation->set_message('check_user', "Invalid User");

            // Incrementing the attempt. 
            $this->login->increment_attempt($user_id);
            return FALSE;
        }

        return true;
    }

    function check_password($val)
    {
        // This should check first before any database query run.
        if ($c = check_db_chars($val)) {
            $this->form_validation->set_message('check_password', "Invalid Password");
            return FALSE;
        }

        return true;
    }
}
