<?php

defined('BASEPATH') or exit('No direct script access allowed');

class States_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('states', 'stt');
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
}
