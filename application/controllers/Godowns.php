<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Godowns extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'godowns';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('central_stores_mdl', 'central_stores');
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_conf')) {
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

        // Validating Godown Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $gdn_id = $input['gdn_id'];

        if (!$this->godowns->save($input, $gdn_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save godown';
        }

        echo json_encode($json);
        return;
    }

    function get_gdns()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['godown_data'] = $this->godowns->index($input);
        echo json_encode($json);
    }

    function get_godowns_by_central_store()
    {
        $cstr_id = $this->input->post('cstr_id');
        $where['gdn_fk_central_stores'] = $cstr_id;
        $options =  $this->godowns->get_active_option($where);
        echo get_options($options, '', "", FALSE, TRUE, 'NO GODOWNS');
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_conf')) {
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

        $gdn_id = $this->input->post('gdn_id');
        $row = $this->godowns->get_by_id($gdn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find godown';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_conf')) {
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

        $gdn_id = $this->input->post('gdn_id');
        $row = $this->godowns->get_by_id($gdn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find godown';
            echo json_encode($json);
            return;
        }

        $this->godowns->deactivate($gdn_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_conf')) {
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

        $gdn_id = $this->input->post('gdn_id');
        $row = $this->godowns->get_by_id($gdn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find godown';
            echo json_encode($json);
            return;
        }

        $this->godowns->activate($gdn_id);
        echo json_encode($json);
        return;
    }
}
