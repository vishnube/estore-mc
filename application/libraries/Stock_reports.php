<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_reports
{
    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();

        $this->CI->load->model("stocks/stocks_mdl", 'stocks');

        $this->CI->load->model("stocks/stk_bills_mdl", 'stk_bills');

        $this->CI->load->model("unit_groups_mdl", 'unit_groups');
        $this->CI->load->helper('unit');
    }

    function get_stock($input)
    {
        if (!isset($input['offset']))
            $input['offset'] = 0;
        if (!isset($input['per_page']))
            $input['per_page'] = '';


        $input['clnt_id'] = $this->CI->clnt_id;

        $input['product_ids'] = $input['per_page'] ? $this->CI->stocks->get_product_ids($input) : '';

        if (!$input['product_ids'] && !$input['prd_id'])
            return array();

        $stock_data = $this->CI->stocks->index($input);

        // If need to get detailed output including unit-html, Qty in Topest Unit.
        if (isset($input['flag']) && $input['flag'] == 1) {
            $fixed_class = 'unt-span';
            $fixed_style = 'padding: 5px;';
            $toggle_class = array('text-info', 'text-success', 'text-danger', 'text-primary', 'text-warning');
            $def_icon = '<i class="fad fa-shield-check text-success" title="Default Unit"></i> &nbsp;';
            $dto = "<span class='cursor-pointer'>";
            $dtc = "</span>";
            $sep = '<i class="fas fa-chevron-double-right" style="font-size: 10px;"></i>';
            foreach ($stock_data as &$r) {
                $r['unit_html'] = $r['unit_html_export'] = '';
                if ($r['UGP_GROUP_NO']) {
                    $arr = get_formated_product_units($r['prd_id'], array($r['UGP_GROUP_NO']), 'span', $sep, $fixed_class, $fixed_style, $toggle_class, array(), $def_icon, $dto, $dtc);
                    $r['unit_html'] = $arr[0]['text_format'];
                    $arr = get_formated_product_units($r['prd_id'], array($r['UGP_GROUP_NO']), '', ', ');
                    $r['unit_html_export'] = $arr[0]['text_format'];

                    $ugp_row = $this->CI->unit_groups->get_highest_unit_by_group_no($r['UGP_GROUP_NO']);
                    $r['top_unt_name'] = $ugp_row['unt_name'];
                    $r['top_qty'] = intdiv($r['QTY'], $ugp_row['ugp_rel']);
                    $r['top_base_qty'] = $r['QTY'] % $ugp_row['ugp_rel'];
                }
            }
        }

        return $stock_data;
    }

    function get_stock_records_option()
    {
        $rec['pchs_bls'] = 'PURCHASE BILLS';
        $rec['sls_bls'] = 'SALES BILLS';

        return $rec;
    }
}
