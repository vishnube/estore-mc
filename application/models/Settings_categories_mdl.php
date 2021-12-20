<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings_categories_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('settings_categories', 'stct');
    }

    public function index($input)
    {
        $this->db->from("$this->table");

        if (ifSetInput($input, 'stct_status'))
            $this->db->where('stct_status', $input['stct_status']);

        $this->db->order_by('stct_sort');
        $this->db->order_by($this->nameField);
        return $this->db->get()->result_array();
    }

    function next_sort()
    {
        $this->db->select_max('stct_sort');
        $R = $this->db->get($this->table)->row_array();
        $next = $R['stct_sort'] ? $R['stct_sort'] + 1 : 1;
        return $next;
    }
}
