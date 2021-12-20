<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Districts_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('districts', 'dst');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table,states");

        if (ifSetInput($input, 'stt_id'))
            $this->db->where("stt_id", $input['stt_id']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where("dst_fk_clients", $input['clnt_id']);
        $this->db->where("stt_id = dst_fk_states");

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,stt_name");

            $this->db->order_by('stt_name');
            $this->db->order_by($this->nameField);

            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            return $R;
        }
    }
}
