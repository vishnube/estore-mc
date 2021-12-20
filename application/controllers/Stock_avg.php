<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Stock_avg extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'stock_avg';
        $this->load->model($this->table . "_mdl", $this->table);
        // $this->load->model("unit_groups_mdl", 'unit_groups');
        // $this->load->model('product_categories_mdl', 'product_categories');
        // $this->load->model('products_mdl', 'products');
        // $this->load->model('product_batches_mdl', 'product_batches');
    }

    function get_stock_flow()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;

        // print_pre($input);
        // return;


        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->stock_avg->index($input, TRUE);
        $json['stock_avg_data'] = $this->stock_avg->index($input, FALSE);
        $json['num_rows'] =  count($json['stock_avg_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_emplys");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        echo json_encode($json);
    }


    function get_all_godown_stock()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';
        $date = $this->input->post('date');
        $cstr_mbr_id = $this->input->post('cstr_mbr_id');
        $prd_id = $this->input->post('prd_id');
        $pdbch_id = $this->input->post('pdbch_ids');

        // If no batch specified, Getting all batches of the given product, having stock
        // if (!$pdbch_ids)
        //     $pdbch_ids = $this->stock_avg->get_batches($this->clnt_id, $prd_id);

        $ugp_id = $this->input->post('ugp_id');


        $json['stock'] = $this->stock_avg->get_all_godown_final_stock($this->clnt_id, $cstr_mbr_id, $prd_id, $pdbch_id, $ugp_id, FALSE, $date);

        echo json_encode($json);
    }
}
