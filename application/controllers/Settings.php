<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Settings extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'settings';
        $this->load->model('settings_categories_mdl', 'settings_categories');
        $this->load->model('settings_keys_mdl', 'settings_keys');
    }

    public function index()
    {
        $data['active_nav'] = 'Development'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'settings'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'settings';
        $data['icon'] = $this->tasks->get_icon(8);

        $data['stct_option'] = $this->settings_categories->get_active_option();
        $data['stky_option'] = $this->settings_keys->get_active_option();
        $data['st_option'] = $this->settings->get_active_option();

        $data['input_types'] = $this->settings->get_input_types();
        $data['ref_tables'] = $this->settings->get_ref_tables();

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('settings/index', $data);
    }

    function save()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Settings Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        $o_err = $this->pval_check();

        if (!$this->form_validation->run() || $o_err) {
            $json['status'] = 2; // Failure;
            $json['o_error'] .= implode('<br>', $o_err);
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : ''; // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->input->post();
        $st_id = $input['st_id'];
        $action = !$st_id ? 'ADD' : 'EDIT';
        $input['st_sort'] = $input['st_sort'] ? $input['st_sort'] : $this->settings->next_sort();

        if (!$st_id) {
            $input['st_date'] = get_sql_date();
            $input['st_fk_versions'] = $this->versions->get_latest();
        }

        // If inputType is Textbox/Textarea, there should not be 'possible values'.
        $input['st_pval'] =  ($input['st_input'] == 1 || $input['st_input']  == 5) ? '' : serialize(array_combine($input['st_pval']['key'], $input['st_pval']['val']));

        $q = array(); // For query

        $ref_tbl = $this->input->post('st_ref_tbl');

        if ($ref_tbl) {
            if (count($ref_tbl) > 1 && $action == 'EDIT') {
                $json['status'] = 2;
                $json['o_error'] = 'Multiple "Reference Tables" are not allowed in EDIT action';
                echo json_encode($json);
                return;
            }

            foreach ($ref_tbl as $ref) {
                $input['st_ref_tbl'] = $ref;
                $q[] = $this->save_me($input, $st_id, $action);
            }
        } else
            $q[] = $this->save_me($input, $st_id, $action);

        $json['query'] = implode("\n", $q);
        echo json_encode($json);
        return;
    }

    function save_me($input, $st_id, $action)
    {
        $st_id = $this->settings->save($input, $st_id);

        $q = $this->settings->get_last_query();

        // Adding settings values to all clients
        if ($action == 'ADD') {
            $clients = $this->clients->get_data();
            foreach ($clients as $row) {
                $d = array();
                $d['cst_fk_clients'] = $row['clnt_id'];
                $d['cst_fk_settings'] = $st_id;
                $d['cst_val'] = $input['st_dval'];
                $d['cst_usertype'] = $input['st_dusertype'];
                $this->client_settings->save($d);
            }
        }

        return $q;
    }

    // function test()
    // {
    //     // Adding all settings to client
    //     $s = $this->settings->get_data();
    //     foreach ($s as $r) {
    //         $d = array();
    //         $d['cst_fk_clients'] = $this->clnt_id;
    //         $d['cst_fk_settings'] = $r['st_id'];
    //         $d['cst_val'] = $r['st_dval'];
    //         $d['cst_usertype'] = $r['st_dusertype'];
    //         $this->client_settings->save($d);
    //     }
    // }

    function get_sts()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        //$json['input_types'] = $this->settings->get_input_types();
        $json['user_types'] = $this->settings->get_user_types();
        $json['ref_tables'] = $this->settings->get_ref_tables();
        $json['version_option'] = $this->versions->get_options();

        $input = $this->get_inputs();
        $input['per_page'] = 50;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $input['clnt_id'] = $this->clnt_id;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->settings->index($input, TRUE);
        $json['settings_data'] = $this->settings->index($input, FALSE);

        foreach ($json['settings_data'] as &$row) {
            $row['st_pval'] = unserialize($row['st_pval']);
        }

        $json['num_rows'] =  count($json['settings_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_sts");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        echo json_encode($json);
    }


    function get_user_settings()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        //$json['ref_tables'] = $this->settings->get_ref_tables();
        $json['user_types'] = $this->settings->get_user_types();
        $json['version_option'] = $this->versions->get_options();

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;

        $json['settings_data'] = $this->settings->get_user_settings($input);

        foreach ($json['settings_data'] as &$row) {
            $row['st_pval'] = unserialize($row['st_pval']);
            $row['ref_tbl_name']  = $row['st_ref_tbl'] ? $this->settings->get_ref_tables($row['st_ref_tbl']) : '';
        }

        echo json_encode($json);
    }



    function save_user_settings()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->input->post();
        $cst_id = $input['cst_id'];
        $row = $this->client_settings->get_by_id($cst_id);
        $st_row = $this->settings->get_by_id($row['cst_fk_settings']);

        if (!$row || !$st_row) {
            $json['status'] = 2; // Failure;
            $json['o_error'] = "Settings not found";
            echo json_encode($json);
            return;
        }

        if ($st_row['st_validation']) {
            $this->form_validation->set_rules('cst_val', 'Value', $st_row['st_validation']);
            if (!$this->form_validation->run()) {
                $json['status'] = 2; // Failure;
                $json['v_error']['cst_val'] = validation_errors() ? form_error('cst_val') : '';
                echo json_encode($json);
                return;
            }
        }

        if (!$this->client_settings->save($input, $cst_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save settings';
        }

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

        $st_id = $this->input->post('st_id');
        $row = $this->settings->get_by_id($st_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
        }

        $row['st_pval'] = unserialize($row['st_pval']);

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

        $st_id = $this->input->post('st_id');
        $row = $this->settings->get_by_id($st_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
        }

        $row['v_name'] = $this->versions->get_name_by_id($row['st_fk_versions']);
        $row['st_dusertype'] = $this->settings->get_user_types($row['st_dusertype']);
        $row['st_ref_tbl'] = $this->settings->get_ref_tables($row['st_ref_tbl']);
        $row['category'] = $this->settings_categories->get_name_by_id($row['st_fk_settings_categories']);
        $row['st_pval'] = unserialize($row['st_pval']);


        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->settings->get_active_option($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $st_id = $this->input->post('st_id');
        $row = $this->settings->get_by_id($st_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
            echo json_encode($json);
            return;
        }

        $this->settings->deactivate($st_id);
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

        $st_id = $this->input->post('st_id');
        $row = $this->settings->get_by_id($st_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find settings';
            echo json_encode($json);
            return;
        }

        $this->settings->activate($st_id);
        echo json_encode($json);
        return;
    }



    function toggle_user()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $cst_id = $this->input->post('cst_id');
        $cur_usrtp = $this->input->post('cst_usertype');
        $new_usrtp = toggle($cur_usrtp, array(1, 2));

        $tbl_data['cst_usertype'] = $new_usrtp;

        if (!$this->client_settings->save($tbl_data, $cst_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save settings';
            echo json_encode($json);
            return;
        }

        $user_types = $this->settings->get_user_types();
        $json['cst_usertype'] = $new_usrtp;
        $json['txt_usrtp'] = $user_types[$new_usrtp];
        echo json_encode($json);
        return;
    }

    function pval_check()
    {
        $input_type = $this->input->post('st_input');

        // If inputType is Textbox/Textarea, no need to validate. Because there will not be any developer defined values to validate.
        if ($input_type == 1 || $input_type == 5)
            return array();

        $keys = $this->input->post("st_pval[key]");
        $vals = $this->input->post("st_pval[val]");

        // Taking count of each values of $keys
        $count = array_count_values($keys);
        $err = array();

        // Checking for uniqueness.
        foreach ($count as $k => $n) {
            // If key occures more than one times.
            if ($n > 1)
                $err[] = "Duplicate ($n times) entry for key '$k'.";
        }

        // Checking for empty values
        for ($i = 0; $i < count($keys); $i++) {
            if ((!$keys[$i] && ($keys[$i] !== "0")) || (!$vals[$i] && ($vals[$i] !== "0")))
                $err[] = "Empty value encountered @ row " . ($i + 1);
        }

        return $err;
    }
}
