<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Brands extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'brands';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('brands_mdl', 'brands');
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

        // Validating Brand Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $brnd_id = $input['brnd_id'];
        $input['brnd_fk_clients'] = $this->clnt_id;

        if (!$this->brands->save($input, $brnd_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save brand';
        }

        echo json_encode($json);
        return;
    }

    function get_brnds()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['brand_data'] = $this->brands->index($input);
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

        $brnd_id = $this->input->post('brnd_id');
        $row = $this->brands->get_by_id($brnd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find brand';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $options =  $this->brands->get_active_option(array('brnd_fk_clients' => $this->clnt_id));
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

        $brnd_id = $this->input->post('brnd_id');
        $row = $this->brands->get_by_id($brnd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find brand';
            echo json_encode($json);
            return;
        }

        $this->brands->deactivate($brnd_id);
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

        $brnd_id = $this->input->post('brnd_id');
        $row = $this->brands->get_by_id($brnd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find brand';
            echo json_encode($json);
            return;
        }

        $this->brands->activate($brnd_id);
        echo json_encode($json);
        return;
    }
}
