<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bill_nos_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bill_nos', 'bln');
    }


    public function get_next_bill_no_id($clnt_id, $blb_id)
    {
        $this->db->from("$this->table");
        $this->db->select_max('bln_name');
        $this->db->where('bln_fk_bill_batches', $blb_id);
        $r = $this->db->get()->row_array();
        $max_id = $r['bln_name'];
        $next = !$max_id ? 1 : $max_id + 1;

        $data = array('bln_fk_clients' => $clnt_id, 'bln_fk_bill_batches' => $blb_id, 'bln_name' => $next);
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}
