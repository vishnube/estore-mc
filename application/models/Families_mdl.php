<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Families_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('families', 'fmly');
    }


    public function index($input, $flag)
    {
        if (ifSetInput($input, 'cat_id')) {
            $WHERE = $this->array_query($input['cat_id'], 'mbrcat_fk_categories');
            $table = "(SELECT DISTINCT(mbrcat_fk_members)  FROM member_categories WHERE $WHERE) MY_TABLE";
            $this->db->where("MEM_TBL_MAIN.mbr_id = MY_TABLE.mbrcat_fk_members");
            $this->db->from("$table");
        }

        $this->db->from("$this->table,members MEM_TBL_MAIN,wards,areas,taluks,districts,states");

        $this->db->join('estores', 'estr_id = wrd_fk_estores', 'left');
        $this->db->join('central_stores', 'cstr_id = estr_fk_central_stores', 'left');

        $bill_from = ifSetInput($input, 'sls_from') ? 'AND bls_date >= "' . get_sql_date_time(date("Y-m-d", strtotime($input['sls_from'])), 'first') . '"' : '';


        $bill_query = " SELECT SUM(bls_net_amount) AS PCHS_AMT, bls_to_fk_members AS member_id ";
        $bill_query .= " FROM bills ";
        $bill_query .= " WHERE bls_bill_type = 'sls_bls' $bill_from  ";
        $bill_query .= " GROUP BY bls_to_fk_members ";
        $this->db->join("($bill_query) MY_BILL", 'MEM_TBL_MAIN.mbr_id = MY_BILL.member_id', 'left');

        if (ifSetInput($input, 'stt_id'))
            $this->db->where('stt_id', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('dst_id', $input['dst_id']);

        if (ifSetInput($input, 'tlk_id'))
            $this->db->where('tlk_id', $input['tlk_id']);

        if (ifSetInput($input, 'ars_id'))
            $this->db->where('ars_id', $input['ars_id']);

        if (ifSetInput($input, 'fmly_fk_wards'))
            $this->db->where('wrd_id', $input['fmly_fk_wards']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'mbr_status'))
            $this->db->where('mbr_status', $input['mbr_status']);

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('cstr_id', $input['cstr_id']);

        if (ifSetInput($input, 'estr_id'))
            $this->db->where('estr_id', $input['estr_id']);

        if (ifSetInput($input, 'inactive_for')) {

            //$this->db->join('bills', 'bls_to_fk_members = mbr_id AND bls_bill_cat="sls"', 'left');

            $date = date_create(); // Today
            date_sub($date, date_interval_create_from_date_string($input['inactive_for'] . " days"));
            $inactive_from = get_sql_date_time(date_format($date, "Y-m-d"), 'last');

            $q1 = "SELECT TBL_BLS_1.bls_to_fk_members FROM bills TBL_BLS_1,members TBL_MBR_1 ";
            $q1 .= " WHERE TBL_MBR_1.mbr_id = TBL_BLS_1.bls_to_fk_members AND TBL_BLS_1.bls_date > '$inactive_from' AND TBL_MBR_1.mbr_fk_member_types = 6 AND  TBL_BLS_1.bls_bill_type='sls_bls'";

            $q2 = "SELECT TBL_MBR_2.mbr_id  FROM members TBL_MBR_2 WHERE TBL_MBR_2.mbr_id NOT IN ($q1)";

            $this->db->where("(MEM_TBL_MAIN.mbr_id IN ($q2) )");
        }


        $this->db->where('fmly_fk_clients', $input['fmly_fk_clients']);
        $this->db->where('MEM_TBL_MAIN.mbr_id = fmly_fk_members');
        $this->db->where('wrd_id = fmly_fk_wards');
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
            $this->db->select("$this->table.*,stt_name,dst_name,tlk_name,ars_name,wrd_name,MEM_TBL_MAIN.mbr_id,mbr_name,mbr_date,mbr_status,cstr_code,cstr_name,estr_name,MY_BILL.PCHS_AMT");

            if ($input['sort_by'] == 'sls_amount')
                $this->db->order_by('MY_BILL.PCHS_AMT', 'desc');

            $this->db->order_by('mbr_name');

            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }



    function get_families_by_estores($estr_id, $fmly_status = ACTIVE, $ret = 'count')
    {
        $this->db->from("$this->table,wards");
        $this->db->where('wrd_fk_estores', $estr_id);
        $this->db->where('fmly_fk_wards = wrd_id');

        if ($fmly_status)
            $this->db->where('fmly_status', $fmly_status);

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

    function get_families_by_central_stores($cstr_id, $fmly_status = ACTIVE, $estr_status = ACTIVE, $ret = 'count')
    {
        $this->db->from("$this->table,wards,estores");
        $this->db->select("$this->table.*");

        $this->db->where('estr_fk_central_stores', $cstr_id);
        $this->db->where('wrd_id = fmly_fk_wards');
        $this->db->where('estr_id = wrd_fk_estores');
        $this->db->where('estr_status', $estr_status);

        if ($fmly_status)
            $this->db->where('fmly_status', $fmly_status);

        if ($ret == 'count') {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
        } else {
            $this->db->order_by($this->nameField);
            $R = $this->db->get('')->result_array();
            if ($ret == 'option') {
                $R = $this->make_option($R, 'fmly_fk_members', $this->nameField);
            }
            return $R;
        }
    }
}
