<?php
defined('BASEPATH') or exit('No direct script access allowed');
function createTables($tables = '')
{
    $tables = $tables ? $tables : getTables('All');
    $INDEX = array();
    $query = '';


    //print_r($tables);
    foreach ($tables as $ref => $tbl) {
        echo "<h3 style='padding:10px 5px; background-color:#359D69; color:#fff; text-align:left;'>Table : " . $ref . "</h3>";
        $flag = 1;
        $func_name = 'getFields_' . $ref;
        $fields = $func_name(true, 'DB Creation');

        foreach ($fields as $fld => $description) {
            if ($fld == 'INDEX') {
                $INDEX[$tbl] = $description;
                continue; //break;
            }
            if ($flag)
                $query = "CREATE TABLE IF NOT EXISTS " . $ref . " (";
            else
                $query .= ', ';
            $query .= $fld . ' ' . $description[0] . ' ';
            if ($description[1])
                $query .= '(' . $description[1] . ') ';
            if ($description[2])
                $query .= $description[2];
            $flag = 0;
        }

        $query .= " );";

        $CI = &get_instance(); // IF you'r not connected with Database or not load any 'model file' may cause error on this line !
        $CI->db->query($query);

        echo $query;
    }

    //Setting Foreign Keys
    if ($INDEX) {
        foreach ($INDEX as $tbl => $rows)
            foreach ($rows as $row) {
                $query = "ALTER TABLE $tbl ADD CONSTRAINT FKey_$tbl" . "_$row[f_key] FOREIGN KEY ($row[f_key]) "
                    . "REFERENCES $row[ref_tbl] ($row[ref_fld]) ON DELETE $row[DELETE] ON UPDATE $row[UPDATE];\n ";

                $CI->db->query($query);

                echo "<br><br>" . $query;
            }
    }
}


function getTables($table = 'All')
{

    $tbl['clients']['title'] = 'client';
    $tbl['clients']['drop_reset'] = FALSE;


    $tbl['members']['title'] = 'Members';
    $tbl['members']['drop_reset'] = FALSE;

    $tbl['member_categories']['title'] = 'Member Categories';
    $tbl['member_categories']['drop_reset'] = FALSE;

    $tbl['categories']['title'] = 'Categories';
    $tbl['categories']['drop_reset'] = FALSE;

    $tbl['member_types']['title'] = 'Member Types';
    $tbl['member_types']['drop_reset'] = FALSE;

    // A Member
    $tbl['employees']['title'] = 'Employees';
    $tbl['employees']['drop_reset'] = FALSE;

    // A Member
    $tbl['developers']['title'] = 'Developers';
    $tbl['developers']['drop_reset'] = FALSE;

    $tbl['users']['title'] = 'Users';
    $tbl['users']['drop_reset'] = FALSE;

    // For Tbl: users
    // Here we used 'groups1' instead of groups is because 'groups' is a 'Reserved Word' in MySql.
    $tbl['groups1']['title'] = 'Groups';
    $tbl['groups1']['drop_reset'] = FALSE;

    $tbl['user_groups']['title'] = 'User Groups';
    $tbl['user_groups']['drop_reset'] = FALSE;

    $tbl['session']['title'] = 'Session';
    $tbl['session']['drop_reset'] = FALSE;

    $tbl['remember_me']['title'] = 'remember_me';
    $tbl['remember_me']['drop_reset'] = FALSE;

    $tbl['tasks']['title'] = 'Tasks';
    $tbl['tasks']['drop_reset'] = FALSE;

    $tbl['user_tasks']['title'] = 'User Tasks';
    $tbl['user_tasks']['drop_reset'] = FALSE;

    $tbl['settings']['title'] = 'Settings';
    $tbl['settings']['drop_reset'] = FALSE;

    $tbl['client_settings']['title'] = 'Client Settings';
    $tbl['client_settings']['drop_reset'] = FALSE;

    $tbl['settings_categories']['title'] = 'Settings Categories';
    $tbl['settings_categories']['drop_reset'] = FALSE;

    $tbl['settings_keys']['title'] = 'Settings Keys';
    $tbl['settings_keys']['drop_reset'] = FALSE;

    $tbl['versions']['title'] = 'Versions';
    $tbl['versions']['drop_reset'] = FALSE;

    $tbl['states']['title'] = 'State';
    $tbl['states']['drop_reset'] = FALSE;

    $tbl['districts']['title'] = 'District';
    $tbl['districts']['drop_reset'] = FALSE;

    $tbl['taluks']['title'] = 'Taluk';
    $tbl['taluks']['drop_reset'] = FALSE;

    $tbl['areas']['title'] = 'Areas';
    $tbl['areas']['drop_reset'] = FALSE;

    $tbl['wards']['title'] = 'Wards';
    $tbl['wards']['drop_reset'] = FALSE;

    $tbl['families']['title'] = 'Family';
    $tbl['families']['drop_reset'] = FALSE;

    $tbl['family_members']['title'] = 'Family Members';
    $tbl['family_members']['drop_reset'] = FALSE;

    $tbl['central_stores']['title'] = 'Central Store';
    $tbl['central_stores']['drop_reset'] = FALSE;

    $tbl['estores']['title'] = 'Estore';
    $tbl['estores']['drop_reset'] = FALSE;

    $tbl['user_central_stores']['title'] = "User's Central Store";
    $tbl['user_central_stores']['drop_reset'] = FALSE;

    // PRODUCTS STARTED
    $tbl['products']['title'] = 'Products';
    $tbl['products']['drop_reset'] = FALSE;

    $tbl['tags']['title'] = 'Tags';
    $tbl['tags']['drop_reset'] = FALSE;

    $tbl['product_tags']['title'] = 'Product Tags';
    $tbl['product_tags']['drop_reset'] = FALSE;

    $tbl['product_photos']['title'] = 'Product Photos';
    $tbl['product_photos']['drop_reset'] = FALSE;

    $tbl['product_categories']['title'] = 'Product Categories';
    $tbl['product_categories']['drop_reset'] = FALSE;

    // $tbl['product_varients']['title'] = 'Product Varients';
    // $tbl['product_varients']['drop_reset'] = FALSE;

    $tbl['product_batches']['title'] = 'Product Batches';
    $tbl['product_batches']['drop_reset'] = FALSE;

    $tbl['companies']['title'] = 'Companies';
    $tbl['companies']['drop_reset'] = FALSE;

    $tbl['brands']['title'] = 'Brands';
    $tbl['brands']['drop_reset'] = FALSE;

    $tbl['parties']['title'] = 'Parties';
    $tbl['parties']['drop_reset'] = FALSE;

    $tbl['gstnumbers']['title'] = 'GST Numbers';
    $tbl['gstnumbers']['drop_reset'] = FALSE;


    $tbl['godowns']['title'] = 'Godowns';
    $tbl['godowns']['drop_reset'] = FALSE;

    $tbl['units']['title'] = 'Units';
    $tbl['units']['drop_reset'] = FALSE;

    $tbl['unit_groups']['title'] = 'Unit Groups';
    $tbl['unit_groups']['drop_reset'] = FALSE;

    $tbl['product_units']['title'] = 'Product Units';
    $tbl['product_units']['drop_reset'] = FALSE;

    $tbl['hsn_details']['title'] = 'HSN Details';
    $tbl['hsn_details']['drop_reset'] = FALSE;

    $tbl['bill_types']['title'] = 'Bill Types';
    $tbl['bill_types']['drop_reset'] = FALSE;

    $tbl['bill_batches']['title'] = 'Bill Batches';
    $tbl['bill_batches']['drop_reset'] = TRUE;

    $tbl['bill_nos']['title'] = 'Bill Numbers';
    $tbl['bill_nos']['drop_reset'] = TRUE;

    $tbl['bills']['title'] = 'Bill';
    $tbl['bills']['drop_reset'] = TRUE;

    $tbl['bill_products']['title'] = 'Bill Products';
    $tbl['bill_products']['drop_reset'] = TRUE;

    $tbl['order_status']['title'] = 'Order Status';
    $tbl['order_status']['drop_reset'] = TRUE;

    $tbl['stock_avg']['title'] = 'Average Stock'; // Average Stock Calculation
    $tbl['stock_avg']['drop_reset'] = TRUE;

    $tbl['price_groups']['title'] = 'Price Groups'; // Average Stock Calculation
    $tbl['price_groups']['drop_reset'] = FALSE;

    $tbl['price_groups_products']['title'] = 'Product Price Groups'; // Average Stock Calculation
    $tbl['price_groups_products']['drop_reset'] = FALSE;

    $tbl['price_group_locations']['title'] = 'Price Group Locations'; // Average Stock Calculation
    $tbl['price_group_locations']['drop_reset'] = FALSE;


    if ($table == 'All')
        return $tbl;
    return $tbl[$table];
}

