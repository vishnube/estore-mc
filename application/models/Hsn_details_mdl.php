<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hsn_details_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('hsn_details', 'hsn');
    }

    public function index($input, $flag)
    {
        $this->db->from($this->table);

        $this->db->where('hsn_fk_clients', $input['clnt_id']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'hsn_name_4_digit'))
            $this->db->where("hsn_name_4_digit LIKE", '%' . $input['hsn_name_4_digit'] . '%');

        if (ifSetInput($input, 'hsn_commodity'))
            $this->db->where("hsn_commodity LIKE", '%' . $input['hsn_commodity'] . '%');

        if (ifSetInput($input, 'hsn_chapter'))
            $this->db->where('hsn_chapter', $input['hsn_chapter']);

        if (ifSetInput($input, 'hsn_sch'))
            $this->db->where('hsn_sch', $input['hsn_sch']);

        if (ifSetInput($input, 'hsn_gst'))
            $this->db->where('hsn_gst', $input['hsn_gst']);

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);


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
}
