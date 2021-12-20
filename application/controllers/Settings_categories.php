<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Settings_categories extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'settings_categories';
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

        // Validating Settings_category Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            $json['v_error'] = get_val_errors($this->table); // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();

        $stct_id = $input['stct_id'];
        $input['stct_sort'] = $input['stct_sort'] ? $input['stct_sort'] : $this->settings_categories->next_sort();

        if (!$this->settings_categories->save($input, $stct_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save settings_category';
        }

        $json['query'] = $this->settings_categories->get_last_query();
        echo json_encode($json);
        return;
    }

    function get_stcts()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();

        $json['table'] = $this->settings_categories->index($input);

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

        $stct_id = $this->input->post('stct_id');
        $row = $this->settings_categories->get_by_id($stct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings_category';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options = $this->settings_categories->get_active_option($input);
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

        $stct_id = $this->input->post('stct_id');
        $row = $this->settings_categories->get_by_id($stct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings_category';
            echo json_encode($json);
            return;
        }

        $this->settings_categories->deactivate($stct_id);
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

        $stct_id = $this->input->post('stct_id');
        $row = $this->settings_categories->get_by_id($stct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings_category';
            echo json_encode($json);
            return;
        }

        $this->settings_categories->activate($stct_id);
        echo json_encode($json);
        return;
    }
}
