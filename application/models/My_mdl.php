<?php
class My_mdl extends CI_Model
{
    /**
     * The name of the associate table name of the Model object
     * @public string
     * @access public
     */
    public $table = NULL;

    /**
     * Container for the fields of the table that this model gets from persistent storage (the database).
     *
     * @public array
     * @access public
     */
    public $fields = array();

    /**
     * Prefix of the fields of table
     *
     * @public string
     * @access public
     */
    public $prefix = NULL;

    /**
     * The name of the ID field for this Model.
     *
     * @public string
     * @access public
     */
    public $p_key = NULL;

    /**
     * Name of the field to sort
     *
     * @public unknown_type
     * @access public
     */
    public $nameField = null;

    /**
     * Name of the field determinig the status
     *
     * @public unknown_type
     * @access public
     */
    public $statusField = null;

    /**
     * Name of the field determinig the date
     *
     * @public unknown_type
     * @access public
     */
    public $dateField = null;

    /**
     * Name of the field determinig the member id
     *
     * @public unknown_type
     * @access public
     */
    public $memberField = null;



    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Load the associated database table.
     *
     * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
     * @access public
     */
    function loadTable($table, $prefix)
    {
        $this->table = $table;
        $this->prefix = $prefix;
        $this->fields = $this->db->list_fields($table);
        $this->p_key = in_array($this->prefix . "_id", $this->fields) ? $this->prefix . "_id" : '';
        $this->nameField = in_array($this->prefix . "_name", $this->fields) ? $this->prefix . "_name" : '';
        $this->statusField = in_array($this->prefix . "_status", $this->fields) ? $this->prefix . "_status" : '';
        $this->dateField = in_array($this->prefix . "_date", $this->fields) ? $this->prefix . "_date" : '';
        $this->memberField = in_array($this->prefix . "_fk_members", $this->fields) ? $this->prefix . "_fk_members" : '';
    }

    /**
     * 
     * @param type $tables : Array of tables to be checked for its existance in database.
     * @return type
     */
    function checkForTables($tables = array())
    {
        $missed = array(); // Variable to store missed tables.

        foreach ($tables as $key => $tbl) {
            $query = $this->db->query("SHOW TABLES LIKE '$key'");

            // If table not found in database.
            if (!$query->row_array())
                $missed[$key] = $tbl;
        }

        return $missed;
    }

    function save($data = null, $id = null, $table = '', $p_key = '')
    {
        if (!$data)
            return FALSE;

        $TABLE = $table ? $table : $this->table;
        $p_key = $p_key ? $p_key : $this->p_key;
        $fields = $table ? $this->db->list_fields($table) : $this->fields;

        foreach ($data as $key => $value) {
            if (array_search($key, $fields) === FALSE) {
                unset($data[$key]);
            }
        }

        if ($id) {
            $this->db->where($p_key, $id);
            if (!$this->db->update($TABLE, $data))
                $id = false;
        } else {

            // Removing primary key on ADD.            
            unset($data[$this->p_key]);

            if ($this->db->insert($TABLE, $data))
                $id = $this->db->insert_id();
            else
                $id = false;
        }

        return $id;
    }

    // function replace($id = '', $data, $id_field = '', $table = '', $where = array())
    // {
    //     $TABLE = $table ? $table : $this->table;
    //     $id_field = $id_field ? $id_field : $this->p_key;
    //     if ($where)
    //         $this->db->where($where);
    //     $this->db->delete($TABLE, array($id_field => $id));
    //     $this->db->insert($TABLE, $data);
    // }

    function delete($id = '', $id_field = '', $table = '', $where = array())
    {
        $TABLE = $table ? $table : $this->table;
        $id_field = $id_field ? $id_field : $this->p_key;

        // Taking data before delete        
        if ($where)
            $this->db->where($where);
        $this->db->where($id_field, $id);
        $deleted = $this->db->get($TABLE)->result_array();

        // Deleting
        if ($where)
            $this->db->where($where);
        $this->db->delete($TABLE, array($id_field => $id));

        return $deleted;
    }

