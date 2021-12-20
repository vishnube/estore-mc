<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Gstnumbers extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'gstnumbers';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("members_mdl", "members");
    }

    // Atleast 1 DEFAULT member is required in a member.
    function check_default($val)
    {
        if ($this->input->post('gst_default'))
            return;

        $mbr_id = $this->input->post('gst_fk_members');
        $where['gst_fk_members'] = $mbr_id;
        $where['gst_default'] = 1;
        $default = $this->gstnumbers->get_row($where);
        if (!$default) {
            $this->form_validation->set_message('check_default', "Atleast one should be default");
            return FALSE;
        }
        return TRUE;
    }

    function save()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating GST DETAILS Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $gst_id = $input['gst_id'];
        $mbr_id = $input['gst_fk_members'];
        $action = $gst_id ? 'EDIT' : 'ADD';
        $input['gst_fk_clients'] = $this->clnt_id;
        $input['gst_default'] = isset($input['gst_default']) ? 1 : 2;

        // Only one DEFAULT member is allowed in a member.
        // If this is the DEFAULT member of the member, Setting others NOT-DEFAULT.
        if ($input['gst_default'] == 1) {
            $this->gstnumbers->update_where(array('gst_default' => 2), array('gst_fk_members' => $mbr_id));
        } else if ($action == 'EDIT') {
            $prev = $this->gstnumbers->get_by_id($gst_id);

            // If previously it was the DEFAULT member, User need to find a new DEFAULT member before edit.
            if ($prev['gst_default'] == 1) {
                $json['status'] = 2;
                $json['o_error'] = 'Please set another default GST details for this member';
                echo json_encode($json);
                return;
            }
        }

        $this->gstnumbers->save($input, $gst_id);
        echo json_encode($json);
        return;
    }


    function before_edit()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $gst_id = $this->input->post('gst_id');
        $row = $this->gstnumbers->get_by_id($gst_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
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
        $data['mbr_row'] = $this->members->get_by_member_id2($mbr_id);

        if (!$data['mbr_row']) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        $data['gst_data'] = $this->gstnumbers->get_gst($this->clnt_id, $mbr_id, '');

        $data['after_ajax'] = TRUE;
        $data['html'] = $this->load->view('gstnumbers/show_details', $data, true);
        $json = array_merge($json, $data);
        echo json_encode($json);
        return;
    }

    // function get_options()
    // {
    //     $input = $this->input->post('mbr_id');
    //     $options =  $this->gstnumbers->get_members_option($input);
    //     echo get_options($options);
    //     return;
    // }

    function deactivate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $gst_id = $this->input->post('gst_id');
        $row = $this->gstnumbers->get_by_id($gst_id);
        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        // DEFAULT GST details couldn't be deactivated
        if ($row['gst_default'] == 1) {
            $json['status'] = 2;
            $json['o_error'] = 'Can\'t deactivate, This is the default GST details of the member';
            echo json_encode($json);
            return;
        }

        $this->gstnumbers->deactivate($gst_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $gst_id = $this->input->post('gst_id');
        $row = $this->gstnumbers->get_by_id($gst_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        $this->gstnumbers->activate($gst_id);
        echo json_encode($json);
        return;
    }
}
