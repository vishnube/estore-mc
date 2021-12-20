<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Categories extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'categories';
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

        // Validating Category Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            $json['v_error'] = get_val_errors($this->table); // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $input['cat_fk_clients'] = $this->clnt_id;

        $cat_id = $input['cat_id'];

        if (!$this->categories->save($input, $cat_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save category';
        }

        echo json_encode($json);
        return;
    }

    function get_cats()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();

        $input['cat_fk_clients'] = $this->clnt_id;

        $json['table'] = $this->categories->index($input);

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

        $cat_id = $this->input->post('cat_id');
        $row = $this->categories->get_by_id($cat_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
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
        $options = $this->categories->get_active_option($input);
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

        $cat_id = $this->input->post('cat_id');
        $row = $this->categories->get_by_id($cat_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
            echo json_encode($json);
            return;
        }

        $this->categories->deactivate($cat_id);
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

        $cat_id = $this->input->post('cat_id');
        $row = $this->categories->get_by_id($cat_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
            echo json_encode($json);
            return;
        }

        $this->categories->activate($cat_id);
        echo json_encode($json);
        return;
    }
}
