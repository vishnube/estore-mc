<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('products', 'prd');
    }

    function insert_hsn($d)
    {
        $this->db->insert('hsn_details', $d);
    }


    public function index($input, $flag)
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


        $this->db->from("$this->table,product_categories");

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        // If Product category given
        // Eg: visit ptice Group's Product-Tab >> Add-Tab
        if (ifSetInput($input, 'rad_product_load_form')) {
            $str = $this->array_query($c, 'prd_fk_product_categories');
            $this->db->where($str);
        }

        $this->db->where('prd_fk_clients', $input['prd_fk_clients']);

        $this->db->where('pct_id = prd_fk_product_categories');

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,pct_name,pct_id");
            $this->db->order_by($this->nameField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            // echo $this->db->last_query();
            return $R;
        }
    }



    function get_made_in($i = '')
    {
        $md[1] = 'India';
        $md[2] = 'China';
        $md[3] = 'Japan';
        $md[4] = 'USA';
        $md[5] = 'Other';

        if ($i && isset($md[$i]))
            return $md[$i];

        return $md;
    }

    function get_rate_types($i = '')
    {
        $md[1] = 'MRP';
        $md[2] = 'Daily Varient';
        $md[3] = 'Frequently Varient';

        if ($i && isset($md[$i]))
            return $md[$i];

        return $md;
    }

    function get_production_types($i = '')
    {
        $md[1] = 'Company';
        $md[2] = 'SSI';
        $md[3] = 'Farmers';

        if ($i && isset($md[$i]))
            return $md[$i];

        return $md;
    }

    function get_dietary_types($i = '')
    {
        $md[1] = 'Veg';
        $md[2] = 'Fish';
        $md[3] = 'Egg';
        $md[4] = 'Non-Veg';

        if ($i && isset($md[$i]))
            return $md[$i];

        return $md;
    }
}
