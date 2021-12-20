<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Groups_mdl extends My_mdl
{

   public function __construct()
   {
      parent::__construct();
      $this->loadTable('groups1', 'grp');
   }

   public function index($input)
   {
      $this->db->from("$this->table");

      if (ifSetInput($input, 'grp_status'))
         $this->db->where('grp_status', $input['grp_status']);

      $this->db->where('grp_fk_clients', $input['grp_fk_clients']);

      $this->db->order_by($this->nameField);
      return $this->db->get()->result_array();
   }
}
