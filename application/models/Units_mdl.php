<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Units_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('units', 'unt');
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

    function get_unit_option($unt_status = ACTIVE, $option = 1)
    {
        $this->db->from($this->table);

        if ($option == 1)
            $this->db->select("CONCAT(unt_name,' - ',unt_dsc) as NAME, $this->p_key");
        else if ($option == 2)
            $this->db->select("unt_name as NAME, $this->p_key");

        if ($unt_status)
            $this->db->where($this->statusField, $unt_status);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();

        $R = $this->make_option($R, 'unt_id', 'NAME');
        return $R;
    }
}
