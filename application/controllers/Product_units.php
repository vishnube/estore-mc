<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Product_units extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'product_units';
        $this->load->model($this->table . "_mdl", $this->table);
        // $this->load->model("_mdl", $this->table);
        $this->load->helper('unit');
    }

    function get_options()
    {
        $prd_id = $this->input->post('prd_id');
        echo get_product_unit_options($prd_id);
        return;
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

        // Validating Product_unit Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_rules('group_no_required', 'Group No:', 'callback_check_unit_group|callback_group_no_required');

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table, array('group_no_required')) : '';

            // There is no visible input field for product id, so showing this validation error in a dialog window.
            if ($json['v_error']['punt_fk_products'])
                $json['o_error'] = "Please Select a Product";

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();

        if (!$this->product_units->save($input)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save unit';
        }

        echo json_encode($json);
        return;
    }

    function get_punts()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;
        $json['product_unit_data'] = $this->product_units->index($input);
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

        $punt_id = $this->input->post('punt_id');
        $row = $this->product_units->get_by_id($punt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product_unit';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
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

        $punt_id = $this->input->post('punt_id');
        $row = $this->product_units->get_by_id($punt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product_unit';
            echo json_encode($json);
            return;
        }

        $this->product_units->deactivate($punt_id);
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

        $punt_id = $this->input->post('punt_id');
        $row = $this->product_units->get_by_id($punt_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product_unit';
            echo json_encode($json);
            return;
        }

        $this->product_units->activate($punt_id);
        echo json_encode($json);
        return;
    }


    function check_unit_group()
    {
        $punt_group_no = $this->input->post('punt_group_no');
        $prd_id = $this->input->post('punt_fk_products');

        $punt_row = $this->product_units->get_row(array('punt_fk_products' => $prd_id, 'punt_group_no' => $punt_group_no, 'punt_status' => ACTIVE));
        if ($punt_row) {
            $this->form_validation->set_message('check_unit_group', "You have already added this unit");
            return FALSE;
        }

        $punt_row = $this->product_units->get_row(array('punt_fk_products' => $prd_id, 'punt_group_no' => $punt_group_no, 'punt_status' => INACTIVE));
        if ($punt_row) {
            $this->form_validation->set_message('check_unit_group', "You have already added this unit. Do you want to <span class='badge bg-success activate_punt cursor-pointer' data-punt_id='" . $punt_row['punt_id'] . "'>Activate</span> this?");
            return FALSE;
        }
        return TRUE;
    }

    function group_no_required($val)
    {
        if (!$val) {
            $this->form_validation->set_message('group_no_required', "Please select a Unit");
            return FALSE;
        }
        return TRUE;
    }
}
