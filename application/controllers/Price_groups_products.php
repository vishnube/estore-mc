<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Price_groups_products extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'price_groups_products';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("unit_groups_mdl", 'unit_groups');
        $this->load->model('product_categories_mdl', 'product_categories');
        $this->load->model('products_mdl', 'products');
        $this->load->model('product_batches_mdl', 'product_batches');
        $this->load->model('price_groups_mdl', 'price_groups');
    }

    function check_existancy($qty, $params)
    {
        list($pgprd_id, $pgp_id, $pdbch_id, $ugp_id) = explode('||', $params);

        $where['pgprd_fk_product_batches'] = $pdbch_id;
        $where['pgprd_fk_price_groups'] = $pgp_id;
        $where['pgprd_qty'] = $qty ? $qty : 0;
        $where['pgprd_fk_unit_groups'] = $ugp_id;

        $exists = $this->price_groups_products->is_exist($where, $pgprd_id);
        // echo $this->price_groups_products->get_last_query();
        if ($exists) {
            $this->form_validation->set_message('check_existancy', 'This price group already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function validate_pgprd()
    {
        $batches = $this->input->post('pgprd_fk_product_batches');
        $pgp_id = $this->input->post('pgprd_fk_price_groups');
        $ugp_ids = $this->input->post('pgprd_fk_unit_groups');
        $pgprd_id = ''; // On Add
        $pgprd = array();

        foreach ($batches as $k => $v) {
            //$pgp_id = $pgp_ids[$k];
            $pdbch_id =  $batches[$k];
            $ugp_id = $ugp_ids[$k];
            $this->form_validation->set_rules("pgprd_fk_product_batches[$k]", "Batch", "required|callback_db_query");
            $this->form_validation->set_rules("pgprd_qty[$k]", "Min-Qty", "callback_check_existancy[$pgprd_id||$pgp_id||$pdbch_id||$ugp_id]|callback_db_query|numeric");
            $this->form_validation->set_rules("pgprd_fk_unit_groups[$k]", "Unit", "required|callback_db_query");
            $this->form_validation->set_rules("pgprd_dsc[$k]", "Discount", "numeric|callback_db_query");
            $this->form_validation->set_rules("pgprd_dscp[$k]", "Discount %", "numeric|callback_db_query");
            $this->form_validation->set_rules("pgprd_rate[$k]", "Rate", "required|numeric|callback_db_query");

            $pgprd[] = "pgprd_fk_product_batches[$k]";
            $pgprd[] = "pgprd_qty[$k]";
            $pgprd[] = "pgprd_fk_unit_groups[$k]";
            $pgprd[] = "pgprd_dsc[$k]";
            $pgprd[] = "pgprd_dscp[$k]";
            $pgprd[] = "pgprd_rate[$k]";
        }

        return $pgprd;
    }



    function save()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_add')) {

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

        $this->form_validation->set_rules('pgprd_fk_products', 'Product', 'callback_required');
        $this->form_validation->set_rules('pgprd_fk_price_groups', 'Price Group', 'callback_required');

        if (!$this->input->post('pgprd_fk_product_batches')) {
            $json['status'] = 2; // Failure;
            $json['o_error'] = 'Nothing to add';
            echo json_encode($json);
            return;
        }

        // Validating Tbl: family_members
        $others = $this->validate_pgprd();

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            if (validation_errors()) {
                $json['v_error'] = get_val_errors('', $others);
                $json['v_error']['pgprd_fk_products'] = form_error('pgprd_fk_products');
                $json['v_error']['pgprd_fk_price_groups'] = form_error('pgprd_fk_price_groups');
            }
            echo json_encode($json);
            return;
        }

        $input = $this->input->post();
        //$pgprd_id = $input['pgprd_id'];
        $batches = $this->input->post('pgprd_fk_product_batches');
        $pgprd_data['pgprd_fk_clients'] = $this->clnt_id;
        $pgprd_data['pgprd_fk_products'] = $input['pgprd_fk_products'];
        $pgprd_data['pgprd_fk_price_groups'] = $input['pgprd_fk_price_groups'];
        $pgprd_data['pgprd_date'] = get_sql_date();
        foreach ($batches as $k => $v) {
            $pgprd_data['pgprd_fk_product_batches'] = $input['pgprd_fk_product_batches'][$k];
            $pgprd_data['pgprd_qty'] = $input['pgprd_qty'][$k];
            $pgprd_data['pgprd_fk_unit_groups'] = $input['pgprd_fk_unit_groups'][$k];

            $ugp_row = $this->unit_groups->get_by_id($input['pgprd_fk_unit_groups'][$k]);
            $pgprd_data['pgprd_ugp_group_no'] = $ugp_row['ugp_group_no'];
            $pgprd_data['pgprd_dsc'] = $input['pgprd_dsc'][$k];
            $pgprd_data['pgprd_dscp'] = $input['pgprd_dscp'][$k];
            $pgprd_data['pgprd_rate'] = $input['pgprd_rate'][$k];
            $this->price_groups_products->save($pgprd_data);
        }

        echo json_encode($json);
        return;
    }

    function edit()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_edit')) {

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

        $pgprd_id = $this->input->post('pgprd_id');
        $row = $this->price_groups_products->get_by_id($pgprd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
        }

        $input = $this->input->post();

        $pgp_id = $row['pgprd_fk_price_groups'];
        $pdbch_id = $row['pgprd_fk_product_batches'];
        $ugp_id = $input['pgprd_fk_unit_groups'];
        $this->form_validation->set_rules("pgprd_qty", "Min-Qty", "callback_check_existancy[$pgprd_id||$pgp_id||$pdbch_id||$ugp_id]|callback_db_query|numeric");
        $this->form_validation->set_rules("pgprd_fk_unit_groups", "Unit", "required|callback_db_query");
        $this->form_validation->set_rules("pgprd_dsc", "Discount", "numeric|callback_db_query");
        $this->form_validation->set_rules("pgprd_dscp", "Discount %", "numeric|callback_db_query");
        $this->form_validation->set_rules("pgprd_rate", "Rate", "required|numeric|callback_db_query");

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $this->price_groups_products->save($input, $pgprd_id);

        echo json_encode($json);
        return;
    }

    function get_pgprds()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_list')) {
            //$this->session->set_flashdata('permission_errors', 'No task found');
            //$this->redirect_me("logout");

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
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->price_groups_products->index($input, TRUE);
        $json['price_group_data'] = $this->price_groups_products->index($input, FALSE);
        $json['num_rows'] =  count($json['price_group_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_pgprds");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function get_pgprds2()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_list')) {
            //$this->session->set_flashdata('permission_errors', 'No task found');
            //$this->redirect_me("logout");

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
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;

        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->price_groups_products->index2($input, TRUE);
        $json['price_group_data'] = $this->price_groups_products->index2($input, FALSE);
        $json['num_rows'] =  count($json['price_group_data']);

        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_pgprds");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }



    function get_unselected_products()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();
        $input['prd_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->products->index($input, TRUE);
        $json['product_data'] = $this->products->index($input, FALSE);

        foreach ($json['product_data'] as &$row) {
            // Is added to input[price group]
            $row['added'] = $this->price_groups_products->is_exist(array('pgprd_fk_price_groups' => $input['pgp_id'], 'pgprd_fk_products' => $row['prd_id'])) ? 1 : 2;
        }
        $json['num_rows'] =  count($json['product_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_prds");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();
        echo json_encode($json);
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_edit')) {
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

        $pgprd_id = $this->input->post('pgprd_id');
        $row = $this->price_groups_products->get_by_id($pgprd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
        }
        $row['prd_name'] = $this->products->get_name_by_id($row['pgprd_fk_products']);
        $row['pgp_name'] = $this->price_groups->get_name_by_id($row['pgprd_fk_price_groups']);
        $row['pdbch_name'] = $this->product_batches->get_name_by_id($row['pgprd_fk_product_batches']);

        // Getting Units
        $json['unit_option'] = get_options($this->unit_groups->get_all_basic_ugps($row['pgprd_fk_products']), $row['pgprd_fk_unit_groups'], '', FALSE);

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function delete()
    {
        //Checking tasks
        if (!has_task('tsk_pgp_deactivate')) {

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

        $pgprd_id = $this->input->post('pgprd_id');
        $row = $this->price_groups_products->get_by_id($pgprd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find price group';
            echo json_encode($json);
            return;
        }

        $this->price_groups_products->remove($pgprd_id);
        echo json_encode($json);
        return;
    }
}
