<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Tags extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'tags';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        // Validating Tag Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $tg_id = $input['tg_id'];
        $input['tg_fk_clients'] = $this->clnt_id;

        if (!$this->tags->save($input, $tg_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save tag';
        }

        echo json_encode($json);
        return;
    }

    function get_tgs()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['tag_data'] = $this->tags->index($input);
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $tg_id = $this->input->post('tg_id');
        $row = $this->tags->get_by_id($tg_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find tag';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $options =  $this->tags->get_active_option(array('tg_fk_clients' => $this->clnt_id));
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $tg_id = $this->input->post('tg_id');
        $row = $this->tags->get_by_id($tg_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find tag';
            echo json_encode($json);
            return;
        }

        $this->tags->deactivate($tg_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $tg_id = $this->input->post('tg_id');
        $row = $this->tags->get_by_id($tg_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find tag';
            echo json_encode($json);
            return;
        }

        $this->tags->activate($tg_id);
        echo json_encode($json);
        return;
    }
}
