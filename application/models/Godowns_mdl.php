<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Godowns_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('godowns', 'gdn');
    }


    /**
     * Listed only godowns under ACTIVE Central stores.
     *
     * @param  mixed $input
     * @return void
     */
    public function index($input)
    {
        $this->db->from("$this->table,central_stores");
        $this->db->select("$this->table.*,cstr_name");

        if (ifSetInput($input, 'gdn_fk_central_stores'))
            $this->db->where("gdn_fk_central_stores", $input['gdn_fk_central_stores']);

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);


        $this->db->where("cstr_id = gdn_fk_central_stores");
        $this->db->where("cstr_status", ACTIVE);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        return $R;
    }
}
