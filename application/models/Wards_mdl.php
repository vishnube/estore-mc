<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wards_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('wards', 'wrd');
    }


    public function index($input, $flag)
    {
        // Only areas under center stores of user.
        if ($input['usr_type'] == 3) {
            $this->db->from("$this->table,areas,taluks,districts,states,user_central_stores");
            $this->db->where('ucs_fk_users', $input['usr_id']);
            $this->db->where('cstr_id = ucs_fk_central_stores');
        } else {
            $this->db->from("$this->table,areas,taluks,districts,states");
        }


        $this->db->join('estores', 'estr_id = wrd_fk_estores', 'left');
        $this->db->join('central_stores', 'cstr_id = estr_fk_central_stores', 'left');


        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'tlk_id'))
            $this->db->where('tlk_id', $input['tlk_id']);

        if (ifSetInput($input, 'ars_id'))
            $this->db->where('ars_id', $input['ars_id']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('cstr_id', $input['cstr_id']);

        if (ifSetInput($input, 'estr_id'))
            $this->db->where('estr_id', $input['estr_id']);

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        if (ifSetInput($input, 'noEstrs'))
            $this->db->where('wrd_fk_estores', 0);

        $this->db->where('wrd_fk_clients', $input['clnt_id']);


        // Connecting via places.
        $this->db->where('ars_id = wrd_fk_areas');
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
            $this->db->select("$this->table.*,stt_name,dst_name,tlk_name,ars_name,cstr_name,estr_name");
            $this->db->order_by('stt_name');
            $this->db->order_by('dst_name');
            $this->db->order_by('tlk_name');
            $this->db->order_by('ars_name');
            $this->db->order_by('wrd_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    // Only areas under user's Central Store
    function get_users_wards($clnt_id, $usr_id, $usr_type, $input = '', $wrd_status = ACTIVE, $cstr_status = '', $estr_status = '', $return = 'options')
    {

        $this->db->select('wrd_id,wrd_name');

        if ($usr_type == 3) {
            $this->db->from("$this->table,user_central_stores,central_stores,members,estore");
            $this->db->where('ucs_fk_users', $usr_id);
            if ($cstr_status)
                $this->db->where('mbr_status', $cstr_status);
            $this->db->where('cstr_id = ucs_fk_central_stores');
            $this->db->where('estr_fk_central_stores = cstr_id');
            $this->db->where('mbr_id = cstr_fk_members');
        } else {
            $this->db->from("$this->table,estore");
        }

        // ars_fk_wards: ars_id,
        // cstr_id: cstr_id

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('estr_fk_central_stores', $input['cstr_id']);

        if (ifSetInput($input, 'estr_id'))
            $this->db->where('estr_id', $input['estr_id']);

        if (ifSetInput($input, 'ars_id'))
            $this->db->where('wrd_fk_areas', $input['ars_id']);

        if ($estr_status)
            $this->db->where('estr_status', $estr_status);

        $this->db->where('wrd_fk_clients', $clnt_id);
        $this->db->where('wrd_status', $wrd_status);
        $this->db->where('estr_id = wrd_fk_estores');

        $this->db->order_by('wrd_name');
        $R = $this->db->get()->result_array();

        if ($return == 'options') {
            $R = $this->make_option($R, 'wrd_id', 'wrd_name');
        }
        return $R;
    }

    // Making wards estore free
    function make_estore_free($ars_id = '', $estr_id = '')
    {
        $data['wrd_fk_estores'] = '';

        if ($ars_id)
            $where['wrd_fk_areas'] = $ars_id;

        if ($estr_id)
            $where['wrd_fk_estores'] = $estr_id;

        $this->update_where($data, $where);
    }



    function get_wards_by_estores($estr_id, $wrd_status = ACTIVE, $ret = 'count')
    {
        $this->db->from("$this->table");
        $this->db->where('wrd_fk_estores', $estr_id);

        if ($wrd_status)
            $this->db->where('wrd_status', $wrd_status);

        if ($ret = 'count') {
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
}
