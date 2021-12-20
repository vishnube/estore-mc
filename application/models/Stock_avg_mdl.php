<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stock_avg_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('stock_avg', 'stkavg');
    }

    function get_final_stock($clnt_id, $cstr_mbr_id = '', $prd_id = '', $pdbch_id = '', $ugp_id = '', $zero_stock = true, $return = 'detailed', $fdate = '', $tdate = '')
    {
        if (!$fdate && !$tdate)
            $final_stock = $this->get_final_stock_query($clnt_id, $cstr_mbr_id, $prd_id, $pdbch_id, $ugp_id, $zero_stock);
        else
            $final_stock = $this->get_final_stock_in_date_query($clnt_id, $cstr_mbr_id, $prd_id, $pdbch_id, $ugp_id, $zero_stock, $fdate, $tdate);

        if ($return == 'summery')
            $R = $this->db->query($final_stock)->result_array();
        else {
            $str = " SELECT s3.*, mbr_name, prd_name,pdbch_name,unt_name ";
            $str .= " FROM $final_stock s3, products,members,product_batches,units";
            $str .= " WHERE mbr_id = s3.stkavg_cstr_mbr_id ";
            $str .= " AND prd_id = s3.stkavg_fk_products ";
            $str .= " AND pdbch_id = s3.stkavg_fk_product_batches ";
            $str .= " AND unt_id = s3.stkavg_unt_id ";
            $str .= " ORDER BY mbr_name, prd_name, pdbch_name, unt_name ";

            $R = $this->db->query($str)->result_array();
        }

        return $R;
    }

    // Getting final stock in each (or given) centralsore/product/batch/unit
    function get_final_stock_query($clnt_id,  $cstr_mbr_id = '', $prd_id = '', $pdbch_id = '', $ugp_id = '', $zero_stock = true)
    {
        $cstr_query = $prd_query = $pdbch_query = $ugp_query = $zero_query = '';

        if ($cstr_mbr_id)
            $cstr_query = is_array($cstr_mbr_id) ? ' AND ' . $this->array_query($cstr_mbr_id, 's1.stkavg_cstr_mbr_id') : " AND  s1.stkavg_cstr_mbr_id = $cstr_mbr_id ";

        if ($prd_id)
            $prd_query = is_array($prd_id) ? ' AND ' . $this->array_query($prd_id, 's1.stkavg_fk_products') : " AND  s1.stkavg_fk_products = $prd_id ";

        if ($pdbch_id)
            $pdbch_query = is_array($pdbch_id) ? ' AND ' . $this->array_query($pdbch_id, 's1.stkavg_fk_product_batches') : " AND  s1.stkavg_fk_product_batches = $pdbch_id ";

        if ($ugp_id)
            $ugp_query = is_array($ugp_id) ? ' AND ' . $this->array_query($ugp_id, 's1.stkavg_ugp_id') : " AND  s1.stkavg_ugp_id = $ugp_id ";

        if (!$zero_stock)
            $zero_query = 'AND s1.stkavg_bal_qty != 0';


        $final_stock = "
            (   SELECT s1.*
                FROM stock_avg s1
                WHERE NOT EXISTS (
                                    SELECT 1
                                    FROM stock_avg s2
                                    WHERE s2.stkavg_cstr_mbr_id = s1.stkavg_cstr_mbr_id AND s2.stkavg_fk_products = s1.stkavg_fk_products AND 
                                    s2.stkavg_fk_product_batches = s1.stkavg_fk_product_batches AND s2.stkavg_ugp_id = s1.stkavg_ugp_id
                                        AND (
                                            s2.stkavg_date > s1.stkavg_date OR 
                                            (s2.stkavg_date = s1.stkavg_date AND s2.stkavg_order > s1.stkavg_order) OR
                                            (s2.stkavg_date = s1.stkavg_date AND s2.stkavg_order = s1.stkavg_order AND s2.stkavg_id > s1.stkavg_id ) 
                                        )  
                                )
                $cstr_query $prd_query $pdbch_query $ugp_query $zero_query AND stkavg_fk_clients = $clnt_id 
            )";
        //echo $final_stock;
        return $final_stock;
    }

    // Getting final stock between two dates in each (or given) centralsore/product/batch/unit
    function get_final_stock_in_date_query($clnt_id, $cstr_mbr_id = '', $prd_id = '', $pdbch_id = '', $ugp_id = '', $zero_stock = true, $fdate = '', $tdate = '')
    {
        $fdt_query = $tdt_query = $cstr_query = $prd_query = $pdbch_query = $ugp_query = $zero_query = '';

        $fdt_query = $fdate ? "  AND stkavg_date >= '" . get_sql_date_time($fdate, 'first') . "'" : '';
        $tdt_query = $tdate ? "  AND stkavg_date <= '" . get_sql_date_time($tdate, 'last') . "'" : '';

        if ($cstr_mbr_id)
            $cstr_query = is_array($cstr_mbr_id) ? ' AND ' . $this->array_query($cstr_mbr_id, 's1.stkavg_cstr_mbr_id') : " AND  s1.stkavg_cstr_mbr_id = $cstr_mbr_id ";

        if ($prd_id)
            $prd_query = is_array($prd_id) ? ' AND ' . $this->array_query($prd_id, 's1.stkavg_fk_products') : " AND  s1.stkavg_fk_products = $prd_id ";

        if ($pdbch_id)
            $pdbch_query = is_array($pdbch_id) ? ' AND ' . $this->array_query($pdbch_id, 's1.stkavg_fk_product_batches') : " AND  s1.stkavg_fk_product_batches = $pdbch_id ";

        if ($ugp_id)
            $ugp_query = is_array($ugp_id) ? ' AND ' . $this->array_query($ugp_id, 's1.stkavg_ugp_id') : " AND  s1.stkavg_ugp_id = $ugp_id ";

        if (!$zero_stock)
            $zero_query = 'AND s1.stkavg_bal_qty != 0';


        $final_stock = "(
                    SELECT s1.*
                    FROM stock_avg s1
                    WHERE s1.stkavg_id = (
                        SELECT s2.stkavg_id
                        FROM stock_avg s2
                        WHERE s2.stkavg_cstr_mbr_id = s1.stkavg_cstr_mbr_id AND s2.stkavg_fk_products = s1.stkavg_fk_products AND 
                        s2.stkavg_fk_product_batches = s1.stkavg_fk_product_batches AND s2.stkavg_ugp_id = s1.stkavg_ugp_id
                        $fdt_query $tdt_query $cstr_query $prd_query $pdbch_query $ugp_query $zero_query AND stkavg_fk_clients = $clnt_id 
                        ORDER BY s2.stkavg_date DESC, s2.stkavg_order DESC , s2.stkavg_id DESC LIMIT 1
                    )
                )";

        return $final_stock;
    }

    // Total product stock without considering batches (Or sum of stock in all batches of a product) in all given central stores.
    // $cstr_mbr_id may single, multiple or null
    function get_total_product_stock($clnt_id, $cstr_mbr_id = '', $prd_id = '', $ugp_id = '', $fdate = '', $tdate = '')
    {
        $final_stock = $this->get_final_stock_in_date_query($clnt_id, $cstr_mbr_id, $prd_id, '', $ugp_id, true, $fdate, $tdate);

        $str = " SELECT s3.stkavg_fk_products, s3.stkavg_ugp_id, s3.stkavg_unt_id, SUM(s3.stkavg_bal_qty) AS qty, SUM((s3.stkavg_bal_rate *  s3.stkavg_bal_qty)) AS amt";
        $str .= " FROM $final_stock s3";
        $str .= " GROUP BY s3.stkavg_fk_products, s3.stkavg_ugp_id ";

        $R = $this->db->query($str)->row_array();
        // print_pre($R);

        return $R;
    }




    /**
     * get_batches
     * Getting all batches in which those have quantity. (Zero qty not be considered)
     *
     * @param  mixed $prd_id
     * @return void
     */
    function get_batches($clnt_id, $prd_id)
    {
        $final_stock = $this->get_final_stock_query($clnt_id,  '', $prd_id, '', '', FALSE);
        $str = " SELECT DISTINCT(s3.stkavg_fk_product_batches) FROM $final_stock s3 ";
        $R = $this->db->query($str)->result_array();
        $R = $this->get_ids_from_query_result($R, 'stkavg_fk_product_batches');
        return $R;
    }

    public function index($input, $flag)
    {
        $this->db->from("$this->table,products,units,godowns,product_batches");


        if (ifSetInput($input, 'f_stkavg_date'))
            $this->db->where('stkavg_date >= ', get_sql_date_time($input['f_stkavg_date'], 'first'));

        if (ifSetInput($input, 't_stkavg_date'))
            $this->db->where('stkavg_date <= ', get_sql_date_time($input['t_stkavg_date'], 'last'));

        if (ifSetInput($input, 'stkavg_cstr_mbr_id'))
            $this->db->where('stkavg_cstr_mbr_id', $input['stkavg_cstr_mbr_id']);

        if (ifSetInput($input, 'stkavg_fk_godowns'))
            $this->db->where('stkavg_fk_godowns', $input['stkavg_fk_godowns']);

        if (ifSetInput($input, 'stkavg_fk_products'))
            $this->db->where('stkavg_fk_products', $input['stkavg_fk_products']);

        if (ifSetInput($input, 'stkavg_fk_product_batches'))
            $this->db->where('stkavg_fk_product_batches', $input['stkavg_fk_product_batches']);

        if (ifSetInput($input, 'stkavg_ugp_id'))
            $this->db->where('stkavg_ugp_id', $input['stkavg_ugp_id']);

        $this->db->where('stkavg_fk_clients', $input['clnt_id']);

        $this->db->where('prd_id = stkavg_fk_products');
        $this->db->where('unt_id = stkavg_unt_id');
        $this->db->where('gdn_id = stkavg_fk_godowns');
        $this->db->where('pdbch_id = stkavg_fk_product_batches');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,prd_name,gdn_name,unt_name,pdbch_name");
            $this->db->order_by('stkavg_date');
            $this->db->order_by('stkavg_order');
            $this->db->order_by($this->p_key);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }



    /**
     * get_next_order
     *
     * @param  mixed $date      : Date should be SQL formated date 'YYYY-MM-DD HH:MM:SS'
     * @return void
     */
    function get_next_order($date)
    {
        // $result = $this->db->select('count(*) as allcount')->where('stkavg_date', $date)->get($this->table)->row();
        // return $result->allcount + 1;

        $R = $this->db->select('MAX(stkavg_order) as odr')
            ->where('stkavg_date', $date)
            ->get($this->table)->row();

        $R = isset($R->odr) ? ++$R->odr : 1;
        return $R;
    }

    function get_godowns_final_qty($date, $gdn_id, $prd_id, $pdbch_id, $ugp_id)
    {
        $this->db->select('stkavg_bal_gdn_qty')
            ->where('stkavg_date <= ', $date)
            ->where('stkavg_fk_godowns', $gdn_id)
            ->where('stkavg_fk_products', $prd_id)
            ->where('stkavg_fk_product_batches', $pdbch_id)
            ->where('stkavg_ugp_id', $ugp_id)
            ->order_by('stkavg_date', 'desc')
            ->order_by('stkavg_order', 'desc')
            ->order_by($this->p_key, 'desc');
        $R = $this->db->get($this->table, 1, 0)->row_array();
        $R = isset($R['stkavg_bal_gdn_qty']) ? $R['stkavg_bal_gdn_qty'] : 0;

        // echo_div($this->db->last_query());

        return $R;
    }

    /**
     * get_central_store_final_stock          : Final Stock in Central Store
     *
     * @param  mixed $date      : Date should be SQL formated date 'YYYY-MM-DD HH:MM:SS'
     * @param  mixed $cstr_id
     * @param  mixed $prd_id
     * @param  mixed $pdbch_id
     * @param  mixed $ugp_id
     * @return void
     */
    function get_central_store_final_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id)
    {
        $this->db->where('stkavg_date <= ', $date)
            ->where('stkavg_cstr_mbr_id', $cstr_id)
            ->where('stkavg_fk_products', $prd_id)
            ->where('stkavg_fk_product_batches', $pdbch_id)
            ->where('stkavg_ugp_id', $ugp_id)
            ->order_by('stkavg_date', 'desc')
            ->order_by('stkavg_order', 'desc')
            ->order_by($this->p_key, 'desc');
        $R = $this->db->get($this->table, 1, 0)->row_array();

        $R['stkavg_bal_qty'] = isset($R['stkavg_bal_qty']) ? $R['stkavg_bal_qty'] : 0;
        $R['stkavg_bal_rate'] = isset($R['stkavg_bal_rate']) ? $R['stkavg_bal_rate'] : 0;
        return $R;
    }


    /**
     * get_stock
     *
     * @param  mixed $date      : Date should be SQL formated date 'YYYY-MM-DD HH:MM:SS'
     * @param  mixed $cstr_id
     * @param  mixed $prd_id
     * @param  mixed $pdbch_id
     * @param  mixed $ugp_id
     * @return void
     */
    function get_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id)
    {
        $this->db->where('stkavg_date >= ', $date)
            ->where('stkavg_cstr_mbr_id', $cstr_id)
            ->where('stkavg_fk_products', $prd_id)
            ->where('stkavg_fk_product_batches', $pdbch_id)
            ->where('stkavg_ugp_id', $ugp_id)
            ->order_by('stkavg_date')
            ->order_by('stkavg_order')
            ->order_by($this->p_key);
        $R = $this->db->get($this->table)->result_array();
        return $R;
    }


    /**
     * get_gdn_stock
     *
     * @param  mixed $date
     * @param  mixed $gdn_id
     * @param  mixed $prd_id
     * @param  mixed $pdbch_id
     * @param  mixed $ugp_id
     * @param  mixed $not       :   Commonly Used on edit
     * @return void
     */
    function get_gdn_stock($date, $gdn_id, $prd_id, $pdbch_id, $ugp_id = '', $not = array())
    {
        if ($ugp_id) {
            $ugp_ids[] = array('stkavg_ugp_id' => $ugp_id);
        } else { // Getting all unit groups first
            $ugp_ids = $this->db->select('DISTINCT(stkavg_ugp_id)')
                ->from($this->table)
                ->where('stkavg_fk_godowns', $gdn_id)
                ->where('stkavg_fk_products', $prd_id)
                ->where('stkavg_fk_product_batches', $pdbch_id)
                ->get()->result_array();
        }

        $stock = array();

        // Taking the final stock under each unit group
        foreach ($ugp_ids as $ugp) {
            $this->db->from("$this->table,units,godowns")
                ->select('gdn_name, gdn_id, stkavg_bal_gdn_qty as gdn_qty, stkavg_ugp_group_no as ugp_group_no, unt_name, stkavg_ugp_id as ugp_id, unt_id')
                ->where('stkavg_date <= ', $date)
                ->where('stkavg_fk_godowns', $gdn_id)
                ->where('stkavg_fk_products', $prd_id)
                ->where('stkavg_fk_product_batches', $pdbch_id)
                ->where('stkavg_ugp_id', $ugp['stkavg_ugp_id'])
                ->where('unt_id = stkavg_unt_id')
                ->where('gdn_id = stkavg_fk_godowns')
                ->order_by('stkavg_date', 'desc')
                ->order_by('stkavg_order', 'desc')
                ->order_by($this->p_key, 'desc');
            if ($not) {
                $this->db->where('NOT(stkavg_ref_id = ' . $not['id'] . ' AND stkavg_ref_tbl = "' . $not['tbl'] . '")');
            }

            $stock[$ugp['stkavg_ugp_id']] = $this->db->get('', 1, 0)->row_array();
        }
        //echo $this->db->last_query();
        return $stock;
    }


    function get_cstr_stock($date = '', $cstr_id, $prd_id, $pdbch_id)
    {
        // Getting all unit groups first
        $ugp_ids = $this->db->select('DISTINCT(stkavg_ugp_id)')
            ->from($this->table)
            ->where('stkavg_cstr_mbr_id', $cstr_id)
            ->where('stkavg_fk_products', $prd_id)
            ->where('stkavg_fk_product_batches', $pdbch_id)
            ->get()->result_array();

        $stock = array();

        // Taking the final stock under each unit group
        foreach ($ugp_ids as $ugp) {
            $this->db->from("$this->table,units")
                ->select('stkavg_bal_qty as bal_qty, stkavg_bal_rate as bal_rate, unt_name, stkavg_ugp_id as ugp_id, unt_id');

            if ($date)
                $this->db->where('stkavg_date <= ', $date);
            $this->db->where('stkavg_cstr_mbr_id', $cstr_id)
                ->where('stkavg_fk_products', $prd_id)
                ->where('stkavg_fk_product_batches', $pdbch_id)
                ->where('stkavg_ugp_id', $ugp['stkavg_ugp_id'])
                ->where('unt_id = stkavg_unt_id')
                ->order_by('stkavg_date', 'desc')
                ->order_by('stkavg_order', 'desc')
                ->order_by($this->p_key, 'desc');

            $stock[$ugp['stkavg_ugp_id']] = $this->db->get('', 1, 0)->row_array();
        }

        return $stock;
    }


    // gETTING ALL CENTRAL STORES where the given product exist
    function get_cstr_ids($clnt_id, $prd_id)
    {
        $R = $this->db->from("$this->table,central_stores")
            ->select('DISTINCT(stkavg_cstr_mbr_id)')
            ->where('cstr_fk_members = stkavg_cstr_mbr_id')
            ->where('cstr_fk_clients', $clnt_id)
            ->where('cstr_status', ACTIVE)
            ->where('stkavg_fk_products', $prd_id)
            ->get()->result_array();

        return $this->get_ids_from_query_result($R, 'stkavg_cstr_mbr_id');
    }

    function get_all_godown_final_stock($clnt_id, $cstr_mbr_id = '', $prd_id = '', $pdbch_id = '', $ugp_id = '', $zero_stock = true, $fdate = '', $tdate = '')
    {
        $final_stock = $fdt_query = $tdt_query = $cstr_query = $prd_query = $pdbch_query = $ugp_query = $zero_query = '';

        $fdt_query = $fdate ? "  AND stkavg_date >= '" . get_sql_date_time($fdate, 'first') . "'" : '';
        $tdt_query = $tdate ? "  AND stkavg_date <= '" . get_sql_date_time($tdate, 'last') . "'" : '';

        if ($cstr_mbr_id)
            $cstr_query = is_array($cstr_mbr_id) ? ' AND ' . $this->array_query($cstr_mbr_id, 's1.stkavg_cstr_mbr_id') : " AND  s1.stkavg_cstr_mbr_id = $cstr_mbr_id ";

        if ($prd_id)
            $prd_query = is_array($prd_id) ? ' AND ' . $this->array_query($prd_id, 's1.stkavg_fk_products') : " AND  s1.stkavg_fk_products = $prd_id ";

        if ($pdbch_id)
            $pdbch_query = is_array($pdbch_id) ? ' AND ' . $this->array_query($pdbch_id, 's1.stkavg_fk_product_batches') : " AND  s1.stkavg_fk_product_batches = $pdbch_id ";

        if ($ugp_id)
            $ugp_query = is_array($ugp_id) ? ' AND ' . $this->array_query($ugp_id, 's1.stkavg_ugp_id') : " AND  s1.stkavg_ugp_id = $ugp_id ";

        if (!$zero_stock)
            $zero_query = 'AND s1.stkavg_bal_qty != 0';

        $final_stock = "(
                    SELECT s1.*
                    FROM stock_avg s1
                    WHERE s1.stkavg_id = (
                        SELECT s2.stkavg_id
                        FROM stock_avg s2
                        WHERE s2.stkavg_fk_godowns = s1.stkavg_fk_godowns AND s2.stkavg_fk_products = s1.stkavg_fk_products AND 
                        s2.stkavg_fk_product_batches = s1.stkavg_fk_product_batches AND s2.stkavg_ugp_id = s1.stkavg_ugp_id
                        $fdt_query $tdt_query $cstr_query $prd_query $pdbch_query $ugp_query $zero_query AND stkavg_fk_clients = $clnt_id 
                        ORDER BY s2.stkavg_date DESC, s2.stkavg_order DESC , s2.stkavg_id DESC LIMIT 1
                    )
                )";

        $str = " SELECT s3.*, gdn_name, prd_name,pdbch_name,unt_name ";
        $str .= " FROM $final_stock s3, products,godowns,product_batches,units";
        $str .= " WHERE gdn_id = s3.stkavg_fk_godowns ";
        $str .= " AND prd_id = s3.stkavg_fk_products ";
        $str .= " AND pdbch_id = s3.stkavg_fk_product_batches ";
        $str .= " AND unt_id = s3.stkavg_unt_id ";
        $str .= " ORDER BY gdn_name, prd_name, pdbch_name, unt_name ";

        $R = $this->db->query($str)->result_array();

        return $R;
    }
}