    function delete_where($where, $table = '', $return_deleted = true)
    {
        $TABLE = $table ? $table : $this->table;
        $deleted = array();

        if ($return_deleted) { // Taking data before delete        
            $this->db->where($where);
            $deleted = $this->db->get($TABLE)->result_array();
        }

        // Deleting
        $this->db->where($where);
        $this->db->delete($TABLE);

        return $deleted;
    }


    function get_last_query()
    {
        return $this->db->last_query() . ';';
    }

    /**
     * $reserved: p_key up to $reserved is reserved for only Developers.
     */
    function get_next_id($reserved = 100)
    {
        $this->db->select_max($this->p_key);
        $this->db->where("$this->p_key > ", $reserved);
        $r = $this->db->get($this->table)->row_array();
        $max_id = $r[$this->p_key];

        if (!$max_id)
            return $reserved + 1;
        else
            return $max_id + 1;
    }

    function save_member($data = null, $mbr_id = null)
    {
        if (!$data)
            return FALSE;

        // If the member is not a developer && only when ADD
        if ($data['mbr_fk_member_types'] != 1 && !$mbr_id) {
            $this->load->model('members_mdl', 'members');
            $data['mbr_id'] = $this->members->get_next_id();
        }

        // Saving Member
        $mbr_id = $this->save($data, $mbr_id, 'members', 'mbr_id');

        // Saving Meber Type (Employee,Vehicle,Parties, ect)
        $p_key = $this->get_id_by_member($mbr_id);
        $data[$this->memberField] =  $mbr_id;
        $p_key = $this->save($data, $p_key);

        // Deleting previous Member Category data
        $this->db->delete('member_categories', array('mbrcat_fk_members' => $mbr_id));

        // Saving Member category
        if (isset($data['cat_id'])) {
            if (is_array($data['cat_id'])) {
                foreach ($data['cat_id'] as $cat_id)
                    $this->save(array('mbrcat_fk_members' => $mbr_id, 'mbrcat_fk_categories' => $cat_id), '', 'member_categories', 'mbrcat_id');
            } else
                $this->save(array('mbrcat_fk_members' => $mbr_id, 'mbrcat_fk_categories' => $data['cat_id']), '', 'member_categories', 'mbrcat_id');
        }
        return  $mbr_id;
    }



    /**
     * Returns a single row
     *
     * @author : "Shihabu Rahman K" <shihab@levoirsolution.com>
     * @return int
     * @access public
     */
    function get_row($where = array(), $or_where = array(), $table = '')
    { #		where $or_where	=	array(	'EMPCAT_ID'=>array(16,11),'EMP_STATUS'=>array(11,12))
        #							Produce a query; OR EMPCAT_ID=16 OR EMPCAT_ID=11 OR EMP_STATUS=11 OR EMP_STATUS=12
        #		OR	$or_where	=	array('EMPCAT_ID'=>16);
        $TABLE = $table ? $table : $this->table;

        if ($where)
            $this->db->where($where);
        if ($or_where)
            foreach ($or_where as $field => $fieldset) {
                if (is_array($fieldset)) {
                    foreach ($fieldset as $value)
                        $this->db->or_where($field, $value);
                } else
                    $this->db->or_where($field, $fieldset);
            }
        $result = $this->db->get($TABLE);

        return $result->row_array();
    }

    function get_data($where = array(), $table = '', $select = array(), $order_by = '')
    {
        $TABLE = $table ? $table : $this->table;
        $ORDER_BY = $order_by ? $order_by : $this->nameField;

        if ($select)
            $this->db->select($select);
        if ($where)
            $this->db->where($where);
        if ($ORDER_BY) {
            if (is_array($ORDER_BY)) {
                foreach ($ORDER_BY as $ody)
                    $this->db->order_by($ody, "asc");
            } else
                $this->db->order_by($ORDER_BY, "asc");
        }

        return $this->db->get($TABLE)->result_array();
    }

    function get_ids($where = '', $table = '', $p_key = '')
    {
        $P_KEY = $p_key ? $p_key : $this->p_key;
        if (!$P_KEY)
            return array();

        $data = $this->get_data($where, $table, $P_KEY);
        $arr = array();

        foreach ($data as $val)
            $arr[] = $val[$P_KEY];
        return $arr;
    }

