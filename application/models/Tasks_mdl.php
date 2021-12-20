<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('tasks', 'tsk');
    }

    // Takes only first level menu
    public function get_tasks($tsk_menu = '', $tsk_status = '', $usr_type)
    {
        $this->db->from("$this->table");

        // IF Not a developer, don't show development related tasks.
        if ($usr_type != 1)
            $this->db->where('tsk_type != ', 1);

        if ($tsk_menu)
            $this->db->where('tsk_menu', $tsk_menu);
        if ($tsk_status)
            $this->db->where('tsk_status', $tsk_status);

        $this->db->where('tsk_parent', 0);
        $this->db->order_by('tsk_parent,tsk_sort');
        $R = $this->db->get()->result_array();
        return $R;
    }

    public function get_by_key($tsk_key)
    {
        $this->db->from("$this->table");
        $this->db->where("tsk_key LIKE", '%' . $tsk_key . '%');
        $this->db->order_by('tsk_key,tsk_sort');
        $R = $this->db->get()->result_array();
        return $R;
    }

    // Current users tasks only
    // public function get_my_parent_menus($usr_id, $usr_type)
    // {
    //     // For developer
    //     if ($usr_type == 1) {
    //         $this->db->from("$this->table");
    //     }

    //     // Default/Master Admin
    //     else if ($usr_type == 2) {
    //         $this->db->from("$this->table");
    //     }

    //     // Normal Users
    //     else if ($usr_type == 3) {
    //         $this->db->from("$this->table,user_tasks");
    //         $this->db->where('utsk_fk_users', $usr_id);
    //         $this->db->where('tsk_id = utsk_fk_tasks');
    //     }

    //     $this->db->select("$this->table.*");

    //     // IF Not a developer, don't show development related tasks.
    //     if ($usr_type != 1)
    //         $this->db->where('tsk_type != ', 1);

    //     $this->db->where('tsk_menu', 1);
    //     $this->db->where('tsk_status', ACTIVE);
    //     $this->db->where('tsk_parent', 0);
    //     $this->db->order_by('tsk_parent,tsk_sort');
    //     $R = $this->db->get()->result_array();
    //     return $R;
    // }



    function get_children($parent_id, $usr_type, $tsk_menu = '', $tsk_status = '')
    {
        if (!$parent_id)
            return array();


        // IF Not a developer, don't show development related tasks.
        if ($usr_type != 1)
            $this->db->where('tsk_type != ', 1);

        if ($tsk_menu)
            $this->db->where('tsk_menu', $tsk_menu);
        if ($tsk_status)
            $this->db->where('tsk_status', $tsk_status);
        $this->db->where("tsk_parent", $parent_id);
        $this->db->order_by('tsk_parent,tsk_sort');
        $R = $this->db->get($this->table)->result_array();
        return $R;
    }

    function next_sort($tsk_parent)
    {
        $this->db->select_max('tsk_sort');
        $this->db->where('tsk_parent', $tsk_parent);
        $R = $this->db->get($this->table)->row_array();

        $next = $R['tsk_sort'] ? $R['tsk_sort'] + 1 : 1;
        return $next;
    }

    function get_icon($tsk_id)
    {
        $this->db->where('tsk_id', $tsk_id);
        $r = $this->db->get($this->table)->row_array();
        return $r;
    }
}
