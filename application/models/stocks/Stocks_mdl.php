<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stocks_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        //$this->loadTable('taluks', 'tlk');
    }

    function ifRec($input, $rec)
    {
        if (isset($input['records'])) {
            if (!$input['records'] || in_array($rec, $input['records']))
                return true;
            else
                return false;
        }

        return TRUE;
    }

    public function index($input)
    {
        # STEP 1: Getting Stock from source tables
        if ($this->ifRec($input, 'pchs_bls'))
            $stock_data[] = '(' . $this->stk_bills->get_purchase_bill_stock_query($input) . ')';
        if ($this->ifRec($input, 'sls_bls'))
            $stock_data[] = '(' . $this->stk_bills->get_sale_bill_stock_query($input) . ')';



        # STEP 2: Creating a new Stock Table named as 'STOCK_TABLE' from all other stock tables
        $stock_table = " SELECT SUM(SUM_QTY) AS QTY, PRODUCT_ID, UNIT_ID, UGP_GROUP_NO  ";
        if (ifSetInput($input, 'grpby_cstr'))
            $stock_table .= ", CSTR_ID ";
        if (ifSetInput($input, 'grpby_batch'))
            $stock_table .= ", BATCH_ID ";
        $stock_table .= "FROM (";
        $stock_table .=  implode(' UNION ALL ', $stock_data);
        $stock_table .= ")  AS STOCK_TABLE";
        $stock_table .= " GROUP BY PRODUCT_ID ";
        if (ifSetInput($input, 'grpby_cstr'))
            $stock_table .= " ,  CSTR_ID";
        if (ifSetInput($input, 'grpby_batch'))
            $stock_table .= " , BATCH_ID ";
        $stock_table .= " , UNIT_ID, UGP_GROUP_NO ";




        # STEP 3: Formating STOCK_TABLE by linking it with Tbl:units, product_batches to get UNIT NAME and BATCH NAME
        // SELECT
        $FORMATED_STOCK_TABLE = " (SELECT QTY, PRODUCT_ID, unt_name AS UNIT_NAME, UNIT_ID, UGP_GROUP_NO";
        if (ifSetInput($input, 'grpby_cstr'))
            $FORMATED_STOCK_TABLE .= ", FORMATED_STOCK_TABLE.CSTR_ID, cstr_name AS CSTR_NAME";
        if (ifSetInput($input, 'grpby_batch'))
            $FORMATED_STOCK_TABLE .= ", BATCH_ID, pdbch_name AS BATCH_NAME";
        // FROM
        $FORMATED_STOCK_TABLE .= " FROM units";
        if (ifSetInput($input, 'grpby_cstr'))
            $FORMATED_STOCK_TABLE .= ", central_stores ";
        $FORMATED_STOCK_TABLE .= " , ($stock_table) AS FORMATED_STOCK_TABLE ";
        if (ifSetInput($input, 'grpby_batch'))
            $FORMATED_STOCK_TABLE .= " LEFT JOIN (product_batches) ON pdbch_id = BATCH_ID";
        $FORMATED_STOCK_TABLE .= " WHERE unt_id = UNIT_ID";
        if (ifSetInput($input, 'grpby_cstr'))
            $FORMATED_STOCK_TABLE .= " AND central_stores.cstr_id = FORMATED_STOCK_TABLE.CSTR_ID";
        $FORMATED_STOCK_TABLE .= ") ";




        # STEP 4: Linking formated 'STOCK_TABLE' with Tbl:products
        $this->db->select("prd_id, prd_name, QTY, UNIT_NAME, UNIT_ID, UGP_GROUP_NO", FALSE);
        if (ifSetInput($input, 'grpby_cstr'))
            $this->db->select("CSTR_ID, CSTR_NAME", FALSE);
        if (ifSetInput($input, 'grpby_batch'))
            $this->db->select("BATCH_ID, BATCH_NAME", FALSE);
        $this->db->from('products');
        $this->db->join("$FORMATED_STOCK_TABLE AS GRANT_TABLE", 'prd_id = PRODUCT_ID', 'left');

        // If a product selected, $input['product_ids'] will contain only that product. So no need to check it again here.
        if ($input['per_page'])
            $this->db->where("prd_id IN ({$input['product_ids']})");
        else if (ifSetInput($input, 'prd_id'))
            $this->db->where($this->array_query($input['prd_id'], 'prd_id'));
        $this->db->order_by('prd_name');
        if (ifSetInput($input, 'grpby_cstr'))
            $this->db->order_by('CSTR_NAME');
        if (ifSetInput($input, 'grpby_batch'))
            $this->db->order_by('BATCH_NAME');
        $R = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $R;
    }
    /*
    SELECT prd_id, prd_name, QTY, UNIT_NAME, UNIT_ID, UGP_GROUP_NO, CSTR_ID, CSTR_NAME, BATCH_ID, BATCH_NAME FROM `products` 
    LEFT JOIN 
    (



        SELECT QTY, PRODUCT_ID, unt_name AS UNIT_NAME, UNIT_ID, UGP_GROUP_NO, FORMATED_STOCK_TABLE.CSTR_ID, cstr_name AS CSTR_NAME, BATCH_ID, pdbch_name AS BATCH_NAME FROM units,  central_stores,
            ( 
                SELECT SUM(SUM_QTY) AS QTY, PRODUCT_ID, UNIT_ID, UGP_GROUP_NO , CSTR_ID , BATCH_ID FROM 
                (
                    (SELECT SUM(blp_basic_qty) AS SUM_QTY, blp_fk_products AS PRODUCT_ID, blp_basic_unt_id AS UNIT_ID, blp_ugp_group_no AS UGP_GROUP_NO, gdn_fk_central_stores as CSTR_ID, blp_fk_product_batches as BATCH_ID FROM `bills`, `bill_products`, `godowns` WHERE `blp_fk_products` IN (8,2,6,10,11,12,1,4,13,5) AND `bls_id` = `blp_fk_bills` AND `gdn_id` = `blp_fk_godowns` AND `bls_bill_type` = "pchs_bls" GROUP BY `blp_fk_products`, `gdn_fk_central_stores`, `blp_fk_product_batches`, `blp_basic_unt_id`, `blp_ugp_group_no`) 
                    
                    UNION ALL 
                    
                    (SELECT ((-1 ) * SUM(blp_basic_qty)) AS SUM_QTY, blp_fk_products AS PRODUCT_ID, blp_basic_unt_id AS UNIT_ID, blp_ugp_group_no AS UGP_GROUP_NO, gdn_fk_central_stores as CSTR_ID, blp_fk_product_batches as BATCH_ID FROM `bills`, `bill_products`, `godowns` WHERE `blp_fk_products` IN (8,2,6,10,11,12,1,4,13,5) AND `bls_id` = `blp_fk_bills` AND `gdn_id` = `blp_fk_godowns` AND `bls_bill_type` = "sls_bls" GROUP BY `blp_fk_products`, `gdn_fk_central_stores`, `blp_fk_product_batches`, `blp_basic_unt_id`, `blp_ugp_group_no`)
                ) 
                AS STOCK_TABLE GROUP BY PRODUCT_ID , CSTR_ID , BATCH_ID , UNIT_ID, UGP_GROUP_NO 
            ) 
        FORMATED_STOCK_TABLE  LEFT JOIN (product_batches) ON pdbch_id = BATCH_ID WHERE units.unt_id = FORMATED_STOCK_TABLE.UNIT_ID AND central_stores.cstr_id = FORMATED_STOCK_TABLE.CSTR_ID
    
    
    
    ) 
    AS GRANT_TABLE ON `prd_id` = `PRODUCT_ID` WHERE `prd_id` IN (8,2,6,10,11,12,1,4,13,5) ORDER BY `prd_name`, `CSTR_NAME`, `BATCH_NAME`
*/


    /**
     * get_product_ids          :   Taking product posted by user or under the given range only (by limit and offset)   
     *
     * @param  mixed $input
     * @param  mixed $flag      :   TRUE => Returns Count, FALSE => Returns Data
     * @param  mixed $idFormat  :   'array' => Returns prd_id array
     *                              'string' => Returns comma seperated string of prd_id
     * @return void
     */
    function get_product_ids($input, $flag = FALSE, $idFormat = 'string')
    {
        if (ifSetInput($input, 'prd_id'))
            $this->db->where($this->array_query($input['prd_id'], 'prd_id'));

        $this->db->where('prd_status', ACTIVE);
        $this->db->where('prd_fk_clients', $input['clnt_id']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $result = $this->db->get('products')->row_array();
            return $result['allcount'];
        } else {
            $this->db->select('prd_id');
            $this->db->order_by('prd_name');
            $R = $this->db->get('products', $input['per_page'], $input['offset'])->result_array();
            $R = $this->get_ids_from_query_result($R, 'prd_id');
            if ($idFormat == 'string')
                $R = implode(',', $R);
            return $R;
        }
    }
}
