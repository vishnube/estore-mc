<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gstnumbers_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('gstnumbers', 'gst');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table");



        if (ifSetInput($input, 'gst_status'))
            $this->db->where('gst_status', $input['gst_status']);

        $this->db->where('gst_fk_clients', $input['gst_fk_clients']);

        if ($flag) {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
            // return  $this->db->count_all_results();
        } else {
            $this->db->select('*');
            $this->db->order_by($this->nameField);
            $R = $this->db->get('', $input['per_page'], $input['offset'])->result_array();
            //echo $this->db->last_query();
            return $R;
        }
    }

    function get_gst($clnt_id, $mbr_id = '', $gst_status = ACTIVE)
    {
        $this->db->from("$this->table, states");
        $this->db->select("$this->table.*,stt_name");
        $this->db->where("gst_fk_clients", $clnt_id);
        if ($mbr_id)
            $this->db->where("gst_fk_members", $mbr_id);
        if ($gst_status)
            $this->db->where("gst_status", $gst_status);
        $this->db->where("stt_id = gst_fks_states");
        $this->db->order_by($this->statusField);
        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        return $R;
    }
}
