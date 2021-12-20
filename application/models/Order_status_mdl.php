<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_status_mdl extends My_mdl
{
    const PENDING = 1;
    const PICKED = 2;
    const BILLED = 3;
    const PACKED = 4;
    const ESTORE = 5;
    const DELIVERED = 6;
    const PAID = 7;

    public function __construct()
    {
        parent::__construct();
        $this->loadTable('order_status', 'ost');
    }

    /**
     * get_status
     *
     * @param  mixed $status
     * @param  mixed $func      :   Any text formating function
     *                          :   1. ucwords (Convert the first character of each word to uppercase)
     *                          :   2. strtolower (Convert all characters to lowercase)
     *                          :   3. strtoupper (Convert all characters to uppercase)
     * @return void
     */
    function get_status($status = '', $func = 'strtoupper')
    {
        $s[self::PENDING] = $func("pending");       // 1
        $s[self::PICKED] = $func("picked");         // 2
        $s[self::BILLED] = $func("billed");         // 3
        $s[self::PACKED] = $func("packed");         // 4
        $s[self::ESTORE] = $func("estore");         // 5
        $s[self::DELIVERED] = $func("delivered");   // 6
        $s[self::PAID] = $func("paid");             // 7

        if ($status && isset($s[$status]))
            return $s[$status];

        return $s;
    }

    /**
     * get_cur_status
     *
     * @param  mixed $bls_id
     * @param  mixed $ret       :   Default is 'row'. It will return full row
     *                              If its value is any field name like 'ost_status', then return the value of that field only.
     * @return void
     */
    public function get_cur_status($bls_id, $ret = 'row')
    {
        $this->db->where('ost_fk_bills', $bls_id)->order_by($this->p_key, 'desc');
        $R =  $this->db->get($this->table, 1, 0)->row_array();
        $R = ($ret != 'row' && isset($R[$ret])) ? $R[$ret] : $R;
        return $R;
    }
    public function get_flow($bls_id)
    {
        $this->db->select("$this->table.*, DATE_FORMAT(ost_date, '%d-%m-%Y %h:%i %p') as ost_date, mbr_name")
            ->where('ost_fk_bills', $bls_id)
            ->where('usr_id = ost_fk_users')
            ->where('mbr_id = usr_fk_members')
            ->order_by($this->p_key);
        $R =  $this->db->get("$this->table, users, members")->result_array();
        return $R;
    }
}
