<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Price_group_locations extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'price_group_locations';
        $this->load->model($this->table . "_mdl", $this->table);
    }


    function save()
    {
        //Checking tasks
        if (($this->input->post('pgpl_id') && !has_task('tsk_pgp_edit')) || (!$this->input->post('pgpl_id') && !has_task('tsk_pgp_add'))) {

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

        // Validating Price_group Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $pgpl_id = $input['pgpl_id'];
        $input['pgpl_fk_clients'] = $this->clnt_id;
        $input['pgpl_date'] =  get_sql_date();
        $input['pgpl_vf'] =  get_sql_date($input['pgpl_vf']);
        $input['pgpl_vt'] =  get_sql_date($input['pgpl_vt']);
        $input['pgpl_fk_states'] =  $input['stt_id'];
        $input['pgpl_fk_districts'] =  $input['dst_id'];
        $input['pgpl_fk_areas'] =  $input['ars_id'];
        $input['pgpl_fk_central_stores'] =  $input['cstr_id'];
        $input['pgpl_fk_wards'] =  $input['wrd_id'];

        // If Edit
        if ($pgpl_id) {
            $prv_data = $this->price_group_locations->get_by_id($pgpl_id);
            if (!$prv_data) {
                $json['status'] = 2;
                $json['o_error'] = 'Couldn\'t find location of this price group';
                echo json_encode($json);
                return;
            }
        }

        if (!$this->price_group_locations->save($input, $pgpl_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save location for price group';
        }

        echo json_encode($json);
        return;
    }

    function get_pgpls()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_list')) {
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
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->price_group_locations->index($input, TRUE);
        $json['price_group_data'] = $this->price_group_locations->index($input, FALSE);
        $json['num_rows'] =  count($json['price_group_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_pgpls");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_edit')) {
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

        $pgpl_id = $this->input->post('pgpl_id');
        $row = $this->price_group_locations->get_by_id($pgpl_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
        }

        $row['stt_id'] = $row['pgpl_fk_states'] ? $row['pgpl_fk_states'] : '';
        $row['cstr_id'] = $row['pgpl_fk_central_stores'] ? $row['pgpl_fk_central_stores'] : '';

        $this->load->model('districts_mdl', 'districts');
        $this->load->model('areas_mdl', 'areas');
        $this->load->model('wards_mdl', 'wards');

        $wrd_id = $row['pgpl_fk_wards'];
        $ars_id = $row['pgpl_fk_areas'];
        $dst_id = $row['pgpl_fk_districts'];
        $stt_id = $row['pgpl_fk_states'];
        $row['wrd_option'] = $row['ars_option'] = $row['dst_option'] = get_options(array());

        if ($wrd_id)
            $row['wrd_option'] = get_options($this->wards->get_active_option(array('wrd_fk_areas' => $ars_id)), $wrd_id);

        if ($ars_id)
            $row['ars_option'] = get_options($this->areas->get_by_district($dst_id), $ars_id);

        if ($dst_id)
            $row['dst_option'] = get_options($this->districts->get_active_option(array('dst_fk_states' => $stt_id)), $dst_id);

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function delete()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_deactivate')) {

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

        $pgpl_id = $this->input->post('pgpl_id');
        $row = $this->price_group_locations->get_by_id($pgpl_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find location';
            echo json_encode($json);
            return;
        }

        $this->price_group_locations->remove($pgpl_id);
        echo json_encode($json);
        return;
    }

    function compare_dates($pgpl_vt)
    {
        $pgpl_vf = $this->input->post('pgpl_vf');


        if (strtotime($pgpl_vt) < strtotime($pgpl_vf)) {
            $this->form_validation->set_message('compare_dates', "Never be less than FROM date");
            return FALSE;
        }
        return TRUE;
    }
}
