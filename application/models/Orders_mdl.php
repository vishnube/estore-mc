<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bills', 'bls');
    }



    public function index($input, $flag)
    {
        if (ifSetInput($input, 'prd_id')) {
            $this->db->from('bill_products');
            $this->db->select('DISTINCT(blp_fk_bills)');
            if (ifSetInput($input, 'prd_id')) {
                $str = $this->array_query($input['prd_id'], 'blp_fk_products');
                $this->db->where($str);
            }
            if (ifSetInput($input, 'pdbch_id')) {
                $str = $this->array_query($input['pdbch_id'], 'blp_fk_product_batches');
                $this->db->where($str);
            }

            // Taking query string without excecute.
            $blp_tbl = $this->db->get_compiled_select();

            $this->db->where("bls_id IN ($blp_tbl)");
        }

        $this->db->from("$this->table,order_status");

        if (ifSetInput($input, 'f_date'))
            $this->db->where('bls_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('bls_date <= ', get_sql_date_time($input['t_date'], 'last'));

        if (ifSetInput($input, 'bls_bill_type'))
            $this->db->where('bls_bill_type', $input['bls_bill_type']);

        if (ifSetInput($input, 'ost_status'))
            $this->db->where('ost_status', $input['ost_status']);

        $this->db->where('ost_final = 1');
        $this->db->where('ost_fk_bills = bls_id');


        if (ifSetInput($input, 'cstr_id')) {
            if (is_array($input['cstr_id']))
                $this->db->where($this->array_query($input['cstr_id'], 'bls_from_fk_members'));
            else
                $this->db->where('bls_from_fk_members', $input['cstr_id']);
        }

        if (ifSetInput($input, 'fmly_id'))
            $this->db->where('bls_to_fk_members', $input['fmly_id']);


        if (ifSetInput($input, 'bls_status'))
            $this->db->where('bls_status', $input['bls_status']);
        // prd_id

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->order_by($this->dateField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    function get_ward_string($fmly_id, $format = 'simple')
    {
        if ($format == 'simple')
            $wrd_q = "SELECT CONCAT('<span class=\"text-info\" title =\"wards\">',wrd_name,'</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span>') AS wrd_str FROM families,wards,areas WHERE fmly_id = $fmly_id AND wrd_id = fmly_fk_wards AND ars_id = wrd_fk_areas";
        else if ($format == 'full')
            $wrd_q = "SELECT CONCAT('<span class=\"text-info\" title =\"wards\">',wrd_name,'</span> <b>&gt;</b> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span>  <b>&gt;</b> <span class=\"text-warning\" title =\"Taluk\">',tlk_name,'</span> <b>&gt;</b> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <b>&gt;</b> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') AS wrd_str FROM families,wards,areas,taluks,districts,states WHERE fmly_id = $fmly_id AND wrd_id = fmly_fk_wards AND ars_id = wrd_fk_areas AND tlk_id = ars_fk_taluks AND dst_id =tlk_fk_districts AND stt_id = dst_fk_states";

        $R = $this->db->query($wrd_q)->row_array();
        $R = isset($R['wrd_str']) && $R['wrd_str'] ? $R['wrd_str'] : '';
        return $R;
    }

    function get_ward($fmly_id, $format = 'simple')
    {
        $this->db->where('fmly_id', $fmly_id)
            ->where("wrd_id = fmly_fk_wards")
            ->where("ars_id = wrd_fk_areas")
            ->from("families,wards,areas")
            ->select("wrd_name,ars_name");
        $R = $this->db->get()->row_array();
        return $R;
    }

    function get_estore_string($fmly_id)
    {
        $this->db->select('estr_name,estr_mob1,estr_mob2')
            ->from('families,wards,estores')
            ->where('fmly_id', $fmly_id)
            ->where('wrd_id = fmly_fk_wards')
            ->where('estr_id = wrd_fk_estores');
        $R = $this->db->get()->row_array();
        return $R;
    }


    function get_picked_qty($prd_id, $ugp_id, $cstr_id)
    {
        $this->db->from("$this->table,order_status,bill_products");
        $this->db->select("SUM(blp_basic_qty) AS qty");
        $this->db->where('blp_fk_products', $prd_id);
        $this->db->where('blp_basic_ugp_id', $ugp_id); // It shoud be basic ugp_id

        if (is_array($cstr_id))
            $this->db->where($this->array_query($cstr_id, 'bls_from_fk_members'));
        else
            $this->db->where('bls_from_fk_members', $cstr_id);


        $this->db->where('ost_status', 2); // Only 1 => PICKED
        $this->db->where('ost_final = 1');
        $this->db->where('ost_fk_bills = bls_id');
        $this->db->where("blp_fk_bills = bls_id");
        $this->db->where('bls_bill_type', 'sls_odr');
        $R = $this->db->get()->row_array();
        $R = isset($R['qty']) ? $R['qty'] : 0;
        return $R;
    }

    function get_stock($input, $flag)
    {
        $this->db->from("$this->table,order_status,bill_products");
        $this->db->select("blp_fk_products,  blp_basic_qty, blp_basic_ugp_id, blp_basic_unt_id");

        if (ifSetInput($input, 'f_date'))
            $this->db->where('bls_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('bls_date <= ', get_sql_date_time($input['t_date'], 'last'));

        if (ifSetInput($input, 'prd_id'))
            $this->db->where('blp_fk_products', $input['prd_id']);


        if (ifSetInput($input, 'cstr_id')) {
            if (is_array($input['cstr_id']))
                $this->db->where($this->array_query($input['cstr_id'], 'bls_from_fk_members'));
            else
                $this->db->where('bls_from_fk_members', $input['cstr_id']);
        }

        if (ifSetInput($input, 'fmly_id'))
            $this->db->where('bls_to_fk_members', $input['fmly_id']);

        $this->db->where('ost_status', 1); // Only 1 => PENDING
        $this->db->where('ost_final = 1');
        $this->db->where('ost_fk_bills = bls_id');
        $this->db->where("blp_fk_bills = bls_id");
        $this->db->where('bls_bill_type', 'sls_odr');



        // Taking query string without excecute.
        $q = $this->db->get_compiled_select();

        if ($flag) {
            $sel = ' SELECT count(*) as allcount ';
            $STR = " $sel FROM ($q) T1 GROUP BY blp_fk_products,blp_basic_ugp_id";
        } else {
            $sel = ' SELECT blp_fk_products,  blp_basic_ugp_id, SUM(blp_basic_qty) AS qty, blp_basic_unt_id';
            $lmt = "LIMIT {$input['offset']},{$input['per_page']}";
            $STR = " $sel FROM ($q) T1 GROUP BY blp_fk_products,blp_basic_ugp_id $lmt";
        }

        $R = $this->db->query($STR);
        $R = $R->result_array();


        if ($flag) {
            $c = isset($R[0]['allcount']) ? $R[0]['allcount'] : 0;
            return $c;
            // return  $this->db->count_all_results();
        } else {
            /// echo $this->db->last_query();
            return $R;
        }
    }
}
