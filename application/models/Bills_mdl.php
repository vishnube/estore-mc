<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bills_mdl extends My_mdl
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

        $this->db->from($this->table);

        if (ifSetInput($input, 'f_date'))
            $this->db->where('bls_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('bls_date <= ', get_sql_date_time($input['t_date'], 'last'));

        if (ifSetInput($input, 'bls_bill_type'))
            $this->db->where('bls_bill_type', $input['bls_bill_type']);

        if (ifSetInput($input, 'bls_from_fk_members'))
            $this->db->where('bls_from_fk_members', $input['bls_from_fk_members']);

        if (ifSetInput($input, 'bls_to_fk_members'))
            $this->db->where('bls_to_fk_members', $input['bls_to_fk_members']);

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
            $this->db->order_by('bls_date', 'desc');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
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
}
