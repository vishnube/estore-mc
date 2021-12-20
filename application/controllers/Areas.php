<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Areas extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'areas';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('states_mdl', 'states');
        $this->load->model('districts_mdl', 'districts');
        $this->load->model('taluks_mdl', 'taluks');
        $this->load->model('areas_mdl', 'areas');
        $this->load->model('wards_mdl', 'wards');
        $this->load->model('user_central_stores_mdl', 'user_central_stores');
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('ars_id') && !has_task('tsk_wrd_edit')) || (!$this->input->post('ars_id') && !has_task('tsk_wrd_add'))) {

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

        // Validating Area Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $ars_id = $input['ars_id'];
        $input['ars_fk_clients'] = $this->clnt_id;

        // If Edit
        if ($ars_id) {
            $prv_data = $this->areas->get_by_id($ars_id);
            if (!$prv_data) {
                $json['status'] = 2;
                $json['o_error'] = 'Couldn\'t find area';
                echo json_encode($json);
                return;
            }

            // If Central Store changed, make all wards under this Area "E-Store Free".
            if ($prv_data['ars_fk_central_stores'] != $input['ars_fk_central_stores']) {
                $this->wards->make_estore_free($ars_id);
            }
        }

        if (!$this->areas->save($input, $ars_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save area';
        }

        echo json_encode($json);
        return;
    }

    function get_arss()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_list')) {
            //$this->session->set_flashdata('permission_errors', 'No task found');
            //$this->redirect_me("logout");

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
        $input['clnt_id'] = $this->clnt_id;
        $input['usr_id'] = $this->usr_id;
        $input['usr_type'] = $this->usr_type;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->areas->index($input, TRUE);
        $json['area_data'] = $this->areas->index($input, FALSE);
        $json['num_rows'] =  count($json['area_data']);
        $json['area_type_option'] =  get_area_types();

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_arss");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_edit')) {
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

        $ars_id = $this->input->post('ars_id');
        $row = $this->areas->get_by_id($ars_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find area';
        }

        $tlk_id = $row['ars_fk_taluks'];
        $tlk_row = $this->taluks->get_by_id($tlk_id);
        $dst_id = $tlk_row['tlk_fk_districts'];
        $dst_row = $this->districts->get_by_id($dst_id);
        $stt_id = $dst_row['dst_fk_states'];
        $cstr_id = $row['ars_fk_central_stores'];

        $row['tlk_option'] = get_options($this->taluks->get_active_option(array('tlk_fk_clients' => $this->clnt_id, 'tlk_fk_districts' => $dst_id)), $tlk_id);
        $row['dst_option'] = get_options($this->districts->get_active_option(array('dst_fk_clients' => $this->clnt_id, 'dst_fk_states' => $stt_id)), $dst_id);

        $row['stt_id'] = $stt_id;

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->areas->get_users_area($this->clnt_id, $this->usr_id, $this->usr_type, $input);
        echo get_options($options);
        return;
    }

    function get_options_by_district()
    {
        $dst_id = $this->input->post('dst_id');
        $options =  $this->areas->get_by_district($dst_id);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_deactivate')) {

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

        $ars_id = $this->input->post('ars_id');
        $row = $this->areas->get_by_id($ars_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find area';
            echo json_encode($json);
            return;
        }

        $this->areas->deactivate($ars_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_activate')) {

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

        $ars_id = $this->input->post('ars_id');
        $row = $this->areas->get_by_id($ars_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find area';
            echo json_encode($json);
            return;
        }

        $this->areas->activate($ars_id);
        echo json_encode($json);
        return;
    }
}
