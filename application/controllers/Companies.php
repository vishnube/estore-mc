<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Companies extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'companies';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('companies_mdl', 'companies');
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

        // Validating Company Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $cmp_id = $input['cmp_id'];
        $input['cmp_fk_clients'] = $this->clnt_id;

        if (!$this->companies->save($input, $cmp_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save company';
        }

        echo json_encode($json);
        return;
    }

    function get_cmps()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['company_data'] = $this->companies->index($input);
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

        $cmp_id = $this->input->post('cmp_id');
        $row = $this->companies->get_by_id($cmp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find company';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $options =  $this->companies->get_active_option(array('cmp_fk_clients' => $this->clnt_id));
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

        $cmp_id = $this->input->post('cmp_id');
        $row = $this->companies->get_by_id($cmp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find company';
            echo json_encode($json);
            return;
        }

        $this->companies->deactivate($cmp_id);
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

        $cmp_id = $this->input->post('cmp_id');
        $row = $this->companies->get_by_id($cmp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find company';
            echo json_encode($json);
            return;
        }

        $this->companies->activate($cmp_id);
        echo json_encode($json);
        return;
    }
}
