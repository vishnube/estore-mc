<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Price_groups_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('price_groups', 'pgp');
    }


    public function index($input, $flag)
    {
        $this->db->from($this->table);

        if (ifSetInput($input, 'f_date'))
            $this->db->where('pgp_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('pgp_date <= ', get_sql_date_time($input['t_date'], 'last'));

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where('pgp_fk_clients', $input['clnt_id']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*");
            $this->db->order_by($this->nameField);
            $this->db->order_by($this->dateField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    // function cal_min_price($data, $r)
    // {
    //     $v = array();
    //     foreach ($data as $d) {
    //         $v[] = bcsub($r, $d['pgprd_dsc'], 5);

    //         // If discount percentage
    //         if ($d['pgprd_dscp']) {
    //             $i = bcmul($r, bcdiv($d['pgprd_dscp'], 100, 5), 5);
    //             $v[] = bcsub($r, $i, 5);
    //         }
    //     }

    //     return ($v ? min($v) : '');
    // }

    // function get_min_ward_price($clnt_id, $wrd_id, $prd_id, $rate)
    // {
    //     $R = $this->db->from("$this->table,price_groups_products,price_group_locations")
    //         ->where('pgprd_fk_products', $prd_id)
    //         ->where('pgpl_fk_wards', $wrd_id)
    //         ->where('pgpl_fk_price_groups = pgprd_fk_price_groups')
    //         ->where('pgp_id = pgpl_fk_price_groups')
    //         ->where('pgp_status', ACTIVE)
    //         ->where('pgp_fk_clients', $clnt_id)
    //         ->get()->result_array();

    //     $min = $this->cal_min_price($R, $rate);
    //     return $min;
    // }

    function get_ward_price($clnt_id, $wrd_id, $prd_id, $pdbch_id, $bls_date)
    {
        $wrd_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">',wrd_name,'</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span> <b>&gt;</b> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <b>&gt;</b> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM wards,areas,taluks,districts,states WHERE wrd_id = pgpl_fk_wards AND ars_id = wrd_fk_areas AND tlk_id = ars_fk_taluks AND dst_id =tlk_fk_districts AND stt_id = dst_fk_states)";

        $R = $this->db->from("$this->table,price_groups_products,price_group_locations,unit_groups,units")
            ->select("pgp_name,price_groups_products.*,unt_name,ugp_group_no, 'WARD' AS LEVEL, $wrd_q as location, '' AS cstr_dt")
            ->where('pgprd_fk_products', $prd_id)
            ->where('pgprd_fk_product_batches', $pdbch_id)
            ->where('pgpl_vf <= ', get_sql_date($bls_date))
            ->where('pgpl_vt >= ', get_sql_date($bls_date))
            ->where('pgpl_fk_wards', $wrd_id)
            ->where('pgpl_fk_price_groups = pgprd_fk_price_groups')
            ->where('pgp_id = pgpl_fk_price_groups')
            ->where('ugp_id = pgprd_fk_unit_groups')
            ->where('unt_id = ugp_fk_units')
            ->where('pgp_status', ACTIVE)
            ->where('pgp_fk_clients', $clnt_id)
            ->order_by('pgp_name')
            ->order_by('pgprd_qty')
            ->order_by('pgprd_date')
            ->get()->result_array();

        //echo "<br>" . $this->db->last_query() . "<br>";
        return $R;
    }

    function get_area_cstr_price($clnt_id, $ars_id, $cstr_mbr_id, $prd_id, $pdbch_id, $bls_date)
    {

        $ars_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span> <b>&gt;</b> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <b>&gt;</b> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM areas,taluks,districts,states WHERE ars_id = pgpl_fk_areas AND tlk_id = ars_fk_taluks AND dst_id =tlk_fk_districts AND stt_id = dst_fk_states)";

        $cstr_q = "(SELECT CONCAT('<span class=\"text-warning\" title =\"Central Store\">',cstr_name,'</span>') as cstr FROM central_stores WHERE cstr_id = pgpl_fk_central_stores)";

        $R = $this->db->from("$this->table,price_groups_products,price_group_locations,unit_groups,units")
            ->select("pgp_name,price_groups_products.*,unt_name,ugp_group_no, 'AREA / CENTRAL STORE' AS LEVEL, IF(pgpl_fk_areas <> 0, $ars_q,'') as location, IF(pgpl_fk_central_stores <> 0, $cstr_q, '') AS cstr_dt")
            ->where('pgprd_fk_products', $prd_id)
            ->where('pgprd_fk_product_batches', $pdbch_id)
            ->where('pgpl_vf <= ', get_sql_date($bls_date))
            ->where('pgpl_vt >= ', get_sql_date($bls_date))
            ->where("(pgpl_fk_central_stores = $cstr_mbr_id OR pgpl_fk_areas = $ars_id)")
            ->where('pgpl_fk_wards', 0)
            ->where('pgpl_fk_price_groups = pgprd_fk_price_groups')
            ->where('pgp_id = pgpl_fk_price_groups')
            ->where('ugp_id = pgprd_fk_unit_groups')
            ->where('unt_id = ugp_fk_units')
            ->where('pgp_status', ACTIVE)
            ->where('pgp_fk_clients', $clnt_id)
            ->order_by('pgp_name')
            ->order_by('pgprd_qty')
            ->order_by('pgprd_date')
            ->get()->result_array();

        // echo "<br>" . $this->db->last_query() . "<br>";
        return $R;
    }

    function get_district_price($clnt_id, $dst_id, $prd_id, $pdbch_id, $bls_date)
    {
        $dst_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">----</span> <b>&gt;</b> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <b>&gt;</b> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM districts,states WHERE dst_id = pgpl_fk_districts AND stt_id = dst_fk_states)";

        $R = $this->db->from("$this->table,price_groups_products,price_group_locations,unit_groups,units")
            ->select("pgp_name,price_groups_products.*,unt_name,ugp_group_no, 'DISTRICT' AS LEVEL, $dst_q as location, '' AS cstr_dt")
            ->where('pgprd_fk_products', $prd_id)
            ->where('pgprd_fk_product_batches', $pdbch_id)
            ->where('pgpl_vf <= ', get_sql_date($bls_date))
            ->where('pgpl_vt >= ', get_sql_date($bls_date))
            ->where('pgpl_fk_districts', $dst_id)
            ->where('pgpl_fk_wards', 0)
            // ->where("pgpl_fk_central_stores", 0)
            ->where("pgpl_fk_areas", 0)
            ->where('pgpl_fk_price_groups = pgprd_fk_price_groups')
            ->where('pgp_id = pgpl_fk_price_groups')
            ->where('ugp_id = pgprd_fk_unit_groups')
            ->where('unt_id = ugp_fk_units')
            ->where('pgp_status', ACTIVE)
            ->where('pgp_fk_clients', $clnt_id)
            ->order_by('pgp_name')
            ->order_by('pgprd_qty')
            ->order_by('pgprd_date')
            ->get()->result_array();
        return $R;
    }

    function get_state_price($clnt_id, $stt_id, $prd_id, $pdbch_id, $bls_date)
    {
        $stt_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">----</span> <b>&gt;</b> <span class=\"text-success\" title =\"District\">----</span> <b>&gt;</b> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM states WHERE stt_id = pgpl_fk_states)";

        $R = $this->db->from("$this->table,price_groups_products,price_group_locations,unit_groups,units")
            ->select("pgp_name,price_groups_products.*,unt_name,ugp_group_no, 'STATE' AS LEVEL, $stt_q as location, '' AS cstr_dt")
            ->where('pgprd_fk_products', $prd_id)
            ->where('pgprd_fk_product_batches', $pdbch_id)
            ->where('pgpl_vf <= ', get_sql_date($bls_date))
            ->where('pgpl_vt >= ', get_sql_date($bls_date))
            ->where('pgpl_fk_states', $stt_id)
            ->where('pgpl_fk_wards', 0)
            ->where("pgpl_fk_areas", 0)
            ->where('pgpl_fk_districts', 0)
            ->where('pgpl_fk_price_groups = pgprd_fk_price_groups')
            ->where('pgp_id = pgpl_fk_price_groups')
            ->where('ugp_id = pgprd_fk_unit_groups')
            ->where('unt_id = ugp_fk_units')
            ->where('pgp_status', ACTIVE)
            ->where('pgp_fk_clients', $clnt_id)
            ->order_by('pgp_name')
            ->order_by('pgprd_qty')
            ->order_by('pgprd_date')
            ->get()->result_array();
        return $R;
    }
}
