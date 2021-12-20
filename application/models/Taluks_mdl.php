<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Taluks_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('taluks', 'tlk');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table,districts,states");

        if (ifSetInput($input, 'stt_id'))
            $this->db->where("stt_id", $input['stt_id']);

        if (ifSetInput($input, 'dst_id'))
            $this->db->where("dst_id", $input['dst_id']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->where("tlk_fk_clients", $input['clnt_id']);
        $this->db->where("dst_id = tlk_fk_districts");
        $this->db->where("stt_id = dst_fk_states");

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select("$this->table.*,dst_name,stt_name");

            $this->db->order_by('stt_name');
            $this->db->order_by('dst_name');
            $this->db->order_by($this->nameField);

            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }
}
