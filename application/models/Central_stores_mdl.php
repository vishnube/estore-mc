<?php

defined('BASEPATH') or exit('No direct script access allowed');

class central_stores_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('central_stores', 'cstr');
    }


    public function index($input, $flag)
    {
        if (ifSetInput($input, 'cat_id')) {
            $WHERE = is_array($input['cat_id']) ? $this->array_query($input['cat_id'], 'mbrcat_fk_categories') : " mbrcat_fk_categories = " . $input['cat_id'];
            $table = "(SELECT DISTINCT(mbrcat_fk_members)  FROM member_categories WHERE $WHERE) MY_TABLE";
            $this->db->where("mbr_id = MY_TABLE.mbrcat_fk_members");
            $this->db->from($table);
        }

        $this->db->from("$this->table,members,taluks,districts,states");

        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'cstr_fk_taluks'))
            $this->db->where('tlk_id', $input['cstr_fk_taluks']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'cstr_code'))
            $this->db->where("cstr_code LIKE", '%' . $input['cstr_code'] . '%');

        if (ifSetInput($input, 'cstr_pin'))
            $this->db->where('cstr_pin', $input['cstr_pin']);

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        $this->db->where('cstr_fk_clients', $input['clnt_id']);
        $this->db->where('mbr_id = cstr_fk_members');
        $this->db->where('tlk_id = cstr_fk_taluks');
        $this->db->where('dst_id = tlk_fk_districts');
        $this->db->where('stt_id = dst_fk_states');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,stt_name,dst_name,tlk_name,mbr_id,mbr_name,mbr_date,mbr_status");
            $this->db->order_by('mbr_name');
            if (isset($input['per_page']))
                $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            else
                $R = $this->db->get()->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    function get_state_dt($mbr_id)
    {
        $this->db->from("$this->table,taluks,districts,states");
        $this->db->select("stt_id,stt_name");
        $this->db->where('cstr_fk_members', $mbr_id);
        $this->db->where('tlk_id = cstr_fk_taluks');
        $this->db->where('dst_id = tlk_fk_districts');
        $this->db->where('stt_id = dst_fk_states');

        $R = $this->db->get()->row_array();
        return $R;
    }
}
