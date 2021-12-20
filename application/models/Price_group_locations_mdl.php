<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Price_group_locations_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('price_group_locations', 'pgpl');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table,price_groups");

        if (ifSetInput($input, 'pgpl_f_date'))
            $this->db->where('pgpl_date >= ', get_sql_date_time($input['pgpl_f_date'], 'first'));

        if (ifSetInput($input, 'pgpl_t_date'))
            $this->db->where('pgpl_date <= ', get_sql_date_time($input['pgpl_t_date'], 'last'));

        if (ifSetInput($input, 'v_date')) {
            $this->db->where('pgpl_vf <= ', get_sql_date($input['v_date']));
            $this->db->where('pgpl_vt >= ', get_sql_date($input['v_date']));
        }

        if (ifSetInput($input, 'pgpl_fk_price_groups')) {
            $str = $this->array_query($input['pgpl_fk_price_groups'], 'pgpl_fk_price_groups');
            $this->db->where($str);
        }

        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'ars_id'))
            $this->db->where('ars_id', $input['ars_id']);

        if (ifSetInput($input, 'wrd_id'))
            $this->db->where('wrd_id', $input['wrd_id']);

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('cstr_id', $input['cstr_id']);

        $this->db->join('states', 'stt_id = pgpl_fk_states  AND stt_status = 1', 'left');
        $this->db->join('districts', 'dst_id = pgpl_fk_districts AND dst_status = 1', 'left');
        $this->db->join('areas', 'ars_id = pgpl_fk_areas  AND ars_status = 1', 'left');
        $this->db->join('wards', 'wrd_id = pgpl_fk_wards  AND wrd_status = 1', 'left');
        $this->db->join('central_stores', 'cstr_id = pgpl_fk_central_stores  AND cstr_status = 1', 'left');


        $this->db->where('pgp_status', ACTIVE);
        $this->db->where('pgp_fk_clients', $input['clnt_id']);
        $this->db->where('pgp_id = pgpl_fk_price_groups');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,stt_name,dst_name,ars_name,wrd_name,cstr_name,pgp_name");
            $this->db->order_by('pgp_name');
            $this->db->order_by('stt_name');
            $this->db->order_by('dst_name');
            $this->db->order_by('ars_name');
            $this->db->order_by('cstr_name');
            $this->db->order_by('wrd_name');
            $this->db->order_by($this->dateField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            // echo $this->db->last_query();
            return $R;
        }
    }
}
