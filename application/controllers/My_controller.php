<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_controller extends CI_Controller
{
    public $clnt_id;
    public $cls;
    public $func;
    public $clsfunc;
    public $master_key;
    public $remember_me;
    public $usr_id;
    public $usr_mbrtp;      // User member type
    public $mbr_name;      // User's Name
    public $mbr_date;      // User's Date
    public $usr_grp_opt;      // User's Name
    public $usr_type;       // 1 => Developer, 2 => Default Admin, 3 => All Others
    public $is_developer;   // Developer:     usr_type = 1
    public $is_admin;       // Default Admin: usr_type = 2
    public $is_other;       // All Others:    usr_type = 3
    public $table;
    public $logo_xs;
    public $logo_xs_base64;

    public function __construct()
    {
        //exit("Do 'client settings', 'user tasks', and Set db file with newly added '_fk_clients' field");
        parent::__construct();

        $this->clnt_id = 1;

        if (!$this->clnt_id)
            exit("Client not mensioned");

        $this->cls = $this->uri->segment(1);
        $this->func = $this->uri->segment(2);
        $this->clsfunc = $this->cls . '/' . $this->func;
        $this->master_key = 13; // For password encription. It should be a number
        $this->remember_me = 'remember';
        $this->table = '';

        // If any table missed in database, creating it.
        if ($this->missedTables())
            exit;

        $this->load->model('login_mdl', 'login', array('place' => 'kavanur'));
        $this->load->model('groups_mdl', 'groups');
        $this->load->model('users_mdl', 'users');
        $this->load->model('members_mdl', 'members');
        $this->load->model('tasks_mdl', 'tasks');
        $this->load->model('versions_mdl', 'versions');
        $this->load->model('settings_mdl', 'settings');
        $this->load->model('clients_mdl', 'clients');
        $this->load->model('client_settings_mdl', 'client_settings');
        $this->load->model("user_tasks_mdl", "user_tasks");

        $this->form_validation->set_message('required', 'This Field Is Required');
        $this->form_validation->set_error_delimiters('<div class="form-validation error">', '</div>');

        // Setting timezone
        date_default_timezone_set('Asia/Kolkata');

        $this->logo_xs = 'dependencies/images/logo/logo5-xs.png';

        // If logo image not exist. This image is used to add logo in PRINT, PDF ect also.
        if (!file_exists($this->logo_xs))
            exit("My_controller: Logo not found - " . $this->logo_xs);

        // Get the image as string and convert into  base64 string 
        $this->logo_xs_base64 = base64_encode(file_get_contents(base_url($this->logo_xs)));
    }

    function required($v)
    {
        if (!$v) {
            $this->form_validation->set_message('required', "This Field Is Required");
            return FALSE;
        }
        return TRUE;
    }

    // Callback
    function isUnique($val, $params)
    {
        list($key, $fld) = explode('||', $params);
        $id = $this->input->post($key);
        $unique[$fld] = strtoupper($val);
        $modal = $this->table;
        if ($val && $this->$modal->is_exist($unique, $id)) {
            $this->form_validation->set_message('isUnique', 'The key already exists');
            return FALSE;
        }
        return TRUE;
    }

    // Callback
    function isUnique2($val, $params)
    {
        list($key, $fld) = explode('||', $params);
        $id = $this->input->post($key);
        $unique[$fld] = $val;
        $modal = $this->table;
        if ($val && $this->$modal->is_exist($unique, $id)) {
            $this->form_validation->set_message('isUnique2', 'The %s already exists');
            return FALSE;
        }
        return TRUE;
    }

    /**
     *  Determining is the logged in user allowed to go forward with the current action.
     */
    function is_allowed()
    {
        // First Step: taking user_id by checking is logged in.
        //Checking is logged in. It will check also is the user under the client.
        if (!$this->usr_id = $this->login->is_logged_in($this->remember_me)) {
            $this->session->set_flashdata('permission_errors', 'Please Login');
            if ($this->input->is_ajax_request()) {
                echo json_encode("Logged Out");
                exit();
            }

            $this->redirect_me("logout");
        }

        $mbr_dt = $this->users->get_member_details($this->usr_id, array('mbr_name', 'mbr_date', 'mbr_fk_member_types')); // User member type
        $this->usr_mbrtp = $mbr_dt['mbr_fk_member_types']; // User member type
        $this->mbr_name = $mbr_dt['mbr_name'];
        $this->mbr_date = $mbr_dt['mbr_date'];
        $this->is_developer = FALSE;   // usr_type = 1
        $this->is_admin = FALSE;       // usr_type = 2
        $this->is_other = FALSE;       //  usr_type = 3
        $this->usr_type = $this->users->get_field_by_id($this->usr_id, 'usr_type');
        if ($this->usr_type == 1)
            $this->is_developer = TRUE;
        else if ($this->usr_type == 2)
            $this->is_admin = TRUE;
        else if ($this->usr_type == 3)
            $this->is_other = TRUE;
        else {
            $this->session->set_flashdata('permission_errors', 'Unknown User Type');
            $this->redirect_me("logout");
        }

        $this->usr_grp_opt = $this->users->get_user_groups($this->usr_id);


        // if permision not set for UPLOAD_DIR. it should be 777 and its curresponding value is 16895
        // if (fileperms(UPLOAD_DIR) != 16895)
        //     $errors[] = "Permission not set for upload directory";


        return true;
    }


    // If any table missed in database, creating it.
    function missedTables()
    {
        if ($missed = $this->my_mdl->checkForTables(getTables('All'))) {
            // Setting messages.
            echo "<h2>Please insert basic values to the table.</h2>";

            // Creating missed tables in database.
            createTables($missed);

            return TRUE;
        }
        return FALSE;
    }



    function redirect_me($url)
    {
        if ($this->input->is_ajax_request())
            echo '<script>window.location = "' . site_url($url) . '";</script>'; // If u change this string, also change variable 'needle' in common_sr.js (function is_logout).
        else
            redirect($url);
        exit();
    }

    /** 	Rendering page
     * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
     * 	@params : 	$data -> Values for view page.
     * 	@access public
     */
    function _render_page($view, $data = null)
    {
        $data['logo_xs'] = $this->logo_xs;
        $data['logo_xs_base64'] = $this->logo_xs_base64;
        $data['nav_menu'] = '';
        $data['client'] = $this->clients->get_by_id($this->clnt_id);
        $data['MENU_APPEAR'] = $this->settings->get_settings_value($this->clnt_id, 17);
        if (isset($data['active_nav'])) {
            $nav_menu = $this->tasks->get_tasks(1, ACTIVE, $this->usr_type); // get_my_parent_menus($this->usr_id, $this->usr_type);
            $ul_attr = 'class="nav nav-pills nav-sidebar flex-column  nav-child-indent" data-widget="treeview" role="menu" data-accordion="false"';
            $data['nav_menu'] = create_menu($nav_menu, $ul_attr, $data['active_nav'], $data['active_subnav'], 1);
        }
        $this->load->view($view, $data);
    }



    function get_inputs($flag = 'filter')
    {
        // study about the inbuilt function filter_input()
        $input = array();
        if ($_GET)
            $fields = $_GET;
        else
            $fields = $this->input->post();

        if (is_array($fields)) {
            foreach ($fields as $fld => $val) {
                if (is_array($val)) {
                    if ($flag == 'filter')
                        $input[$fld] = array_filter($val, 'trim');
                    elseif ($flag == 'nofilter')
                        $input[$fld] = $val;
                } else
                    $input[$fld] = trim($val);
            }
        }
        return $input;
    }

    // function get_inputs2($allowed_fields = 'all', $flag = 'filter')
    // {
    //     $input = array();

    //     if ($allowed_fields == 'all') {
    //         if ($_GET)
    //             $fields = $_GET;
    //         else
    //             $fields = $this->input->post();

    //         if (is_array($fields)) {
    //             foreach ($fields as $fld => $val) {
    //                 if (is_array($val)) {
    //                     if ($flag == 'filter')
    //                         $input[$fld] = array_filter($val, 'trim');
    //                     elseif ($flag == 'nofilter')
    //                         $input[$fld] = $val;
    //                 } else
    //                     $input[$fld] = trim($val);
    //             }
    //         }
    //     } else {
    //     }

    //     return $input;
    // }



    function valid_date($val)
    {
        if ($val && date('Y', strtotime($val)) ==  "1970") {
            $this->form_validation->set_message('valid_date', "Invalid Date");
            return FALSE;
        }

        return true;
    }

    function db_query($val)
    {
        // This should check first before any database query run.
        // If there is more than one times database keywords found, it should be blocked.
        $count = check_db_chars($val);
        if ($count >= 2) {
            $this->form_validation->set_message('db_query', "Invalid value");
            return FALSE;
        }

        // Single/Double quotes are not allowed.
        $count = check_quotes($val);
        if ($count) {
            $this->form_validation->set_message('db_query', "Quotes are not allowed");
            return FALSE;
        }

        return true;
    }
}
