<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class States extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'states';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    /**
     * add_states: Adding all states
     *
     * @return void
     */
    // function add_states()
    // {
    //     $st = get_GST_state_codes('',FALSE);
    //     foreach ($st as $s)
    //         $this->states->save(array('stt_name' => $s));
    // }

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

        // Validating State Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $stt_id = $input['stt_id'];

        if (!$this->states->save($input, $stt_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save state';
        }

        echo json_encode($json);
        return;
    }

    function get_stts()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['state_data'] = $this->states->index($input);
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

        $stt_id = $this->input->post('stt_id');
        $row = $this->states->get_by_id($stt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find state';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $options =  $this->states->get_active_option();
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

        $stt_id = $this->input->post('stt_id');
        $row = $this->states->get_by_id($stt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find state';
            echo json_encode($json);
            return;
        }

        $this->states->deactivate($stt_id);
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

        $stt_id = $this->input->post('stt_id');
        $row = $this->states->get_by_id($stt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find state';
            echo json_encode($json);
            return;
        }

        $this->states->activate($stt_id);
        echo json_encode($json);
        return;
    }
}
