<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Districts extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'districts';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_conf')) {
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

        // Validating District Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $dst_id = $input['dst_id'];
        $input['dst_fk_clients'] = $this->clnt_id;

        if (!$this->districts->save($input, $dst_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save district';
        }

        echo json_encode($json);
        return;
    }

    function get_dsts()
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
        $json['total_rows'] = $this->districts->index($input, TRUE);
        $json['district_data'] = $this->districts->index($input, FALSE);
        $json['num_rows'] =  count($json['district_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_dsts");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_conf')) {
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

        $dst_id = $this->input->post('dst_id');
        $row = $this->districts->get_by_id($dst_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find district';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }


    function get_options()
    {
        $input = $this->get_inputs();
        $input['dst_fk_clients'] = $this->clnt_id;
        $options =  $this->districts->get_active_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_conf')) {
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

        $dst_id = $this->input->post('dst_id');
        $row = $this->districts->get_by_id($dst_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find district';
            echo json_encode($json);
            return;
        }

        $this->districts->deactivate($dst_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_conf')) {
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

        $dst_id = $this->input->post('dst_id');
        $row = $this->districts->get_by_id($dst_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find district';
            echo json_encode($json);
            return;
        }

        $this->districts->activate($dst_id);
        echo json_encode($json);
        return;
    }
}
