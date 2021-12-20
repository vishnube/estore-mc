<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Users extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'users';
        //$this->load->model('employees_mdl', 'employees');
        $this->load->model('groups_mdl', 'groups');
        $this->load->model('user_groups_mdl', 'user_groups');
        $this->load->model('member_types_mdl', 'member_types');
    }

    public function index()
    {
        if (!has_task('tsk_usr')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'office'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'users'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'users';
        $data['icon'] = $this->tasks->get_icon(7);

        $dont_want = array('parties', 'central_stores');

        // Member Type = "Developers" is available only for developers
        if (!$this->is_developer)
            $dont_want[] = 'developers';

        $data['mbrtp_option'] = $this->member_types->get_mbrtp_option('', $dont_want);
        $data['grp_option'] = $this->groups->get_active_option(array('grp_fk_clients' => $this->clnt_id));

        // For user_central_stores
        $data['usr_option'] = $this->users->get_users_option(array('usr_fk_clients' => $this->clnt_id), 3);
        $this->load->model('states_mdl', 'states');
        $data['stt_option'] = $this->states->get_active_option();

        // Checking user tasks
        $data['tsk_usr_list'] = has_task('tsk_usr_list');
        $data['tsk_usr_add'] = has_task('tsk_usr_add');
        $data['tsk_usr_edit'] = has_task('tsk_usr_edit');
        $data['tsk_usr_conf'] = has_task('tsk_usr_conf');
        $data['tsk_usr_activate'] = has_task('tsk_usr_activate');
        $data['tsk_usr_deactivate'] = has_task('tsk_usr_deactivate');
        $data['tsk_usr_pdf'] = has_task('tsk_usr_pdf');
        $data['tsk_usr_excel'] = has_task('tsk_usr_excel');
        $data['tsk_usr_print'] = has_task('tsk_usr_print');
        $data['tsk_usr_utsk'] = has_task('tsk_usr_utsk');
        $data['tsk_usr_ucs'] = has_task('tsk_usr_ucs');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('users/index', $data);
    }



    function check_group($v)
    {
        $usr_id = $this->input->post('usr_id');
        // IF edit
        if ($usr_id) {
            $usr_row = $this->users->get_by_id($usr_id);

            // If user is a developer/Master Admin, no need of grouping. Because they needs all privillages.
            if ($v && $usr_row['usr_type'] != 3) {
                $this->form_validation->set_message('check_group', "This user haven't be any groups");
                return FALSE;
            } else if (!$v && $usr_row['usr_type'] == 3) {
                $this->form_validation->set_message('check_group', "This Field Is Required");
                return FALSE;
            }
        }

        // If ADD,
        else if (!$v) {
            $this->form_validation->set_message('check_group', "This Field Is Required");
            return FALSE;
        }
        return TRUE;
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('usr_id') && !has_task('tsk_usr_edit')) || (!$this->input->post('usr_id') && !has_task('tsk_usr_add'))) {
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

        // If ADD
        if (!$this->input->post('usr_id')) {

            // Validating User Fields
            $v_config = validationConfigs($this->table);

            $this->form_validation->set_rules($v_config);
            $this->form_validation->set_rules('mbr_fk_member_types', 'Member Type', 'callback_required');
            $this->form_validation->set_rules('usr_fk_members', 'Member', 'callback_required');
            $this->form_validation->set_rules('usr_password', 'Password', 'required|callback_db_query');
        } else
            $this->form_validation->set_rules('usr_date', 'Date', 'required');


        // Extra validation fields
        $this->form_validation->set_rules('grp_id[]', 'Group', 'callback_check_group');


        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table, array('mbr_fk_member_types', 'grp_id[]')) : ''; // Validation Errors;
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $input['usr_fk_clients'] = $this->clnt_id;

        $input['usr_otp'] = '1234'; // This line should be deleted. It if for only temporary purpose.

        $usr_id = $this->input->post('usr_id');
        $action = !$usr_id ? 'ADD' : 'EDIT';

        $input['usr_date'] =  $action == 'ADD' ? get_sql_date() : get_sql_date($input['usr_date']);

        if ($action == 'ADD')
            $input['usr_type'] = 3;

        if (!$usr_id = $this->users->save($input, $usr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save user';
        }

        // If ADD, Encripting the password
        if ($action == 'ADD') {
            $arr['usr_password'] = get_encripted($usr_id, $input['usr_password']);
            $usr_id = $this->users->save($arr, $usr_id);

            // Saving group
            foreach ($input['grp_id'] as $gid)
                $this->user_groups->save(array('ugrp_fk_users' => $usr_id, 'ugrp_fk_groups' => $gid));
        } else {

            if (isset($input['grp_id'])) {
                // Deleting previous data
                $this->user_groups->delete($usr_id, 'ugrp_fk_users');

                // Saving new group
                foreach ($input['grp_id'] as $gid)
                    $this->user_groups->save(array('ugrp_fk_users' => $usr_id, 'ugrp_fk_groups' => $gid));
            }
        }

        echo json_encode($json);
        return;
    }

    function get_usrs()
    {
        //Checking tasks
        if (!has_task('tsk_usr_list')) {
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
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];

        $select = array('usr_id', 'mbr_name', 'usr_lock', 'usr_date', 'usr_attempt', 'usr_attempt_round', 'usr_status');

        $json['total_rows'] = $this->users->index($select, $input, TRUE, $this->usr_type);
        //$json['user_data'] = array_merge($this->users->index($input, FALSE), $this->users->index($input, FALSE), $this->users->index($input, FALSE), $this->users->index($input, FALSE));
        $json['user_data'] = $this->users->index($select, $input, FALSE, $this->usr_type);

        $json['num_rows'] =  count($json['user_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_usrs");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        $json['user_groups'] = array();
        foreach ($json['user_data'] as &$row) {
            if (strtotime($row['usr_lock'])  >=  strtotime(get_sql_date_time())) {
                $row['usr_lock'] = date('d-m-Y h:i:s A', strtotime($row['usr_lock']));
                $row['is_locked'] = true;
            } else
                $row['is_locked'] = false;

            // Reading member groups
            $json['user_groups'][$row['usr_id']] = $this->users->get_user_groups($row['usr_id']);
        }

        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_usr_edit')) {
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

        $usr_id = $this->input->post('usr_id');
        $temp = $this->users->get_by_id($usr_id);

        if (!$temp) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find user';
        }


        $row['usr_id'] = $temp['usr_id'];
        $row['usr_date'] = $temp['usr_date'];


        // Getting User groups
        $row['grp_id'] = $this->users->get_user_groups($usr_id, '', 'id');

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function load_profile()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $row = $this->users->get_field_by_id($this->usr_id, array('usr_username', 'usr_email', 'usr_mob'));

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find user';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function edit_profile()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';


        $this->form_validation->set_rules('usr_username', 'Username', 'required|callback_db_query|max_length[50]|callback_check_unique[usr_username]');
        $this->form_validation->set_rules('usr_email', 'Email', 'required|callback_db_query|max_length[50]|valid_email|callback_check_unique[usr_email]');
        $this->form_validation->set_rules('usr_mob', 'Mobile', 'required|numeric|callback_db_query|max_length[10]|min_length[10]|callback_check_unique[usr_mob]');

        if ($this->input->post('npw')) {
            $this->form_validation->set_rules('opw', 'Old Password', 'required|callback_db_query|callback_check_old_pwd');
            $this->form_validation->set_rules('npw', 'Password', 'callback_db_query|min_length[8]');
        }

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table, array('opw', 'npw')) : ''; // Validation Errors;
            echo json_encode($json);
            return;
        }

        // In any case, usr_type could not be edited by user. hacker may try to insert it throug Javascript. So blocking this.
        // A user can't be edit others data (By passing usr_id)
        if ($this->input->post('usr_type') || $this->input->post('usr_id')) {
            $json['status'] = 2;
            $json['o_error'] = 'You can\'t change this';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();

        if ($this->input->post('npw')) {
            $input['usr_password'] = get_encripted($this->usr_id, $this->input->post('npw'));
        }

        if (!$this->users->save($input, $this->usr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save user';
        }

        echo json_encode($json);
        return;
    }

    function check_old_pwd($opw)
    {
        $row = $this->users->get_by_id($this->usr_id);

        if (!check_encription($this->usr_id, $opw, $row['usr_password'])) {
            $this->form_validation->set_message('check_old_pwd', 'Wrong Password');
            return FALSE;
        }
        return TRUE;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $input['usr_status'] = ACTIVE;
        $input['usr_fk_clients'] = $this->clnt_id;
        $options =  $this->users->get_users_option($input, $this->usr_type);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_usr_deactivate')) {
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

        $usr_id = $this->input->post('usr_id');
        $row = $this->users->get_by_id($usr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find user';
            echo json_encode($json);
            return;
        }

        $this->users->deactivate($usr_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_usr_activate')) {
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

        $usr_id = $this->input->post('usr_id');
        $row = $this->users->get_by_id($usr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find user';
            echo json_encode($json);
            return;
        }

        $this->login->delete_all_attempts($usr_id);
        $this->users->activate($usr_id);
        echo json_encode($json);
        return;
    }

    function unlock()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $usr_id = $this->input->post('usr_id');
        $row = $this->users->get_by_id($usr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find user';
        }

        $this->login->delete_all_attempts($usr_id);
        echo json_encode($json);
        return;
    }



    // Callback
    function check_unique($val, $fld)
    {
        $id = $this->usr_id;
        $unique[$fld] = $val;
        if ($val && $this->users->is_exist($unique, $id)) {
            $this->form_validation->set_message('check_unique', 'The %s already exists');
            return FALSE;
        }
        return TRUE;
    }

    // Callback
    function check_unique2($val, $fld)
    {
        $id = $this->input->post('usr_id');
        $unique[$fld] = $val;
        if ($val && $this->users->is_exist($unique, $id)) {
            $this->form_validation->set_message('check_unique2', 'The %s already exists');
            return FALSE;
        }
        return TRUE;
    }
}
