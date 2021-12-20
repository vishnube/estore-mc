<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Price_groups extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'price_groups';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("products_mdl", "products");
        $this->load->model("unit_groups_mdl", "unit_groups");
        $this->load->model('states_mdl', 'states');
        $this->load->model('central_stores_mdl', 'central_stores');
        $this->load->model('product_categories_mdl', 'product_categories');
    }



    public function index()
    {
        if (!has_task('tsk_pgp')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'particulars'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'price groups'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'price groups';
        $data['icon'] = $this->tasks->get_icon(179);

        // For Stockflow @ stock_avg\stock_flow.php
        $cstr_mbrtp_id = $this->central_stores->get_member_type_id();
        $data['cstr_mbr_option'] = $this->central_stores->get_members_option(array('mbr_fk_clients' => $this->clnt_id), ACTIVE, $cstr_mbrtp_id);

        $data['pgp_option'] =  $this->price_groups->get_active_option(array('pgp_fk_clients' => $this->clnt_id));
        $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
        $data['stt_option'] = $this->states->get_active_option();
        $data['cstr_option'] = $this->central_stores->get_active_option(array('cstr_fk_clients' => $this->clnt_id));
        $data['pct_option'] = $this->product_categories->get_active_option(array('pct_fk_clients' => $this->clnt_id, 'pct_parent' => 0));

        // Checking user tasks
        $data['tsk_pgp_list'] = has_task('tsk_pgp_list');
        $data['tsk_pgp_add'] = has_task('tsk_pgp_add');
        $data['tsk_pgp_edit'] = has_task('tsk_pgp_edit');
        $data['tsk_pgp_conf'] = has_task('tsk_pgp_conf');
        $data['tsk_pgp_activate'] = has_task('tsk_pgp_activate');
        $data['tsk_pgp_deactivate'] = has_task('tsk_pgp_deactivate');
        $data['tsk_pgp_pdf'] = has_task('tsk_pgp_pdf');
        $data['tsk_pgp_excel'] = has_task('tsk_pgp_excel');
        $data['tsk_pgp_print'] = has_task('tsk_pgp_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('price_groups/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('pgp_id') && !has_task('tsk_pgp_edit')) || (!$this->input->post('pgp_id') && !has_task('tsk_pgp_add'))) {

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
        $pgp_id = $input['pgp_id'];
        $input['pgp_fk_clients'] = $this->clnt_id;
        $input['pgp_date'] =  get_sql_date_time();

        // If Edit
        if ($pgp_id) {
            $prv_data = $this->price_groups->get_by_id($pgp_id);
            if (!$prv_data) {
                $json['status'] = 2;
                $json['o_error'] = 'Couldn\'t find price group';
                echo json_encode($json);
                return;
            }
        }

        if (!$this->price_groups->save($input, $pgp_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save price group';
        }

        echo json_encode($json);
        return;
    }

    function get_pgps()
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
        // $input['usr_id'] = $this->usr_id;
        // $input['usr_type'] = $this->usr_type;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->price_groups->index($input, TRUE);
        $json['price_group_data'] = $this->price_groups->index($input, FALSE);
        $json['num_rows'] =  count($json['price_group_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_pgps");
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

        $pgp_id = $this->input->post('pgp_id');
        $row = $this->price_groups->get_by_id($pgp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->price_groups->get_active_option(array('pgp_fk_clients' => $this->clnt_id));
        echo get_options($options);
        return;
    }

    function deactivate()
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

        $pgp_id = $this->input->post('pgp_id');
        $row = $this->price_groups->get_by_id($pgp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
            echo json_encode($json);
            return;
        }

        $this->price_groups->deactivate($pgp_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_activate')) {

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

        $pgp_id = $this->input->post('pgp_id');
        $row = $this->price_groups->get_by_id($pgp_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
            echo json_encode($json);
            return;
        }

        $this->price_groups->activate($pgp_id);
        echo json_encode($json);
        return;
    }
}
