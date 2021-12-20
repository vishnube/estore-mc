<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Product_categories extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'product_categories';
        $this->load->model($this->table . "_mdl", $this->table);
        // $this->load->model('categories_mdl', 'categories');
        // $this->load->model('member_categories_mdl', 'member_categories');
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

        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table)) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $pct_id = $input['pct_id'];

        if ($pct_id && ($pct_id == $input['pct_parent'])) {
            $json['status'] = 2;
            $json['o_error'] = "Parent shouldn't be the same category itself";
            echo json_encode($json);
            return;
        }

        $input['pct_fk_clients'] = $this->clnt_id;

        if (!$this->product_categories->save($input, $pct_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save product';
            echo json_encode($json);
            return;
        }

        echo json_encode($json);
        return;
    }

    function get_pcts()
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

        $input = $this->get_inputs();
        $input['pct_fk_clients'] = $this->clnt_id;
        $json['pct_data'] = $this->product_categories->index($input, FALSE);
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

        $pct_id = $this->input->post('pct_id');
        $row = $this->product_categories->get_by_id($pct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
            echo json_encode($json);
            return;
        }

        if ($row['pct_parent'])
            $json['pct_parent_name'] = $this->product_categories->get_name_by_id($row['pct_parent']);

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        if (isset($input['not'])) {
            if ($input['not'])
                $input['pct_id != '] = $input['not'];
            unset($input['not']);
        }
        $input['pct_fk_clients'] = $this->clnt_id;
        $options =  $this->product_categories->get_active_option($input);
        echo get_options($options, '', 'Select Category', true, false, 'No Categories');
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
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

        $pct_id = $this->input->post('pct_id');
        $row = $this->product_categories->get_by_id($pct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
            echo json_encode($json);
            return;
        }

        $this->product_categories->deactivate($pct_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
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

        $pct_id = $this->input->post('pct_id');
        $row = $this->product_categories->get_by_id($pct_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find category';
            echo json_encode($json);
            return;
        }

        $this->product_categories->activate($pct_id);
        echo json_encode($json);
        return;
    }
}
