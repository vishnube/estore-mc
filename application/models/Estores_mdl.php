<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estores_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('estores', 'estr');
    }


    public function index($input, $flag)
    {
        if (ifSetInput($input, 'cat_id')) {
            $WHERE = is_array($input['cat_id']) ? $this->array_query($input['cat_id'], 'mbrcat_fk_categories') : " mbrcat_fk_categories = " . $input['cat_id'];
            $table = "(SELECT DISTINCT(mbrcat_fk_members)  FROM member_categories WHERE $WHERE) MY_TABLE";
            $this->db->where("mbr_id = MY_TABLE.mbrcat_fk_members");
            $this->db->from($table);
        }

        $this->db->from("$this->table,central_stores,members,taluks,districts,states");

        if (ifSetInput($input, 'estr_fk_central_stores'))
            $this->db->where('estr_fk_central_stores', $input['estr_fk_central_stores']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'tlk_id'))
            $this->db->where('tlk_id', $input['tlk_id']);


        $this->db->where('estr_fk_clients', $input['clnt_id']);
        $this->db->where('mbr_id = estr_fk_members');
        $this->db->where('tlk_id = cstr_fk_taluks');
        $this->db->where('dst_id = tlk_fk_districts');
        $this->db->where('stt_id = dst_fk_states');
        $this->db->where('cstr_id = estr_fk_central_stores');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,stt_name,dst_name,tlk_name,mbr_id,mbr_name,mbr_date,mbr_status,cstr_name,cstr_code");
            $this->db->order_by('mbr_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    function get_estores_by_central_store($cstr_id, $estr_status = ACTIVE, $ret = 'count')
    {
        $this->db->from("$this->table,members");

        $this->db->where('estr_fk_central_stores', $cstr_id);

        if ($estr_status)
            $this->db->where('mbr_status', $estr_status);

        $this->db->where('mbr_id = estr_fk_members');

        if ($ret == 'count') {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
        } else {
            $this->db->select('*');
            $this->db->order_by($this->nameField);
            $R = $this->db->get('')->result_array();
            if ($ret == 'option') {
                $R = $this->make_option($R, $this->p_key, $this->nameField);
            }
            return $R;
        }
    }

    function get_estores_by_area($clnt_id, $usr_id, $usr_type, $ars_id, $cstr_status = '', $return = 'options')
    {
        if ($usr_type == 3) {
            $this->db->from("$this->table,user_central_stores,central_stores,areas");
            $this->db->where('ucs_fk_users', $usr_id);
            if ($cstr_status)
                $this->db->where('cstr_status', $cstr_status);
            $this->db->where('cstr_id = ucs_fk_central_stores');
            $this->db->where('estr_fk_central_stores = cstr_id');
        } else {
            $this->db->from("$this->table,areas");
        }

        $this->db->where('ars_id', $ars_id);
        $this->db->where('estr_fk_clients', $clnt_id);
        $this->db->where('ars_fk_central_stores = estr_fk_central_stores');

        $this->db->select("$this->table.*");
        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();

        if ($return == 'options') {
            $R = $this->make_option($R, $this->p_key, $this->nameField);
        }
        return $R;
    }
}
