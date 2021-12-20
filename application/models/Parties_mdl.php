<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parties_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('parties', 'prty');
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


        if (ifSetInput($input, 'mbr_id'))
            $this->db->where('mbr_id', $input['mbr_id']);

        // if (ifSetInput($input, $this->nameField))
        //     $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        $this->db->where('prty_fk_clients', $input['prty_fk_clients']);

        $this->db->where('mbr_id = prty_fk_members');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select('*');
            $this->db->order_by('mbr_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }
}
