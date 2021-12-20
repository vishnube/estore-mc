<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Hsn_details extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'hsn_details';
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

        // Validating Hsn_detail Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $hsn_id = $input['hsn_id'];
        $input['hsn_fk_clients'] = $this->clnt_id;

        if (!$this->hsn_details->save($input, $hsn_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save hsn_detail';
        }

        echo json_encode($json);
        return;
    }

    function get_hsns()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->hsn_details->index($input, TRUE);
        $json['hsn_detail_data'] = $this->hsn_details->index($input, FALSE);
        $json['num_rows'] =  count($json['hsn_detail_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_hsns");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
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

        $hsn_id = $this->input->post('hsn_id');
        $row = $this->hsn_details->get_by_id($hsn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find hsn_detail';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $options =  $this->hsn_details->get_active_option();
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

        $hsn_id = $this->input->post('hsn_id');
        $row = $this->hsn_details->get_by_id($hsn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find hsn_detail';
            echo json_encode($json);
            return;
        }

        $this->hsn_details->deactivate($hsn_id);
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

        $hsn_id = $this->input->post('hsn_id');
        $row = $this->hsn_details->get_by_id($hsn_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find hsn_detail';
            echo json_encode($json);
            return;
        }

        $this->hsn_details->activate($hsn_id);
        echo json_encode($json);
        return;
    }
}
