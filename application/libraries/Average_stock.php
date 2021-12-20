<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Average_stock
{
    protected $CI;

    // SELECT `stkavg_id` as id, `stkavg_date` as date,`stkavg_order` as odr, `stkavg_fk_godowns` as gdn, `stkavg_fk_product_batches` as batch, `stkavg_qty_in` as qin, `stkavg_qty_out` as qout, `stkavg_rate` as rate, `stkavg_ugp_id` as ugp, `stkavg_bal_qty` as bal_qty, `stkavg_bal_rate` as bal_rate,`stkavg_bal_qty`*`stkavg_bal_rate` as bal_amt, `stkavg_bal_gdn_qty` as gdn_qty FROM `stock_avg`  
    // ORDER BY `stock_avg`.`stkavg_date`  ASC

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();

        $this->CI->load->model("stock_avg_mdl", 'stock_avg');
        $this->CI->load->model("products_mdl", 'products');
        $this->CI->load->model("unit_groups_mdl", 'unit_groups');
        // $this->CI->load->helper('unit');
    }

    //SELECT * FROM `stock_avg` WHERE `stkavg_cstr_mbr_id`=175 AND `stkavg_fk_product_batches` = 1 AND `stkavg_ugp_id` = 1 
    /**
     * reset_stock        : Recalculating the clossing stock qty/rate
     *
     * @param  mixed $date      : Date should be SQL formated date 'YYYY-MM-DD HH:MM:SS'
     * @param  mixed $cstr_id
     * @param  mixed $prd_id
     * @param  mixed $pdbch_id
     * @param  mixed $ugp_id
     * @return void
     */
    function reset_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id)
    {
        // For accurracy, taking stock from 1 second back.
        $date = date("Y-m-d H:i:s", strtotime("$date -1 second"));
        $stk = $this->CI->stock_avg->get_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id);

        if (!$stk)
            return;

        // Final stock date should be less than the stock taken date (above date).
        $final_date = date("Y-m-d H:i:s", strtotime("$date -1 second"));
        $fstk = $this->get_central_store_final_stock($final_date, $cstr_id, $prd_id, $pdbch_id, $ugp_id);

        // Sometimes there may have duplicate godown entry with same centralstore/product/bactch/ugp_id in a bill.
        // So we need to keep the running godown stock till the end of itration.
        $last_gdn_qty = array();

        foreach ($stk as &$r) {
            $bal_qty = $bal_rate = $bal_gdn_qty = 0;
            $gdn_id = $r['stkavg_fk_godowns'];

            if (isset($last_gdn_qty[$gdn_id])) {
                $final_gdn_qty =  $last_gdn_qty[$gdn_id];
            } else {
                $final_gdn_qty =   $this->CI->stock_avg->get_godowns_final_qty($final_date, $gdn_id, $prd_id, $pdbch_id, $ugp_id);
            }

            if ((float)$r['stkavg_qty_in']) {
                // Balance Quantity = Current Quantity + Final Quantity
                $bal_qty = bcadd($fstk['stkavg_bal_qty'], $r['stkavg_qty_in'], 5);

                // Balance Godown qty
                $bal_gdn_qty = bcadd($final_gdn_qty, $r['stkavg_qty_in'], 5);

                // Current Amount = Current Quantity * Current Rate
                $cur_amt = bcmul($r['stkavg_qty_in'], $r['stkavg_rate'], 5);

                // Final  Amount = Final Quantity * Final Rate
                $final_amt = bcmul($fstk['stkavg_bal_qty'], $fstk['stkavg_bal_rate'], 5);

                // Balance Amount = Current Amount + Final Amount
                $bal_amt = bcadd($cur_amt, $final_amt, 5);

                // Average rate of Balance stock
                $bal_rate = (float)$bal_qty ? bcdiv($bal_amt, $bal_qty, 5) : $fstk['stkavg_bal_rate'];
            } else if ((float)$r['stkavg_qty_out']) {
                // Balance Quantity = Final Quantity - Current Quantity
                $bal_qty = bcsub($fstk['stkavg_bal_qty'], $r['stkavg_qty_out'], 5);

                // Balance Godown qty
                $bal_gdn_qty = bcsub($final_gdn_qty, $r['stkavg_qty_out'], 5);

                // Average rate of Balance stock
                $bal_rate = $fstk['stkavg_bal_rate'];
            } else {
                $bal_qty = $fstk['stkavg_bal_qty'];

                // Balance Godown qty
                $bal_gdn_qty = $final_gdn_qty;

                // Average rate of Balance stock
                $bal_rate = $fstk['stkavg_bal_rate'];
            }

            $r['stkavg_bal_qty'] = $bal_qty;
            $r['stkavg_bal_rate'] = $bal_rate;
            $r['stkavg_bal_gdn_qty'] = $bal_gdn_qty;
            $last_gdn_qty[$gdn_id] = $bal_gdn_qty;

            $this->CI->stock_avg->save($r, $r['stkavg_id']);
            $fstk = $r;
        }
    }

    function get_central_store_final_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id)
    {
        $final_stk = $this->CI->stock_avg->get_central_store_final_stock($date, $cstr_id, $prd_id, $pdbch_id, $ugp_id);
        return $final_stk;
    }



    /**
     * check_stock
     *
     * @param  mixed $bls_date      : SQL formated datetime
     * @param  mixed $gdn_id        : 
     * @param  mixed $prd_id        : 
     * @param  mixed $pdbch_id      : 
     * @param  mixed $qty           : Input Qty
     * @param  mixed $ugp_id        : Input ugp_id 
     * @param  mixed $running_stock : [optional] running stock of the product when duplicate product entry occures (Usefull in billing).
     *                                  If there is a duplicate entry of a product in a bill, 
     *                                  to get actual stock, we need to sum qty of all duplicate entries.
     * @param  mixed $not           : [optional] Does not take the stock related to $not['id'] & $not['tbl']  
     *                                  $not['id'] => Fld: stkavg_ref_id @ Tbl: stock_avg;
                                        $not['tbl'] = Fld: stkavg_ref_tbl @ Tbl: stock_avg;
     * @param  mixed $msg_preppend  : [optional] If anything to preppend with message (Usefull in billing)
     * @param  mixed $msg_append    : [optional] If anything to append with message (Usefull in billing)
     * @return void
     */
    function check_stock($bls_date, $gdn_id, $prd_id, $pdbch_id, $qty, $ugp_id, $running_stock = array(), $not = array(), $msg_preppend = '', $msg_append = '')
    {
        $stock_report = array();
        $error_msg = '';
        $avail_stk = array('qty' => '', 'ugp_id' => '', 'unt_name' => ''); // Available Stock
        $stk_status = TRUE; // Having enough Stock.

        $prd_name = $this->CI->products->get_name_by_id($prd_id);

        // Getting basic ugp_id
        $basic_ugp_id = $this->CI->unit_groups->get_basic_ugp($ugp_id);

        // Getting current stock in basic ugp_id
        $stk = $this->CI->stock_avg->get_gdn_stock($bls_date, $gdn_id, $prd_id, $pdbch_id, $basic_ugp_id, $not);

        $dup_entry_str = ''; // Not a duplicate product entry

        if ($stk[$basic_ugp_id]) {

            // Current available stock (As per given datetime).
            $avail_stk['qty'] = $stk[$basic_ugp_id]['gdn_qty'];
            $avail_stk['ugp_id'] = $basic_ugp_id;
            $avail_stk['unt_name'] = $stk[$basic_ugp_id]['unt_name'];

            $ugp_row = $this->CI->unit_groups->get_by_id($ugp_id);

            // Input Quantity in Basic ugp_id
            $base_qty = bcmul($qty, $ugp_row['ugp_rel'], 5);

            // If duplicate product entry occured, Summing current qty with the previous qty
            if (isset($running_stock[$gdn_id][$prd_id][$pdbch_id][$basic_ugp_id])) {
                $base_qty = bcadd($base_qty, $running_stock[$gdn_id][$prd_id][$pdbch_id][$basic_ugp_id], 5);

                // It is a duplicate product entry
                $dup_entry_str = '<span class="dup-entry" title="The product has already entered in previous row"><i class="far fa-exclamation-triangle text-warning"></i> DUPLICATE ENTRY</span>';
            }

            $running_stock[$gdn_id][$prd_id][$pdbch_id][$basic_ugp_id] = $base_qty;

            if ($stk[$basic_ugp_id]['gdn_qty'] <  $base_qty) {
                $stk_status = FALSE; // No enough Stock.
                $error_msg = $this->create_nostock_msg($ugp_id, $stk[$basic_ugp_id]['gdn_qty'], $msg_preppend, $msg_append, $dup_entry_str, $prd_name);
            }
        } else {
            $stk_status = FALSE; // No enough Stock.
            $error_msg = '<i class="far fa-exclamation-triangle text-warning"></i>&nbsp;' . $msg_preppend . " No Stock Available for <span class='text-info'>$prd_name</span> " . $msg_append . $dup_entry_str;
        }

        $stock_report['stk_status'] = $stk_status;  // TRUE =>  Having enough stock, FALSE => No enough stock
        $stock_report['avail_stk'] = $avail_stk;    // Current vailable stock details (as per given datetime)
        $stock_report['error_msg'] = $error_msg;    // Error message when no enough stock
        $stock_report['running_stock'] = $running_stock; // Running stock (It is usefull when duplicate product entry occures. Eg: in Billing)

        return $stock_report;
    }



    /**
     * create_nostock_msg
     *
     * @param  mixed $ugp_id        :   Current Ugp_id
     * @param  mixed $cur_stk_qty   :   Current  Qty in basic unit
     * @param  mixed $msg_preppend  : [optional] If anything to preppend with message (Usefull in billing)
     * @param  mixed $msg_append    :   [optional] If anything to append with message (Usefull in billing)
     * @param  mixed $dup_entry_str :   [optional] The message related to duplicate product entry (Usefull in billing)
     * @return void
     */
    function create_nostock_msg($ugp_id, $cur_stk_qty, $msg_preppend = '', $msg_append = '', $dup_entry_str = '', $prd_name)
    {
        $ugp_row = $this->CI->unit_groups->get_ugp_dt($ugp_id);
        $group_no = $this->CI->unit_groups->get_group_no($ugp_id);
        $heigh_ugp = $this->CI->unit_groups->get_highest_unit_by_group_no($group_no);

        // Getting basic ugp_id
        $basic_ugp_id = $this->CI->unit_groups->get_basic_ugp($ugp_id);
        $basic_upg_row = $this->CI->unit_groups->get_ugp_dt($basic_ugp_id);
        $basic_str = round($cur_stk_qty, 2) . ' ' . $basic_upg_row['unt_name'];

        $rel_str = '';
        $top_str = '';

        // If the current unit itself not both the basic unit and heighest unit.
        if (($ugp_id != $basic_ugp_id) && ($ugp_id != $heigh_ugp['ugp_id'])) {
            $rel_str .= ' <span class="text-danger">[1 ' . $ugp_row['unt_name'] . ' = ' . (float)$ugp_row['ugp_rel'] . ' ' . $basic_upg_row['unt_name'] . '] </span>';
        }

        // If the basic unit itself is the heighest unit
        if ($heigh_ugp['ugp_id'] != $basic_ugp_id) {
            $top_unt_name = $heigh_ugp['unt_name'];
            $top_qty = intdiv($cur_stk_qty, $heigh_ugp['ugp_rel']);
            $balance_base_qty = $cur_stk_qty % $heigh_ugp['ugp_rel'];
            if ($top_qty) {
                $top_str = ' <span class="text-info">[' . $top_qty . ' ' . $top_unt_name;
                if ($balance_base_qty)
                    $top_str .=  ' + ' . $balance_base_qty . ' ' . $basic_upg_row['unt_name'];
                $top_str .=  ']</span>';
            }
            $rel_str .= ' <span class="text-primary">[1 ' . $top_unt_name . ' = ' . (float)$heigh_ugp['ugp_rel'] . ' ' . $basic_upg_row['unt_name'] . '] </span>';
        }

        $rel_str = $rel_str ? '<span class="dup-entry mx-2">' . $rel_str . '</span>' : '';

        $error_msg = '<i class="far fa-exclamation-triangle text-warning"></i>&nbsp;' . $msg_preppend . "Available Stock of <span class='text-info'>$prd_name</span> is only " . $basic_str . $top_str  . $msg_append . $rel_str . $dup_entry_str;

        return $error_msg;
    }
}
