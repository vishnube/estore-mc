<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Areas_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('areas', 'ars');
    }


    public function index($input, $flag)
    {
        // Only areas under center stores of user.
        if ($input['usr_type'] == 3) {
            $this->db->from("$this->table,central_stores,taluks,districts,states,user_central_stores");
            $this->db->where('ucs_fk_users', $input['usr_id']);
            $this->db->where('cstr_id = ucs_fk_central_stores');
        } else {
            $this->db->from("$this->table,central_stores,taluks,districts,states");
        }

        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'ars_fk_taluks'))
            $this->db->where('tlk_id', $input['ars_fk_taluks']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'ars_fk_central_stores'))
            $this->db->where('cstr_id', $input['ars_fk_central_stores']);

        if (ifSetInput($input, 'ars_type'))
            $this->db->where('ars_type', $input['ars_type']);

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where('ars_fk_clients', $input['clnt_id']);
        $this->db->where('cstr_id = ars_fk_central_stores');
        $this->db->where('tlk_id = ars_fk_taluks');
        $this->db->where('dst_id = tlk_fk_districts');
        $this->db->where('stt_id = dst_fk_states');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,stt_name,dst_name,tlk_name,cstr_name");
            $this->db->order_by('stt_name');
            $this->db->order_by('dst_name');
            $this->db->order_by('tlk_name');
            $this->db->order_by('ars_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    // Only areas under user's Central Store
    function get_users_area($clnt_id, $usr_id, $usr_type, $input = '', $ars_status = ACTIVE, $cstr_status = '', $return = 'options')
    {

        $this->db->select('ars_id,ars_name');

        if ($usr_type == 3) {
            $this->db->from("$this->table,central_stores,members,user_central_stores");
            $this->db->where('ucs_fk_users', $usr_id);
            if ($cstr_status)
                $this->db->where('mbr_status', $cstr_status);
            $this->db->where('cstr_id = ucs_fk_central_stores');
            $this->db->where('ars_fk_central_stores = cstr_id');
            $this->db->where('mbr_id = cstr_fk_members');
        } else {
            $this->db->from("$this->table");
        }



        if (ifSetInput($input, 'tlk_id'))
            $this->db->where('ars_fk_taluks', $input['tlk_id']);

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('ars_fk_central_stores', $input['cstr_id']);

        $this->db->where('ars_fk_clients', $clnt_id);
        $this->db->where('ars_status', $ars_status);
        $this->db->order_by('ars_name');
        $R = $this->db->get()->result_array();

        if ($return == 'options') {
            $R = $this->make_option($R, 'ars_id', 'ars_name');
        }
        return $R;
    }

    function get_by_district($dst_id, $return = 'options')
    {
        $this->db->from("$this->table,taluks");
        $this->db->where('tlk_id = ars_fk_taluks');
        $this->db->where('tlk_fk_districts', $dst_id);
        $this->db->where('tlk_status', ACTIVE);
        $this->db->where('ars_status', ACTIVE);
        $this->db->order_by('ars_name');
        $R = $this->db->get()->result_array();

        if ($return == 'options') {
            $R = $this->make_option($R, 'ars_id', 'ars_name');
        }
        return $R;
    }
}
