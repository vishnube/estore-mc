<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Stocks extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();

        $this->load->library('stock_reports');
    }

    public function index()
    {
        if (!has_task('tsk_stk')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $this->load->model("central_stores_mdl", 'central_stores');
        $this->load->model("products_mdl", 'products');

        $data['active_nav'] = 'Reports';  // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'stocks'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'stocks';
        $data['icon'] = $this->tasks->get_icon(174);

        $data['cstr_option'] = $this->central_stores->get_active_option(array('cstr_fk_clients' => $this->clnt_id));
        $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
        $data['rec_option'] =  $this->stock_reports->get_stock_records_option();

        // Checking user tasks
        $data['tsk_stk_list'] = has_task('tsk_stk_list');
        $data['tsk_stk_pdf'] = has_task('tsk_stk_pdf');
        $data['tsk_stk_excel'] = has_task('tsk_stk_excel');
        $data['tsk_stk_print'] = has_task('tsk_stk_print');


        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('stocks/index', $data);
    }

    function get_stks()
    {
        //Checking tasks
        if (!has_task('tsk_stk_list')) {
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['clnt_id'] = $this->clnt_id;

        $curPage =  (isset($input['offset']) && $input['offset'] != 0) ?  $input['offset'] : 1;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];


        // "stocks/stocks_mdl" is loaded in "libraries\Stock_reports.php"
        $json['total_rows'] = $this->stocks->get_product_ids($input, TRUE);

        $json['stock_data'] = $this->stock_reports->get_stock($input);

        $json['num_rows'] = $json['total_rows'];
        if ($json['total_rows'] && $input['per_page']) {
            $json['num_rows'] =  $curPage > intdiv($json['total_rows'], $input['per_page']) ? ($json['total_rows'] % $input['per_page']) : $input['per_page'];
        }

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_stks");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        echo json_encode($json);
    }
}
