<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class central_stores extends My_controller
{
    private $mbrtp_id;
    var $allowed;
    var $upload_dir;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'central_stores';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('states_mdl', 'states');
        $this->load->model('districts_mdl', 'districts');
        $this->load->model('taluks_mdl', 'taluks');
        $this->load->model('estores_mdl', 'estores');
        $this->load->model('member_categories_mdl', 'member_categories');
        $this->mbrtp_id = $this->central_stores->get_member_type_id(); // Member Type Id of central_stores
        // Allowed file types for Panchath licence
        $this->allowed = array('doc', 'docx', 'pdf', 'jpg', 'gif', 'jpeg', 'pjpeg', 'png', 'x-png');
        $this->upload_dir = get_clients_upload_dir();
    }

    public function index()
    {
        if (!has_task('tsk_cstr')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }
        $data['active_nav'] = 'members'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'central stores'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'Central stores';
        $data['icon'] = $this->tasks->get_icon(40);
        $data['cstr_cat_option'] = $this->central_stores->get_categories_option(array('cat_fk_clients' => $this->clnt_id));
        $data['mbr_option'] = $this->central_stores->get_members_option();
        $data['mbrtp_id'] = $this->mbrtp_id;
        $data['stt_option'] = $this->states->get_active_option();
        $data['cstr_option'] = $this->central_stores->get_active_option(array('cstr_fk_clients' => $this->clnt_id));

        // Checking user tasks
        $data['tsk_cstr_list'] = has_task('tsk_cstr_list');
        $data['tsk_cstr_add'] = has_task('tsk_cstr_add');
        $data['tsk_cstr_edit'] = has_task('tsk_cstr_edit');
        $data['tsk_cstr_conf'] = has_task('tsk_cstr_conf');
        $data['tsk_cstr_activate'] = has_task('tsk_cstr_activate');
        $data['tsk_cstr_deactivate'] = has_task('tsk_cstr_deactivate');
        $data['tsk_cstr_pdf'] = has_task('tsk_cstr_pdf');
        $data['tsk_cstr_excel'] = has_task('tsk_cstr_excel');
        $data['tsk_cstr_print'] = has_task('tsk_cstr_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('central_stores/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('mbr_id') && !has_task('tsk_cstr_edit')) || (!$this->input->post('mbr_id') && !has_task('tsk_cstr_add'))) {
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

        // Validating central_store Fields
        $v_config_1 = validationConfigs($this->table);

        // Validating Member Fields
        $v_config_2 = validationConfigs('members');

        $v_config = array_merge($v_config_1, $v_config_2);

        $this->form_validation->set_rules($v_config);

        $this->form_validation->set_rules('mbr_date', 'Date', 'callback_valid_date'); // don't want 'required' rule

        $cstr_lic_error = $this->validate_licence('cstr_lic');

        // Extra validation fields
        $this->form_validation->set_rules('cat_id', 'Category', 'required');

        if (!$this->form_validation->run() || $cstr_lic_error) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table, 'members'), array('cat_id')) : array();

            if ($cstr_lic_error) {
                $json['v_error']['cstr_lic_error'] = $cstr_lic_error;
            }

            echo json_encode($json);
            return;
        }

        $upload = $this->upload_licence();
        if ($upload['error']) {
            $json['status'] = 2;
            $json['o_error'] = $upload['error'];
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $mbr_id = $input['mbr_id'];
        $action = $input['mbr_id'] ? 'EDIT' : 'ADD';
        $input['mbr_fk_clients'] = $this->clnt_id;
        $input['cstr_fk_clients'] = $this->clnt_id;
        $input['cstr_name'] = $input['mbr_name'];
        $input['cstr_address1'] = $input['mbr_address'];
        $input['mbr_date'] =  get_sql_date();

        if ($input['mbr_fk_member_types'] != $this->mbrtp_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Member type not match';
            echo json_encode($json);
            return;
        }



        // If Editing
        if ($mbr_id) {
            $cstr_id = $this->central_stores->get_id_by_member($mbr_id);
            $cstr_lic = $this->central_stores->get_field_by_id($cstr_id, 'cstr_lic');

            // If having a new licence, Deleting previous one
            if ($cstr_lic) {
                $file = $this->upload_dir . '/' . $cstr_lic;
                if (is_file($file))
                    unlink($file);
            }
        }

        // If files to upload
        $input['cstr_lic'] = isset($upload['licence']) && $upload['licence'] ? $upload['licence'] : '';

        if (!$mbr_id = $this->central_stores->save_member($input, $mbr_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save central_store';
        }

        // Adding a default godown
        if ($action == 'ADD') {
            $this->load->model('godowns_mdl', 'godowns');
            $gdn['gdn_fk_clients'] = $this->clnt_id;
            $gdn['gdn_fk_central_stores'] = $this->central_stores->get_id_by_member($mbr_id);
            $gdn['gdn_name'] = 'Store 1';
            $this->godowns->save($gdn);
        }

        echo json_encode($json);
        return;
    }

    function upload_licence()
    {
        $error = '';
        $licence = array();

        if (!isset($_FILES['cstr_lic'])) {
            return array('error' => $error, 'licence' => $licence);
        }

        // If not set the upload dir
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }

        // if permision not set for UPLOAD directry. it should be 777 and its curresponding value is 16895
        if (fileperms($this->upload_dir) != 16895) {
            $error .= "<div>Set Permission for" . $this->upload_dir . " 777</div>";
        }

        // If product licence is uploaded.
        if (isset($_FILES['cstr_lic'])) {
            $uploaded = $this->do_upload('cstr_lic');
            if (isset($uploaded['upload_data']))
                $licence = $uploaded['upload_data']['file_name'];
            else
                $error .= $uploaded['error'];
        }

        return array('error' => $error, 'licence' => $licence);
    }

    /**
     * 
     * @param type $file_input: name of the $_FILES[input_name]
     * @param type $no: Just a value to make the file name unique.
     * @return type
     */
    function do_upload($file_input)
    {
        $config['upload_path'] = $this->upload_dir;
        $config['allowed_types'] = implode('|', $this->allowed);
        $config['file_name'] = "cstr_licence_" . time() . "_$this->usr_id";
        $this->load->library('upload');

        // Initializing uploader & Cleaning previous upload history if exist.
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_input)) {
            $error = array('error' => $this->upload->display_errors('', ''));

            return $error;
        } else {
            $data = array('upload_data' => $this->upload->data());

            return $data;
        }
    }



    function validate_licence($field)
    {
        if (!isset($_FILES[$field]))
            return "";

        $file_input = $_FILES[$field];

        // Get Image Dimension
        $fileinfo = @getimagesize($file_input["tmp_name"]);
        $width = isset($fileinfo[0]) ? $fileinfo[0] : 0;
        $height = isset($fileinfo[0]) ? $fileinfo[1] : 0;

        // Get image file extension
        $file_extension = pathinfo($file_input["name"], PATHINFO_EXTENSION);

        // Validate file input to check if is not empty
        // This case will ocure when user edited the file after it browsed by file input.
        if (!file_exists($file_input["tmp_name"])) {
            return "<div class=\"form-validation error\">File not found</div>";
        }

        // Validate file input to check if is with valid extension
        if (!in_array($file_extension, $this->allowed)) {
            return "<div class=\"form-validation error\">Only " . implode(', ', $this->allowed) . " file types allowed</div>";
        }

        // Validate image file size       
        if (($file_input["size"] > 2000000)) {
            return "<div class=\"form-validation error\">Image size exceeds 2MB</div>";
        }

        // Validate image file dimension
        if ($width > "1000" || $height > "1500") {
            return "<div class=\"form-validation error\">Image dimension should be within 1000 X 1500</div>";
        }

        return '';
    }


    function get_cstrs()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_list')) {
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

        $json['total_rows'] = $this->central_stores->index($input, TRUE);
        $json['central_store_data'] = $this->central_stores->index($input, FALSE);
        $json['num_rows'] =  count($json['central_store_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_cstrs");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        $json['estore_count'] = array();
        $json['cats'] = array();
        foreach ($json['central_store_data'] as &$row) {
            $json['cats'][$row['cstr_id']] = $this->members->get_categories($row['cstr_fk_members']);
            $row['cstr_lic'] = $row['cstr_lic'] ? $this->upload_dir . '/' . $row['cstr_lic'] : '';
            $json['estore_count'][$row['cstr_id']] = $this->estores->get_estores_by_central_store($row['cstr_id']);
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
        $row = $this->central_stores->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
        }

        $row['category'] =  $this->members->get_categories($mbr_id);

        $row['after_ajax'] = TRUE;
        $row['html'] = $this->load->view('central_stores/show_details', $row, true);
        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }




    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_edit')) {
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
        $row = $this->central_stores->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find central_store';
            echo json_encode($json);
            return;
        }

        $tlk_id = $row['cstr_fk_taluks'];
        $tlk_row = $this->taluks->get_by_id($tlk_id);
        $dst_id = $tlk_row['tlk_fk_districts'];
        $dst_row = $this->districts->get_by_id($dst_id);
        $stt_id = $dst_row['dst_fk_states'];

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
        $options =  $this->central_stores->get_active_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_deactivate')) {
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
        $cstr_id = $this->input->post('cstr_id');

        $row = $this->central_stores->get_by_member_id($mbr_id);
        $cstr_row = $this->central_stores->get_by_id($cstr_id);

        if (!$row || !$cstr_row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find central_store';
            echo json_encode($json);
            return;
        }

        $this->central_stores->deactivate_member($mbr_id);
        $this->central_stores->deactivate($cstr_id);

        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_cstr_activate')) {
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
        $cstr_id = $this->input->post('cstr_id');

        $row = $this->central_stores->get_by_member_id($mbr_id);
        $cstr_row = $this->central_stores->get_by_id($cstr_id);

        if (!$row || !$cstr_row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find central_store';
            echo json_encode($json);
            return;
        }

        $this->central_stores->activate_member($mbr_id);
        $this->central_stores->activate($cstr_id);
        echo json_encode($json);
        return;
    }

    function get_gdn_options()
    {
        $this->load->model('godowns_mdl', 'godowns');
        $cstr_id = $this->input->post('cstr_id');
        if (!$cstr_id) {
            $mbr_id = $this->input->post('mbr_id');
            $cstr_id = $this->central_stores->get_id_by_member($mbr_id);
        }
        $where['gdn_fk_central_stores'] = $cstr_id;
        $options =  $this->godowns->get_active_option($where);
        echo get_options($options, '', "Select", TRUE, FALSE, 'NO GODOWNS');
        return;
    }
}
