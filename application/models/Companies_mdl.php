<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Companies_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('companies', 'cmp');
    }

    public function index($input)
    {
        $this->db->from($this->table);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where("cmp_fk_clients", $input['clnt_id']);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        return $R;
    }
}
