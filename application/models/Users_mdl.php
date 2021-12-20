<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('users', 'usr');
    }

    function get_users_option($input = array(), $usr_type)
    {
        $this->db->from("$this->table,members");
        $this->db->select('usr_id,mbr_name');

        // IF Not a developer, don't show developeres list.
        if ($usr_type != 1)
            $this->db->where('usr_type != ', 1);

        // If non Admins, no need to see Admins list
        if ($usr_type == 3)
            $this->db->where('usr_type', 3);

        if (ifSetInput($input, 'mbrtp_id'))
            $this->db->where('mbr_fk_member_types', $input['mbrtp_id']);
        if (ifSetInput($input, 'usr_status'))
            $this->db->where('usr_status', $input['usr_status']);

        $this->db->where('usr_fk_clients', $input['usr_fk_clients']);

        $this->db->where('mbr_id = usr_fk_members');
        $this->db->order_by('mbr_name');
        $R = $this->db->get()->result_array();
        $R = $this->make_option($R, 'usr_id', 'mbr_name');
        return $R;
    }


    public function index($select, $input, $flag, $usr_type)
    {
        if (ifSetInput($input, 'grp_id')) {
            $WHERE = $this->array_query($input['grp_id'], 'ugrp_fk_groups');
            $table = "(SELECT DISTINCT(ugrp_fk_users)  FROM user_groups WHERE $WHERE) MY_TABLE";
            $this->db->where("usr_id = MY_TABLE.ugrp_fk_users");
            $this->db->from("$this->table,members,$table");
        } else
            $this->db->from("$this->table,members");

        $this->db->select($select);

        // IF Not a developer, don't show developeres list.
        if ($usr_type != 1)
            $this->db->where('usr_type != ', 1);

        // If non Admins, no need to see Admins list
        if ($usr_type == 3)
            $this->db->where('usr_type', 3);

        if (ifSetInput($input, 'usr_name'))
            $this->db->where("usr_name LIKE", '%' . $input['usr_name'] . '%');

        if (ifSetInput($input, 'usr_status'))
            $this->db->where('usr_status', $input['usr_status']);

        if (ifSetInput($input, 'mbrtp_id'))
            $this->db->where('mbr_fk_member_types', $input['mbrtp_id']);

        $this->db->where('mbr_id = usr_fk_members');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,mbr_name");
            $this->db->order_by('usr_type');
            $this->db->order_by('mbr_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    function get_user_groups($usr_id = '', $status = ACTIVE, $return = 'option')
    {
        $this->db->from("$this->table,groups1, user_groups");

        if ($status)
            $this->db->where('grp_status', $status);

        if ($usr_id)
            $this->db->where('ugrp_fk_users', $usr_id);

        $this->db->where('grp_id = ugrp_fk_groups');
        $this->db->where('ugrp_fk_users = usr_id');

        $r = $this->db->get()->result_array();

        if ($return == 'option')
            $r = $this->make_option($r, 'grp_id', 'grp_name');
        else if ($return == 'id')
            $r = $this->get_ids_from_query_result($r, 'grp_id');
        return $r;
    }



    function get_member_type($usr_id)
    {
        $this->db->from("$this->table,members");
        $this->db->select('mbr_fk_member_types');
        $this->db->where('usr_id', $usr_id);
        $this->db->where('mbr_id = usr_fk_members');

        $r = $this->db->get()->row_array();
        return $r['mbr_fk_member_types'];
    }

    function get_member_details($usr_id, $select = '*')
    {
        $this->db->from("$this->table,members");
        $this->db->select($select);
        $this->db->where('usr_id', $usr_id);
        $this->db->where('mbr_id = usr_fk_members');

        $r = $this->db->get()->row_array();
        return $r;
    }
}
