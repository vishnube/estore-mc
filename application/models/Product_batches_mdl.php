<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_batches_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('product_batches', 'pdbch');
    }


    public function index($input)
    {
        $this->db->from("$this->table");

        if (ifSetInput($input, 'pdbch_fk_products'))
            $this->db->where('pdbch_fk_products', $input['pdbch_fk_products']);

        $this->db->where('pdbch_status', ACTIVE);

        $this->db->where('pdbch_fk_clients', $input['clnt_id']);
        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $R;
    }

    function  get_batches_by_prd_ids($prd_ids, $clnt_id, $pdbch_status = ACTIVE, $return = 'option')
    {
        $this->db->from("$this->table");
        if (is_array($prd_ids))
            $this->db->where($this->product_batches->array_query($prd_ids, 'pdbch_fk_products'));
        else
            $this->db->where('pdbch_fk_products', $prd_ids);

        $this->db->where('pdbch_fk_clients', $clnt_id);

        if ($pdbch_status)
            $this->db->where('pdbch_status', $pdbch_status);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();

        if ($return == 'option')
            $R = $this->make_option($R);
        return $R;
    }
}
