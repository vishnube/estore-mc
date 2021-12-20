<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Member_types_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('member_types', 'mbrtp');
    }

    /**
     * @param $want:        'mbrtp_name' need to include in the option list. It may array or a single string
     *                      Eg: (1) 'parties'
     *                          (2) array('parties', 'employees')
     *                          
     * @param $dont_want:   'mbrtp_name' don't need to include in the option list. It may array or a single string
     *                      Eg: (1) 'parties'
     *                          (2) array('parties', 'employees')
     * 
     */
    function get_mbrtp_option($want = array(), $dont_want = array(), $val = 'mbrtp_id', $text = 'mbrtp_title', $mbrtp_status = ACTIVE)
    {
        $this->db->from("$this->table");
        if ($want) {
            if (is_array($want))
                $this->db->where($this->array_query($want, $this->nameField));
            else
                $this->db->where($this->nameField, $want);
        }
        if ($dont_want) {
            if (is_array($dont_want)) {
                foreach ($dont_want as $dw)
                    $this->db->where("$this->nameField != ", $dw);
            } else
                $this->db->where("$this->nameField != ", $dont_want);
        }

        if ($mbrtp_status)
            $this->db->where($this->statusField, $mbrtp_status);

        $this->db->select("$val,$text");
        $this->db->order_by($text);
        $R = $this->db->get('')->result_array();
        $R = $this->make_option($R, $val, $text);

        return $R;
    }
}
