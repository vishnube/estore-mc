<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bill_batches_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bill_batches', 'blb');
    }

    public function index($input, $flag)
    {
        $this->db->from("$this->table");

        $this->db->where('blb_for', $input['blb_for']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where("blb_fk_clients", $input['clnt_id']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
        } else {
            $this->db->select("$this->table.*");
            $this->db->order_by($this->nameField);

            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            return $R;
        }
    }

    /**
     * get_current_bill_batch
     *
     * @param  mixed $blb_for   :   its value should match Tbl:bill_types.btp_key
     * @param  mixed $blb_type  :   1 => Non-Tax, 2 => Tax
     * @return void
     */
    function get_current_bill_batch($blb_for, $blb_type)
    {
        $this->db->from("bill_batches");
        $this->db->where('blb_for', $blb_for);
        $this->db->where('blb_type', $blb_type);
        $this->db->where('blb_status', ACTIVE);
        $r = $this->db->get()->row_array();

        return $r;
    }
}
