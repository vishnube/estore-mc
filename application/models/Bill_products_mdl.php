<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bill_products_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bill_products', 'blp');
    }

    public function get_bill_products($bls_id)
    {
        $this->db->from("$this->table,godowns,products,unit_groups,units");
        $this->db->join('product_batches', 'pdbch_id = blp_fk_product_batches', 'left');

        $this->db->select("$this->table.*,gdn_name,prd_name,unt_name,pdbch_name,pdbch_exp,pdbch_mrp,gdn_id,prd_id,unt_id,pdbch_id");
        $this->db->where('blp_fk_bills', $bls_id);
        $this->db->where('gdn_id = blp_fk_godowns');
        $this->db->where('prd_id = blp_fk_products');
        $this->db->where('ugp_id = blp_fk_unit_groups');
        $this->db->where('unt_id = ugp_fk_units');


        $this->db->order_by($this->p_key);
        $R = $this->db->get()->result_array();
        return $R;
    }

    public function get_order_products($bls_id)
    {
        $this->db->from("$this->table,godowns,products,unit_groups,units");
        $this->db->join('product_batches', 'pdbch_id = blp_fk_product_batches', 'left');

        $this->db->select("$this->table.*,gdn_name,prd_name,unt_name,pdbch_name,pdbch_exp,pdbch_mrp,gdn_id,prd_id,unt_id,pdbch_id");
        $this->db->where('blp_fk_bills', $bls_id);
        $this->db->where('gdn_id = blp_fk_godowns');
        $this->db->where('prd_id = blp_fk_products');
        $this->db->where('ugp_id = blp_fk_unit_groups');
        $this->db->where('unt_id = ugp_fk_units');


        $this->db->order_by($this->p_key);
        $R = $this->db->get()->result_array();
        return $R;
    }

    function get_product_count($bls_id)
    {
        $this->db->from("$this->table");
        $this->db->where('blp_fk_bills', $bls_id);
        $this->db->select('count(*) as allcount');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }
}