    // Returns multiple ids.
    function update_where($data, $where = '', $table = '')
    {
        $TABLE = $table ? $table : $this->table;
        $this->db->update($TABLE, $data, $where);
        // echo_div($this->db->last_query());

        // Return all primary keys if existing one.
        return $this->get_ids($where);
    }

    function remove($id = null, $table = '')
    {
        $TABLE = $table ? $table : $this->table;

        if ($id) {
            if ($this->db->delete($TABLE, array($this->p_key => $id)))
                return true;
        } else {
            if ($this->db->delete($TABLE))
                return true;
        }

        return FALSE;
    }

    /**
     * Returns option list with the value of 'status' field = "Active"
     */
    function get_active_option($where = array(), $table = '', $key = '', $field = '', $status_field = '')
    {
        if (!$this->statusField && !$status_field)
            return;

        $TABLE = $table ? $table : $this->table;
        $key = $key ? $key : $this->p_key;
        $field = $field ? $field : $this->nameField;
        $this->statusField = $status_field ? $status_field : $this->statusField;
        $where[$this->statusField] = ACTIVE;
        $select = array($key, $field);
        $options = $this->get_data($where, $TABLE, $select);

        $option = array();
        foreach ($options as $row) {
            if ($field == '*')
                $option[$row[$key]] = $row;
            else if (is_array($field)) {
                $value_array = array();
                foreach ($field as $value)
                    $value_array[$value] = $row[$value];
                $option[$row[$key]] = $value_array;
            } else
                $option[$row[$key]] = $row[$field];
        }
        return $option;
    }

    /**
     * Returns option list with the value of 'status' field = "Active"
     */
    function get_options($where = array(), $table = '', $key = '', $field = '')
    {
        $TABLE = $table ? $table : $this->table;
        $key = $key ? $key : $this->p_key;
        $field = $field ? $field : $this->nameField;
        $select = array($key, $field);
        $options = $this->get_data($where, $TABLE, $select);

        $option = array();
        foreach ($options as $row) {
            if ($field == '*')
                $option[$row[$key]] = $row;
            else if (is_array($field)) {
                $value_array = array();
                foreach ($field as $value)
                    $value_array[$value] = $row[$value];
                $option[$row[$key]] = $value_array;
            } else
                $option[$row[$key]] = $row[$field];
        }
        return $option;
    }

    function get_member_type_id()
    {
        $mbr_type_id = $this->get_id_by_field('mbrtp_name', $this->table, 'member_types', 'mbrtp_id');
        return $mbr_type_id;
    }

    /**
     * Returns option list with the value of 'status' field = "Active"
     */
    function get_members_option($where = array(), $status = ACTIVE, $mbr_type_id = '')
    {
        $mbr_type_id = $mbr_type_id ? $mbr_type_id : $this->get_member_type_id();
        $this->db->from("members");
        $this->db->select('mbr_id,mbr_name');

        if ($where)
            $this->db->where($where);

        $this->db->where('mbr_status', $status);
        $this->db->where('mbr_fk_member_types',  $mbr_type_id);
        $this->db->order_by('mbr_name');
        $r = $this->db->get()->result_array();
        $option = array();
        foreach ($r as $row)
            $option[$row['mbr_id']] = $row['mbr_name'];
        return $option;
    }

    /**
     * Returns option list with the value of 'status' field = "Active"
     */
    function get_categories_option($where = array(), $status = ACTIVE)
    {
        $mbr_type_id = $this->get_member_type_id();

        $this->db->from("categories");
        $this->db->select('cat_id,cat_name');

        if ($where)
            $this->db->where($where);

        $this->db->where('cat_status', $status);
        $this->db->where('cat_fk_member_types',  $mbr_type_id);
        $this->db->order_by('cat_name');
        $r = $this->db->get()->result_array();
        $option = array();
        foreach ($r as $row)
            $option[$row['cat_id']] = $row['cat_name'];
        return $option;
    }

    function get_id_by_member($mbr_id)
    {
        $this->db->select($this->p_key);
        $this->db->where($this->memberField, $mbr_id);
        $r = $this->db->get($this->table)->row_array();
        // echo "<br>Mem:" . $mbr_id . "<br>";
        // echo $this->get_last_query();
        // echo "<br>AAA:";
        // print_r($r);
        return isset($r[$this->p_key]) ? $r[$this->p_key] : '';
    }


