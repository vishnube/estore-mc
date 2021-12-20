<?php

defined('BASEPATH') or exit('No direct script access allowed');

class settings_keys_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('settings_keys', 'stky');
    }

    public function index($input)
    {
        $this->db->from("$this->table");

        if (ifSetInput($input, 'stky_name'))
            $this->db->where('stky_name LIKE', '%' . ifSetInput($input, 'stky_name') . '%');

        if (ifSetInput($input, 'stky_desc'))
            $this->db->where('stky_desc LIKE', '%' . ifSetInput($input, 'stky_desc') . '%');

        if (ifSetInput($input, 'stky_status'))
            $this->db->where('stky_status', $input['stky_status']);

        $this->db->order_by($this->nameField);
        return $this->db->get()->result_array();
    }
}
