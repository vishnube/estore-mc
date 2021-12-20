<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Product_batches extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'product_batches';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model("central_stores_mdl", 'central_stores');
        $this->load->model("stock_avg_mdl", 'stock_avg');
    }

    function save()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Product_batch Fields
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;
            $json['v_error'] = validation_errors() ? get_val_errors($this->table) : '';
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();

        $pdbch_id = $input['pdbch_id'];

        // On ADD
        if (!$pdbch_id) {
            $input['pdbch_fk_clients'] = $this->clnt_id;
            $input['pdbch_date'] =  get_sql_date();
        }

        $input['pdbch_mfg'] =  get_sql_date($input['pdbch_mfg']);
        $input['pdbch_exp'] =  get_sql_date($input['pdbch_exp']);
        $input['pdbch_farmer'] =  isset($input['pdbch_farmer']) ? 1 : 2;

        if (!$this->product_batches->save($input, $pdbch_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save product_batch';
        }

        echo json_encode($json);
        return;
    }

    function get_pdbchs()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->get_inputs();

        $input['clnt_id'] = $this->clnt_id;
        $json['product_batch_data'] = $this->product_batches->index($input);
        //$json['cstr_stock'] = "kkkkkkk";

        if ($input['cstr_mbr_id'] && $json['product_batch_data']) {
            $this->load->model("godowns_mdl", 'godowns');

            // $this->load->helper('unit');
            $this->load->library('average_stock');

            $cstr_id = $this->central_stores->get_id_by_member($input['cstr_mbr_id']);
            $where['gdn_fk_central_stores'] = $cstr_id;
            $options =  $this->godowns->get_active_option($where);

            $date =  get_sql_date_time($input['bls_date'] . ' ' . $input['bill_time']);



            // Taking stock of the prodcut btch in each godown
            foreach ($json['product_batch_data'] as &$pdbch) {
                $pdbch['cstrstk'] = $this->stock_avg->get_cstr_stock($date, $input['cstr_mbr_id'], $input['pdbch_fk_products'], $pdbch['pdbch_id']);
                foreach ($options as $gdn_id => $gdn_name) {
                    // echo 'GDN: ' . $gdn_name . ' PRD: ' . $input['pdbch_fk_products'] . ' PDBCH: ' . $pdbch['pdbch_name'] . '<br><br>';
                    $gdn_stock = $this->stock_avg->get_gdn_stock($date, $gdn_id, $input['pdbch_fk_products'], $pdbch['pdbch_id']);
                    if ($gdn_stock) {
                        foreach ($gdn_stock as $ugp_id => &$gs)
                            if (!$gs)
                                $gs = array('gdn_name' => $this->godowns->get_name_by_id($gdn_id), 'gdn_id' => $gdn_id, 'gdn_qty' => 0, 'unt_name' => '', 'ugp_id' => $ugp_id, 'unt_id' => '');

                        $pdbch['gdn_stock'][$gdn_id] =  $gdn_stock;
                    } else
                        $pdbch['gdn_stock'][$gdn_id][0] =  array('gdn_name' => $this->godowns->get_name_by_id($gdn_id), 'gdn_id' => $gdn_id, 'gdn_qty' => 0, 'unt_name' => '', 'ugp_id' => '', 'unt_id' => '');
                    // print_pre($pdbch['gdn_stock'][$gdn_id]);
                    // echo "<br>-----------------------------------------------------------------<br>";
                }
            }

            //print_pre($json['product_batch_data']);
        }

        $json['pdbch_html'] = get_options($this->product_batches->make_option($json['product_batch_data']));
        $json['price_groups'] = $this->get_price_group();
        $icon = $this->tasks->get_icon(179);
        $json['price_groups_icon'] = $icon['tsk_icon'];

        echo json_encode($json);
    }

    function get_price_group()
    {
        $this->load->model('price_groups_mdl', 'price_groups');
        $this->load->model('families_mdl', 'families');
        $bill_type = $this->input->post('bls_bill_type');

        // $data['unit_option'] = get_options($this->unit_groups->get_all_basic_ugps($prd_id), '', '', FALSE);

        $price = array();

        // Getting Price Group
        if ($bill_type == 'sls_qtn' ||  $bill_type == 'sls_odr' ||  $bill_type == 'sls_bls') {
            $fmly_mbr_id = $this->input->post('fmly_mbr_id');
            $cstr_mbr_id = $this->input->post('cstr_mbr_id');
            $cstr_id = $this->central_stores->get_id_by_member($cstr_mbr_id);

            $bls_date = $this->input->post('bls_date');
            //$ugp_id_selected = $this->input->post('ugp_id');
            $prd_id = $this->input->post('pdbch_fk_products');

            // Getting all batches of the given product, having stock
            $pdbch_ids = $this->stock_avg->get_batches($this->clnt_id, $prd_id);
            // $qty = $this->input->post('qty');

            if (!$fmly_mbr_id || !$cstr_id || !$prd_id || !$pdbch_ids)
                return $price;

            // Getting the ward of the Family
            $wrd_id = $this->families->get_field_by_field('fmly_fk_wards', 'fmly_fk_members', $fmly_mbr_id);
            // echo "Ward: $wrd_id<br><br>";
            $this->load->model('wards_mdl', 'wards');
            $this->load->model('areas_mdl', 'areas');
            $this->load->model('taluks_mdl', 'taluks');
            $this->load->model('districts_mdl', 'districts');
            // $ugp_row = $this->unit_groups->get_by_id($ugp_id_selected);
            // $basic_qty = bcmul($qty, $ugp_row['ugp_rel'], 3);
            foreach ($pdbch_ids as $pdbch_id) {
                // Getting Price Group of the Ward
                $price[$pdbch_id] = $this->price_groups->get_ward_price($this->clnt_id, $wrd_id, $prd_id, $pdbch_id, $bls_date);

                // If no ward price, Getting Price Group of Area and Centalstore
                // Area & Central Store has equal priority
                if (!$price[$pdbch_id]) {
                    $ars_id = $this->wards->get_field_by_id($wrd_id, 'wrd_fk_areas');
                    $price[$pdbch_id] = $this->price_groups->get_area_cstr_price($this->clnt_id, $ars_id, $cstr_id, $prd_id, $pdbch_id, $bls_date);

                    // If no Area/Cstr price, Getting Price Group of District
                    if (!$price[$pdbch_id]) {
                        $tlk_id = $this->areas->get_field_by_id($ars_id, 'ars_fk_taluks');
                        $dst_id = $this->taluks->get_field_by_id($tlk_id, 'tlk_fk_districts');
                        $price[$pdbch_id] = $this->price_groups->get_district_price($this->clnt_id, $dst_id, $prd_id, $pdbch_id, $bls_date);

                        // If no District price, Getting Price Group of State
                        if (!$price[$pdbch_id]) {
                            $stt_id = $this->districts->get_field_by_id($dst_id, 'dst_fk_states');
                            $price[$pdbch_id] = $this->price_groups->get_state_price($this->clnt_id, $stt_id, $prd_id, $pdbch_id, $bls_date);
                        }
                    }
                }
            }
        }

        // print_pre(($price));

        return $price;
    }



    function test()
    {


        $f = $this->stock_avg->get_final_stock($this->clnt_id, '', '', '', '', true, 'detailed', '2021-07-01', '2021-07-07');

        echo '<table>';
        echo '<tr><th>id</th> <th>date</th> <th>order</th> <th>CSTR</th> <th>prd</th> <th>pdbch</th> <th>Qty</th> <th>unt</th> </tr>';
        foreach ($f as $g) {
            echo "<tr>";
            echo '<td>' . $g['stkavg_id'] . '</td>';
            echo '<td>' . $g['stkavg_date'] . '</td>';
            echo '<td>' . $g['stkavg_order'] . '</td>';
            echo '<td>' . $g['mbr_name'] . '</td>';
            echo '<td>' . $g['prd_name'] . '</td>';
            echo '<td>' . $g['pdbch_name'] . '</td>';
            echo '<td>' . $g['stkavg_bal_qty'] . '</td>';
            echo '<td>' . $g['unt_name'] . '</td>';
            echo "</tr>";
        }
        echo '</table>';
    }

    function before_edit()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $pdbch_id = $this->input->post('pdbch_id');
        $row = $this->product_batches->get_by_id($pdbch_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product_batch';
            echo json_encode($json);
            return;
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_batches_by_prd_ids()
    {
        $prd_ids = $this->input->post('prd_ids');
        $options =  $this->product_batches->get_batches_by_prd_ids($prd_ids, $this->clnt_id);
        echo get_options($options);
        return;
    }

    function check_prices()
    {
        $billing = $this->input->post('pdbch_bp');
        $selling = $this->input->post('pdbch_sp');
        if ($billing && $selling && (max($billing, $selling) == $selling)) {
            $this->form_validation->set_message('check_prices', 'Selling Price should be smaller than Billing Price');
            return FALSE;
        }
        return TRUE;
    }

    // function deactivate()
    // {
    //     //Checking tasks
    //     if (!has_task('tsk_wrd_conf')) {
    //         $json['status'] = 2;
    //         $json['o_error'] = 'No task found';
    //         echo json_encode($json);
    //         return;
    //     }

    //     // 1 -> Succes;   2 -> Failure;
    //     $json['status'] = 1;

    //     // Validation Errors;
    //     $json['v_error'] = array();

    //     // Other Errors;
    //     $json['o_error'] = '';

    //     $pdbch_id = $this->input->post('pdbch_id');
    //     $row = $this->product_batches->get_by_id($pdbch_id);

    //     if (!$row) {
    //         $json['status'] = 2;
    //         $json['o_error'] = 'Couldn\'t find product_batch';
    //         echo json_encode($json);
    //         return;
    //     }

    //     $this->product_batches->deactivate($pdbch_id);
    //     echo json_encode($json);
    //     return;
    // }

    // function activate()
    // {
    //     //Checking tasks
    //     if (!has_task('tsk_wrd_conf')) {
    //         $json['status'] = 2;
    //         $json['o_error'] = 'No task found';
    //         echo json_encode($json);
    //         return;
    //     }

    //     // 1 -> Succes;   2 -> Failure;
    //     $json['status'] = 1;

    //     // Validation Errors;
    //     $json['v_error'] = array();

    //     // Other Errors;
    //     $json['o_error'] = '';

    //     $pdbch_id = $this->input->post('pdbch_id');
    //     $row = $this->product_batches->get_by_id($pdbch_id);

    //     if (!$row) {
    //         $json['status'] = 2;
    //         $json['o_error'] = 'Couldn\'t find product_batch';
    //         echo json_encode($json);
    //         return;
    //     }

    //     $this->product_batches->activate($pdbch_id);
    //     echo json_encode($json);
    //     return;
    // }
}
