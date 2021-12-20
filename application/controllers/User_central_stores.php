<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class User_central_stores extends My_controller
{
    var $add_btn;
    var $rem_btn;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'user_central_stores';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("central_stores_mdl", 'central_stores');
        $this->add_btn = "fal fa-times text-danger add_ucs cursor-pointer";
        $this->rem_btn = "fal fa-check text-success remove_ucs cursor-pointer";
    }

    function get_ucss()
    {
        //Checking tasks
        if (!has_task('tsk_usr_ucs')) {
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

        $input = $this->input->post();
        $input['clnt_id'] = $this->clnt_id;
        $input['mbr_status'] = ACTIVE;

        $data['usr_id'] = $input['usr_id'];
        $data['usr_name'] = $this->users->get_member_details($input['usr_id'], 'mbr_name');
        // Reading user's groups
        $data['user_groups'] = $this->users->get_user_groups($input['usr_id']);
        $data['cstr_table'] = $this->central_stores->index($input, FALSE);
        $data['ucs_ids'] = $this->user_central_stores->get_users_central_stores_ids($input['usr_id']);
        $data['after_ajax'] = TRUE;
        $json['html'] = $this->load->view('user_central_stores/list', $data, true);
        echo json_encode($json);
        return;
    }

    function insert_ucs()
    {
        //Checking tasks
        if (!has_task('tsk_usr_ucs')) {
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
        $cstr_id = $this->input->post('cstr_id');
        $ucs_data = array('ucs_fk_users' => $usr_id, 'ucs_fk_central_stores' => $cstr_id);
        $row = $this->user_central_stores->get_row($ucs_data);

        if ($row) {
            $json['status'] = 2;
            $json['o_error'] = 'Already added';
            echo json_encode($json);
            return;
        }

        $this->user_central_stores->save($ucs_data);
        $json['button'] = $this->rem_btn; // Toggling buttons
        echo json_encode($json);
        return;
    }


    function remove_ucs()
    {
        //Checking tasks
        if (!has_task('tsk_usr_ucs')) {
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
        $cstr_id = $this->input->post('cstr_id');
        $ucs_data = array('ucs_fk_users' => $usr_id, 'ucs_fk_central_stores' => $cstr_id);
        $row = $this->user_central_stores->get_row($ucs_data);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Already not added';
            echo json_encode($json);
            return;
        }

        $this->user_central_stores->remove($row['ucs_id']);
        $json['button'] = $this->add_btn; // Toggling buttons

        echo json_encode($json);
        return;
    }
}
