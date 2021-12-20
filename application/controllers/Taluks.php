<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Taluks extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'taluks';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('districts_mdl', 'districts');
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

        // Validating Taluk Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $tlk_id = $input['tlk_id'];
        $input['tlk_fk_clients'] = $this->clnt_id;

        if (!$this->taluks->save($input, $tlk_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save taluk';
        }

        echo json_encode($json);
        return;
    }

    function get_tlks()
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
        $json['total_rows'] = $this->taluks->index($input, TRUE);
        $json['taluk_data'] = $this->taluks->index($input, FALSE);
        $json['num_rows'] =  count($json['taluk_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_tlks");
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

        $tlk_id = $this->input->post('tlk_id');
        $row = $this->taluks->get_by_id($tlk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find taluk';
            echo json_encode($json);
            return;
        }
        $dst_id = $row['tlk_fk_districts'];
        $dst_row = $this->districts->get_by_id($dst_id);
        $stt_id = $dst_row['dst_fk_states'];
        $row['dst_option'] = get_options($this->districts->get_active_option(array('dst_fk_clients' => $this->clnt_id, 'dst_fk_states' => $stt_id)), $dst_id);
        $row['stt_id'] = $stt_id;
        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $input['tlk_fk_clients'] = $this->clnt_id;
        $options =  $this->taluks->get_active_option($input);
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

        $tlk_id = $this->input->post('tlk_id');
        $row = $this->taluks->get_by_id($tlk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find taluk';
            echo json_encode($json);
            return;
        }

        $this->taluks->deactivate($tlk_id);
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

        $tlk_id = $this->input->post('tlk_id');
        $row = $this->taluks->get_by_id($tlk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find taluk';
            echo json_encode($json);
            return;
        }

        $this->taluks->activate($tlk_id);
        echo json_encode($json);
        return;
    }
}
