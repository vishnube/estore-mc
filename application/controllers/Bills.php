<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Bills extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'bills';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("bill_products_mdl", 'bill_products');
        $this->load->model("user_central_stores_mdl", 'user_central_stores');
        $this->load->model("parties_mdl", 'parties');
        $this->load->model("godowns_mdl", 'godowns');
        $this->load->model("central_stores_mdl", 'central_stores');
        $this->load->model("bill_batches_mdl", 'bill_batches');
        $this->load->model("bill_nos_mdl", 'bill_nos');
        $this->load->model("bill_types_mdl", 'bill_types');
        $this->load->model("products_mdl", 'products');
        $this->load->model("product_batches_mdl", 'product_batches');
        $this->load->model("units_mdl", 'units');
        $this->load->model("unit_groups_mdl", 'unit_groups');
        $this->load->model("gstnumbers_mdl", 'gstnumbers');
        $this->load->model("stock_avg_mdl", 'stock_avg');
        $this->load->model("order_status_mdl", 'order_status');
        $this->load->model("families_mdl", 'families');
        $this->load->helper('unit');
        $this->load->library('average_stock');
    }

    function index()
    {
        if (!has_task('tsk_bls')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'vouchers'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'billing'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'billings';
        $data['icon'] = $this->tasks->get_icon(102);
        $data['cstr_option'] = $this->user_central_stores->get_users_central_stores_option($this->clnt_id, $this->usr_id, $this->usr_type);

        $prty_mbrtp_id = $this->parties->get_member_type_id(); //mbrtp_id = 3 => Parties
        $data['prty_option'] = $this->members->get_members_option(array('mbr_fk_clients' => $this->clnt_id), ACTIVE, $prty_mbrtp_id);
        $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
        $data['prd_data'] = $this->products->get_data(array('prd_fk_clients' => $this->clnt_id, 'prd_status' => ACTIVE), '', array('prd_id', 'prd_name', 'prd_code', 'prd_barcode', 'prd_estr_cmsn_p', 'prd_exp_p'));

        // Reading User Tasks
        $tasks = array_merge($this->tasks->get_by_key('tsk_pchs'), $this->tasks->get_by_key('tsk_sls'));
        $data['tasks'] = array();
        foreach ($tasks as $tsk)
            $data['tasks'][$tsk['tsk_key']] = has_task($tsk['tsk_key']);

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('bills/index', $data);
    }

    function validate($save = FALSE)
    {
        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        $bls_id = $this->input->post('bls_id');

        //Checking tasks
        if (($bls_id && !has_task('tsk_' . $bill_type . '_edit')) || (!$bls_id && !has_task('tsk_' . $bill_type . '_add'))) {
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return FALSE;
        }

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Bill Fields
        $this->form_validation->set_error_delimiters('<div class="verr"><i class="far fa-exclamation-circle text-warning"></i>&nbsp;', '</div>');
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_rules('cstr_id', 'Central Store', 'required', array('required' => 'You must provide a %s'));
        $this->form_validation->set_rules('bls_date', 'Date', 'required|callback_valid_date|callback_db_query', array('required' => 'You must provide a %s'));

        $bls_bill_cat = $this->input->post('bls_bill_cat');

        // If Purchase
        if ($bls_bill_cat == 'pchs')
            $this->form_validation->set_rules('prty_id', 'Supplier', 'required', array('required' => 'You must provide a %s'));

        // If Sale
        else if ($bls_bill_cat == 'sls')
            $this->form_validation->set_rules('fmly_id', 'Family', 'required', array('required' => 'You must provide a %s'));

        // Validating Tbl: Bill Products
        $blp_errors = $this->validate_blp();

        if (!$this->form_validation->run() || $blp_errors) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() || $blp_errors ? validation_errors() . $blp_errors : '';
            $json['o_error'] = 'Some errors occured';
            echo json_encode($json);
            return FALSE;
        }

        $blb_type = $this->input->post('bls_taxable') == 1 ? 2 : 1; // 1 => Non-tax, 2 => Tax
        $bill_batch = $this->bill_batches->get_current_bill_batch($this->input->post('bls_bill_type'), $blb_type);
        if (!$bill_batch) {
            $json['status'] = 2; // Failure;
            $json['v_error'] =  '<div class="verr">Please set a new Bill Batch</div>';
            $json['o_error'] = 'Some errors occured';
            echo json_encode($json);
            return FALSE;
        }

        // After completion of validations successfully.
        // If no need to save. (Only validation)
        if (!$save) {
            echo json_encode($json);
            return TRUE;
        }

        // If need to go back to save function
        else
            return TRUE;
    }

    function save($validated = FALSE)
    {
        if (!$validated && !$this->validate(true))
            return;

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        $bls_id = $this->input->post('bls_id');
        $action = $bls_id ? 'EDIT' : 'ADD';

        $bls_bill_cat = $this->input->post('bls_bill_cat');

        // $this->get_inputs() rejects input data in product row if it has no value. So use here $this->input->post()
        // (Eg: if blp_gst[0] = '', it will be removed)
        $input = $this->input->post();

        $blb_type = $input['bls_taxable'] == 1 ? 2 : 1; // 1 => Non-tax, 2 => Tax
        $bill_batch =   $this->bill_batches->get_current_bill_batch($input['bls_bill_type'], $blb_type);
        // if (!$bill_batch) {
        //     $json['status'] = 2; // Failure;
        //     $json['v_error'] =  '<div class="verr">Please set a new Bill Batch</div>';
        //     echo json_encode($json);
        //     return;
        // }

        $input['bls_fk_clients'] = $this->clnt_id;
        $input['bls_fk_central_stores'] = $input['cstr_id'];
        if ($bls_bill_cat == 'pchs') {
            $input['bls_from_fk_members'] = $input['prty_id'];
            $input['bls_from_fk_gstnumbers'] = $input['prty_gst_id'];
            $input['bls_to_fk_members'] = $input['cstr_id'];
        } else if ($bls_bill_cat == 'sls') {
            $input['bls_from_fk_members'] = $input['cstr_id'];
            $input['bls_to_fk_members'] = $input['fmly_id'];
        }

        $input['bls_date'] =  get_sql_date_time($input['bls_date'] . ' ' . $input['bill_time']);

        $date_changed = FALSE; // Usage is on EDIT
        $next_order = '';       // For Tbl: stock_avg
        $ost_data = array(); // For Tbl:order_status

        // On Add
        if ($action == 'ADD') {
            // Getting new Bill no
            $input['bls_fk_bill_nos'] = $this->bill_nos->get_next_bill_no_id($this->clnt_id, $bill_batch['blb_id']);

            // If the bill is converted from another bill (Eg: sale order to sale bill)
            // Changing status of source bill 'CONVERTED'
            if ($input['bls_ref_key']) {
                $this->bills->save(array('bls_status' => 2), $input['bls_ref_key']); // 2 => CONVERTED
            }

            $next_order = $this->stock_avg->get_next_order($input['bls_date']);
        }

        // On Edit
        else {
            // Getting previous details
            $prv_bls_data = $this->bills->get_by_id($bls_id);
            if ($prv_bls_data['bls_taxable'] !=  $input['bls_taxable']) {
                $json['status'] = 2; // Failure;
                $json['v_error'] =  '<div class="verr">Tax type can\'t be edited</div>';
                echo json_encode($json);
                return;
            }

            // Removing previous products details
            $prv_blp_data = $this->bill_products->delete_where(array('blp_fk_bills' => $bls_id));

            // Deleting from Stock Table
            $dlt_stk = $this->stock_avg->delete_where(array('stkavg_ref_id' => $bls_id, 'stkavg_ref_tbl' => $this->table));

            if (strtotime($prv_bls_data['bls_date']) != strtotime($input['bls_date']))
                $date_changed = TRUE;

            if (!$date_changed && isset($dlt_stk[0]['stkavg_order']))
                $next_order = $dlt_stk[0]['stkavg_order'];
            else
                $next_order = $this->stock_avg->get_next_order($input['bls_date']);

            // Reseting Average Stock Value in Stock Table
            foreach ($dlt_stk as $r) {
                $this->average_stock->reset_stock($r['stkavg_date'], $r['stkavg_cstr_mbr_id'], $r['stkavg_fk_products'], $r['stkavg_fk_product_batches'], $r['stkavg_ugp_id']);
            }
        }

        if (!$bls_id = $this->bills->save($input, $bls_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save bill';
        }

        // Adding products
        foreach ($input['blp_fk_godowns'] as $k => $v) {
            $blp_data = array();
            $blp_data['blp_fk_clients'] = $this->clnt_id;
            $blp_data['blp_fk_godowns'] = $v;
            $blp_data['blp_fk_bills'] = $bls_id;
            $blp_data['blp_fk_products'] = $input['blp_fk_products'][$k];
            $blp_data['blp_fk_product_batches'] = $input['blp_fk_product_batches'][$k];
            // new_qty: In the case of Order Processing
            if (isset($_POST['new_qty']))
                $blp_data['blp_qty'] = $input['new_qty'][$k];
            else
                $blp_data['blp_qty'] = $input['blp_qty'][$k];
            $blp_data['blp_fk_unit_groups'] = $input['blp_fk_unit_groups'][$k];
            $blp_data['blp_rate'] = $input['blp_rate'][$k]; // Save
            $blp_data['blp_trate'] = $input['blp_trate'][$k];

            $ugp_row = $this->unit_groups->get_by_id($input['blp_fk_unit_groups'][$k]);
            $blp_data['blp_ugp_group_no'] = $ugp_row['ugp_group_no'];
            $blp_data['blp_basic_unt_id'] = $ugp_row['ugp_fk_bunits'];
            $blp_data['blp_basic_ugp_rel'] = $ugp_row['ugp_rel'];
            $blp_data['blp_basic_qty'] = bcmul($blp_data['blp_qty'], $ugp_row['ugp_rel'], 3);
            $blp_data['blp_basic_rate'] = $blp_data['blp_rate'] ? bcdiv($blp_data['blp_rate'], $ugp_row['ugp_rel'], 5) : 0;
            $blp_data['blp_basic_trate'] = $blp_data['blp_trate'] ? bcdiv($blp_data['blp_trate'], $ugp_row['ugp_rel'], 5) : 0;
            $blp_data['blp_basic_ugp_id'] = $this->unit_groups->get_basic_ugp($input['blp_fk_unit_groups'][$k]);

            $blp_data['blp_amount'] = $input['blp_amount'][$k];

            $blp_data['blp_cgst_p'] = $input['blp_cgst_p'][$k];
            $blp_data['blp_cgst'] = $input['blp_cgst'][$k];
            $blp_data['blp_sgst_p'] = $input['blp_sgst_p'][$k];
            $blp_data['blp_sgst'] = $input['blp_sgst'][$k];
            $blp_data['blp_igst_p'] = $input['blp_igst_p'][$k];
            $blp_data['blp_igst'] = $input['blp_igst'][$k];

            $blp_data['blp_gross_amt'] = $input['blp_gross_amt'][$k];
            $this->bill_products->save($blp_data);

            // for stock table            
            if ($input['bls_bill_type'] == 'pchs_bls' || $input['bls_bill_type'] == 'pchs_rtn' || $input['bls_bill_type'] == 'sls_bls' || $input['bls_bill_type'] == 'sls_rtn') {
                $stkavg = array();
                $stkavg['stkavg_fk_clients'] = $this->clnt_id;
                $stkavg['stkavg_date'] = $input['bls_date'];
                $stkavg['stkavg_ref_tbl'] = $this->table;
                $stkavg['stkavg_ref_id'] = $bls_id;
                $stkavg['stkavg_cstr_mbr_id'] = $input['bls_fk_central_stores'];
                $stkavg['stkavg_fk_godowns'] = $blp_data['blp_fk_godowns'];
                $stkavg['stkavg_fk_products'] = $blp_data['blp_fk_products'];
                $stkavg['stkavg_fk_product_batches'] = $blp_data['blp_fk_product_batches'];

                if ($input['bls_bill_type'] == 'pchs_bls' || $input['bls_bill_type'] == 'sls_rtn') {
                    $stkavg['stkavg_qty_in'] = $blp_data['blp_basic_qty'];
                    $stkavg['stkavg_qty_out'] = 0;
                } else if ($input['bls_bill_type'] == 'sls_bls' || $input['bls_bill_type'] == 'pchs_rtn') {
                    $stkavg['stkavg_qty_in'] =  0;
                    $stkavg['stkavg_qty_out'] = $blp_data['blp_basic_qty'];
                }
                $stkavg['stkavg_order'] = $next_order;
                $stkavg['stkavg_rate'] = $blp_data['blp_basic_trate'];
                $stkavg['stkavg_ugp_id'] = $blp_data['blp_basic_ugp_id'];
                $stkavg['stkavg_ugp_group_no'] = $blp_data['blp_ugp_group_no'];
                $stkavg['stkavg_unt_id'] = $blp_data['blp_basic_unt_id'];

                $this->stock_avg->save($stkavg);
                $this->average_stock->reset_stock($input['bls_date'], $input['bls_fk_central_stores'], $stkavg['stkavg_fk_products'], $stkavg['stkavg_fk_product_batches'], $stkavg['stkavg_ugp_id']);
            }
        }

        // For Tbl:order_status
        if ($action == 'ADD') {
            $ost_data['ost_fk_clients'] = $this->clnt_id;
            $ost_data['ost_date'] = $input['bls_date'];
            $ost_data['ost_fk_users'] = $this->usr_id;
            $ost_data['ost_final'] = 1; // This is the final status

            if ($bill_type == 'sls_odr') {
                $ost_data['ost_fk_bills'] = $bls_id;
                $ost_data['ost_status'] = 1; // PENDING
                $this->order_status->update_where(array('ost_final' => 2),  "ost_fk_bills = $bls_id"); // Seting previous are not final.
                $this->order_status->save($ost_data);
            }

            // If the bill is created from a "Sale Order"
            else if ($bill_type == 'sls_bls' && $input['bls_ref_key']) {
                $prev_bill_type = $this->bills->get_field_by_id($input['bls_ref_key'], 'bls_bill_type');
                if ($prev_bill_type == 'sls_odr') {
                    $ost_data['ost_fk_bills'] = $input['bls_ref_key'];
                    $ost_data['ost_status'] = 3; // BILLED
                    $this->order_status->update_where(array('ost_final' => 2),  "ost_fk_bills = {$input['bls_ref_key']}"); // Seting previous are not final.
                    $this->order_status->save($ost_data);
                }
            }
        }
        if ($this->input->post('print_bill') == 'yes')
            $json['print_html'] = $this->get_print_html($bls_id);

        echo json_encode($json);
        return;
    }

    function get_blss()
    {
        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect

        //Checking tasks
        if (!has_task('tsk_' . $bill_type . '_list')) {
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


        if ($input['bls_bill_cat'] == 'pchs') {
            $input['bls_from_fk_members'] = ifSetInput($input, 'prty_id');
            $input['bls_to_fk_members'] = ifSetInput($input, 'cstr_id');
        } else if ($input['bls_bill_cat'] == 'sls') {
            $input['bls_from_fk_members'] = ifSetInput($input, 'cstr_id');
            $input['bls_to_fk_members'] = ifSetInput($input, 'fmly_id');
        }


        $input['bls_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->bills->index($input, TRUE);
        $json['bill_data'] = $this->bills->index($input, FALSE);
        $json['num_rows'] =  count($json['bill_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_blss");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        // Reading bill categories
        $json['blp'] = array();
        foreach ($json['bill_data'] as &$row) {
            $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
            $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
            $row['bill_no'] = array_merge($bill_no, $bill_batch);

            $row['bill_time'] = date('h:i:s A', strtotime($row['bls_date']));
            $row['bls_date'] = date('d-m-Y', strtotime($row['bls_date']));

            $row['from'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
            $row['to'] = $this->members->get_name_by_id($row['bls_to_fk_members']);
            $row['blp_data'] = $this->bill_products->get_bill_products($row['bls_id']);

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

    function download()
    {
        $this->load->library('pdf');
        $bls_id = $this->uri->segment(3);
        $html = $this->get_print_html($bls_id);
        $this->pdf->load_view($html, "bill-" . time());
    }

    function print_bill()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $bls_id = $this->input->post('bls_id');
        $json['html'] = $this->get_print_html($bls_id);
        echo json_encode($json);
    }


    function get_print_html($bls_id)
    {
        $row = $this->bills->get_by_id($bls_id);
        if (!$row)
            return;

        $fmly_row = $this->families->get_row(array('fmly_fk_members' => $row['bls_to_fk_members']));
        $fmly_id = $fmly_row['fmly_id'];
        $row['fmly_address'] = $fmly_row['fmly_address'];
        $estore = $this->bills->get_estore_string($fmly_id);
        $row['estr_name'] = $estore['estr_name'];

        $area = $this->bills->get_ward($fmly_id);
        $row['area'] = $area['ars_name'] . " (" . $area['wrd_name'] . ")";

        $bill_no = $this->bill_nos->get_by_id($row['bls_fk_bill_nos']);
        $bill_batch = $this->bill_batches->get_by_id($bill_no['bln_fk_bill_batches']);
        $row['bill_no'] = array_merge($bill_no, $bill_batch);
        $row['cstr_name'] = $this->members->get_name_by_id($row['bls_from_fk_members']);
        $row['family'] = $this->members->get_name_by_id($row['bls_to_fk_members']);
        $row['bls_date'] = date('d-m-Y h:i:s A', strtotime($row['bls_date']));
        $row['blp_data'] = $this->bill_products->get_bill_products($bls_id);

        // Container styles
        $row['container_width'] = "700";
        $row['container_px'] = "5"; // Horizontal Padding
        $row['container_py'] = "5"; // Horizontal Padding
        $row['container_ml'] = "10"; // Left Margin
        $row['container_mt'] = "10"; // Top Margin

        // Company  details
        $row['com_font_size'] = "20";

        $html = $this->load->view('bills/print_bill', $row, true);
        return $html;
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
            $json['o_error'] = 'Couldn\'t find bill';
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
        $json['html'] = $this->load->view('bills/show_details', $row, true);

        // $json['lat'] = $row['fmly_lat'];
        // $json['log'] = $row['fmly_log'];
        echo json_encode($json);
        return;
    }


    function get_options()
    {
        $options =  $this->bills->get_active_option();
        echo get_options($options);
        return;
    }

    function delete()
    {
        //Checking tasks
        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        if (!has_task('tsk_' . $bill_type . '_deactivate')) {
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



        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_cat'] != $this->input->post('bls_bill_cat')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Category Not Match';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_type'] != $this->input->post('bls_bill_type')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Type Not Match';
            echo json_encode($json);
            return;
        }

        // deleting Bill
        $bls_data = $this->bills->delete($bls_id);

        // Removing products details
        $blp_data = $this->bill_products->delete_where(array('blp_fk_bills' => $bls_id));
        echo json_encode($json);
        return;
    }

    function cancel()
    {
        //Checking tasks
        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        if (!has_task('tsk_' . $bill_type . '_cancel')) {
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

        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_cat'] != $this->input->post('bls_bill_cat')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Category Not Match';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_type'] != $this->input->post('bls_bill_type')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Type Not Match';
            echo json_encode($json);
            return;
        }

        $this->bills->save(array('bls_status' => 3), $bls_id);
        echo json_encode($json);
        return;
    }

    function validate_blp()
    {
        $cstr_mbr_id = $this->input->post('cstr_id');
        $cstr_id = $this->central_stores->get_id_by_member($cstr_mbr_id);
        $godowns = $this->input->post('blp_fk_godowns');
        if (!$godowns) {
            return '<div class="verr">Godowns not found</div>';
        }
        $products = $this->input->post('blp_fk_products');
        $batches = $this->input->post('blp_fk_product_batches');

        // new_qty: In the case of Order Processing
        if (isset($_POST['new_qty']))
            $qty = $this->input->post('new_qty');
        else
            $qty = $this->input->post('blp_qty');

        $units = $this->input->post('blp_fk_unit_groups');
        //$rates = $this->input->post('blp_rate');
        $trates = $this->input->post('blp_trate'); // Taxed rate
        $amt = $this->input->post('blp_amount');
        $cgst = $this->input->post('blp_cgst');
        $sgst = $this->input->post('blp_sgst');
        $igst = $this->input->post('blp_igst');
        $gamt = $this->input->post('blp_gross_amt');
        $bill_type = $this->input->post('bls_bill_type');


        $blp_errors = array();

        // To check stock. Some times there may be duplicate entry of products.
        // So to get actual stock, we need to sum all duplicate entries.
        $running_stock = array();

        // Here we are looping with godown array. So if when there is a godown is missing, that row won't be validate.
        // So first checking missing godowns.
        if (count($godowns) != count($products) || count($godowns) != count($batches) || count($godowns) != count($amt)) {
            $blp_errors[] = 'A godown is missed in a row';
        } else if ($godowns) {
            foreach ($godowns as $k => $v) {
                if (!$v)
                    $blp_errors[] = 'Godown is required @ ' . ($k + 1) . ' row';
                else if (!$this->godowns->is_exist(array('gdn_fk_central_stores' => $cstr_id)))
                    $blp_errors[] = 'Godown not found under current central store @ ' . ($k + 1) . ' row';

                if (!$products[$k])
                    $blp_errors[] = 'Product is required @ ' . ($k + 1) . ' row';

                if (!$batches[$k])
                    $blp_errors[] = 'Batch is required @ ' . ($k + 1) . ' row';
                else {
                    $batch_exist = $this->product_batches->is_exist(array('pdbch_fk_products' => $products[$k], 'pdbch_id' => $batches[$k]));
                    if (!$batch_exist)
                        $blp_errors[] = 'Batch not matching with Product @ ' . ($k + 1) . ' row';
                }

                if (!$qty[$k])
                    $blp_errors[] = 'Quantity is required @ ' . ($k + 1) . ' row';
                else if (!is_numeric($qty[$k]))
                    $blp_errors[] = 'Quantity should be a number @ ' . ($k + 1) . ' row';

                if (!$units[$k])
                    $blp_errors[] = 'Unit is required @ ' . ($k + 1) . ' row';
                else if (!in_array($units[$k], get_product_unit_options($products[$k], 'id_array')))
                    $blp_errors[] = 'Unit is not found for the product @ ' . ($k + 1) . ' row';

                // Checking Stock
                else if (($bill_type == 'pchs_rtn' || $bill_type == 'sls_bls') && $qty[$k] && is_numeric($qty[$k])) {
                    // 1 => allow to save even there is no enough stock, 2=> Don't allow when no stock
                    $check_stock = $this->settings->get_settings_value($this->clnt_id, '', $bill_type, 1, '', 'NO_STK');

                    if ($check_stock == 2) {
                        $bls_date =  get_sql_date_time($this->input->post('bls_date') . ' ' . $this->input->post('bill_time'));
                        $bls_id = $this->input->post('bls_id');
                        $not = array();
                        // If Edit
                        if ($bls_id) {
                            $not['id'] = $bls_id;
                            $not['tbl'] = $this->table;
                        }

                        $msg = ' @ ' . ($k + 1) . ' row ';
                        $stock = $this->average_stock->check_stock($bls_date, $godowns[$k], $products[$k], $batches[$k], $qty[$k], $units[$k], $running_stock, $not, '', $msg);
                        $running_stock = $stock['running_stock'];
                        if ($stock['error_msg'])
                            $blp_errors[] = $stock['error_msg'];
                    }
                }

                if (!$trates[$k])
                    $blp_errors[] = 'Rate is required @ ' . ($k + 1) . ' row';

                else if (!is_numeric($trates[$k]))
                    $blp_errors[] = 'Rate should be a number @ ' . ($k + 1) . ' row';

                if ($amt[$k] && !is_numeric($amt[$k]))
                    $blp_errors[] = 'Amount should be a number @ ' . ($k + 1) . ' row';

                if ($cgst[$k] &&  !is_numeric($cgst[$k]))
                    $blp_errors[] = 'CGST should be a number @ ' . ($k + 1) . ' row';

                if ($sgst[$k] && !is_numeric($sgst[$k]))
                    $blp_errors[] = 'SGST should be a number @ ' . ($k + 1) . ' row';

                if ($igst[$k] && !is_numeric($igst[$k]))
                    $blp_errors[] = 'IGST should be a number @ ' . ($k + 1) . ' row';

                if (($cgst[$k] || $sgst[$k]) && $igst[$k])
                    $blp_errors[] = 'IGST with CGST|SGST is not allowed @ ' . ($k + 1) . ' row. Please refresh the page';

                if ($gamt[$k] && !is_numeric($gamt[$k]))
                    $blp_errors[] = 'Gross Amount should be a number @ ' . ($k + 1) . ' row';
            }
        } else {
            $blp_errors[] = 'No godowns or no product data entered';
        }

        $blp_errors = $blp_errors ? '<div class="verr">' . implode('</div><div class="verr">', $blp_errors) . '</div>' : '';

        return $blp_errors;
    }


    function before_edit()
    {
        //Checking tasks
        $bill_type = $this->input->post('bls_bill_type');  // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        if (!has_task('tsk_' . $bill_type . '_edit')) {
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

        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_cat'] != $this->input->post('bls_bill_cat')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Category Not Match';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_type'] != $this->input->post('bls_bill_type')) {
            $json['status'] = 2;
            $json['o_error'] = 'Bill Type Not Match';
            echo json_encode($json);
            return;
        }

        if ($row['bls_bill_cat'] == 'pchs') {
            $row['prty_id'] = $row['bls_from_fk_members']; // mbr_id of Party
            $row['cstr_id'] = $row['bls_to_fk_members']; // mbr_id of Central Store
            $prty_gst = $this->get_gst_of_member($row['prty_id'], 'local');
            $row['prty_gst_dt'] = $prty_gst;
            $row['prty_gst_option'] = get_options($this->gstnumbers->make_option($prty_gst), $row['bls_from_fk_gstnumbers'], '', FALSE);
        } else if ($row['bls_bill_cat'] == 'sls') {
            $row['cstr_id'] = $row['bls_from_fk_members']; // mbr_id of Central Store
            $row['fmly_option'] = $this->get_cstr_family_options($row['cstr_id'], $row['bls_to_fk_members'], 'local');
        }

        $row['cstr_gst_dt'] = $this->get_cstr_state_dt($row['cstr_id'], 'local');

        $row['bill_time'] = date('h:i:s A', strtotime($row['bls_date']));
        $row['bls_date'] = date('d-m-Y', strtotime($row['bls_date']));
        $blp_data = $this->bill_products->get_bill_products($bls_id);

        foreach ($blp_data as &$blp) {
            $blp['prd_name']  = $this->products->get_name_by_id($blp['blp_fk_products']);

            $pdbch_option =  $this->product_batches->get_options(array('pdbch_fk_products' => $blp['blp_fk_products'], 'pdbch_status' => ACTIVE));
            $blp['pdbch_option']  =  get_options($pdbch_option, $blp['blp_fk_product_batches'], 'Select', TRUE, FALSE, "No Batches");

            $blp['gdn_option']  = $this->get_gdn_options($row['cstr_id'], $blp['blp_fk_godowns'], 'local');
            $blp['prd_data']  = $this->get_product_data($blp['blp_fk_products'], $blp['blp_fk_unit_groups'], 'local');
        }

        $row['blp_data'] = $blp_data;

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }



    function before_convert()
    {
        //Checking tasks 
        $oldBillCat = $this->input->post('oldBillCat'); // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        $oldBillType = $this->input->post('oldBillType');
        $newBillCat = $this->input->post('newBillCat'); // purchase|sale
        $newBillType = $this->input->post('newBillType');
        if (!has_task('tsk_' . $newBillType . '_add')) {
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

        $bls_id = $this->input->post('bls_id');
        $row = $this->bills->get_by_id($bls_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find bill';
            echo json_encode($json);
            return;
        }

        if ($oldBillCat == 'purchase') {
            if ($newBillCat == 'purchase') {
                $row['prty_id'] = $row['bls_from_fk_members']; // mbr_id of Party
                $row['cstr_id'] = $row['bls_to_fk_members']; // mbr_id of Central Store
                $prty_gst = $this->get_gst_of_member($row['prty_id'], 'local');
                $row['prty_gst_dt'] = $prty_gst;
                $row['prty_gst_option'] = get_options($this->gstnumbers->make_option($prty_gst), $row['bls_from_fk_gstnumbers'], '', FALSE);
            } else if ($newBillCat == 'sale') {
                $row['cstr_id'] = $row['bls_to_fk_members']; // mbr_id of Central Store
                $row['fmly_option'] = $this->get_cstr_family_options($row['cstr_id'], '', 'local');
            }
        } else if ($oldBillCat == 'sale') {
            if ($newBillCat == 'purchase') {
                $json['status'] = 2;
                $json['o_error'] = 'Can\'t convert sale to purchase';
                echo json_encode($json);
                return;
            } else if ($newBillCat == 'sale') {
                $row['cstr_id'] = $row['bls_from_fk_members']; // mbr_id of Central Store
                $row['fmly_option'] = $this->get_cstr_family_options($row['cstr_id'], $row['bls_to_fk_members'], 'local');
            }
        }

        $row['cstr_gst_dt'] = $this->get_cstr_state_dt($row['cstr_id'], 'local');

        // $row['bill_time'] = date('h:i:s A', strtotime($row['bls_date']));
        // $row['bls_date'] = date('d-m-Y', strtotime($row['bls_date']));
        $blp_data = $this->bill_products->get_bill_products($bls_id);

        foreach ($blp_data as &$blp) {
            $blp['prd_name']  = $this->products->get_name_by_id($blp['blp_fk_products']);

            $pdbch_option =  $this->product_batches->get_options(array('pdbch_fk_products' => $blp['blp_fk_products'], 'pdbch_status' => ACTIVE));
            $blp['pdbch_option']  =  get_options($pdbch_option, $blp['blp_fk_product_batches'], 'Select', TRUE, FALSE, "No Batches");

            $blp['gdn_option']  = $this->get_gdn_options($row['cstr_id'], $blp['blp_fk_godowns'], 'local');
            $blp['prd_data']  = $this->get_product_data($blp['blp_fk_products'], $blp['blp_fk_unit_groups'], 'local');
        }

        $row['blp_data'] = $blp_data;

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_cstr_family_options($cstr_mbr_id = '', $fmly_mbr_id = '', $posted = 'ajax')
    {
        if ($posted == 'ajax') {
            $cstr_id = $this->input->post('cstr_id');
            if (!$cstr_id) {
                $mbr_id = $this->input->post('mbr_id');
                $cstr_id = $this->central_stores->get_id_by_member($mbr_id);
            }

            $options =  $this->families->get_families_by_central_stores($cstr_id, ACTIVE, ACTIVE, 'option');
            echo get_options($options);
            return;
        } else  if ($posted == 'local') {
            if (!$cstr_mbr_id)
                return '';
            $cstr_id = $this->central_stores->get_id_by_member($cstr_mbr_id);

            $options =  $this->families->get_families_by_central_stores($cstr_id, ACTIVE, ACTIVE, 'option');
            return get_options($options, $fmly_mbr_id);
        }
    }

    function get_product_data($prd_id = '', $sel = '', $posted = 'ajax')
    {
        // 1 -> Succes;   2 -> Failure;
        $data['status'] = 1;

        // Other Errors;
        $data['o_error'] = '';

        $prd_id = $posted == 'ajax' ? $this->input->post('prd_id') : $prd_id;
        $row = $this->products->get_by_id($prd_id);
        if (!$row) {
            if ($posted == 'ajax') {
                $data['status'] = 2;
                $data['o_error'] = 'Product Not Found';
                echo json_encode($data);
            }
            return;
        }

        // Getting Units
        $data['option'] = $posted == 'ajax' ? get_product_unit_options($prd_id) : get_product_unit_options_2($prd_id, $sel);
        $data['option_data'] = get_product_units($prd_id);

        // Getting GST Rate
        $data['hsn'] = '';
        if ($row['prd_hsn_code']) {
            $this->load->model('hsn_details_mdl', 'hsn_details');
            $where = array('hsn_name' => $row['prd_hsn_code']);
            $data['hsn'] = $this->hsn_details->get_row($where);
        }


        if ($posted == 'ajax') {
            echo json_encode($data);
            return;
        } else if ($posted == 'local')
            return $data;
    }

    function get_gdn_options($mbr_id = '', $gdn_id = '', $posted = 'ajax')
    {
        if ($posted == 'ajax') {
            $cstr_id = $this->input->post('cstr_id');
            if (!$cstr_id) {
                $mbr_id = $this->input->post('mbr_id');
                $cstr_id = $this->central_stores->get_id_by_member($mbr_id);
            }
            $where['gdn_fk_central_stores'] = $cstr_id;
            $options =  $this->godowns->get_active_option($where);
            echo get_options($options, '', "", FALSE, TRUE, 'NO GODOWNS');
            return;
        } else if ($posted == 'local') {
            $cstr_id = $this->central_stores->get_id_by_member($mbr_id);
            $where['gdn_fk_central_stores'] = $cstr_id;
            $options =  $this->godowns->get_active_option($where);
            return get_options($options, $gdn_id, "", FALSE, TRUE, 'NO GODOWNS');
        }
    }

    function get_gst_of_member($mbr_id = '', $posted = 'ajax')
    {
        // 1 -> Succes;   2 -> Failure;
        $gst['status'] = 1;
        $mbr_id = !$mbr_id ? $this->input->post('mbr_id') : $mbr_id;
        $gst['dt'] = $this->gstnumbers->get_gst($this->clnt_id, $mbr_id);
        if ($posted == 'ajax') {
            $options = $this->gstnumbers->make_option($gst['dt']);
            $gst['option'] = get_options($options, NULL, '', FALSE);
            echo json_encode($gst);
            return;
        } else if ($posted == 'local')
            return $gst['dt'];
    }

    function get_cstr_state_dt($mbr_id = '', $posted = 'ajax')
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;
        $mbr_id = !$mbr_id ? $this->input->post('mbr_id') : $mbr_id;
        $json['dt'] = $this->central_stores->get_state_dt($mbr_id);
        if ($posted == 'ajax') {
            echo json_encode($json);
            return;
        } else if ($posted == 'local')
            return $json['dt'];
    }
}
