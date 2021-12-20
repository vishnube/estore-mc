<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Estores extends My_controller
{
    private $mbrtp_id;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'estores';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('states_mdl', 'states');
        $this->load->model('areas_mdl', 'areas');
        $this->load->model('central_stores_mdl', 'central_stores');
        $this->load->model('wards_mdl', 'wards');
        $this->load->model('families_mdl', 'families');
        $this->load->model('member_categories_mdl', 'member_categories');
        $this->mbrtp_id = $this->estores->get_member_type_id(); // Member Type Id of estores
    }

    public function index()
    {
        if (!has_task('tsk_estr')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }
        $data['active_nav'] = 'members'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'estores'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'Estores';
        $data['icon'] = $this->tasks->get_icon(30);
        $data['estr_cat_option'] = $this->estores->get_categories_option(array('cat_fk_clients' => $this->clnt_id));
        $data['mbrtp_id'] = $this->mbrtp_id;
        $data['stt_option'] = $this->states->get_active_option();
        $data['cstr_option'] = $this->central_stores->get_active_option(array('cstr_fk_clients' => $this->clnt_id));

        // Checking user tasks
        $data['tsk_estr_list'] = has_task('tsk_estr_list');
        $data['tsk_estr_add'] = has_task('tsk_estr_add');
        $data['tsk_estr_edit'] = has_task('tsk_estr_edit');
        $data['tsk_estr_conf'] = has_task('tsk_estr_conf');
        $data['tsk_estr_activate'] = has_task('tsk_estr_activate');
        $data['tsk_estr_deactivate'] = has_task('tsk_estr_deactivate');
        $data['tsk_estr_pdf'] = has_task('tsk_estr_pdf');
        $data['tsk_estr_excel'] = has_task('tsk_estr_excel');
        $data['tsk_estr_print'] = has_task('tsk_estr_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('estores/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('mbr_id') && !has_task('tsk_estr_edit')) || (!$this->input->post('mbr_id') && !has_task('tsk_estr_add'))) {
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

        // Validating estore Fields
        $v_config_1 = validationConfigs($this->table);

        // Validating Member Fields
        $v_config_2 = validationConfigs('members');

        $v_config = array_merge($v_config_1, $v_config_2);

        $this->form_validation->set_rules($v_config);

        $this->form_validation->set_rules('mbr_date', 'Date', 'callback_valid_date'); // don't want 'required' rule

        // Extra validation fields
        $this->form_validation->set_rules('cat_id', 'Category', 'required');

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table, 'members'), array('cat_id')) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $mbr_id = $input['mbr_id'];
        $input['mbr_fk_clients'] = $this->clnt_id;
        $input['estr_fk_clients'] = $this->clnt_id;
        $input['estr_name'] = $input['mbr_name'];
        $input['estr_address1'] = $input['mbr_address'];
        $input['mbr_date'] =  get_sql_date();

        // If Edit
        if ($mbr_id) {
            $prv_data = $this->estores->get_by_member_id($mbr_id);
            if (!$prv_data) {
                $json['status'] = 2;
                $json['o_error'] = 'Couldn\'t find area';
                echo json_encode($json);
                return;
            }

            // If Central Store changed, make all wards under this Area "E-Store Free".
            if ($prv_data['estr_fk_central_stores'] != $input['estr_fk_central_stores']) {
                $this->wards->make_estore_free('', $prv_data['estr_id']);
            }
        }

        if ($input['mbr_fk_member_types'] != $this->mbrtp_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Member type not match';
            echo json_encode($json);
            return;
        }


        if (!$this->estores->save_member($input, $mbr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save estore';
        }

        echo json_encode($json);
        return;
    }

    function get_estrs()
    {
        //Checking tasks
        if (!has_task('tsk_estr_list')) {
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

        $json['total_rows'] = $this->estores->index($input, TRUE);
        $json['estore_data'] = $this->estores->index($input, FALSE);
        $json['num_rows'] =  count($json['estore_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_estrs");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        $json['family_count'] = array();
        $json['cats'] = array();
        $json['ward_count'] = array();
        foreach ($json['estore_data'] as $row) {
            $json['cats'][$row['estr_id']] = $this->members->get_categories($row['estr_fk_members']);
            $json['family_count'][$row['estr_id']] = $this->families->get_families_by_estores($row['estr_id']);
            $json['ward_count'][$row['estr_id']] = $this->wards->get_wards_by_estores($row['estr_id']);
        }

        echo json_encode($json);
    }

    function get_details()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->estores->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
        }

        $row['category'] =  $this->members->get_categories($mbr_id);

        $row['after_ajax'] = TRUE;
        $row['html'] = $this->load->view('estores/show_details', $row, true);
        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_estr_edit')) {
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

        $mbr_id = $this->input->post('mbr_id');
        $row = $this->estores->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find estore';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options_by_area()
    {
        $ars_id = $this->input->post('ars_id');
        $options =  $this->estores->get_estores_by_area($this->clnt_id, $this->usr_id, $this->usr_type, $ars_id);
        echo get_options($options);
        return;
    }

    function get_options_by_central_store()
    {
        $cstr_id = $this->input->post('cstr_id');
        $options =  $this->estores->get_estores_by_central_store($cstr_id, ACTIVE, 'option');
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_estr_deactivate')) {
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

        $mbr_id = $this->input->post('mbr_id');
        $estr_id = $this->input->post('estr_id');

        $row = $this->estores->get_by_member_id($mbr_id);
        $estr_row = $this->estores->get_by_id($estr_id);

        if (!$row || !$estr_row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find estore';
            echo json_encode($json);
            return;
        }

        $this->estores->deactivate_member($mbr_id);
        $this->estores->deactivate($estr_id);

        // Deactivating the user data also.
        $usr_id =  $this->users->get_id_by_field('usr_fk_members', $mbr_id);
        $this->users->deactivate($usr_id);

        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_estr_activate')) {
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

        $mbr_id = $this->input->post('mbr_id');
        $estr_id = $this->input->post('estr_id');

        $row = $this->estores->get_by_member_id($mbr_id);
        $estr_row = $this->estores->get_by_id($estr_id);

        if (!$row || !$estr_row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find estore';
            echo json_encode($json);
            return;
        }

        $this->estores->activate_member($mbr_id);
        $this->estores->activate($estr_id);
        echo json_encode($json);
        return;
    }
}
