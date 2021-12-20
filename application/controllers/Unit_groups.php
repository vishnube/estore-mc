<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Unit_groups extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'unit_groups';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('units_mdl', 'units');
        $this->load->helper('unit');
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        // Validating unit_groups Fields
        $ugp = $this->validate_ugp_add();

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] =  validation_errors() ? get_val_errors('', $ugp) : array();
            echo json_encode($json);
            return;
        }


        // ugp_fk_clients, ugp_fk_units, ugp_is_basic, ugp_default, ugp_fk_bunits, ugp_rel, ugp_group_no,
        $input = $this->input->post();
        $basic_unt = $input['ugp_fk_units'][0];
        $next_group = $this->unit_groups->get_next_group_no();
        $ugp_name = $this->input->post('ugp_name');

        foreach ($input['ugp_fk_units'] as $i => $r) {
            $tbl_data = array();
            $tbl_data['ugp_fk_clients'] = $this->clnt_id;
            $tbl_data['ugp_name'] = $ugp_name;
            $tbl_data['ugp_fk_units'] = $r;
            $tbl_data['ugp_default'] = $input['ugp_default'][$i];
            $tbl_data['ugp_group_no'] = $next_group;
            $tbl_data['ugp_fk_bunits'] = $basic_unt; // Basic Unit of current unit.

            // First row contains basic unit
            if ($i == 0) {
                $tbl_data['ugp_is_basic'] = 1; // Basic Unit
                $tbl_data['ugp_rel'] = 1; // Relation with Basic Unit. (As it is itself a basic unit, it'll be 1)
            } else {
                $tbl_data['ugp_is_basic'] = 2; // Not a Basic Unit
                $tbl_data['ugp_rel'] = $input['ugp_rel'][$i]; // Relation with Basic Unit
            }

            $this->unit_groups->save($tbl_data);
        }

        echo json_encode($json);
        return;
    }

    function get_ugps()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['ugp_fk_clients'] = $this->clnt_id;
        $groups = $this->unit_groups->get_group_nos($this->clnt_id);

        //format_unit_group()  @ unit_helper
        $json['unit_group_data'] = format_unit_group($groups, $input);
        echo json_encode($json);
    }


    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $ugp_group_no = $this->input->post('ugp_group_no');
        $input['ugp_fk_clients'] = $this->clnt_id;
        $input['ugp_group_no'] =  $ugp_group_no;
        $units = $this->unit_groups->index($input);

        if (!$units) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find Unit Group';
            echo json_encode($json);
            return;
        }

        foreach ($units as $u)
            $this->unit_groups->deactivate($u['ugp_id']);

        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $ugp_group_no = $this->input->post('ugp_group_no');
        $input['ugp_fk_clients'] = $this->clnt_id;
        $input['ugp_group_no'] =  $ugp_group_no;
        $units = $this->unit_groups->index($input);

        if (!$units) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find Unit Group';
            echo json_encode($json);
            return;
        }

        foreach ($units as $u)
            $this->unit_groups->activate($u['ugp_id']);

        echo json_encode($json);
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        $ugp_group_no = $this->input->post('ugp_group_no');
        $input['ugp_fk_clients'] = $this->clnt_id;
        $input['ugp_group_no'] =  $ugp_group_no;
        $units = $this->unit_groups->index($input);

        if (!$units) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find Unit Group';
            echo json_encode($json);
            return;
        }

        $unt_option = $this->units->get_unit_option(ACTIVE, 2);
        $str = '';
        foreach ($units as $j => $r) {
            $rel = (float)$r['ugp_rel']; // Removing trailing zeros
            $str .= '<tr>';
            // If the basic unit
            if ($r['ugp_is_basic'] == 1) {
                $str .= '<td>
                <input type="hidden" name="ugp_id[]" class="ugp_id" value="' . $r['ugp_id'] . '">
                <input type="hidden" name="ugp_fk_units[]" class="ugp_fk_units" value="' . $r['ugp_fk_units'] . '">
                <span class="btn btn-info">' . $unt_option[$r['ugp_fk_units']] . '</span></td>';
                $str .= '<td><input type="hidden" name="ugp_rel[]" class="ugp_rel" value="' . $rel . '"></td>';
            } else {
                $str .= '<td>
                            <input type="hidden" name="ugp_id[]" class="ugp_id" value="' . $r['ugp_id'] . '">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-info">1</span>
                                </div>
                                <select name="ugp_fk_units[]" class="ugp_fk_units form-control form-control-sm">' . get_options($unt_option, $r['ugp_fk_units']) . '</select>
                            </div>
                        </td>';

                $str .= '<td>
                            <div class="input-group  input-group-sm mb-3">
                                <input type="text" name="ugp_rel[]" value="' . $rel . '" class="ugp_rel form-control form-control-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text basic_unt_txt bg-info">' . $unt_option[$r['ugp_fk_bunits']] . '</span>
                                </div>
                            </div>
                        </td>';
            }
            $checked =  $r['ugp_default'] == 1 ? ' checked=""' : '';
            $str .= '<td>
                        <div class="form-group clearfix"> 
                            <div class="sr-wraper sr-rad icheck-danger d-inline">
                                <input type="radio" value="' . $r['ugp_id'] . '" name="ugp_default" id="edit_ugp_default' . $r['ugp_id'] . '" class="ugp_default"' . $checked . '>
                                <label for="edit_ugp_default' . $r['ugp_id'] . '">Default Unit</label>
                            </div>
                        </div>
                    </td>';

            $str .= '</tr>';
        }

        $json['html'] = $str;
        echo json_encode($json);
        return;
    }

    function edit()
    {
        //Checking tasks
        if (!has_task('tsk_prd_conf')) {
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

        // Validating unit_groups Fields
        $ugp = $this->validate_ugp_edit();

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] =  validation_errors() ? get_val_errors('', $ugp) : array();
            echo json_encode($json);
            return;
        }

        $input = $this->input->post();
        $ugp_ids = $input['ugp_id'];
        $default_unit = $input['ugp_default'];
        $ugp_name = $this->input->post('ugp_name');

        foreach ($ugp_ids as $i => $ugp_id) {
            $tbl_data = array();
            $tbl_data['ugp_name'] = $ugp_name;
            $tbl_data['ugp_fk_units'] = $input['ugp_fk_units'][$i];
            $tbl_data['ugp_default'] = $ugp_id == $default_unit ? 1 : 2;
            $tbl_data['ugp_rel'] = $input['ugp_rel'][$i];
            $this->unit_groups->save($tbl_data, $ugp_id);
        }

        echo json_encode($json);
        return;
    }

    function validate_ugp_edit()
    {
        $ugp_fk_units = $this->input->post('ugp_fk_units');
        $units = array();
        $ugp_rel = $this->input->post('ugp_rel');
        $ugp = array();
        $rel = array(); // To store ugp_rel;
        $ugp_default = $this->input->post('ugp_default');

        foreach ($ugp_fk_units as $k => $v) {

            // Validating Units
            $unit_rules = "required|callback_db_query";
            if (in_array($v, $units))
                $unit_rules .= "|callback_dup_unit";
            $units[] = $v;
            $this->form_validation->set_rules("ugp_fk_units[$k]", "Unit", $unit_rules);

            // Validating Relations
            // There won't be relation for basic unit (ie: $k=0). So no need to validate this.
            if ($k >= 1) {
                $relation_rules = "required|numeric|callback_db_query";

                // Relative value shouldn't be 1
                if ($ugp_rel[$k] == 1)
                    $relation_rules .= "|callback_no_one";
                if (in_array($ugp_rel[$k], $rel))
                    $relation_rules .= "|callback_dup_relation";
                $rel[] = $ugp_rel[$k];
                $this->form_validation->set_rules("ugp_rel[$k]", "Relation", $relation_rules);
            }

            $ugp[] = "ugp_fk_units[$k]";
            $ugp[] = "ugp_rel[$k]";
            $ugp[] = "ugp_default[$k]";
        }

        if (!$ugp_default)
            $this->form_validation->set_rules("ugp_default", "Default", "callback_min_default");

        $this->form_validation->set_rules("ugp_name", "Group Name", 'required|callback_db_query|max_length[100]');
        $ugp[] = 'ugp_name';

        return $ugp;
    }

    function validate_ugp_add()
    {
        $ugp_fk_units = $this->input->post('ugp_fk_units');
        $units = array();
        $ugp_rel = $this->input->post('ugp_rel');
        $ugp = array();
        $default = 0;
        $rel = array(); // To store ugp_rel;
        $ugp_default = $this->input->post('ugp_default');

        foreach ($ugp_fk_units as $k => $v) {

            // Validating Units
            $unit_rules = "required|callback_db_query";
            if (in_array($v, $units))
                $unit_rules .= "|callback_dup_unit";
            $units[] = $v;
            $this->form_validation->set_rules("ugp_fk_units[$k]", "Unit", $unit_rules);

            // Validating Relations
            // There won't be relation for basic unit (ie: $k=0). So no need to validate this.
            if ($k >= 1) {
                $relation_rules = "required|numeric|callback_db_query";

                // Relative value shouldn't be 1
                if ($ugp_rel[$k] == 1)
                    $relation_rules .= "|callback_no_one";
                if (in_array($ugp_rel[$k], $rel))
                    $relation_rules .= "|callback_dup_relation";
                $rel[] = $ugp_rel[$k];
                $this->form_validation->set_rules("ugp_rel[$k]", "Relation", $relation_rules);
            }
            if ($ugp_default[$k] == 1) {
                $default++;
            }

            if ($default >= 2 && $ugp_default[$k] == 1)
                $this->form_validation->set_rules("ugp_default[$k]", "Default", "callback_valid_default");

            $ugp[] = "ugp_fk_units[$k]";
            $ugp[] = "ugp_rel[$k]";
            $ugp[] = "ugp_default[$k]";
        }
        if (!$default)
            $this->form_validation->set_rules("ugp_default[0]", "Default", "callback_min_default");

        $this->form_validation->set_rules("ugp_name", "Group Name", 'required|callback_db_query|max_length[100]');
        $ugp[] = 'ugp_name';

        return $ugp;
    }

    // Relation should not be 1
    function no_one($val)
    {
        $this->form_validation->set_message('no_one', "Shouldn't be 1");
        return FALSE;
    }


    function valid_default($val)
    {
        $this->form_validation->set_message('valid_default', "Only one 'Default' allowed");
        return FALSE;
    }


    function min_default($val)
    {
        $this->form_validation->set_message('min_default', "Atleast one should be 'Default'");
        return FALSE;
    }

    // Duplicate unit
    function dup_unit($val)
    {
        $this->form_validation->set_message('dup_unit', "Unit already given");
        return FALSE;
    }

    // Duplicate relations
    function dup_relation($val)
    {
        $this->form_validation->set_message('dup_relation', "Relation already given");
        return FALSE;
    }
}
