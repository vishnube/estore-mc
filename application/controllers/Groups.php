<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class groups extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'groups';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_usr_conf')) {
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

        // Validating group Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            $json['v_error'] = get_val_errors($this->table); // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $input['grp_fk_clients'] = $this->clnt_id;

        $grp_id = $input['grp_id'];



        if (!$this->groups->save($input, $grp_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save group';
        }

        echo json_encode($json);
        return;
    }

    function get_grps()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();

        $input['grp_fk_clients'] = $this->clnt_id;
        $json['table'] = $this->groups->index($input);

        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_usr_conf')) {
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

        $grp_id = $this->input->post('grp_id');
        $row = $this->groups->get_by_id($grp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find group';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $input['grp_fk_clients'] = $this->clnt_id;
        $options = $this->groups->get_active_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_usr_conf')) {
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

        $grp_id = $this->input->post('grp_id');
        $row = $this->groups->get_by_id($grp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find group';
            echo json_encode($json);
            return;
        }

        $this->groups->deactivate($grp_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_usr_conf')) {
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

        $grp_id = $this->input->post('grp_id');
        $row = $this->groups->get_by_id($grp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find group';
            echo json_encode($json);
            return;
        }

        $this->groups->activate($grp_id);
        echo json_encode($json);
        return;
    }
}
