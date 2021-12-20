<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Families extends My_controller
{
    private $mbrtp_id;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'families';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('family_members_mdl', 'family_members');
        $this->load->model('categories_mdl', 'categories');
        $this->load->model('member_categories_mdl', 'member_categories');
        $this->load->model('states_mdl', 'states');
        $this->load->model('districts_mdl', 'districts');
        $this->load->model('taluks_mdl', 'taluks');
        $this->load->model('areas_mdl', 'areas');
        $this->load->model('wards_mdl', 'wards');
        $this->load->model('central_stores_mdl', 'central_stores');
        $this->mbrtp_id = $this->families->get_member_type_id(); // Member Type Id of Families
    }


    public function index()
    {
        if (!has_task('tsk_fmly')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'members'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'families'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'families';
        $data['icon'] = $this->tasks->get_icon(50);
        $data['cat_option'] = $this->families->get_categories_option(array('cat_fk_clients' => $this->clnt_id));
        $data['mbr_option'] = $this->families->get_members_option();
        $data['mbrtp_id'] = $this->mbrtp_id;
        $data['stt_option'] = $this->states->get_active_option();
        $data['cstr_option'] = $this->central_stores->get_active_option(array('cstr_fk_clients' => $this->clnt_id));

        // Checking user tasks
        $data['tsk_fmly_list'] = has_task('tsk_fmly_list');
        $data['tsk_fmly_add'] = has_task('tsk_fmly_add');
        $data['tsk_fmly_edit'] = has_task('tsk_fmly_edit');
        $data['tsk_fmly_conf'] = has_task('tsk_fmly_conf');
        $data['tsk_fmly_activate'] = has_task('tsk_fmly_activate');
        $data['tsk_fmly_deactivate'] = has_task('tsk_fmly_deactivate');
        $data['tsk_fmly_pdf'] = has_task('tsk_fmly_pdf');
        $data['tsk_fmly_excel'] = has_task('tsk_fmly_excel');
        $data['tsk_fmly_print'] = has_task('tsk_fmly_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('families/index', $data);
    }


    function valid_prime_meber($val)
    {
        $this->form_validation->set_message('valid_prime_meber', "Only one allowed");
        return FALSE;
    }


    function min_prim($val)
    {
        $this->form_validation->set_message('min_prim', "Atleast one required");
        return FALSE;
    }

    function validate_fmlm()
    {
        // If edit
        if ($this->input->post('mbr_id'))
            return array();

        $fmlm_name = $this->input->post('fmlm_name');
        $fmlm = array();
        $prim = 0;
        $fmlm_is_prime = $this->input->post('fmlm_is_prime');

        foreach ($fmlm_name as $k => $v) {
            $this->form_validation->set_rules("fmlm_name[$k]", "Name", "required|callback_db_query|max_length[70]");
            $this->form_validation->set_rules("fmlm_mob[$k]", "Contact", "numeric|max_length[10]|min_length[10]|callback_db_query");
            $this->form_validation->set_rules("fmlm_dob[$k]", "Date of Birth", "callback_valid_date|callback_db_query");
            if ($fmlm_is_prime[$k] == 1) {
                $prim++;
            }
            if ($prim >= 2 && $fmlm_is_prime[$k] == 1)
                $this->form_validation->set_rules("fmlm_is_prime[$k]", "Primery", "callback_valid_prime_meber");

            $fmlm[] = "fmlm_name[$k]";
            $fmlm[] = "fmlm_mob[$k]";
            $fmlm[] = "fmlm_dob[$k]";
            $fmlm[] = "fmlm_is_prime[$k]";
        }
        if (!$prim)
            $this->form_validation->set_rules("fmlm_is_prime[0]", "Primery", "callback_min_prim");

        return $fmlm;
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('mbr_id') && !has_task('tsk_fmly_edit')) || (!$this->input->post('mbr_id') && !has_task('tsk_fmly_add'))) {
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

        // Validating Family Fields
        $v_config_1 = validationConfigs($this->table);

        // Validating Member Fields
        $v_config_2 = validationConfigs('members');

        $v_config = array_merge($v_config_1, $v_config_2);

        $this->form_validation->set_rules($v_config);

        // Validating Tbl: family_members
        $fmlm = $this->validate_fmlm();

        // Extra validation fields
        $this->form_validation->set_rules('cat_id[]', 'Category', 'callback_required');

        $others = $fmlm;
        $others[] = 'cat_id[]';

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table, 'members'), $others) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $mbr_id = $input['mbr_id'];
        $action = $mbr_id ? 'EDIT' : 'ADD';
        $input['mbr_fk_clients'] = $this->clnt_id;
        $input['fmly_fk_clients'] = $this->clnt_id;
        $input['fmly_name'] = $input['mbr_name'];
        $input['fmly_address'] = $input['mbr_address'];

        if ($input['mbr_fk_member_types'] != $this->mbrtp_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Member type not match';
            echo json_encode($json);
            return;
        }

        $input['mbr_date'] =  get_sql_date($input['mbr_date']);
        $mbr_id = $this->families->save_member($input, $mbr_id);
        if (!$mbr_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save family';
        }


        if ($action == 'ADD') {
            $fmly_id = $this->families->get_id_by_member($mbr_id);
            foreach ($input['fmlm_name'] as $k => $v) {
                $fmlm_data = array();
                $fmlm_data['mbr_fk_clients'] = $this->clnt_id;
                $fmlm_data['mbr_fk_member_types'] =  $this->family_members->get_member_type_id(); // Member Type Id of family_members
                $fmlm_data['mbr_name'] = $input['fmlm_name'][$k];
                $fmlm_data['mbr_date'] = get_sql_date();
                $fmlm_data['fmlm_fk_clients'] = $this->clnt_id;
                $fmlm_data['fmlm_fk_families'] = $fmly_id;
                $fmlm_data['fmlm_name'] = $input['fmlm_name'][$k];
                $fmlm_data['fmlm_mob'] = $input['fmlm_mob'][$k];
                $fmlm_data['fmlm_dob'] =  get_sql_date($input['fmlm_dob'][$k]);
                $fmlm_data['fmlm_is_prime'] = $input['fmlm_is_prime'][$k];
                $mbr_id = $this->family_members->save_member($fmlm_data);
            }
        }

        echo json_encode($json);
        return;
    }

    function get_fmlys()
    {
        //Checking tasks
        if (!has_task('tsk_fmly_list')) {
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
        $input['fmly_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->families->index($input, TRUE);
        $json['family_data'] = $this->families->index($input, FALSE);
        $json['num_rows'] =  count($json['family_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_fmlys");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();


        $json['fmlm_count'] = array();
        $json['cats'] = array();
        foreach ($json['family_data'] as $row) {
            $json['cats'][$row['mbr_id']] = $this->members->get_categories($row['mbr_id']);
            $json['fmlm_count'][$row['mbr_id']] = $this->family_members->get_family_members_by_family($row['fmly_id']);
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
        $row = $this->families->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }

        $row['category'] =  $this->members->get_categories($mbr_id);
        $row['after_ajax'] = TRUE;
        $json['html'] = $this->load->view('families/show_details', $row, true);

        // $json['lat'] = $row['fmly_lat'];
        // $json['log'] = $row['fmly_log'];
        echo json_encode($json);
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_fmly_edit')) {
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
        $row = $this->families->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }

        $wrd_id = $row['fmly_fk_wards'];
        $wrd_row = $this->wards->get_by_id($wrd_id);
        $ars_id = $wrd_row['wrd_fk_areas'];
        $ars_row = $this->areas->get_by_id($ars_id);
        $tlk_id = $ars_row['ars_fk_taluks'];
        $tlk_row = $this->taluks->get_by_id($tlk_id);
        $dst_id = $tlk_row['tlk_fk_districts'];
        $dst_row = $this->districts->get_by_id($dst_id);
        $stt_id = $dst_row['dst_fk_states'];

        $row['wrd_option'] = get_options($this->wards->get_active_option(array('wrd_fk_clients' => $this->clnt_id, 'wrd_fk_areas' => $ars_id)), $wrd_id);
        $row['ars_option'] = get_options($this->areas->get_active_option(array('ars_fk_clients' => $this->clnt_id, 'ars_fk_taluks' => $tlk_id)), $ars_id);
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
        $options =  $this->families->get_members_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_fmly_deactivate')) {
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
        $row = $this->families->get_by_member_id($mbr_id);
        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }
        $this->families->deactivate_member($mbr_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_fmly_activate')) {
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
        $row = $this->families->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }

        $this->families->activate_member($mbr_id);
        echo json_encode($json);
        return;
    }
}
