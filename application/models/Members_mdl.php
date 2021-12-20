<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Members_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('members', 'mbr');
    }

    function get_categories($mbr_id, $status = ACTIVE, $return = 'option')
    {

        $this->db->from('categories, member_categories');

        if ($status)
            $this->db->where('cat_status', $status);

        if ($mbr_id)
            $this->db->where('mbrcat_fk_members', $mbr_id);

        $this->db->where('cat_id = mbrcat_fk_categories');
        $r = $this->db->get()->result_array();

        if ($return == 'option')
            $r = $this->make_option($r, 'cat_id', 'cat_name');
        else if ($return == 'id')
            $r = $this->get_ids_from_query_result($r, 'cat_id');
        return $r;
    }

    /** 
     * Getting members whom are not a USER
     */
    function get_non_users($input, $return = 'option')
    {
        $this->db->from("$this->table");


        if (ifSetInput($input, 'mbr_fk_member_types'))
            $this->db->where('mbr_fk_member_types', $input['mbr_fk_member_types']);

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        // if (ifSetInput($input, 'mbr_id'))
        //     $this->db->where('mbr_id !=', $input['mbr_id']);

        // Members not in Tbl: users
        $Q = " (SELECT usr_fk_members FROM users) ";
        $this->db->where("mbr_id NOT IN $Q");

        $this->db->order_by($this->nameField);

        $r = $this->db->get()->result_array();

        if ($return == 'option')
            $r = $this->make_option($r);

        return $r;
    }
}
