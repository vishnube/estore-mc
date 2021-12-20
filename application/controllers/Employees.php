<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Employees extends My_controller
{
    private $mbrtp_id;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'employees';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('categories_mdl', 'categories');
        $this->load->model('member_categories_mdl', 'member_categories');
        $this->mbrtp_id = $this->employees->get_member_type_id(); // Member Type Id of Employees
    }

    public function index()
    {
        if (!has_task('tsk_emply')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'members'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'employees'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'employees';
        $data['icon'] = $this->tasks->get_icon(3);
        $data['cat_option'] = $this->employees->get_categories_option(array('cat_fk_clients' => $this->clnt_id));
        $data['mbr_option'] = $this->employees->get_members_option();
        $data['mbrtp_id'] = $this->mbrtp_id;
        $data['wg_option'] = get_wage_options();

        // Checking user tasks
        $data['tsk_emply_list'] = has_task('tsk_emply_list');
        $data['tsk_emply_add'] = has_task('tsk_emply_add');
        $data['tsk_emply_edit'] = has_task('tsk_emply_edit');
        $data['tsk_emply_conf'] = has_task('tsk_emply_conf');
        $data['tsk_emply_activate'] = has_task('tsk_emply_activate');
        $data['tsk_emply_deactivate'] = has_task('tsk_emply_deactivate');
        $data['tsk_emply_pdf'] = has_task('tsk_emply_pdf');
        $data['tsk_emply_excel'] = has_task('tsk_emply_excel');
        $data['tsk_emply_print'] = has_task('tsk_emply_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('employees/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('mbr_id') && !has_task('tsk_emply_edit')) || (!$this->input->post('mbr_id') && !has_task('tsk_emply_add'))) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Employee Fields
        $v_config_1 = validationConfigs($this->table);

        // Validating Member Fields
        $v_config_2 = validationConfigs('members');

        $v_config = array_merge($v_config_1, $v_config_2);

        $this->form_validation->set_rules($v_config);

        // Extra validation fields
        //$this->form_validation->set_rules('state_id[]', 'State', 'callback_required');
        $this->form_validation->set_rules('cat_id[]', 'Category', 'callback_required');

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            // if ($json['o_error'])
            //$json['o_error'] .= validation_errors();

            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table, 'members'), array('cat_id[]')) : '';

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $mbr_id = $input['mbr_id'];
        $input['mbr_fk_clients'] = $this->clnt_id;
        $input['emply_fk_clients'] = $this->clnt_id;

        // If Add
        if (!$mbr_id) {
            $input['emply_is_admin'] = 2;
        }

        if ($input['mbr_fk_member_types'] != $this->mbrtp_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Member type not match';
            echo json_encode($json);
            return;
        }

        $input['mbr_date'] =  get_sql_date($input['mbr_date']);

        // Make checkbox values as comma seperated string.
        $input['emply_wage_option'] =  get_wage_options_string();

        if (!$this->employees->save_member($input, $mbr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save employee';
        }

        echo json_encode($json);
        return;
    }

    function get_emplys()
    {
        //Checking tasks
        if (!has_task('tsk_emply_list')) {
            //$this->session->set_flashdata('permission_errors', 'No task found');
            //$this->redirect_me("logout");

            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['emply_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->employees->index($input, TRUE);
        $json['employee_data'] = $this->employees->index($input, FALSE);
        $json['num_rows'] =  count($json['employee_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_emplys");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        // Reading member categories
        $json['cats'] = array();
        foreach ($json['employee_data'] as $row) {
            $json['cats'][$row['mbr_id']] = $this->members->get_categories($row['mbr_id']);
        }

        echo json_encode($json);
    }

    function get_details()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->employees->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find employee';
            echo json_encode($json);
            return;
        }

        $row['category'] =  $this->members->get_categories($mbr_id);
        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_emply_edit')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->employees->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find employee';
            echo json_encode($json);
            return;
        }

        // Normal users couldn't be edit Master Admin.
        if ($row['emply_is_admin'] == 1 && $this->usr_type == 3) {
            $json['status'] = 2;
            $json['o_error'] = 'You can\'t do it!';
            echo json_encode($json);
            return;
        }

        // Getting checkbox values from comma seperated string.
        if ($row['emply_wage_option'])
            $row = array_merge($row, get_wage_options_array($row['emply_wage_option']));

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->employees->get_members_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_emply_deactivate')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->employees->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find employee';
            echo json_encode($json);
            return;
        }

        // Nobody can deactivate Master Admin.
        if ($row['emply_is_admin'] == 1) {
            $json['status'] = 2;
            $json['o_error'] = 'You can\'t do it!';
            echo json_encode($json);
            return;
        }

        $this->employees->deactivate_member($mbr_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_emply_activate')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->employees->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find employee';
            echo json_encode($json);
            return;
        }

        $this->employees->activate_member($mbr_id);
        echo json_encode($json);
        return;
    }
}
