<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_units_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('product_units', 'punt');
    }

    // public function index($input)
    // {
    //     $this->db->from("$this->table,unit_groups,units");

    //     if (ifSetInput($input, 'punt_fk_clients'))
    //         $this->db->where('punt_fk_clients', $input['punt_fk_clients']);

    //     if (ifSetInput($input, 'prd_id'))
    //         $this->db->where("punt_fk_products", $input['punt_fk_products']);

    //     if (ifSetInput($input, 'punt_group_no'))
    //         $this->db->where("punt_group_no", $input['punt_group_no']);

    //     if (ifSetInput($input, $this->statusField))
    //         $this->db->where($this->statusField, $input[$this->statusField]);

    //     $this->db->order_by('punt_group_no');
    //     $this->db->order_by("punt_is_basic"); //Basic Unit comes first
    //     $R = $this->db->get()->result_array();
    //     return $R;
    // }

    function get_unit_goups_no($prd_id, $punt_status = ACTIVE)
    {
        $this->db->from($this->table);
        $this->db->select('punt_group_no');
        $this->db->where('punt_fk_products', $prd_id);
        if ($punt_status)
            $this->db->where($this->statusField, $punt_status);
        $R = $this->db->get()->result_array();
        $arr = array();
        foreach ($R as $r)
            $arr[] = $r['punt_group_no'];
        return $arr;
    }
}
