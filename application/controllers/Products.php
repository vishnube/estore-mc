<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Products extends My_controller
{
    var $allowed;
    var $upload_dir;
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'products';
        $this->load->model($this->table . "_mdl", $this->table);
        $this->load->model('product_categories_mdl', 'product_categories');
        $this->load->model('tags_mdl', 'tags');
        $this->load->model('product_tags_mdl', 'product_tags');
        $this->load->model('product_photos_mdl', 'product_photos');
        $this->load->model('companies_mdl', 'companies');
        $this->load->model('brands_mdl', 'brands');
        $this->load->model('units_mdl', 'units');
        $this->load->model('unit_groups_mdl', 'unit_groups');
        $this->load->model('product_units_mdl', 'product_units');
        $this->load->model('product_batches_mdl', 'product_batches');
        $this->load->model('hsn_details_mdl', 'hsn_details');
        $this->load->helper('unit');

        // Allowed file types for product photo
        //$this->allowed = array('doc', 'docx', 'pdf', 'jpg', 'gif', 'jpeg', 'pjpeg', 'png', 'x-png');
        $this->allowed = array('jpg', 'gif', 'jpeg', 'pjpeg', 'png', 'x-png');
        $this->upload_dir = get_clients_upload_dir();
    }

    public function index()
    {
        if (!has_task('tsk_prd')) {
            $this->session->set_flashdata('permission_errors', 'No task found');
            $this->redirect_me("logout");
        }

        $data['active_nav'] = 'particulars'; // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'products'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'products';
        $data['icon'] = $this->tasks->get_icon(71);
        $data['pct_option'] = $this->product_categories->get_active_option(array('pct_fk_clients' => $this->clnt_id, 'pct_parent' => 0));
        $data['tg_option'] = $this->tags->get_active_option(array('tg_fk_clients' => $this->clnt_id));
        $data['cmp_option'] = $this->companies->get_active_option(array('cmp_fk_clients' => $this->clnt_id));
        $data['brnd_option'] = $this->brands->get_active_option(array('brnd_fk_clients' => $this->clnt_id));
        $data['unt_option'] = $this->units->get_unit_option();

        $data['made_in_option'] = $this->products->get_made_in();
        $data['prodction_option'] = $this->products->get_production_types();
        $data['dietary_option'] = $this->products->get_dietary_types();

        // Checking user tasks
        $data['tsk_prd_list'] = has_task('tsk_prd_list');
        $data['tsk_prd_add'] = has_task('tsk_prd_add');
        $data['tsk_prd_edit'] = has_task('tsk_prd_edit');
        $data['tsk_prd_conf'] = has_task('tsk_prd_conf');
        $data['tsk_prd_activate'] = has_task('tsk_prd_activate');
        $data['tsk_prd_deactivate'] = has_task('tsk_prd_deactivate');
        $data['tsk_prd_pdf'] = has_task('tsk_prd_pdf');
        $data['tsk_prd_excel'] = has_task('tsk_prd_excel');
        $data['tsk_prd_print'] = has_task('tsk_prd_print');

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('products/index', $data);
    }

    function save()
    {
        //Checking tasks
        if (($this->input->post('prd_id') && !has_task('tsk_prd_edit')) || (!$this->input->post('prd_id') && !has_task('tsk_prd_add'))) {
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

        // Validating Product Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        // Extra validation fields
        $this->form_validation->set_rules('tg_id[]', 'Tags', 'callback_required');
        $this->form_validation->set_rules('pct_parent_selector', 'Category', 'callback_check_cats');
        $this->form_validation->set_rules('product_units', 'Units', 'callback_no_units');

        //print_pre($this->input->post('product_units'));
        $photo1_error = $this->validate_photo('photo1');
        $photo2_error = $this->validate_photo('photo2');

        if (!$this->form_validation->run() || $photo1_error || $photo2_error) {
            $json['status'] = 2; // Failure;

            if ($json['o_error'])
                $json['o_error'] .= validation_errors();

            $json['v_error'] = validation_errors() ? get_val_errors(array($this->table), array('tg_id[]', 'pct_parent_selector', 'product_units')) : array();

            if ($photo1_error)
                $json['v_error']['photo1'] = $photo1_error;

            if ($photo2_error)
                $json['v_error']['photo2'] = $photo2_error;
            //print_pre($json['v_error']);
            echo json_encode($json);
            return;
        }

        $upload = $this->upload_photos();
        if ($upload['error']) {
            $json['status'] = 2;
            $json['o_error'] = $upload['error'];
            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $prd_id = $input['prd_id'];
        $action = $prd_id ? 'EDIT' : 'ADD';
        $input['prd_fk_clients'] = $this->clnt_id;
        $input['prd_fk_product_categories'] = $input['pct_parent'];

        if (!$prd_id = $this->products->save($input, $prd_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save product';
            echo json_encode($json);
            return;
        }

        if ($action == 'ADD') {
            // Saving Units
            foreach ($input['product_units'] as $unt) {
                $tbl_data = array();
                $tbl_data['punt_fk_clients'] = $this->clnt_id;
                $tbl_data['punt_fk_products'] = $prd_id;
                $tbl_data['punt_group_no'] = $unt;
                $this->product_units->save($tbl_data);
            }
        }


        // Deleting previous Tags
        $this->product_tags->delete_where(array('ptg_fk_products' => $prd_id));

        // Saving Tags
        if ($input['tg_id']) {
            foreach ($input['tg_id'] as $tg_id) {
                $tbl_data = array();
                $tbl_data['ptg_fk_products'] = $prd_id;
                $tbl_data['ptg_fk_tags'] = $tg_id;
                $this->product_tags->save($tbl_data);
            }
        }

        // If files to upload
        if (isset($upload['photo'])) {
            foreach ($upload['photo'] as $name) {
                $tbl_data = array();
                $tbl_data['prdpt_fk_clients'] = $this->clnt_id;
                $tbl_data['prdpt_name'] = $name;
                $tbl_data['prdpt_fk_products'] = $prd_id;
                $this->product_photos->save($tbl_data);
            }
        }

        echo json_encode($json);
        return;
    }

    function upload_photos()
    {
        $error = '';
        $photo = array();

        if (!isset($_FILES['photo1']) && !isset($_FILES['photo2'])) {
            return array('error' => $error, 'photo' => $photo);
        }

        // If not set the upload dir
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }

        // if permision not set for UPLOAD directory. it should be 777 and its curresponding value is 16895
        if (fileperms($this->upload_dir) != 16895) {
            $error .= "<div>Set Permission for" . $this->upload_dir . " 777</div>";
        }


        // If product photo 1 is uploaded.
        if (isset($_FILES['photo1'])) {
            $uploaded = $this->do_upload('photo1', 1);
            if (isset($uploaded['upload_data']))
                $photo[] = $uploaded['upload_data']['file_name'];
            else
                $error .= $uploaded['error'];
        }

        // If product photo 2 is uploaded.
        if (isset($_FILES['photo2'])) {
            $uploaded = $this->do_upload('photo2', 2);
            if (isset($uploaded['upload_data']))
                $photo[] = $uploaded['upload_data']['file_name'];
            else
                $error .= $uploaded['error'];
        }

        return array('error' => $error, 'photo' => $photo);
    }

    /**
     * 
     * @param type $file_input: name of the $_FILES[input_name]
     * @param type $no: Just a value to make the file name unique.
     * @return type
     */
    function do_upload($file_input, $no)
    {
        $config['upload_path'] = $this->upload_dir;
        $config['allowed_types'] = implode('|', $this->allowed);
        $config['file_name'] = "prd_photo_" . time() . "_$this->usr_id" . "_$no";
        $this->load->library('upload');

        // Initializing uploader & Cleaning previous upload history if exist.
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_input)) {
            $error = array('error' => $this->upload->display_errors('', ''));

            return $error;
        } else {
            $data = array('upload_data' => $this->upload->data());

            return $data;
        }
    }

    function get_prds()
    {
        //Checking tasks
        if (!has_task('tsk_prd_list')) {
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
        $input['prd_fk_clients'] = $this->clnt_id;
        $input['offset'] = (isset($input['offset']) && $input['offset'] != 0) ? ($input['offset'] - 1) * $input['per_page'] : 0;
        $json['offset'] =  $input['offset'];
        $json['total_rows'] = $this->products->index($input, TRUE);
        $json['product_data'] = $this->products->index($input, FALSE);
        $json['num_rows'] =  count($json['product_data']);
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->cls . "/get_prds");
        $config['total_rows'] = $json['total_rows'];
        $config['per_page'] = $input['per_page'];
        $this->pagination->initialize($config);
        $json['page_link'] = $this->pagination->create_links();

        $json['tags'] = array();
        foreach ($json['product_data'] as &$row) {
            $json['tags'][$row['prd_id']] = $this->product_tags->get_tags($row['prd_id']);
            $cats = $this->product_categories->parent_hierarchy($row['pct_id'], 'option', 'reverse', FALSE);
            $row['cats'] = implode('<i class="far fa-chevron-double-right mx-1" style="font-size:8px"></i>', $cats);
            $cats_export = $this->product_categories->parent_hierarchy($row['pct_id'], 'option', 'reverse', FALSE);
            $row['cats_export'] = implode(' > ', $cats_export);


            // Product unit
            $fixed_class = 'unt-span';
            $fixed_style = 'padding: 5px;';
            $toggle_class = array('text-info', 'text-success', 'text-danger', 'text-primary', 'text-warning');
            $def_icon = '<i class="fad fa-shield-check text-success" title="Default Unit"></i> &nbsp;';
            $dto = "<span class='cursor-pointer'>";
            $dtc = "</span>";
            $sep = '<i class="fas fa-chevron-double-right" style="font-size: 10px;"></i>';
            $arr1 = get_formated_product_units($row['prd_id'], '', 'span', $sep, $fixed_class, $fixed_style, $toggle_class, array(), $def_icon, $dto, $dtc);
            $arr2 = get_formated_product_units($row['prd_id'], '', '', ', ');
            $str1 = '';
            $str2 = '';
            foreach ($arr1 as $i => $a) {
                $str1 .= '<div class="unt-dv">
                            <input type="hidden" class="punt_id" value="' . $a['punt_id'] . '">
                            ' . $a['text_format'];
                if ($a['punt_status'] == ACTIVE)
                    $str1 .= '&nbsp<i class="fas fa-trash-alt cursor-pointer deactivate_punt" title="Deactivate Unit"></i>';
                else
                    $str1 .= '&nbsp<i class="fas fa-check cursor-pointer activate_punt" title="Activate Unit"></i>';
                $str1 .= '</div>';
                $str2 .= $arr2[$i]['text_format'];
            }
            $row['product_units'] = $str1;
            $row['product_unit_export'] = $str2;
            $row['add_unit'] = '<div class="badge bg-success cursor-pointer add_punt">ADD UNIT</div>';

            $row['prd_rate_type'] = $this->products->get_rate_types($row['prd_rate_type']);
            $row['prd_madein'] = $this->products->get_made_in($row['prd_madein']);
            $row['prd_prod_type'] = $this->products->get_production_types($row['prd_prod_type']);
            $row['prd_dietary'] = $this->products->get_dietary_types($row['prd_dietary']);
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

        $prd_id = $this->input->post('prd_id');
        $row = $this->products->get_by_id($prd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product';
            echo json_encode($json);
            return;
        }


        $cats = $this->product_categories->parent_hierarchy($row['prd_fk_product_categories'], 'option', 'reverse', FALSE);
        $row['cats'] = implode('<i class="far fa-chevron-double-right mx-1" style="font-size:8px"></i>', $cats);
        $row['cat_name'] = $this->product_categories->get_name_by_id($row['prd_fk_product_categories']);
        $row['cmp_name'] = $this->companies->get_name_by_id($row['prd_fk_companies']);
        $row['brnd_name'] = $this->brands->get_name_by_id($row['prd_fk_brands']);
        $row['prd_madein'] = $this->products->get_made_in($row['prd_madein']);
        $row['prd_rate_type'] = $this->products->get_rate_types($row['prd_rate_type']);
        $row['prd_prod_type'] = $this->products->get_production_types($row['prd_prod_type']);
        $row['prd_dietary'] = $this->products->get_dietary_types($row['prd_dietary']);
        $row['tg_ids'] = $this->product_tags->get_tags($prd_id, ACTIVE, 'option');
        $row['photos'] = $this->product_photos->get_data(array('prdpt_fk_clients' => $this->clnt_id, 'prdpt_fk_products' => $prd_id));
        $row['upload_dir'] = $this->upload_dir;

        $row['after_ajax'] = TRUE;


        $json['html'] = $this->load->view('products/show_details', $row, true);
        echo json_encode($json);
        return;
    }


    function get_product_data()
    {
        // 1 -> Succes;   2 -> Failure;
        $data['status'] = 1;

        // Other Errors;
        $data['o_error'] = '';

        $prd_id = $this->input->post('prd_id');
        $row = $this->products->get_by_id($prd_id);
        if (!$row) {
            $data['status'] = 2;
            $data['o_error'] = 'Product Not Found';
            echo json_encode($data);
            return;
        }

        // Used after Ajax response
        $data['prd_id'] = $prd_id;
        $data['prd_name'] = $this->input->post('prd_name');
        $data['pgp_name'] = $this->input->post('pgp_name');

        // Getting Units
        $data['unit_option'] = get_options($this->unit_groups->get_all_basic_ugps($prd_id), '', '', FALSE);

        // Getting batches
        $pdbch_input['pdbch_fk_products'] = $prd_id;
        $pdbch_input['clnt_id'] = $this->clnt_id;
        $data['product_batch_data'] = $this->product_batches->index($pdbch_input);

        $this->load->model("stock_avg_mdl", 'stock_avg');

        // To Get Avg Purchas Price acros central_stores
        $avg_stock = array(); // $avg_stock[pdbch_id][ugp_id] = array('qty' => xxx, 'amt' => yyy, 'rate' => zzz)

        // Current stock in each central store
        $centralstore_stock = array();


        $this->load->model('central_stores_mdl', 'central_stores');

        if ($data['product_batch_data']) {
            // Getting all central stores where the product was
            $cstr_mbr_ids = $this->stock_avg->get_cstr_ids($this->clnt_id, $prd_id);

            // Taking central store stock of each batch
            foreach ($cstr_mbr_ids as $cstr_mbr_id) {
                foreach ($data['product_batch_data'] as $pdbch) {
                    $cstrstk = $this->stock_avg->get_cstr_stock('', $cstr_mbr_id, $prd_id, $pdbch['pdbch_id']);
                    $cstr_id = $this->central_stores->get_id_by_member($cstr_mbr_id);
                    $cstr_name = $this->central_stores->get_name_by_id($cstr_id);
                    $temp['cstr'] = array('cstr_id' => $cstr_id, 'cstr_name' => $cstr_name);
                    $temp['stock'] = $cstrstk;
                    $centralstore_stock[$pdbch['pdbch_id']][$cstr_mbr_id] = $temp;
                    // echo "<br>" . $cstr_name;
                    // print_pre($centralstore_stock[$pdbch['pdbch_id']]);

                    if ($cstrstk) {
                        foreach ($cstrstk as $ugp_id => $cstk) {
                            if (isset($avg_stock[$pdbch['pdbch_id']][$ugp_id])) {
                                $qty = bcadd($avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'], $cstk['bal_qty'], 5);
                                $amt_1 = bcmul($cstk['bal_qty'], $cstk['bal_rate'], 5);
                                $amt_2 = $avg_stock[$pdbch['pdbch_id']][$ugp_id]['amt'];
                                $amt = bcadd($amt_1, $amt_2, 5);
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'] = $qty;
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['amt'] = $amt;

                                // Adding Rate also
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['rate'] = (float)$avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'] ? bcdiv($avg_stock[$pdbch['pdbch_id']][$ugp_id]['amt'], $avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'], 5) : 0;
                            }

                            // Only if Qty exist
                            else if ($cstk['bal_qty']) {
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'] = $cstk['bal_qty'];
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['amt'] = bcmul($cstk['bal_qty'], $cstk['bal_rate'], 5);

                                // Adding Rate also
                                $avg_stock[$pdbch['pdbch_id']][$ugp_id]['rate'] = (float)$avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'] ? bcdiv($avg_stock[$pdbch['pdbch_id']][$ugp_id]['amt'], $avg_stock[$pdbch['pdbch_id']][$ugp_id]['qty'], 5) : 0;
                            }
                        }
                    }
                }
            }
        }

        /*
        // Now we have stock detais of the product only in basic ugp_id.
        // So now we adding to all ugp_ids (by calculating ugp_rel).
        foreach ($avg_stock as $pdbch_id => &$batch_stock) {
            foreach ($batch_stock as $ugp_id => $ugp_stock) {
                // Getting all other ugp_ids in the current ugp set.
                $group_no = $this->unit_groups->get_group_no($ugp_id);
                $ugp_data = $this->unit_groups->get_by_group_no($group_no);
                $basic_ugp_id = $this->unit_groups->get_basic_ugp($ugp_id, 'id');
                if ($ugp_id != $basic_ugp_id) {
                    // In Tbl: stock_avg we are saving stock data in basic ugp_id.
                    // But in future, in any special case, we are saving stock in other ugp_ids, We need to 
                    // find the stock data in basic ugp_id first
                    $data['status'] = 2;
                    $data['o_error'] = 'Some stock data is not in basic unit, So need to find stock in basic unit first';
                    echo json_encode($data);
                    return;
                }
                foreach ($ugp_data as $ugp_row) {
                    if ($ugp_row['ugp_id'] == $ugp_id)
                        continue;

                    $new_ugp_id = $ugp_row['ugp_id'];
                    $rel = $ugp_row['ugp_rel'];
                    if (!isset($batch_stock[$new_ugp_id])) {
                        $batch_stock[$new_ugp_id]['qty'] = bcmul($ugp_stock['qty'], $rel, 5);
                        $batch_stock[$new_ugp_id]['rate'] = bcmul($ugp_stock['rate'], $rel, 5);
                        $batch_stock[$new_ugp_id]['amt'] = bcmul($ugp_stock['amt'], $rel, 5);
                    }
                }
            }
        }*/

        // Removing Bathes has no stock exist.
        $temp = $data['product_batch_data'];
        foreach ($temp as $k => $pdbch) {
            if (!isset($avg_stock[$pdbch['pdbch_id']]))
                unset($data['product_batch_data'][$k]);
        }

        $data['cstr_stock'] =  $centralstore_stock;
        $data['avg_stock'] = $avg_stock;
        //print_pre($data['avg_stock']);
        echo json_encode($data);
        return;
    }

    // Getting all basic units of the given proct
    function get_basic_units()
    {
        $prd_id = $this->input->post('prd_id');

        // Getting Units
        echo get_options($this->unit_groups->get_all_basic_ugps($prd_id), '', "", FALSE, TRUE, 'NO GODOWNS');
        return;
    }

    function before_edit()
    {
        //Checking tasks
        if (!has_task('tsk_prd_edit')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
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

        $prd_id = $this->input->post('prd_id');
        $row = $this->products->get_by_id($prd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product';
            echo json_encode($json);
            return;
        }

        if ($row['prd_fk_product_categories'])
            $json['cat_name'] = $this->product_categories->get_name_by_id($row['prd_fk_product_categories']);



        $json['tg_id'] = $this->product_tags->get_tags($prd_id, ACTIVE, 'id');

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function get_options()
    {
        $input = $this->get_inputs();
        $options =  $this->products->get_options($input);
        echo get_options($options);
        return;
    }

    function deactivate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_deactivate')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
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

        $prd_id = $this->input->post('prd_id');
        $row = $this->products->get_by_id($prd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product';
            echo json_encode($json);
            return;
        }

        $this->products->deactivate($prd_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        //Checking tasks
        if (!has_task('tsk_prd_activate')) {
            // $this->session->set_flashdata('permission_errors', 'No task found');
            // $this->redirect_me("logout");
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

        $prd_id = $this->input->post('prd_id');
        $row = $this->products->get_by_id($prd_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find product';
            echo json_encode($json);
            return;
        }

        $this->products->activate($prd_id);
        echo json_encode($json);
        return;
    }

    function check_cats($val)
    {
        if (!$this->input->post('pct_parent')) {
            $this->form_validation->set_message('check_cats', "This Field Is Required");
            return FALSE;
        }
        return TRUE;
    }


    function validate_photo($field)
    {
        if (!isset($_FILES[$field]))
            return "";

        $file_input = $_FILES[$field];

        // Get Image Dimension
        $fileinfo = @getimagesize($file_input["tmp_name"]);
        $width = $fileinfo[0];
        $height = $fileinfo[1];

        // Get image file extension
        $file_extension = pathinfo($file_input["name"], PATHINFO_EXTENSION);

        // Validate file input to check if is not empty
        // This case will ocure when user edited the file after it browsed by file input.
        if (!file_exists($file_input["tmp_name"])) {
            return "<div class=\"form-validation error\">File not found</div>";
        }

        // Validate file input to check if is with valid extension
        if (!in_array($file_extension, $this->allowed)) {
            return "<div class=\"form-validation error\">Only " . implode(', ', $this->allowed) . " file types allowed</div>";
        }

        // Validate image file size       
        if (($file_input["size"] > 2000000)) {
            return "<div class=\"form-validation error\">Image size exceeds 2MB</div>";
        }

        // Validate image file dimension
        if ($width > "300" || $height > "400") {
            return "<div class=\"form-validation error\">Image dimension should be within 300X400</div>";
        }

        return '';
    }

    function no_units()
    {
        $prd_id = $this->input->post('prd_id');
        if ($prd_id)
            return TRUE;

        $val = $this->input->post('product_units');
        if (!$val) {
            $this->form_validation->set_message('no_units', "Please select a Unit");
            return FALSE;
        }
        return TRUE;
    }
}
