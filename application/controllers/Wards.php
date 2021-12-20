<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Wards extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'wards';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('states_mdl', 'states');
        $this->load->model('districts_mdl', 'districts');
        $this->load->model('taluks_mdl', 'taluks');
        $this->load->model('areas_mdl', 'areas');
        $this->load->model('estores_mdl', 'estores');
        $this->load->model('user_central_stores_mdl', 'user_central_stores');
    }

    public function index()
    {
        if (!has_task('tsk_wrd')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'particulars'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'Places'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'Places';
        $data['icon'] = $this->tasks->get_icon(20);
        $data['stt_option'] = $this->states->get_active_option();

        // Only user's central stores.
        $data['cstr_option'] = $this->user_central_stores->get_users_central_stores($this->clnt_id, $this->usr_id, $this->usr_type);

        // Checking user tasks
        $data['tsk_wrd_list'] = has_task('tsk_wrd_list');
        $data['tsk_wrd_add'] = has_task('tsk_wrd_add');
        $data['tsk_wrd_edit'] = has_task('tsk_wrd_edit');
        $data['tsk_wrd_conf'] = has_task('tsk_wrd_conf');
        $data['tsk_wrd_activate'] = has_task('tsk_wrd_activate');
        $data['tsk_wrd_deactivate'] = has_task('tsk_wrd_deactivate');
        $data['tsk_wrd_pdf'] = has_task('tsk_wrd_pdf');
        $data['tsk_wrd_excel'] = has_task('tsk_wrd_excel');
        $data['tsk_wrd_print'] = has_task('tsk_wrd_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('wards/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('wrd_id') && !has_task('tsk_wrd_edit')) || (!$this->input->post('wrd_id') && !has_task('tsk_wrd_add'))) {
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

        // Validating Ward Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $wrd_id = $input['wrd_id'];
        $input['wrd_fk_clients'] = $this->clnt_id;

        if (!$this->wards->save($input, $wrd_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save ward';
        }

        echo json_encode($json);
        return;
    }

    function get_wrds()
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
        $input['usr_id'] = $this->usr_id;
        $input['usr_type'] = $this->usr_type;
        $input['clnt_id'] = $this->clnt_id;

        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->wards->index($input, TRUE);
        $json['ward_data'] = $this->wards->index($input, FALSE);
        $json['num_rows'] =  count($json['ward_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_wrds");
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

        $wrd_id = $this->input->post('wrd_id');
        $row = $this->wards->get_by_id($wrd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find ward';
            echo json_encode($json);
            return;
        }

        $ars_id = $row['wrd_fk_areas'];
        $ars_row = $this->areas->get_by_id($ars_id);
        $tlk_id = $ars_row['ars_fk_taluks'];
        $tlk_row = $this->taluks->get_by_id($tlk_id);
        $dst_id = $tlk_row['tlk_fk_districts'];
        $dst_row = $this->districts->get_by_id($dst_id);
        $stt_id = $dst_row['dst_fk_states'];
        $estr_id = $row['wrd_fk_estores'];
        //$estr_row = $this->estores->get_by_id($estr_id);

        $row['ars_option'] = get_options($this->areas->get_active_option(array('ars_fk_clients' => $this->clnt_id, 'ars_fk_taluks' => $tlk_id)), $ars_id);
        $row['tlk_option'] = get_options($this->taluks->get_active_option(array('tlk_fk_clients' => $this->clnt_id, 'tlk_fk_districts' => $dst_id)), $tlk_id);
        $row['dst_option'] = get_options($this->districts->get_active_option(array('dst_fk_clients' => $this->clnt_id, 'dst_fk_states' => $stt_id)), $dst_id);

        // Only estore which are under cntral store of the Area of the current ward.
        $row['estr_option'] = get_options($this->estores->get_active_option(array('estr_fk_clients' => $this->clnt_id, 'estr_fk_central_stores' => $ars_row['ars_fk_central_stores'])), $estr_id);

        $row['stt_id'] = $stt_id;

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->wards->get_users_wards($this->clnt_id, $this->usr_id, $this->usr_type, $input);
        echo get_options($options);
        return;
    }

    function get_options2()
    {
        $ars_id = $this->input->post('ars_id');
        $options =  $this->wards->get_active_option(array('wrd_fk_clients' => $this->clnt_id, 'wrd_fk_areas' => $ars_id));
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_deactivate')) {
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

        $wrd_id = $this->input->post('wrd_id');
        $row = $this->wards->get_by_id($wrd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find ward';
            echo json_encode($json);
            return;
        }

        $this->wards->deactivate($wrd_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_wrd_activate')) {
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

        $wrd_id = $this->input->post('wrd_id');
        $row = $this->wards->get_by_id($wrd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find ward';
            echo json_encode($json);
            return;
        }

        $this->wards->activate($wrd_id);
        echo json_encode($json);
        return;
    }
}
