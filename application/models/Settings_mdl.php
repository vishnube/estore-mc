<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('settings', 'st');
    }

    public function index($input, $flag)
    {

        if (ifSetInput($input, 'st_id'))
            $this->db->where('st_id', $input['st_id']);

        if (ifSetInput($input, 'st_ref_tbl'))
            $this->db->where('st_ref_tbl', $input['st_ref_tbl']);

        if (ifSetInput($input, 'cst_usertype'))
            $this->db->where('cst_usertype', $input['cst_usertype']);

        if (ifSetInput($input, 'st_dusertype'))
            $this->db->where('st_dusertype', $input['st_dusertype']);

        if (ifSetInput($input, 'stct_id')) {
            $q = $this->array_query($input['stct_id'], 'stct_id');
            $this->db->where($q);
        }

        if (ifSetInput($input, 'stky_id')) {
            $q = $this->array_query($input['stky_id'], 'stky_id');
            $this->db->where($q);
        }

        if (ifSetInput($input, 'st_status'))
            $this->db->where('st_status', $input['st_status']);

        $this->db->where('stct_id = st_fk_settings_categories');
        $this->db->where('stky_id = st_fk_settings_keys');
        $this->db->where('cst_fk_settings = st_id');
        $this->db->where('cst_fk_clients', $input['clnt_id']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get("$this->table,settings_categories,settings_keys,client_settings");
            $result = $query->result_array();
            return $result[0]['allcount'];
        } else {
            $this->db->select('*');
            $this->db->order_by('st_ref_tbl', 'asc');
            $this->db->order_by('stct_sort', 'asc');
            //$this->db->order_by('stky_name', 'asc');
            $this->db->order_by('st_sort', 'asc');
            $R = $this->db->get("$this->table,settings_categories,settings_keys,client_settings", $input['per_page'], $input['offset'])->result_array();
            return $R;
        }
    }

    function get_settings_value($clnt_id, $st_id = '', $ref_table = '', $cat_id = '', $key_id = '', $key_name = '')
    {
        $this->db->from("$this->table, settings_keys,client_settings");
        $this->db->select('cst_val');
        if ($st_id)
            $this->db->where('st_id', $st_id);
        if ($ref_table)
            $this->db->where('st_ref_tbl', $ref_table);
        if ($cat_id)
            $this->db->where('st_fk_settings_categories', $cat_id);
        if ($key_id)
            $this->db->where('st_fk_settings_keys', $key_id);
        if ($key_name)
            $this->db->where('stky_name', $key_name);

        $this->db->where('stky_id = st_fk_settings_keys');
        $this->db->where('cst_fk_settings = st_id');
        $this->db->where('cst_fk_clients', $clnt_id);

        $R = $this->db->get()->row_array();
        return $R['cst_val'];
    }


    public function get_user_settings($input)
    {
        $this->db->from("$this->table, settings_categories, settings_keys,client_settings");
        if (ifSetInput($input, 'user_settings_reftbl'))
            $this->db->where('st_ref_tbl', $input['user_settings_reftbl']);

        $this->db->where('stct_id = st_fk_settings_categories');
        $this->db->where('stky_id = st_fk_settings_keys');
        $this->db->where('cst_fk_settings = st_id');
        $this->db->where('cst_fk_clients', $input['clnt_id']);

        $this->db->order_by('stct_sort', 'asc');
        $this->db->order_by('st_sort', 'asc');
        $R = $this->db->get()->result_array();
        return $R;
    }

    // function get_settings($st_ids)
    // {
    //     $this->db->select("$this->p_key, $this->nameField, cst_val");
    //     $q = $this->array_query($st_ids, $this->p_key);
    //     $this->db->where($q);
    //     $R = $this->db->get($this->table)->result_array();
    //     return $this->reindex_query_result_by_id($R);
    // }

    function get_input_types($i = NULL)
    {
        $inputs[1] = 'Textbox';
        $inputs[2] = 'Dropdown';
        $inputs[3] = 'Radio Button';
        $inputs[4] = 'Checkbox';
        $inputs[5] = 'Textarea';
        if ($i) {
            if (isset($inputs[$i]))
                return $inputs[$i];
            else
                return 'UNDEFINED';
        } else
            return $inputs;
    }

    function get_user_types($i = NULL)
    {
        $inputs[1] = 'For All Users';
        $inputs[2] = 'Admins Only';
        $inputs[3] = 'Developer Only';
        if ($i) {
            if (isset($inputs[$i]))
                return $inputs[$i];
            else
                return 'UNDEFINED';
        } else
            return $inputs;
    }

    function get_ref_tables($i = NULL)
    {
        $inputs['sls_qtn'] = 'SALES QUOTATIONS';
        $inputs['sls_odr'] = 'SALES ORDERS';
        $inputs['sls_bls'] = 'SALES BILLS';
        $inputs['sls_rtn'] = 'SALES RETURNS';
        $inputs['pchs_qtn'] = 'PURCHASE QUOTATIONS';
        $inputs['pchs_odr'] = 'PURCHASE ORDERS';
        $inputs['pchs_bls'] = 'PURCHASE BILLS';
        $inputs['pchs_rtn'] = 'PURCHASE RETURNS';

        if ($i) {
            if (isset($inputs[$i]))
                return $inputs[$i];
            else
                return 'UNDEFINED';
        } else
            return $inputs;
    }

    function next_sort()
    {
        $this->db->select_max('st_sort');
        $R = $this->db->get($this->table)->row_array();
        $next = $R['st_sort'] ? $R['st_sort'] + 1 : 1;
        return $next;
    }
}