    function array_query($input, $field)
    {
        if (!is_array($input))
            return '';
        $i = 0;
        $str = "($field = '";
        foreach ($input as $status) {
            $str .= $status;
            if ($i == (count($input) - 1))
                $str .= "')";
            else
                $str .= "' OR $field = '";
            $i++;
        }
        return $str;
    }


    /**
     * 
     * @param type $id = id value
     * @param type $field= name of the id field.
     * @param type $table = name of the table.
     * @return type
     */
    function get_by_id($id, $field = '', $table = '', $select = '')
    {
        $data = array();

        if (!$id)
            return $data;

        $TABLE = $table ? $table : $this->table;
        $FIELD = $field ? $field : $this->p_key;

        if ($select)
            $this->db->select($select);

        if (is_array($id)) {
            $where = $this->array_query($id, $FIELD);
            $this->db->where($where);
            $data = $this->db->get($TABLE)->result_array();
        } else {
            $this->db->where($FIELD, $id);
            $data = $this->db->get($TABLE)->row_array();
        }

        return $data;
    }


    /**
     * get_by_member_id: Data collect from both tables
     *
     * @param  mixed $mbr_id
     * @return void
     */
    function get_by_member_id($mbr_id)
    {
        $this->db->from("$this->table, members");
        $this->db->where('mbr_id', $mbr_id);
        $this->db->where($this->memberField, $mbr_id);
        $r = $this->db->get()->row_array();
        if ($r) {
            $this->load->model('members_mdl', 'members');

            // Don't change the array key 'cat_id'.
            $r['cat_id'] = $this->members->get_categories($mbr_id, ACTIVE, 'id');
        }
        return $r;
    }


    /**
     * get_by_member_id2: Data collects only from Tbl: members
     *
     * @param  mixed $mbr_id
     * @return void
     */
    function get_by_member_id2($mbr_id)
    {
        $this->db->from("members");
        $this->db->where('mbr_id', $mbr_id);
        $r = $this->db->get()->row_array();
        return $r;
    }

    /**
     * $field:  1: Array like array('emply_name','emply_addr')
     *          2: A single field name like 'emply_name'
     */
    function get_field_by_id($id, $field, $table = '')
    {
        $data = $this->get_by_id($id, '', $table, $field);

        if (is_array($field)) {
            return $data;
        } else {
            if (isset($data[$field]))
                return $data[$field];
        }
        return '';
    }

    function get_id_by_field($field = '', $val = '', $table = '', $id_field = '')
    {
        $table = $table ?  $table : $this->table;
        $id_field = $id_field ?  $id_field : $this->p_key;
        $this->db->select($id_field);
        $this->db->where($field, $val);
        $row = $this->db->get($table)->row_array();
        return $row[$id_field];
    }


    /**
     * get_field_by_field: Geting a field value by a given field and its value
     *
     * @param  mixed $getFieldName      :   Name of the Field to get the value. Eg: 'bls_name'
     * @param  mixed $givenFieldName    :   Given field name
     * @param  mixed $givenFieldVal     :   Value of the given field
     * @param  mixed $table
     * @return void
     */
    function get_field_by_field($getFieldName, $givenFieldName, $givenFieldVal = '', $table = '')
    {
        $table = $table ?  $table : $this->table;
        $this->db->where($givenFieldName, $givenFieldVal);
        $this->db->select($getFieldName);
        $row = $this->db->get($table)->row_array();
        return $row[$getFieldName];
    }

    function get_ids_from_option($option)
    {
        $ids = array();

        if (!$option || !is_array($option))
            return $ids;

        foreach ($option as $id => $text)
            $ids[] = $id;

        return $ids;
    }

    /**
     * Function returns the value of the NAME Field by recieving the primary key value.
     * @param type $id 
     * @return type
     */
    function get_name_by_id($id)
    {
        $name = '';
        //echo "Namefield: " . $this->nameField . " Id: " . $id;
        if ($this->nameField && $id) {
            $row = $this->get_by_id($id);
            if (isset($row[$this->nameField]))
                $name = $row[$this->nameField];
        }
        //$this->get_last_query();
        return $name;
    }

