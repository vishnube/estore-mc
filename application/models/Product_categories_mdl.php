<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_categories_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('product_categories', 'pct');
    }


    public function index($input, $flag)
    {
        $this->db->from("$this->table");
        $q = "(SELECT pct_name FROM product_categories TEMP WHERE TEMP.pct_id = product_categories.pct_parent)";
        $this->db->select("$this->table.*, $q AS parent_name");

        if (ifSetInput($input, 'pct_parent'))
            $this->db->where('pct_parent', $input['pct_parent']);

        if (ifSetInput($input, 'pct_status'))
            $this->db->where('pct_status', $input['pct_status']);

        if (ifSetInput($input, $this->nameField))
            $this->db->where("$this->nameField LIKE", '%' . $input[$this->nameField] . '%');

        $this->db->where('pct_fk_clients', $input['pct_fk_clients']);

        $this->db->order_by($this->nameField);
        $R = $this->db->get()->result_array();
        return $R;

        // SELECT product_categories.*, (SELECT pct_name FROM product_categories TEMP WHERE TEMP.pct_id = product_categories.pct_parent) AS pct_pname FROM `product_categories`  WHERE `pct_fk_clients` = 1 ORDER BY `pct_name`
    }

    function parent_hierarchy($pct_id, $return = 'ids', $order = 'forward', $include_me = TRUE)
    {
        $parents = array();

        if ($return == 'ids') {
            if ($include_me)
                $parents[] = $pct_id;
            // Parent
            $p = $this->get_field_by_id($pct_id, 'pct_parent');
            while ($p) {
                $parents[] = $p;
                $p = $this->get_field_by_id($p, 'pct_parent');
            }
        }

        if ($return == 'rows') {
            if ($include_me)
                $parents[] = $this->get_by_id($pct_id);
            // Parent
            $p = $this->get_field_by_id($pct_id, 'pct_parent');
            while ($p) {
                $parents[] = $this->get_by_id($p);
                $p = $this->get_field_by_id($p, 'pct_parent');
            }
        }

        if ($return == 'option') {
            if ($include_me)
                $parents[$pct_id] = $this->get_name_by_id($pct_id);
            // Parent
            $p = $this->get_field_by_id($pct_id, 'pct_parent');
            while ($p) {
                $parents[$p] = $this->get_name_by_id($p);
                $p = $this->get_field_by_id($p, 'pct_parent');
            }
        }

        if ($order == 'reverse')
            $parents  = array_reverse($parents, ($return == 'option'));
        return $parents;
    }

    // Getting all children (recursively) of the given parent category
    function get_children($parent_id)
    {
        $children = array();
        $R = $this->db->from($this->table)
            ->select('pct_id')
            ->where('pct_parent', $parent_id)
            ->get()->result_array();
        if ($R) {
            foreach ($R as $r) {
                $children[] = $r['pct_id'];
                $children = array_merge($children, $this->get_children($r['pct_id']));
            }
        }
        return $children;
    }
}
