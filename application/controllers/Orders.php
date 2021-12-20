<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Orders extends My_controller
{
    var $PENDING;
    var $PICKED;
    var $BILLED;
    var $PACKED;
    var $ESTORE;
    var $DELIVERED;
    var $PAID;

    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'bills';
        $this->load->model("orders_mdl", 'orders');
        $this->load->model("bills_mdl", 'bills');
        $this->load->model("order_status_mdl", 'order_status');
        $this->load->model("bill_products_mdl", 'bill_products');
        $this->load->model("user_central_stores_mdl", 'user_central_stores');
        $this->load->model("families_mdl", 'families');
        $this->load->model("central_stores_mdl", 'central_stores');
        $this->load->model("bill_batches_mdl", 'bill_batches');
        $this->load->model("bill_nos_mdl", 'bill_nos');
        $this->load->model("bill_types_mdl", 'bill_types');
        $this->load->model("products_mdl", 'products');
        $this->load->model("units_mdl", 'units');
        $this->load->model("stock_avg_mdl", 'stock_avg');
        $this->load->library('average_stock');


        $this->PENDING = $this->order_status::PENDING;
        $this->PICKED = $this->order_status::PICKED;
        $this->BILLED = $this->order_status::BILLED;
        $this->PACKED = $this->order_status::PACKED;
        $this->ESTORE = $this->order_status::ESTORE;
        $this->DELIVERED = $this->order_status::DELIVERED;
        $this->PAID = $this->order_status::PAID;
    }

    function index()
    {
        if (!has_task('tsk_odr')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'vouchers'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'sales orders'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'sales orders';
        $data['icon'] = $this->tasks->get_icon(189);
        $data['cstr_option'] = $this->user_central_stores->get_users_central_stores_option($this->clnt_id, $this->usr_id, $this->usr_type);

        // $prty_mbrtp_id = $this->parties->get_member_type_id(); //mbrtp_id = 3 => Parties
        // $data['prty_option'] = $this->members->get_members_option(array('mbr_fk_clients' => $this->clnt_id), ACTIVE, $prty_mbrtp_id);
        $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));


        $data['odr_status'] = $this->order_status->get_status();
        $data['prd_data'] = $this->products->get_data(array('prd_fk_clients' => $this->clnt_id, 'prd_status' => ACTIVE), '', array('prd_id', 'prd_name', 'prd_code', 'prd_barcode', 'prd_estr_cmsn_p', 'prd_exp_p'));

        // Reading User Tasks
        $tasks = $this->tasks->get_by_key('tsk_odr');
        $data['tasks'] = array();
        foreach ($tasks as $tsk)
            $data['tasks'][$tsk['tsk_key']] = has_task($tsk['tsk_key']);

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('orders/index', $data);
    }

    // function t()
    // {
    //     echo $this->order_status->get_cur_status(8, 'ost_status');
    //     print_r($this->order_status->get_cur_status(8));
    // }

    function get_odrs()
    {
        $bill_type = 'sls_odr';  // Only Sales order needs here.

        //Checking tasks
        if (!has_task('tsk_odr_list')) {
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


        $input['cstr_id'] = $input['cstr_id'] ? $input['cstr_id'] : $this->user_central_stores->get_users_central_stores_option($this->clnt_id, $this->usr_id, $this->usr_type, ACTIVE, 'ids');


        $input['bls_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->orders->index($input, TRUE);
        $json['order_data'] = $this->orders->index($input, FALSE);
        $json['num_rows'] =  count($json['order_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_odrs");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        $json['ost_option_vals'] = $this->order_status->get_status();

        foreach ($json['order_data'] as &$row) {
            $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
            $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
            $row['bill_no'] = array_merge($bill_no, $bill_batch);

            $row['bill_time'] = date('h:i:s A', strtotime($row['bls_date']));
            $row['bls_date'] = date('d-m-Y', strtotime($row['bls_date']));

            $row['from'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
            $row['to'] = $this->members->get_name_by_id($row['bls_to_fk_members']);
            $fmly_id = $this->families->get_id_by_member($row['bls_to_fk_members']);
            $row['ward'] = $this->orders->get_ward_string($fmly_id);
            $row['estore_data'] = $this->orders->get_estore_string($fmly_id);
            $row['prd_count'] = $this->bill_products->get_product_count($row['bls_id']);

            $row['ost_status'] = '';
            $row['ost_status_txt'] = '';
            $ost_status = $this->order_status->get_cur_status($row['bls_id'], 'ost_status');
            $row['ost_status'] = $ost_status;
            if ($ost_status) {
                // Some classes are defined @ orders\index.php
                $toggle_class = array('badge-pink', 'badge-info', 'badge-success', 'badge-danger', 'badge-primary', 'badge-warning', 'badge-teal', 'badge-yellow');
                $class = toggle2(($ost_status - 1), $toggle_class);
                $ost_status = $this->order_status->get_status($ost_status, 'strtoupper');
                $row['ost_status_txt'] = "<span class='badge $class'>$ost_status</span>";
            }
            $row['order_flow'] = $this->order_status->get_flow($row['bls_id']);


            // Converted bills. Eg:- Sales Order converted to Sale Bill
            $row['ref_to'] = '';
            if ($ref_row = $this->bills->get_data(array('bls_ref_key' => $row['bls_id']), '', 'bls_bill_cat,bls_bill_type')) {
                if (count($ref_row) > 1) {
                    $row['ref_to'] = "MANY";  // Converted to other forms many times
                } else {
                    $ref_to = $this->bill_types->get_row(array('btp_key' => $ref_row[0]['bls_bill_type']));
                    $row['ref_to'] = $ref_to['btp_name']; // If only once converted, Converted to which form.
                }
            }
        }

        echo json_encode($json);
    }

    function move_to_next()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $moveTo = $this->input->post('moveTo');
        $orders = $this->input->post('orders');


        if (!$moveTo) {
            $json['status'] = 2;
            $json['o_error'] = 'Next level not specified';
            echo json_encode($json);
            return;
        }
        if (!$orders) {
            $json['status'] = 2;
            $json['o_error'] = 'No orders found';
            echo json_encode($json);
            return;
        }

        foreach ($orders as $bls_id) {
            $this->order_status->update_where(array('ost_final' => 2),  "ost_fk_bills = $bls_id"); // Setting previous are not final.
            $ost_data = array();
            $ost_data['ost_fk_clients'] = $this->clnt_id;
            $ost_data['ost_fk_bills'] = $bls_id;
            $ost_data['ost_date'] = get_sql_date_time();
            $ost_data['ost_fk_users'] = $this->usr_id;
            $ost_data['ost_status'] = $moveTo;
            $ost_data['ost_final'] = 1; // This is the final status
            $this->order_status->save($ost_data);
        }

        echo json_encode($json);
    }

    function get_details()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find order';
            echo json_encode($json);
            return;
        }

        $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
        $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
        $row['bill_no'] = array_merge($bill_no, $bill_batch);
        $row['from'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
        $row['to'] = $this->members->get_name_by_id($row['bls_to_fk_members']);
        $row['bls_date'] = date('d-m-Y h:i:s A', strtotime($row['bls_date']));
        $row['blp_data'] = $this->bill_products->get_bill_products($bls_id);

        $row['after_ajax'] = TRUE;
        $json['html'] = $this->load->view('orders/show_details', $row, true);
        echo json_encode($json);
        return;
    }



    function show_bill()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $ref_key = $this->input->post('ref_key');
        $row = $this->bills->get_row(array('bls_ref_key' => $ref_key, 'bls_bill_type' => 'sls_bls'));

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find order';
            echo json_encode($json);
            return;
        }
        //echo "To: " . $row['bls_to_fk_members'] . "<br>";
        $bls_id = $row['bls_id'];
        $json['bls_id'] =  $bls_id;

        $fmly_id = $this->families->get_id_by_member($row['bls_to_fk_members']);
        $estore = $this->orders->get_estore_string($fmly_id);
        $row['estr_name'] = $estore['estr_name'];

        $area = $this->orders->get_ward($fmly_id);
        $row['area'] = $area['ars_name'] . " (" . $area['wrd_name'] . ")";


        $ost_status = $this->order_status->get_cur_status($bls_id, 'ost_status');
        $row['ost_status_txt'] = $ost_status ? $this->order_status->get_status($ost_status, 'strtoupper') : 'UNKNOWN';

        $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
        $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
        $row['bill_no'] = array_merge($bill_no, $bill_batch);
        $row['cstr_name'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
        $row['family'] = $this->members->get_name_by_id($row['bls_to_fk_members']);
        $row['bls_date'] = date('d-m-Y h:i:s A', strtotime($row['bls_date']));
        $row['blp_data'] = $this->bill_products->get_bill_products($bls_id);

        $row['after_ajax'] = TRUE;
        $json['html'] = $this->load->view('orders/show_bill', $row, true);
        echo json_encode($json);
        return;
    }

    function read_order()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find order';
            echo json_encode($json);
            return;
        }
        $json  = array_merge($json, $row);
        $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
        $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
        $bls_date = date('d-m-Y h:i:s A', strtotime($row['bls_date']));
        $fmly_id = $this->families->get_id_by_member($row['bls_to_fk_members']);
        $estore = $this->orders->get_estore_string($fmly_id);
        $area = $this->orders->get_ward($fmly_id);
        $to = $this->members->get_name_by_id($row['bls_to_fk_members']);

        $ost_status = $this->order_status->get_cur_status($bls_id, 'ost_status');
        $ost_status_txt = $ost_status ? $this->order_status->get_status($ost_status, 'strtoupper') : 'UNKNOWN';

        $row['blp_data'] = $this->bill_products->get_order_products($bls_id);


        $json['odrcstr'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
        $json['odrno'] = $bill_batch['blb_prefix'] . $bill_no['bln_name'] . $bill_batch['blb_sufix'];
        $json['odrdt'] = $bls_date;
        $json['odrestr'] = $estore['estr_name'];
        $json['odrst'] = $ost_status_txt;
        $json['odrare'] = $area['ars_name'] . " (" . $area['wrd_name'] . ")";
        $json['odrfmly'] = $to;

        $json['html'] = $this->load->view('orders/add_order', $row, true);
        echo json_encode($json);
        return;
    }

    function test()
    {
        $cstr_ids = $this->user_central_stores->get_users_central_stores_option($this->clnt_id, $this->usr_id, $this->usr_type, ACTIVE, 'ids');

        // ($clnt_id, $cstr_mbr_id = '', $prd_id = '', $pdbch_id = '', $ugp_id = '', $fdate = '', $tdate = '')
        $this->stock_avg->get_total_stock($this->clnt_id, $cstr_ids);
    }




    function get_stock()
    {
        //Checking tasks
        if (!has_task('tsk_odr_stk_list')) {
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
        $input['bls_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];

        $input['cstr_id'] = $input['cstr_id'] ? $input['cstr_id'] : $this->user_central_stores->get_users_central_stores_option($this->clnt_id, $this->usr_id, $this->usr_type, ACTIVE, 'ids');

        $json['total_rows'] = $this->orders->get_stock($input, TRUE);
        $json['order_data'] = $this->orders->get_stock($input, FALSE);
        $json['num_rows'] =  count($json['order_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_stock");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        foreach ($json['order_data'] as &$r) {

            // Getting PICKED stock also
            $r['picked'] = $this->orders->get_picked_qty($r['blp_fk_products'], $r['blp_basic_ugp_id'], $input['cstr_id']);

            $r['stk'] = $this->stock_avg->get_total_product_stock($this->clnt_id, $input['cstr_id'], $r['blp_fk_products'], $r['blp_basic_ugp_id']);

            $r['prd_name'] = $this->products->get_name_by_id($r['blp_fk_products']);
            $r['unt_name'] = $this->units->get_name_by_id($r['blp_basic_unt_id']);
        }

        echo json_encode($json);
    }

    function check_stock()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $godowns = $this->input->post('blp_fk_godowns');
        $products = $this->input->post('blp_fk_products');
        $batches = $this->input->post('blp_fk_product_batches');
        $qty = $this->input->post('blp_qty');
        $units = $this->input->post('blp_fk_unit_groups');

        $blp_errors = array();

        // To check stock. Some times there may be duplicate entry of products.
        // So to get actual stock, we need to sum all duplicate entries.
        $running_stock = array();

        $wraper_1 = '<span>';
        $wraper_2 = '&nbsp;<i class="fas fa-search no-stock cursor-pointer" title="Check in other godown/batch"></i></span>';


        // Here we are looping with godown array. So if when there is a godown is missing, that row won't be validate.
        // So first checking missing godowns.
        if (count($godowns) != count($products) || count($godowns) != count($batches) || count($godowns) != count($units)) {
            $json['o_error'] .= '<div>A godown is missed in a row</div>';
        } else {
            foreach ($godowns as $k => $v) {
                // 1 => allow to save even there is no enough stock, 2=> Don't allow when no stock
                $check_stock = $this->settings->get_settings_value($this->clnt_id, '', 'sls_bls', 1, '', 'NO_STK');

                if ($check_stock == 2) {
                    $date =  get_sql_date_time();
                    $stock = $this->average_stock->check_stock($date, $godowns[$k], $products[$k], $batches[$k], $qty[$k], $units[$k], $running_stock);
                    $running_stock = $stock['running_stock'];

                    // If don't having enough Stock.
                    if ($stock['stk_status'] == FALSE)
                        $blp_errors[$k] = $wraper_1 . $stock['error_msg'] . $wraper_2;
                }
            }
        }

        $json['status'] = $blp_errors ? 2 : $json['status'];
        $json['v_error'] = $blp_errors;
        echo json_encode($json);
        return;
    }
}
