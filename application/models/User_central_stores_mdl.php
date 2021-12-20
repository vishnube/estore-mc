<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_central_stores_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('user_central_stores', 'ucs');
    }

    public function get_users_central_stores($clnt_id, $usr_id, $usr_type, $cstr_status = ACTIVE, $return = 'options')
    {
        $this->db->select('cstr_id,cstr_name');
        if ($usr_type == 3) {
            $this->db->from("$this->table,central_stores,members");
            $this->db->where('ucs_fk_users', $usr_id);
            $this->db->where('cstr_id = ucs_fk_central_stores');
        } else {
            $this->db->from("central_stores,members");
        }

        $this->db->where('cstr_fk_clients', $clnt_id);
        $this->db->where('mbr_status', $cstr_status);
        $this->db->where('mbr_id = cstr_fk_members');
        $this->db->order_by('cstr_name');
        $R = $this->db->get()->result_array();

        if ($return == 'options') {
            $R = $this->make_option($R, 'cstr_id', 'cstr_name');
        }

        return $R;
    }

    // Using cstr_fk_members as key part of option instead of cstr_id.
    public function get_users_central_stores_option($clnt_id, $usr_id, $usr_type, $cstr_status = ACTIVE, $ret = 'option')
    {
        $this->db->select('cstr_name,cstr_fk_members');
        if ($usr_type == 3) {
            $this->db->from("$this->table,central_stores,members");
            $this->db->where('ucs_fk_users', $usr_id);
            $this->db->where('cstr_id = ucs_fk_central_stores');
        } else {
            $this->db->from("central_stores,members");
        }
        $this->db->where('cstr_fk_clients', $clnt_id);
        $this->db->where('mbr_status', $cstr_status);
        $this->db->where('mbr_id = cstr_fk_members');
        $this->db->order_by('cstr_name');
        $R = $this->db->get()->result_array();

        if ($ret == 'option')
            $R = $this->make_option($R, 'cstr_fk_members', 'cstr_name');

        else if ($ret == 'ids')
            $R = $this->get_ids_from_query_result($R, 'cstr_fk_members');

        return $R;
    }

    public function get_users_central_stores_ids($usr_id)
    {
        $this->db->from("$this->table");
        $this->db->select("ucs_fk_central_stores as cstr_id");
        $this->db->where('ucs_fk_users', $usr_id);
        $R = $this->db->get()->result_array();
        $R = $this->get_ids_from_query_result($R, 'cstr_id');
        return $R;
    }
}