function getFields($table, $id = true)
{
    $func_name = 'getFields_' . $table;
    $fields = array_keys($func_name($id));
    return $fields;
}

/* * Including validation field names inside the settings[] array. because the form elements are declared as arrays 
 * 
 * @param type $table :         Name of the table.
 * 
 * @param type $validate_on :   [optional] Current action(add/edit/delete). 
 *                              if we want to applay unique/different validation for different actions (add/edit/delete). 
 * 
 * @param type $container:      [optional] The name of the container array.
 *                              If the form elements are declared as arrays, this parameter holds the name of the container array.
 *                              Eg: if the name of the form element is as <input name="workcentre['wcntr_name']" > 
 *                                  you should use the string 'workcentre' as the $container.
 * @return type
 */

function validationConfigs($table, $container = '')
{
    $config = array();
    $func_name = 'getFields_' . $table;
    $fields = $func_name();
    foreach ($fields as $key => $val) {
        $rules = $val[4];

        $config[] = array('field' => $key, 'label' => $val[3], 'rules' => $rules);
    }
    if ($container) {
        // Including validation field names inside the $container array. (In the case when the form elements are declared as arrays)
        foreach ($config as &$value)
            $value['field'] = $container . '[' . $value['field'] . ']';
    }

    return $config;
}

/**
 * Returuns the validation errors related to each field seperately.
 * Usage: See beds/save & beds/transfer
 * 
 * @param type $table
 * @param type $xvld_flds  :  Extra Validation Fields Or On the spot added validation feilds. 
 *                            Ie: the fields those are not in getFields_(function name) function.
 *                            But they are added inside the controller/function just before validation.
 * @return type
 */
function get_val_errors($table = '', $xvld_flds = array())
{
    $data = array();

    if ($table) {
        if (is_array($table)) {
            foreach ($table as $tbl) {
                $flds = getFields($tbl, true);

                foreach ($flds as $fld)
                    $data[$fld] = form_error($fld);
            }
        } else {
            $flds = getFields($table, true);

            foreach ($flds as $fld)
                $data[$fld] = form_error($fld);
        }
    }

    // Validating Extra validation fields:
    foreach ($xvld_flds as $fld)
        $data[$fld] = form_error($fld);

    return $data;
}
