<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class settings_keys extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'settings_keys';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    function save()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Settings_key Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            $json['v_error'] = get_val_errors($this->table); // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();

        $stky_id = $input['stky_id'];
        $input['stky_name'] = strtoupper($input['stky_name']);

        if (!$this->settings_keys->save($input, $stky_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save settings_key';
        }

        $json['query'] = $this->settings_keys->get_last_query();
        echo json_encode($json);
        return;
    }

    function get_stkys()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();

        $json['table'] = $this->settings_keys->index($input);

        echo json_encode($json);
    }

    function before_edit()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $stky_id = $this->input->post('stky_id');
        $row = $this->settings_keys->get_by_id($stky_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings_key';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options = $this->settings_keys->get_active_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $stky_id = $this->input->post('stky_id');
        $row = $this->settings_keys->get_by_id($stky_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings key';
            echo json_encode($json);
            return;
        }

        $this->settings_keys->deactivate($stky_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $stky_id = $this->input->post('stky_id');
        $row = $this->settings_keys->get_by_id($stky_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings key';
            echo json_encode($json);
            return;
        }

        $this->settings_keys->activate($stky_id);
        echo json_encode($json);
        return;
    }
}
