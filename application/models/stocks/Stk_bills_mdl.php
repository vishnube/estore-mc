<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stk_bills_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bills', 'bls');
    }


    function get_purchase_bill_stock_query($input)
    {
        $this->get_general_bill_query($input, 'in');
        $this->db->where('bls_bill_type = "pchs_bls"');

        // Taking query string without excecute.
        return $this->db->get_compiled_select();
    }

    function get_sale_bill_stock_query($input)
    {
        $this->get_general_bill_query($input, 'out');
        $this->db->where('bls_bill_type = "sls_bls"');

        // Taking query string without excecute.
        return $this->db->get_compiled_select();
    }

    function get_general_bill_query($input, $stock_direction)
    {
        $this->db->from('bills,bill_products,godowns');

        if ($stock_direction == 'in')
            $s =  "SUM(blp_basic_qty)";
        else if ($stock_direction == 'out')
            $s =  "((-1 ) * SUM(blp_basic_qty))";

        $sel = "$s AS SUM_QTY,blp_fk_products AS PRODUCT_ID, blp_basic_unt_id AS UNIT_ID,blp_ugp_group_no AS UGP_GROUP_NO ";

        if (ifSetInput($input, 'grpby_cstr'))
            $sel .= " , gdn_fk_central_stores as CSTR_ID ";
        if (ifSetInput($input, 'grpby_batch'))
            $sel .= " , blp_fk_product_batches as BATCH_ID ";

        $this->db->select($sel, false);

        if (ifSetInput($input, 'cstr_id')) {
            $this->db->where('gdn_fk_central_stores', $input['cstr_id']);
        }

        if (ifSetInput($input, 'gdn_id')) {
            $str = $this->array_query($input['gdn_id'], 'blp_fk_godowns');
            $this->db->where($str);
        }

        if (ifSetInput($input, 'pdbch_id')) {
            $str = $this->array_query($input['pdbch_id'], 'blp_fk_product_batches');
            $this->db->where($str);
        }

        if (ifSetInput($input, 'f_date'))
            $this->db->where('bls_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('bls_date <= ', get_sql_date_time($input['t_date'], 'last'));

        if ($input['per_page'])
            $this->db->where("blp_fk_products IN ({$input['product_ids']})");
        else if (ifSetInput($input, 'prd_id')) {
            $str = $this->array_query($input['prd_id'], 'blp_fk_products');
            $this->db->where($str);
        }

        $this->db->where('bls_id = blp_fk_bills');
        $this->db->where('gdn_id = blp_fk_godowns');
        $this->db->group_by('blp_fk_products'); // This should math $stock_table in index()

        if (ifSetInput($input, 'grpby_cstr'))
            $this->db->group_by('gdn_fk_central_stores'); // This should math $stock_table in index()

        if (ifSetInput($input, 'grpby_batch'))
            $this->db->group_by('blp_fk_product_batches'); // This should math $stock_table in index()

        $this->db->group_by('blp_basic_unt_id'); // This should math $stock_table in index()
        $this->db->group_by('blp_ugp_group_no'); // This should math $stock_table in index()
    }
}
