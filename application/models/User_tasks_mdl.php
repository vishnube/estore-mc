<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_tasks_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('user_tasks', 'utsk');
    }

    function get_user_task($grp_id, $tsk_id)
    {
        $this->db->from("$this->table");
        $this->db->where("utsk_fk_groups", $grp_id);
        $this->db->where("utsk_fk_tasks", $tsk_id);
        $R = $this->db->get()->row();
        return $R;
    }

    /**
     * @param $user_type:   Uertype
     * @param $usr_id:      Id
     * @param $keys:        value of tsk_key. It may array also
     *                      Eg: tsk_emply_list
     *                          array('tsk_emply_add','tsk_emply_edit')
     */
    function has_task($usr_type, $usr_id, $keys, $tsk_id)
    {
        // Developer
        if ($usr_type == 1)
            return TRUE;

        // Master Admin
        if ($usr_type == 2) {
            $this->db->from("tasks");
            $this->db->where("tsk_type", 2);
        }

        // Normal Users
        else if ($usr_type == 3) {
            $Q = "(SELECT ugrp_fk_groups AS grp_id FROM user_groups WHERE ugrp_fk_users = $usr_id)";
            $this->db->from("$this->table,tasks,  $Q MY_TABLE");
            $this->db->where("tsk_type", 2);
            $this->db->where("utsk_fk_groups = grp_id");
            $this->db->where("utsk_fk_tasks = tsk_id");
        }
        if ($keys) {
            if (is_array($keys)) {
                $str = $this->array_query($keys, 'tsk_key');
                $this->db->where($str);
            } else
                $this->db->where("tsk_key", $keys);
        }
        if ($tsk_id)
            $this->db->where("tsk_id", $tsk_id);


        $this->db->select('count(*) as allcount');
        $query = $this->db->get();
        $result = $query->result_array();
        $r = $result[0]['allcount'] ? TRUE : FALSE;
        return $r;
    }
}
