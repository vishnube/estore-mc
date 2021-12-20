<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Price_groups_products_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('price_groups_products', 'pgprd');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table,price_groups,products,product_batches,unit_groups,units");

        if (ifSetInput($input, 'pgprd_f_date'))
            $this->db->where('pgprd_date >= ', get_sql_date_time($input['pgprd_f_date'], 'first'));

        if (ifSetInput($input, 'pgprd_t_date'))
            $this->db->where('pgprd_date <= ', get_sql_date_time($input['pgprd_t_date'], 'last'));

        if (ifSetInput($input, 'v_on')) {
            $this->db->where('pgpl_vf <= ', get_sql_date($input['v_on']));
            $this->db->where('pgpl_vt >= ', get_sql_date($input['v_on']));
        }

        if (ifSetInput($input, 'v_start'))
            $this->db->where('pgpl_vf >= ', get_sql_date($input['v_start']));

        if (ifSetInput($input, 'v_end'))
            $this->db->where('pgpl_vt <= ', get_sql_date($input['v_end']));

        if (ifSetInput($input, 'pgprd_fk_price_groups')) {
            $str = $this->array_query($input['pgprd_fk_price_groups'], 'pgprd_fk_price_groups');
            $this->db->where($str);
        }

        if (ifSetInput($input, 'pgprd_fk_products')) {
            $str = $this->array_query($input['pgprd_fk_products'], 'pgprd_fk_products');
            $this->db->where($str);
        }

        $this->db->where('pgp_status', ACTIVE);
        $this->db->where('prd_status', ACTIVE);
        $this->db->where('pgprd_fk_clients', $input['clnt_id']);


        if (ifSetInput($input, 'stt_id'))
            $this->db->where('pgpl_fk_states', $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where('pgpl_fk_districts', $input['dst_id']);

        if (ifSetInput($input, 'ars_id'))
            $this->db->where('pgpl_fk_areas', $input['ars_id']);

        if (ifSetInput($input, 'wrd_id'))
            $this->db->where('pgpl_fk_wards', $input['wrd_id']);

        if (ifSetInput($input, 'cstr_id'))
            $this->db->where('pgpl_fk_central_stores', $input['cstr_id']);




        $this->db->where('pgp_id = pgprd_fk_price_groups');
        $this->db->where('prd_id = pgprd_fk_products');
        $this->db->where('pdbch_id = pgprd_fk_product_batches');
        $this->db->where('ugp_id = pgprd_fk_unit_groups');
        $this->db->where('unt_id = ugp_fk_units');

        $this->db->join('price_group_locations', 'pgpl_fk_price_groups = pgprd_fk_price_groups ', 'left');
        // $this->db->join('states', 'stt_id = pgpl_fk_states  AND stt_status = 1', 'left');
        // $this->db->join('districts', 'dst_id = pgpl_fk_districts AND dst_status = 1', 'left');
        // $this->db->join('areas', 'ars_id = pgpl_fk_areas  AND ars_status = 1', 'left');
        // $this->db->join('wards', 'wrd_id = pgpl_fk_wards  AND wrd_status = 1', 'left');
        // $this->db->join('central_stores', 'cstr_id = pgpl_fk_central_stores  AND cstr_status = 1', 'left');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {

            $wrd_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">',wrd_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM wards,areas,taluks,districts,states WHERE wrd_id = pgpl_fk_wards AND ars_id = wrd_fk_areas AND tlk_id = ars_fk_taluks AND dst_id =tlk_fk_districts AND stt_id = dst_fk_states)";

            $ars_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-primary\" title =\"Area\">',ars_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM areas,taluks,districts,states WHERE ars_id = pgpl_fk_areas AND tlk_id = ars_fk_taluks AND dst_id =tlk_fk_districts AND stt_id = dst_fk_states)";

            $dst_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-primary\" title =\"Area\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-success\" title =\"District\">',dst_name,'</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM districts,states WHERE dst_id = pgpl_fk_districts AND stt_id = dst_fk_states)";

            $stt_q = "(SELECT CONCAT('<span class=\"text-info\" title =\"wards\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-primary\" title =\"Area\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-success\" title =\"District\">----</span> <span class=\"code-block-sm\">&gt;&gt;</span> <span class=\"text-danger\" title =\"State\">',stt_name,'</span>') FROM states WHERE stt_id = pgpl_fk_states)";

            $cstr_q = "(SELECT CONCAT('<span class=\"text-warning\" title =\"Central Store\">',cstr_name,'</span>') as cstr FROM central_stores WHERE cstr_id = pgpl_fk_central_stores)";

            $locations = " (CASE ";
            $locations .= " when pgpl_fk_wards <> 0 then  $wrd_q"; //  as locations
            $locations .= " when pgpl_fk_areas <> 0 then  $ars_q";
            $locations .= " when pgpl_fk_districts <> 0 then  $dst_q";
            $locations .= " when pgpl_fk_states <> 0 then  $stt_q";
            $locations .= " END) ";

            $this->db->select("$this->table.*,pgpl_vf,pgpl_vt,prd_name,pgp_name,pdbch_name,unt_name,$locations AS locations, IF(pgpl_fk_central_stores <> 0, $cstr_q, '') AS cstr_dt");
            $this->db->order_by('pgp_name');
            $this->db->order_by('prd_name');
            $this->db->order_by('pdbch_name');
            $this->db->order_by('pgprd_qty');
            $this->db->order_by($this->dateField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }


    public function index2($input, $flag)
    {

        // It should be done before the following db querry
        // If Product category given
        // Eg: visit ptice Group's Product-Tab >> Add-Tab
        if (ifSetInput($input, 'rad_product_load_form')) {
            // Getting all children (recursively) of the given parent category
            $c = $this->product_categories->get_children($input['rad_product_load_form']);
            $c[] = $input['rad_product_load_form']; // Including the given parent category also

            // echo "<br>-----------------------<br>";
            // foreach ($c as $a)
            //     echo $this->product_categories->get_name_by_id($a) . "<br>";
        }

        $this->db->from("$this->table,price_groups,products,product_batches,unit_groups,units");

        if (ifSetInput($input, 'pgprd_f_date'))
            $this->db->where('pgprd_date >= ', get_sql_date_time($input['pgprd_f_date'], 'first'));

        if (ifSetInput($input, 'pgprd_t_date'))
            $this->db->where('pgprd_date <= ', get_sql_date_time($input['pgprd_t_date'], 'last'));

        if (ifSetInput($input, 'pgprd_fk_price_groups')) {
            $this->db->where('pgprd_fk_price_groups', $input['pgprd_fk_price_groups']);
        }

        // If Product category given
        // Eg: visit ptice Group's Product-Tab >> Add-Tab
        if (ifSetInput($input, 'rad_product_load_form')) {
            $str = $this->array_query($c, 'prd_fk_product_categories');
            $this->db->where($str);
        }

        if (ifSetInput($input, 'prd_name'))
            $this->db->where("prd_name LIKE", '%' . $input['prd_name'] . '%');

        $this->db->where('pgp_status', ACTIVE);
        $this->db->where('prd_status', ACTIVE);
        $this->db->where('pgprd_fk_clients', $input['clnt_id']);

        $this->db->where('pgp_id = pgprd_fk_price_groups');
        $this->db->where('prd_id = pgprd_fk_products');
        $this->db->where('pdbch_id = pgprd_fk_product_batches');
        $this->db->where('pdbch_fk_products = prd_id');
        $this->db->where('ugp_id = pgprd_fk_unit_groups');
        $this->db->where('unt_id = ugp_fk_units');



        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,prd_id,prd_name,pgp_name,pdbch_name,unt_name");
            $this->db->order_by('pgp_name');
            $this->db->order_by('prd_name');
            $this->db->order_by('pgprd_qty');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }


    public function get_unselected_products($input, $flag)
    {
        // It should be done before the following db querry
        // If Product category given
        // Eg: visit ptice Group's Product-Tab >> Add-Tab
        if (ifSetInput($input, 'rad_product_load_form')) {
            // Getting all children (recursively) of the given parent category
            $c = $this->product_categories->get_children($input['rad_product_load_form']);
            $c[] = $input['rad_product_load_form']; // Including the given parent category also
        }


        $this->db->from("products");

        if (ifSetInput($input, 'prd_name'))
            $this->db->where("prd_name LIKE", '%' . $input['prd_name'] . '%');

        if (ifSetInput($input, 'prd_status'))
            $this->db->where('prd_status', $input['prd_status']);

        // If Product category given
        // Eg: visit ptice Group's Product-Tab >> Add-Tab
        if (ifSetInput($input, 'rad_product_load_form')) {
            $str = $this->array_query($c, 'prd_fk_product_categories');
            $this->db->where($str);
        }

        $this->db->where('prd_fk_clients', $input['prd_fk_clients']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*");
            $this->db->order_by('prd_name');
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            // echo $this->db->last_query();
            return $R;
        }
    }
}
