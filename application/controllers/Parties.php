<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Parties extends My_controller
{
    private $mbrtp_id;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'parties';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('categories_mdl', 'categories');
        $this->load->model('member_categories_mdl', 'member_categories');
        $this->load->model('gstnumbers_mdl', 'gstnumbers');
        $this->mbrtp_id = $this->parties->get_member_type_id(); // Member Type Id of Parties
    }

    public function index()
    {
        if (!has_task('tsk_prty')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'members'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'parties'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'parties';
        $data['icon'] = $this->tasks->get_icon(83);
        $data['cat_option'] = $this->parties->get_categories_option(array('cat_fk_clients' => $this->clnt_id));
        $data['mbr_option'] = $this->parties->get_members_option();
        $data['mbrtp_id'] = $this->mbrtp_id;

        // Checking user tasks
        $data['tsk_prty_list'] = has_task('tsk_prty_list');
        $data['tsk_prty_add'] = has_task('tsk_prty_add');
        $data['tsk_prty_edit'] = has_task('tsk_prty_edit');
        $data['tsk_prty_conf'] = has_task('tsk_prty_conf');
        $data['tsk_prty_activate'] = has_task('tsk_prty_activate');
        $data['tsk_prty_deactivate'] = has_task('tsk_prty_deactivate');
        $data['tsk_prty_pdf'] = has_task('tsk_prty_pdf');
        $data['tsk_prty_excel'] = has_task('tsk_prty_excel');
        $data['tsk_prty_print'] = has_task('tsk_prty_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('parties/index', $data);
    }


    function valid_default_meber($val)
    {
        $this->form_validation->set_message('valid_default_meber', "Only one 'Default' allowed");
        return FALSE;
    }


    function min_default($val)
    {
        $this->form_validation->set_message('min_default', "Atleast one should be 'Default'");
        return FALSE;
    }

    function validate_gst()
    {
        // If edit
        if ($this->input->post('mbr_id'))
            return array();

        $gst_name = $this->input->post('gst_name');
        $gst = array();
        $default = 0;
        $gst_default = $this->input->post('gst_default');

        foreach ($gst_name as $k => $v) {
            // User can be add parties if he has no gst details. So validating only if more GST details entered.
            if (count($gst_name) > 1)
                $this->form_validation->set_rules("gst_name[$k]", "GST", "required|callback_db_query|max_length[70]");
            else
                $this->form_validation->set_rules("gst_name[$k]", "GST", "callback_db_query|max_length[70]");

            $this->form_validation->set_rules("gst_fks_states[$k]", "State", "required|callback_db_query");
            if ($gst_default[$k] == 1) {
                $default++;
            }
            if ($default >= 2 && $gst_default[$k] == 1)
                $this->form_validation->set_rules("gst_default[$k]", "Default", "callback_valid_default_meber");

            $gst[] = "gst_name[$k]";
            $gst[] = "gst_fks_states[$k]";
            $gst[] = "gst_default[$k]";
        }
        if (!$default)
            $this->form_validation->set_rules("gst_default[0]", "Default", "callback_min_default");

        return $gst;
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('mbr_id') && !has_task('tsk_prty_edit')) || (!$this->input->post('mbr_id') && !has_task('tsk_prty_add'))) {
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

        // Validating Party Fields
        $v_config_1 = validationConfigs($this->table);

        // Validating Member Fields
        $v_config_2 = validationConfigs('members');

        $v_config = array_merge($v_config_1, $v_config_2);

        $this->form_validation->set_rules($v_config);

        // Extra validation fields
        $this->form_validation->set_rules('cat_id[]', 'Category', 'callback_required');

        // Validating Tbl: gstnumbers
        $gst = $this->validate_gst();

        $others = $gst;
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
        $input['prty_fk_clients'] = $this->clnt_id;

        if ($input['mbr_fk_member_types'] != $this->mbrtp_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Member type not match';
            echo json_encode($json);
            return;
        }

        $input['mbr_date'] =  get_sql_date($input['mbr_date']);

        if (!$mbr_id = $this->parties->save_member($input, $mbr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save party';
        }

        if ($action == 'ADD') {
            foreach ($input['gst_name'] as $k => $v) {
                if (!$input['gst_name'][$k])
                    continue;
                $gst_data = array();
                $gst_data['gst_fk_clients'] = $this->clnt_id;
                $gst_data['gst_fk_members'] = $mbr_id;
                $gst_data['gst_name'] = $input['gst_name'][$k];
                $gst_data['gst_fks_states'] = $input['gst_fks_states'][$k];
                $gst_data['gst_default'] = $input['gst_default'][$k];
                $this->gstnumbers->save($gst_data);
            }
        }

        echo json_encode($json);
        return;
    }

    function get_prtys()
    {
        //Checking tasks
        if (!has_task('tsk_prty_list')) {
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
        $input['prty_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->parties->index($input, TRUE);
        $json['party_data'] = $this->parties->index($input, FALSE);
        $json['num_rows'] =  count($json['party_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_prtys");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        // Reading member categories
        $json['cats'] = array();
        foreach ($json['party_data'] as &$row) {
            $json['cats'][$row['mbr_id']] = $this->members->get_categories($row['mbr_id']);
            $row['gst'] = $this->gstnumbers->get_gst($this->clnt_id, $row['mbr_id']);

            // Formating Address
            $addr = array();
            if ($row['mbr_address'])
                $addr[] = nl2br($row['mbr_address']);
            if ($row['prty_address2'])
                $addr[] = $row['prty_address2'];
            if ($row['prty_address3'])
                $addr[] = $row['prty_address3'];
            $row['addr'] = implode('<br>', $addr);
            $row['addr_comma'] = implode(', ', $addr);

            // Formating Contacts
            $con = array();
            if ($row['prty_mob1'])
                $con[] = $row['prty_mob1'];
            if ($row['prty_mob2'])
                $con[] = $row['prty_mob2'];
            $row['contacts'] = implode('<br>', $con);
            $row['contacts_comma'] = implode(', ', $con);
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
        $row = $this->parties->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }

        $row['category'] =  $this->members->get_categories($mbr_id);
        $row['after_ajax'] = TRUE;


        // Formating Address
        $addr = array();
        if ($row['mbr_address'])
            $addr[] = nl2br($row['mbr_address']);
        if ($row['prty_address2'])
            $addr[] = $row['prty_address2'];
        if ($row['prty_address3'])
            $addr[] = $row['prty_address3'];
        $row['addr'] = $addr;

        // Formating Contacts
        $con = array();
        if ($row['prty_mob1'])
            $con[] = $row['prty_mob1'];
        if ($row['prty_mob2'])
            $con[] = $row['prty_mob2'];
        $row['contacts'] = $con;

        $json['html'] = $this->load->view('parties/show_details', $row, true);

        echo json_encode($json);
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_prty_edit')) {
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
        $row = $this->parties->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find party';
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
        $options =  $this->parties->get_members_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_prty_deactivate')) {
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
        $row = $this->parties->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find party';
            echo json_encode($json);
            return;
        }

        $this->parties->deactivate_member($mbr_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_prty_activate')) {
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
        $row = $this->parties->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find party';
            echo json_encode($json);
            return;
        }

        $this->parties->activate_member($mbr_id);
        echo json_encode($json);
        return;
    }
}
