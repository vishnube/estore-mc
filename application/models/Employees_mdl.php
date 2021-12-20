<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Employees_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('employees', 'emply');
    }


    public function index($input, $flag)
    {
        if (ifSetInput($input, 'cat_id')) {
            $WHERE = $this->array_query($input['cat_id'], 'mbrcat_fk_categories');
            $table = "(SELECT DISTINCT(mbrcat_fk_members)  FROM member_categories WHERE $WHERE) MY_TABLE";
            $this->db->where("mbr_id = MY_TABLE.mbrcat_fk_members");
            $this->db->from("$this->table,members,$table");
        } else
            $this->db->from("$this->table,members");



        //     // IF Not a developer, don't show developeres list.
        //     if ($usr_type != 1)
        //     $this->db->where('usr_type != ', 1);

        // // If non Admins, no need to see Admins list
        // if ($usr_type == 3)
        //     $this->db->where('usr_type', 3);

        if (ifSetInput($input, 'mbr_id'))
            $this->db->where('mbr_id', $input['mbr_id']);

        // if (ifSetInput($input, $this->nameField))
        //     $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        $this->db->where('emply_fk_clients', $input['emply_fk_clients']);

        $this->db->where('mbr_id = emply_fk_members');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select('*');
            $this->db->order_by('emply_is_admin');
            $this->db->order_by('mbr_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }
}
