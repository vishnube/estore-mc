<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Family_members extends My_controller
{
    private $mbrtp_id;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'family_members';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("families_mdl", "families");
        $this->mbrtp_id = $this->family_members->get_member_type_id(); // Member Type Id of Families
    }




    // Atleast 1 PRIMARY member is required in a family.
    function check_prim($val)
    {
        if ($this->input->post('fmlm_is_prime'))
            return;

        $fmly_id = $this->input->post('fmlm_fk_families');
        $where['fmlm_fk_families'] = $fmly_id;
        $where['fmlm_is_prime'] = 1;
        $prime = $this->family_members->get_row($where);
        if (!$prime) {
            $this->form_validation->set_message('check_prim', "Atleast one PRIMARY member is required");
            return FALSE;
        }
        return TRUE;
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('fmlm_id') && !has_task('tsk_fmly_edit')) || (!$this->input->post('fmlm_id') && !has_task('tsk_fmly_add'))) {
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



        // Validating Family member Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table, 'members')) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $mbr_id = $input['mbr_id'];
        $fmly_id = $input['fmlm_fk_families'];
        $action = $mbr_id ? 'EDIT' : 'ADD';


        $input['mbr_fk_clients'] = $this->clnt_id;
        $input['mbr_fk_member_types'] =  $this->mbrtp_id; // Member Type Id of family_members
        $input['mbr_name'] = $input['fmlm_name'];

        if ($action == 'ADD')
            $input['mbr_date'] = get_sql_date();

        $input['fmlm_fk_clients'] = $this->clnt_id;
        $input['fmlm_dob'] = get_sql_date($input['fmlm_dob']);
        $input['fmlm_is_prime'] = isset($input['fmlm_is_prime']) ? 1 : 2;

        // Only one PRIME member is allowed in a family.
        // If this is the PRIME member of the family, Setting others NOT-PRIME.
        if ($input['fmlm_is_prime'] == 1)
            $this->family_members->update_where(array('fmlm_is_prime' => 2), array('fmlm_fk_families' => $fmly_id));
        else if ($action == 'EDIT') {
            $prev = $this->family_members->get_by_member_id($mbr_id);

            // If previously it was the PRIME member, User need to find a new PRIME member before edit.
            if ($prev['fmlm_is_prime'] == 1) {
                $json['status'] = 2;
                $json['o_error'] = 'You need to set a new PRIMARY member for this family';
                echo json_encode($json);
                return;
            }
        }

        $mbr_id = $this->family_members->save_member($input, $mbr_id);

        if (!$mbr_id) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save member';
        }


        echo json_encode($json);
        return;
    }


    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_fmly_edit')) {
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
        $row = $this->family_members->get_by_member_id($mbr_id);

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
        $data['fmly_row'] = $this->families->get_by_member_id($mbr_id);

        if (!$data['fmly_row']) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find family';
            echo json_encode($json);
            return;
        }

        $fmly_id = $data['fmly_row']['fmly_id'];
        $data['fmlm_data'] = $this->family_members->get_family_members_by_family($fmly_id, '', 'all');

        $data['after_ajax'] = TRUE;
        $data['html'] = $this->load->view('family_members/show_details', $data, true);
        $json = array_merge($json, $data);
        echo json_encode($json);
        return;
    }

    // function get_options()
    // {
    //     $input = $this->get_inputs();
    //     $options =  $this->family_members->get_members_option($input);
    //     echo get_options($options);
    //     return;
    // }

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
        $row = $this->family_members->get_by_member_id($mbr_id);
        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        // PRIMARY member couldn't be deactivated
        if ($row['fmlm_is_prime'] == 1) {
            $json['status'] = 2;
            $json['o_error'] = 'Can\'t deactivate the PRIMARY member';
            echo json_encode($json);
            return;
        }


        $this->family_members->deactivate_member($mbr_id);
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
        $row = $this->family_members->get_by_member_id($mbr_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find member';
            echo json_encode($json);
            return;
        }

        $this->family_members->activate_member($mbr_id);
        echo json_encode($json);
        return;
    }
}
