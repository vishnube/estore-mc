<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categories_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('categories', 'cat');
    }

    public function index($input)
    {
        $this->db->from("$this->table");

        if (ifSetInput($input, 'cat_fk_clients'))
            $this->db->where('cat_fk_clients', $input['cat_fk_clients']);

        if (ifSetInput($input, 'cat_fk_member_types'))
            $this->db->where('cat_fk_member_types', $input['cat_fk_member_types']);

        if (ifSetInput($input, 'cat_status'))
            $this->db->where('cat_status', $input['cat_status']);

        $this->db->order_by($this->nameField);
        return $this->db->get()->result_array();
    }
}
