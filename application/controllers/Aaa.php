<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Aaa extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'aaa_test_stock';
        $this->load->model("aaa_mdl", 'aaa');
    }

    function index()
    {
        if ($data = $this->input->post()) {
            //$input = $this->input->post();
            //$tbl = $this->aaa->index($input, $data);
            $date = '2021-02-28';
            $data['tbl'] = array();
            for ($i = 1; $i <= 5; $i++) {
                $date = date('Y-m-d', strtotime($date . ' +1 day'));
                $sold_qty = $this->aaa->get_sold_qty($date);
                $sold_amt = $this->aaa->get_sold_amt($date);
                $purchase_data = $this->aaa->get_purchase_data($sold_qty, $date);
            }

            echo_div(CI_VERSION);

            $str = $this->load->view('aaa/index', $data, TRUE);
            echo $str;
        } else {

            $data['post'] = '';
            // echo "<div style='margin-left:100px; margin-top:50px;'>";

            // $date = '2021-05-08';
            // $total_sale_qty = $this->aaa->get_sale_qty($date);
            // echo "Sale Qty Upto $date = $total_sale_qty <br><br>";

            // $invoked_purchase = $this->aaa->get_invoked_purchase($date, $total_sale_qty);
            // print_pre($invoked_purchase);


            // echo '</div>';

            $data['user_settings_reftbl'] = '';
            $this->_render_page('aaa/index', $data);
        }
    }



    function test()
    {
        if ($this->input->post()) {
            $input = $this->input->post();
            asort($input);
            echo json_encode($input);
        } else {
            $data['user_settings_reftbl'] = '';
            $this->_render_page('aaa/test_table', $data);
        }
    }
}
