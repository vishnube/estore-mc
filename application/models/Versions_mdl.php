<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Versions_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('versions', 'v');
    }


    public function get_latest($return = 'v_id')
    {
        $this->db->select('*');
        $this->db->order_by('v_name', 'desc');
        $R = $this->db->get($this->table, 1, 0)->row_array();

        if ($return == 'row')
            return $R;
        else
            return $R[$return];
    }
}
