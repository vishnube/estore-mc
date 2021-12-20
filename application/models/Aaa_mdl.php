<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Aaa_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('aaa', '');
    }

    function index($input)
    {

        $this->db->from($this->table);


        // id	invoice_date	pur_quantity	pur_rate	pur_amount	sl_quantity	sl_rate	sl_amount 	

        if (ifSetInput($input, 'f_date'))
            $this->db->where('invoice_date >= ', get_sql_date_time($input['f_date'], 'first'));

        if (ifSetInput($input, 't_date'))
            $this->db->where('invoice_date <= ', get_sql_date_time($input['t_date'], 'last'));

        $r['result'] =   $this->db->get()->result_array();
        $r['query'] = $this->db->last_query();
        return $r;
    }

    function get_sold_qty($date)
    {
        $this->db->from($this->table);
        $this->db->select('SUM(sl_quantity) QTY');
        $this->db->where('invoice_date <= ', $date);
        $r = $this->db->get()->row();
        return $r->QTY;
    }

    function get_sold_amt($date)
    {
        $this->db->from($this->table);
        $this->db->select('SUM(sl_amount) AMT');
        $this->db->where('invoice_date <= ', $date);
        $r = $this->db->get()->row();
        return $r->AMT;
    }

    // select t1.`pur_quantity`,t1.sl_quantity, sum(t1.pur_quantity) over (order by t1.id) as tot_purchase, sum(t1.pur_quantity-t1.sl_quantity) over (order by t1.id) as balance from aaa t1

    function get_purchase_data($sold_qty, $date)
    {
        // https://stackoverflow.com/questions/60244326/how-to-select-until-sum-a-column-reaches-a-value

        // In MySQL 8+ and the more recent versions of MySQL, you can use window functions:
        $q1 = "select t1.*, sum(t1.pur_quantity) over (order by t1.id) as running_amount from aaa t1 WHERE t1.invoice_date <= '$date'";
        $q1 = "select t1.* from ( $q1) t1 where running_amount <= $sold_qty ";



        // In older versions, you can do this with a correlated subquery or variables:
        $q2 = "(select sum(tt1.`pur_quantity`) from aaa tt1 where tt1.id <= t1.id)";
        $q2 = "select t1.*, $q2 as running_amount from aaa t1 WHERE t1.invoice_date <= '$date'";
        $q2 = "select t1.* from ($q2) t1 where running_amount <= 22 ";


        $r = $this->db->query($q1)->result_array();
        echo_div($this->db->last_query());
        return $r;
    }

    function get_invoked_purchase($date, $total_sale_qty)
    {
        // id	invoice_date	pur_quantity	pur_rate	pur_amount	sl_quantity	sl_rate	sl_amount 	

        // https://stackoverflow.com/questions/60244326/how-to-select-until-sum-a-column-reaches-a-value

        $q = "(select sum(tt1.`pur_quantity`) from aaa tt1 where tt1.id <= t1.id) as running_amount";
        $q = "select t1.* from (select t1.*, $q from  aaa  t1) t1 where running_amount <= $total_sale_qty";


        $r = $this->db->query($q)->result_array();
        echo_div($this->db->last_query());
        return $r;
    }
}
