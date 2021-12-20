<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Bill_batches extends My_controller
{
    var $bill_type = '';
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'bill_batches';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->bill_type = $this->input->post('bill_type');
        if (!$this->bill_type) {
            $json['status'] = 2;
            $json['o_error'] = 'No Bill Type found';
            echo json_encode($json);
            exit();
        }
    }

    function save()
    {
        if (!has_task('tsk_' . $this->bill_type . '_conf')) {
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

        // Validating Bill_batch Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $blb_id = $input['blb_id'];
        $input['blb_fk_clients'] = $this->clnt_id;
        $input['blb_date'] =  get_sql_date($input['blb_date']);

        // Only one Bill Batch should be allowed for a 'blb_for' & 'blb_type' set.
        // So if there is a bill batch already existing for the current 'blb_for' & 'blb_type' set, Deactivating than
        if (!$blb_id) {
            $where['blb_for'] = $input['blb_for'];
            $where['blb_type'] = $input['blb_type'];
            $this->bill_batches->deactivate_where($where);
        }

        if (!$this->bill_batches->save($input, $blb_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save bill batch';
        }

        echo json_encode($json);
        return;
    }

    function get_blbs()
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
        $json['total_rows'] = $this->bill_batches->index($input, TRUE);
        $json['bill_batch_data'] = $this->bill_batches->index($input, FALSE);
        $json['num_rows'] =  count($json['bill_batch_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_blbs");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_' . $this->bill_type . '_conf')) {
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

        $blb_id = $this->input->post('blb_id');
        $row = $this->bill_batches->get_by_id($blb_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill_batch';
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
        $input['blb_fk_clients'] = $this->clnt_id;
        $options =  $this->bill_batches->get_active_option($input);
        echo get_options($options);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_' . $this->bill_type . '_conf')) {
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

        $blb_id = $this->input->post('blb_id');
        $row = $this->bill_batches->get_by_id($blb_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill batch';
            echo json_encode($json);
            return;
        }

        // Only one Bill Batch should be allowed for a 'blb_for' & 'blb_type' set.
        // So if there is an active bill batch for the current 'blb_for' & 'blb_type' set, Deactivating that
        $where['blb_for'] = $row['blb_for'];
        $where['blb_type'] = $row['blb_type'];
        $this->bill_batches->deactivate_where($where);

        $this->bill_batches->activate($blb_id);
        echo json_encode($json);
        return;
    }
}
