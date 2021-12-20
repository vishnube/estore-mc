<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Family_members_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('family_members', 'fmlm');
    }

    function get_family_members_by_family($fmly_id, $fmlm_status = ACTIVE, $ret = 'count')
    {
        $this->db->from("$this->table,members");

        $this->db->where('fmlm_fk_families', $fmly_id);
        $this->db->where('mbr_id = fmlm_fk_members');

        if ($fmlm_status)
            $this->db->where($this->statusField, $fmlm_status);

        if ($ret == 'count') {
            $this->db->select('count(*) as allcount');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result[0]['allcount'];
        } else {
            $this->db->select('*');
            $this->db->order_by($this->statusField); // For family_members show details (Needs both ACTIVE and INACTIVE)
            $this->db->order_by($this->nameField);
            $R = $this->db->get('')->result_array();
            if ($ret == 'option') {
                $R = $this->make_option($R, $this->p_key, $this->nameField);
            } else if ($ret == 'all')
                return $R;
        }
    }
}