    // Change the function name as makeOptionFromQueryResult
    function make_option($data, $key_field = '', $value_field = '')
    {
        if (!is_array($data))
            return array();
        $KEY = $key_field ?: $this->p_key;
        $VAL = $value_field ?: $this->nameField;
        $option = array();

        foreach ($data as $row)
            $option[$row[$KEY]] = $row[$VAL];

        return $option;
    }

    /**
     * 
     * @param type $result      : A query result as follows;
     *                              array(
     *                                      [0] => array('emply_id' = 1, 'emply_name' = 'Shihab');
     *                                      [1] => array('emply_id' = 2, 'emply_name' = 'Mujeeb');
     *                                      [2] => array('emply_id' = 3, 'emply_name' = 'Sameer');
     *                              );
     * @param type $id_field    : Name of the id field, But you can give any field rather id field.
     *                             By default the primary key field will be selected.
     * @return array            : Return array of $id_field values. For eg:-
     *                                  if the $id_field = 'emply_id', then return array(1,2,3)
     */
    function get_ids_from_query_result($result, $id_field = '')
    {
        if (!$result || !is_array($result))
            return array();

        $P_KEY = $id_field ? $id_field : $this->p_key;
        $ids = array();

        foreach ($result as $row)
            if (isset($row[$P_KEY]))
                $ids[] = $row[$P_KEY];

        return $ids;
    }

    function reindex_query_result_by_id($qr, $id_field = '')
    {
        if (!$qr || !is_array($qr))
            return array();

        $P_KEY = $id_field ? $id_field : $this->p_key;
        $new_array = array();

        foreach ($qr as $row)
            if (isset($row[$P_KEY]))
                $new_array[$row[$P_KEY]] = $row;

        return $new_array;
    }

    /**
     * 
     * @param type $unique  :  Conditions to check.
     * @param type $id      :  Primary key value. The search result will not contain the row that contains the given primary key value. 
     * @param type $return  :  Return format. Possible values are 'id','row'.
     *                            'id' : if the searched data found, returns the primary key value. Else return ''.
     *                            'row': if the searched data found, return it.
     *                            'bool': TRUE or FALSE.  
     * @param type $table
     * @return string
     */
    function is_exist($unique, $id = '', $return = 'bool', $table = '')
    {
        $TABLE = $table ? $table : $this->table;
        if ($id)
            $this->db->where("$this->p_key !=", $id);
        $this->db->where($unique);

        $result = $this->db->get($TABLE);
        $result = $result->row_array();

        if ($return == 'bool') {
            if ($result) return TRUE;
            else return FALSE;
        } else if ($return == 'id') {
            if (isset($result[$this->p_key]))
                return $result[$this->p_key];
            else
                return '';
        } else if ($return == 'row')
            return $result;
    }

    function activate_member($id)
    {
        $this->db->update('members', array('mbr_status' => ACTIVE), "mbr_id = $id");

        if ($this->statusField) {
            $p_key =  $this->get_id_by_member($id);
            $this->db->update($this->table, array($this->statusField => ACTIVE), "$this->p_key = $p_key");
        }
        return true;
    }

    function deactivate_member($id)
    {
        $this->db->update('members', array('mbr_status' => INACTIVE), "mbr_id = $id");

        if ($this->statusField) {
            $p_key =  $this->get_id_by_member($id);
            $this->db->update($this->table, array($this->statusField => INACTIVE), "$this->p_key = $p_key");
        }
        return true;
    }

    function activate($id)
    {
        if (!$this->statusField || !$id)
            return false;
        $this->db->update($this->table, array($this->statusField => ACTIVE), "$this->p_key = $id");
        return true;
    }

    function deactivate($id)
    {
        if (!$this->statusField || !$id)
            return false;
        $this->db->update($this->table, array($this->statusField => INACTIVE), "$this->p_key = $id");
        return true;
    }

    function deactivate_where($where)
    {
        if (!$this->statusField || !$where)
            return false;
        $this->db->update($this->table, array($this->statusField => INACTIVE), $where);
        return true;
    }

    function activate_where($where)
    {
        if (!$this->statusField || !$where)
            return false;
        $this->db->update($this->table, array($this->statusField => ACTIVE), $where);
        return true;
    }
}
