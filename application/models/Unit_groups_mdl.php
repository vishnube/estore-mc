<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Unit_groups_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('unit_groups', 'ugp');
    }

    public function index($input)
    {
        $this->db->from($this->table);

        $this->db->where('ugp_fk_clients', $input['ugp_fk_clients']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        if (ifSetInput($input, 'ugp_group_no'))
            $this->db->where("ugp_group_no", $input['ugp_group_no']);

        if (ifSetInput($input, $this->statusField))
            $this->db->where($this->statusField, $input[$this->statusField]);

        $this->db->order_by("ugp_is_basic"); //Basic Unit comes first
        $this->db->order_by($this->p_key);
        $R = $this->db->get()->result_array();
        return $R;
    }

    function get_group_nos($clnt_id)
    {
        $this->db->from($this->table);
        $this->db->distinct();
        $this->db->select('ugp_group_no');
        $this->db->where('ugp_fk_clients', $clnt_id);

        $this->db->order_by('ugp_group_no');
        $R = $this->db->get()->result_array();
        return $R;
    }

    function get_group_no($ugp_id)
    {
        $this->db->from($this->table);
        $this->db->select('ugp_group_no');
        $this->db->where('ugp_id', $ugp_id);
        $R = $this->db->get()->row_array();
        return $R['ugp_group_no'];
    }

    function get_by_group_no($grp_nos)
    {
        $this->db->from($this->table);
        if (is_array($grp_nos)) {
            $q = $this->array_query($grp_nos, 'ugp_group_no');
            $this->db->where($q);
        } else
            $this->db->where('ugp_group_no', $grp_nos);

        $this->db->order_by('ugp_is_basic');
        $this->db->order_by($this->p_key);
        $R = $this->db->get()->result_array();
        return $R;
    }

    function get_next_group_no()
    {
        $this->db->from($this->table);
        $this->db->select_max('ugp_group_no');
        $R = $this->db->get()->row_array();
        $next = $R['ugp_group_no'] ? $R['ugp_group_no'] : 0;
        return ++$next;
    }


    /**
     * get_basic_ugp : Get basic ugp details in a unit group by ugp_id.
     *
     * @param  mixed $ugp_id
     * @return void
     */
    function get_basic_ugp($ugp_id, $return = 'id')
    {
        $group_no = $this->get_field_by_id($ugp_id, 'ugp_group_no');
        $this->db->where('ugp_group_no', $group_no);
        $this->db->where('ugp_is_basic', 1);
        $R = $this->db->get($this->table)->row_array();
        if ($return == 'id')
            return $R['ugp_id'];
        return $R;
    }


    /**
     * get_all_basic_ugps: all basic ugp_id of a product
     *
     * @param  mixed $prd_id
     * @param  mixed $return
     * @return void
     */
    function get_all_basic_ugps($prd_id, $return = 'option')
    {
        $this->load->model('product_units_mdl', 'product_units');
        $grp_nos = $this->product_units->get_unit_goups_no($prd_id);

        $this->db->from("$this->table,units");
        $this->db->select("$this->table.*,unt_name");

        $q = $this->array_query($grp_nos, 'ugp_group_no');
        $this->db->where($q);
        $this->db->where('unt_id = ugp_fk_units');
        $this->db->where('ugp_is_basic', 1); // Only basic unit
        $this->db->where('ugp_status', ACTIVE);
        $this->db->where('unt_status', ACTIVE);
        $this->db->order_by($this->p_key);
        $R = $this->db->get()->result_array();
        if ($return == 'option')
            $R = $this->make_option($R, 'ugp_id', 'unt_name');
        else if ($return == 'id')
            $R = $this->get_ids_from_query_result($R);
        return $R;
    }

    /**
     * get highest unit of unit group by group_no
     *
     * @param  mixed $group_no
     * @return void
     */
    function get_highest_unit_by_group_no($group_no)
    {
        $this->db->from("$this->table,units");
        $this->db->where('ugp_group_no', $group_no);
        $this->db->where('unt_id = ugp_fk_units');
        $this->db->order_by('ugp_rel', 'desc');
        $R = $this->db->get('', 1, 0)->row_array();
        return $R;
    }

    function get_ugp_dt($ugp_id)
    {
        $this->db->from("$this->table,units");
        $this->db->where('ugp_id', $ugp_id);
        $this->db->where('unt_id = ugp_fk_units');
        $R = $this->db->get()->row_array();
        return $R;
    }
}
