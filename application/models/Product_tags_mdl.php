<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_tags_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('product_tags', 'ptg');
    }

    public function index($input)
    {
        $this->db->from($this->table);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        return $R;
    }



    function get_tags($prd_id, $status = ACTIVE, $return = 'option')
    {

        $this->db->from("$this->table,tags");

        if ($prd_id)
            $this->db->where('ptg_fk_products', $prd_id);

        if ($status)
            $this->db->where('tg_status', $status);

        $this->db->where('tg_id = ptg_fk_tags');
        $r = $this->db->get()->result_array();

        if ($return == 'option')
            $r = $this->make_option($r, 'tg_id', 'tg_name');
        else if ($return == 'id')
            $r = $this->get_ids_from_query_result($r, 'tg_id');
        return $r;
    }
}
