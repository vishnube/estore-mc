<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Members extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'members';
        $this->load->model($this->table . "_mdl", $this->table);
    }

    /** 
     * Getting members whom are not a USER
     */
    function get_non_user_options()
    {
        $input = $this->get_inputs();
        $input['mbr_status'] = ACTIVE;
        $options =  $this->members->get_non_users($input);
        echo get_options($options, NULL, 'Select', TRUE, FALSE, "No Members");
        return;
    }
}
