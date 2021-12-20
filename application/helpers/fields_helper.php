<?php
defined('BASEPATH') or exit('No direct script access allowed');
function getFields_clients($id = true, $action = '')
{
    if ($id)
        $field['clnt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['clnt_date'] = array('date', '', '', 'Date', 'required|callback_valid_date|callback_db_query');
    $field['clnt_name'] = array('varchar', '50', '', 'Name', 'required|callback_db_query|max_length[50]');
    $field['clnt_print_name'] = array('varchar', '50', '', 'Name', 'required|callback_db_query|max_length[50]');
    $field['clnt_address'] = array('varchar', '200', '', 'Address', 'required|max_length[200]|callback_db_query');
    $field['clnt_phone'] = array('varchar', '50', '', 'Phone', 'required|callback_db_query|max_length[50]');
    $field['clnt_email'] = array('varchar', '50', '', 'Email', 'required|callback_db_query|max_length[50]|valid_email');
    $field['clnt_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}


function getFields_member_types($id = true, $action = '')
{
    if ($id)
        $field['mbrtp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    // Table name of members, Eg: employees, vehicles, family_members, etc   
    $field['mbrtp_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query');

    // Title, Eg: EMPLOYEES, VEHICLES, FAMILY MEMBERS, etc
    $field['mbrtp_title'] = array('varchar', '20', '', 'Name', 'required|callback_db_query');
    $field['mbrtp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');    //array(1 => 'Active',2 => 'Inactive');

    return $field;
}

function getFields_members($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['mbr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['mbr_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['mbr_fk_member_types'] = array('int', '11', 'unsigned NOT NULL', 'Member Type', 'callback_db_query|callback_required');
    $field['mbr_name'] = array('varchar', '100', '', 'Name', 'required|callback_db_query|max_length[100]');   //array(1 => 'Active',2 => 'Inactive');
    $field['mbr_address'] = array('varchar', '200', '', 'Address', 'required|max_length[200]|callback_db_query');
    $field['mbr_date'] = array('date', '', '', 'Date', 'required|callback_valid_date|callback_db_query');
    $field['mbr_ob'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'OB', 'numeric|callback_db_query');
    $field['mbr_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_member_categories($id = true, $action = '')
{
    if ($id)
        $field['mbrcat_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['mbrcat_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');
    $field['mbrcat_fk_categories'] = array('int', '11', 'unsigned NOT NULL', 'Category', 'callback_required|callback_db_query');

    return $field;
}

function getFields_categories($id = true, $action = '')
{
    if ($id)
        $field['cat_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['cat_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['cat_fk_member_types'] = array('int', '11', 'unsigned NOT NULL', 'Member Type', 'callback_required|callback_db_query');

    // For Employees:Staff, Admin, Accountant, etc
    // For Parties: Suppliers, Customers, ect     
    // For Vehicles: Ours, Others, ect
    $field['cat_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query');

    $field['cat_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');    //array(1 => 'Active',2 => 'Inactive');

    return $field;
}

function getFields_employees($id = true, $action = '')
{
    if ($id)
        $field['emply_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['emply_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['emply_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');

    // 1 => Master Admin, 2 => Other non admins
    // Nobody can deactivate him.
    $field['emply_is_admin'] = array('tinyint', '1', 'NOT NULL DEFAULT 2', 'Is Master Admin', 'callback_db_query');

    // Commaseperated list of values returned by common_helper/get_wage_options()
    $field['emply_wage_option'] = array('varchar', '100', 'NULL DEFAULT NULL', 'Wage Option', 'callback_db_query');

    $field['emply_daily'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Daily', 'numeric|callback_db_query');
    $field['emply_ot'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'OT', 'numeric|callback_db_query');
    $field['emply_salary'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Salary', 'numeric|callback_db_query');

    return $field;
}

function getFields_developers($id = true, $action = '')
{
    if ($id)
        $field['dpr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['dpr_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');

    return $field;
}



function getFields_tasks($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['tsk_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['tsk_name'] = array('varchar', '70', 'NOT NULL', 'Name', 'required|max_length[70]');
    $field['tsk_url'] = array('varchar', '100', 'NULL DEFAULT NULL', 'Name', 'callback_db_query');
    $field['tsk_key'] = array('varchar', '40', 'NULL DEFAULT NULL', 'Key', 'max_length[70]|callback_db_query');
    $field['tsk_menu'] = array('tinyint', '1', 'NOT NULL', 'Is Menu', 'callback_db_query'); // 1 => Menu, 2=> Not Menu
    $field['tsk_parent'] = array('int', '11', 'NOT NULL DEFAULT 0', 'Parent', 'required|callback_db_query');
    $field['tsk_sort'] = array('int', '11', 'NOT NULL', 'Sort Order', 'callback_db_query');
    $field['tsk_icon'] = array('varchar', '70', 'NULL DEFAULT NULL', 'Icon', 'callback_db_query');
    $field['tsk_color'] = array('varchar', '70', 'NULL', 'Icon Color', 'callback_db_query');

    // Primary Color in the case of Duotone icons
    $field['tsk_primary'] = array('varchar', '70', 'NULL', 'Primary Color', 'callback_db_query');

    // Secondary Color in the case of Duotone icons
    $field['tsk_secondary'] = array('varchar', '70', 'NULL', 'Secondary Color', 'callback_db_query');


    $field['tsk_type'] = array('tinyint', '1', 'NOT NULL', 'Task Type', 'callback_db_query'); // 1 => Only For Developer, 2=> For All
    $field['tsk_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_user_tasks($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['utsk_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['utsk_fk_groups'] = array('int', '11', 'unsigned NOT NULL', 'Group', 'callback_required|callback_db_query');
    $field['utsk_fk_tasks'] = array('int', '11', 'unsigned NOT NULL', 'Tasks', 'callback_required|callback_db_query');

    return $field;
}


function getFields_groups1($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['grp_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['grp_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['grp_name'] = array('varchar', '50', 'NOT NULL', 'Name', 'required|max_length[50]|callback_db_query');

    // 1 => Developer (The value of users::usr_type should be 1)
    // 2 => Master Admin (The value of users::usr_type should be 2)
    // 3 => All others (The value of users::usr_type should be 3)
    // 
    //$field['grp_type'] = array('tinyint', '1', 'NOT NULL DEFAULT 2', 'Predefined', 'callback_db_query'); // 1 => Inbuilt, 2 => User Defined

    $field['grp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_users($id = true, $action = '', $validation = '')
{
    // USer id 1-100 is reserved for Developers
    if ($id)
        $field['usr_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT ', '', 'callback_db_query');

    $field['usr_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['usr_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Employee', 'callback_db_query');

    // 1 => Only For Developer, 2 => Only for Default Admins, 3=> For other users
    // Its value can't be edited by user.
    // User can't be use its value 1 or 2, Because it should be set by Developer.
    $field['usr_type'] = array('tinyint', '1', 'NOT NULL', 'User Type', '');

    $field['usr_username'] = array('varchar', '50', 'NULL', 'Username', 'required|callback_db_query|max_length[50]|callback_check_unique2[usr_username]');
    $field['usr_email'] = array('varchar', '50', 'NULL', 'Email', 'required|callback_db_query|max_length[50]|valid_email|callback_check_unique2[usr_email]');
    $field['usr_mob'] = array('varchar', '10', 'NULL', 'Mobile', 'required|numeric|callback_db_query|max_length[10]|min_length[10]|callback_check_unique2[usr_mob]');
    $field['usr_password'] = array('varchar', '300', 'NULL', 'Password', 'callback_db_query|min_length[8]');
    $field['usr_otp'] = array('varchar', '15', 'NULL', 'OTP', 'callback_db_query');
    $field['usr_date'] = array('date', '', '', 'Date', ''); // Date of creation.
    $field['usr_attempt'] = array('tinyint', '4', " NOT NULL  DEFAULT '0'", 'Attempts', 'callback_db_query');
    $field['usr_attempt_round'] = array('tinyint', '4', " NOT NULL  DEFAULT '1'", 'Attempt Round', 'callback_db_query');
    $field['usr_lock'] = array('datetime', '', 'NULL DEFAULT NULL', 'Lock', 'callback_db_query'); // Lock user upto usr_lock (Date).
    $field['usr_status'] = array('tinyint', '1', " NOT NULL DEFAULT '1'", 'Status', 'callback_db_query'); // 1=> Active, 2=> Inactive
    return $field;
}

function getFields_user_groups($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['ugrp_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['ugrp_fk_users'] = array('int', '11', 'unsigned NOT NULL', 'User', 'callback_required|callback_db_query');
    $field['ugrp_fk_groups'] = array('int', '11', 'unsigned NOT NULL', 'Group', 'callback_required|callback_db_query');

    return $field;
}

function getFields_session($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['ssn_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['ssn_name'] = array('varchar', '500', 'NOT NULL', 'Name', 'callback_db_query');
    $field['ssn_fk_users'] = array('int', '11', 'unsigned NOT NULL', 'User', 'callback_db_query');  // Logged in USER.
    $field['ssn_start'] = array('datetime', '', '', 'Start', 'callback_db_query'); // Date of creation.
    $field['ssn_expire '] = array('datetime', '', '', 'Expire', 'callback_db_query'); // Date of creation.
    $field['ssn_ip'] = array('varchar', '70', 'NULL', 'Name', 'max_length[70]|callback_db_query');
    $field['ssn_devise'] = array('varchar', '70', 'NULL', 'Devise', 'max_length[70]|callback_db_query');
    $field['ssn_mac'] = array('varchar', '70', 'NULL', 'Mac', 'max_length[70]|callback_db_query');
    $field['ssn_last_updated'] = array('datetime', '', '', 'Last Updated', 'callback_db_query'); // Date of creation.

    return $field;
}

function getFields_remember_me($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['rmbr_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['rmbr_fk_users'] = array('int', '11', 'unsigned NOT NULL', 'User', 'callback_db_query');  // Logged in USER.
    $field['rmbr_name'] = array('varchar', '50', 'NOT NULL', 'Name', 'callback_db_query');
    $field['rmbr_cookie '] = array('varchar', '500', 'NULL', 'Remember Me', 'callback_db_query');
    $field['rmbr_last_updated'] = array('datetime', '', '', 'Last Updated', 'callback_db_query'); // Date of creation.

    return $field;
}

function getFields_settings($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['st_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['st_date'] = array('date', '', '', 'Date', 'callback_db_query');
    $field['st_name'] = array('varchar', '100', 'NOT NULL', 'Name', 'required|max_length[100]');
    $field['st_desc'] = array('varchar', '500', 'NULL', 'Description', 'required');

    // It may NULL. (For Eg: in the case of General settings)
    $field['st_ref_tbl'] = array('varchar', '150', 'NULL DEFAULT NULL', 'Reference Table', 'callback_db_query');

    // Sale Bill: Tax
    // Sale Bill: Estimate
    // Sale Bill: Tax Print
    // Sale Bill: Estimate Print
    $field['st_fk_settings_categories'] = array('int', '11', 'unsigned NOT NULL', 'Category', 'callback_required|callback_db_query');
    $field['st_fk_settings_keys'] = array('int', '11', 'unsigned NOT NULL', 'Key', 'callback_required|callback_db_query');

    // 1 => Textbox
    // 2 => Dropdown
    // 3 => Radio
    // 4 => Checkbox
    // 5 => Textarea
    $field['st_input'] = array('tinyint', '1', 'NOT NULL', 'Input Type', 'required|callback_db_query');

    // Serialized array of possible values. 
    // array-key represents the "Value" and array-value represents the "Text" related to the "Value"
    $field['st_pval'] = array('text', '', 'NULL', 'Possible Values', 'callback_db_query');

    $field['st_dval'] = array('varchar', '200', 'NULL', 'Default Value', 'required|max_length[200]|callback_db_query');


    // Default User Type
    // 1 => For All Users
    // 2=> Admins Only
    $field['st_dusertype'] = array('tinyint', '1', 'NOT NULL', 'Default User Type', 'required|callback_db_query');
    $field['st_sort'] = array('int', '11', 'NULL', 'Sort Order', 'callback_db_query');

    $field['st_fk_versions'] = array('int', '11', 'unsigned NOT NULL', 'Version', 'callback_db_query');

    $field['st_validation'] = array('varchar', '200', 'NULL', 'Validation', 'max_length[200]|callback_db_query');
    $field['st_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query'); // 1 => Active, 2  => Inactive
    return $field;
}

function getFields_client_settings($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['cst_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['cst_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_required|callback_db_query');
    $field['cst_fk_settings'] = array('int', '11', 'unsigned NOT NULL', 'Settings', 'callback_required|callback_db_query');
    $field['cst_val'] = array('varchar', '200', 'NULL', 'Value', 'required|max_length[200]|callback_db_query');

    // User Type
    // 1 => For All Users
    // 2=> Admins Only
    $field['cst_usertype'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'User Type', 'required|callback_db_query');

    return $field;
}

function getFields_settings_categories($id = true, $action = '')
{
    if ($id)
        $field['stct_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');

    // Sale Bill: General Settings
    // Sale Bill: Tax
    // Sale Bill: Estimate
    // Sale Bill: Tax Print
    // Sale Bill: Estimate Print
    $field['stct_name'] = array('varchar', '100', '', 'Name', 'required|callback_db_query|max_length[100]');
    $field['stct_sort'] = array('int', '11', 'NULL', 'Sort Order', 'callback_db_query');
    $field['stct_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');    //array(1 => 'Active',2 => 'Inactive');

    return $field;
}

function getFields_settings_keys($id = true, $action = '')
{
    if ($id)
        $field['stky_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');

    // Sale Bill: PRINT
    // Sale Bill: TAX_PRINT
    // Sale Bill: TAX_GPS
    $field['stky_name'] = array('varchar', '100', '', 'Name', 'required|callback_db_query|max_length[100]|callback_isUnique[stky_id||stky_name]');
    $field['stky_desc'] = array('varchar', '200', '', 'Description', 'required|callback_db_query|max_length[200]');
    $field['stky_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');    //array(1 => 'Active',2 => 'Inactive');

    return $field;
}

function getFields_versions($id = true, $action = '')
{
    if ($id)
        $field['v_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');

    $field['v_date'] = array('date', '', '', 'Date', 'required|callback_valid_date|callback_db_query');
    $field['v_name'] = array('varchar', '6', '', 'Name', 'required|callback_db_query|max_length[6]');

    return $field;
}



function getFields_states($id = true, $action = '')
{
    if ($id)
        $field['stt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['stt_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query|max_length[20]');
    $field['stt_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_districts($id = true, $action = '')
{
    if ($id)
        $field['dst_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['dst_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['dst_fk_states'] = array('int', '11', 'unsigned NOT NULL', 'State', 'required|callback_db_query');
    $field['dst_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query|max_length[20]');
    $field['dst_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_taluks($id = true, $action = '')
{
    if ($id)
        $field['tlk_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['tlk_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['tlk_fk_districts'] = array('int', '11', 'unsigned NOT NULL', 'District', 'required|callback_db_query');
    $field['tlk_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query|max_length[20]');
    $field['tlk_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_areas($id = true, $action = '')
{
    if ($id)
        $field['ars_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['ars_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['ars_fk_taluks'] = array('int', '11', 'unsigned NOT NULL', 'Taluk', 'required|callback_db_query');
    $field['ars_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'Central Store', 'required|callback_db_query');
    $field['ars_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query|max_length[20]');

    // 1=> Corporation, 2 => Municipality, 3 => Panchayath @ common_helper/get_area_types
    $field['ars_type'] = array('tinyint', '1', 'NULL DEFAULT NULL', 'Area Type', 'required|callback_db_query');
    $field['ars_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_wards($id = true, $action = '')
{
    if ($id)
        $field['wrd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['wrd_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['wrd_fk_areas'] = array('int', '11', 'unsigned NOT NULL', 'Taluk', 'required|callback_db_query');
    $field['wrd_fk_estores'] = array('int', '11', 'unsigned NOT NULL', 'Central Store', 'required|callback_db_query');
    $field['wrd_name'] = array('varchar', '20', '', 'Name', 'required|callback_db_query|max_length[20]');
    $field['wrd_color'] = array('varchar', '20', '', 'Colour Code', 'required|callback_db_query|max_length[20]');
    $field['wrd_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_families($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['fmly_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['fmly_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['fmly_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');
    $field['fmly_fk_wards'] = array('int', '11', 'unsigned NOT NULL', 'Wark', 'required|callback_db_query');
    $field['fmly_name'] = array('varchar', '100', '', 'House Name', 'callback_db_query|max_length[100]');
    $field['fmly_address'] = array('varchar', '200', '', 'Address', 'max_length[200]|callback_db_query');
    $field['fmly_no'] = array('varchar', '20', '', 'House Number', 'required|callback_db_query|max_length[20]');
    $field['fmly_landmark'] = array('varchar', '100', '', 'Landmark', 'callback_db_query|max_length[100]');
    $field['fmly_fbill'] = array('date', '', '', 'First Bill', 'callback_valid_date|callback_db_query');
    $field['fmly_lbill'] = array('date', '', '', 'Last Bill', 'callback_valid_date|callback_db_query');
    $field['fmly_lat'] = array('varchar', '50', 'NULL DEFAULT NULL', 'Latitude', 'callback_db_query|max_length[50]');
    $field['fmly_log'] = array('varchar', '50', 'NULL DEFAULT NULL', 'Longitude', 'callback_db_query|max_length[50]');
    $field['fmly_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_family_members($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['fmlm_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['fmlm_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['fmlm_fk_families'] = array('int', '11', 'unsigned NOT NULL', 'Family', 'required|callback_db_query|callback_required');
    $field['fmlm_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');
    $field['fmlm_name'] = array('varchar', '70', '', 'Name', 'required|callback_db_query|max_length[70]');
    $field['fmlm_mob'] = array('varchar', '10', '', 'Mob', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['fmlm_dob'] = array('date', '', '', 'Date of Birth', 'callback_valid_date|callback_db_query');
    $field['fmlm_is_prime'] = array('tinyint', '1', 'NOT NULL DEFAULT 2', 'Is Primary', 'callback_db_query|callback_check_prim'); // 1=> Primary, 2 => Not
    $field['fmlm_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_central_stores($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['cstr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['cstr_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['cstr_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');
    $field['cstr_fk_taluks'] = array('int', '11', 'unsigned NOT NULL', 'Taluk', 'required|callback_db_query');
    $field['cstr_name'] = array('varchar', '100', 'NULL', 'Name', 'callback_db_query|max_length[100]');
    $field['cstr_code'] = array('varchar', '70', '', 'Code', 'required|callback_db_query|max_length[70]');
    $field['cstr_address1'] = array('varchar', '200', '', 'Address 1', 'max_length[200]|callback_db_query');
    $field['cstr_address2'] = array('varchar', '200', '', 'Address 2', 'max_length[200]|callback_db_query');
    $field['cstr_landmark'] = array('varchar', '100', '', 'Landmark', 'callback_db_query|max_length[100]');
    $field['cstr_pin'] = array('varchar', '10', '', 'Pincode', 'callback_db_query|max_length[10]');
    $field['cstr_bownr'] = array('varchar', '100', '', 'Building Owner', 'callback_db_query|max_length[100]');
    $field['cstr_bownr_mob1'] = array('varchar', '10', '', 'Building Owner Mob1', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['cstr_bownr_mob2'] = array('varchar', '10', '', 'Building Owner Mob2', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['cstr_rent'] = array('int', '11', 'NOT NULL DEFAULT 0', 'Monthly Rent', 'numeric|callback_db_query');
    $field['cstr_adv'] = array('int', '11', 'NOT NULL DEFAULT 0', 'Advance', 'numeric|callback_db_query');
    $field['cstr_sqft'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Sqft', 'numeric|callback_db_query');
    $field['cstr_mob1'] = array('varchar', '10', '', 'Contact 1', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['cstr_mob2'] = array('varchar', '10', '', 'Contact 2', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['cstr_police'] = array('varchar', '15', '', 'Police Number', 'max_length[15]|callback_db_query');
    $field['cstr_fire'] = array('varchar', '15', '', 'Fire Station Number', 'max_length[15]|callback_db_query');
    $field['cstr_lat'] = array('varchar', '50', 'NULL DEFAULT NULL', 'Latitude', 'callback_db_query|max_length[50]');
    $field['cstr_log'] = array('varchar', '50', 'NULL DEFAULT NULL', 'Longitude', 'callback_db_query|max_length[50]');
    $field['cstr_lic'] = array('varchar', '100', 'NULL', 'Panchayath Licence', 'callback_db_query|max_length[100]');
    $field['cstr_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_estores($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['estr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['estr_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['estr_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'Central Store', 'required|callback_db_query');
    $field['estr_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');
    $field['estr_name'] = array('varchar', '100', 'NULL', 'Name', 'callback_db_query|max_length[100]');
    $field['estr_mob1'] = array('varchar', '10', '', 'Mobile No: 1', 'required|numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['estr_mob2'] = array('varchar', '10', '', 'Mobile No: 2', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['estr_address1'] = array('varchar', '100', '', 'Address 1', 'max_length[100]|callback_db_query');
    $field['estr_address2'] = array('varchar', '100', '', 'Address 2', 'max_length[100]|callback_db_query');
    $field['estr_place'] = array('varchar', '100', '', 'Place', 'callback_db_query|max_length[100]');
    $field['estr_pin'] = array('varchar', '10', '', 'Pincode', 'callback_db_query|max_length[10]');
    $field['estr_clsrel'] = array('varchar', '100', '', 'Close Relative', 'callback_db_query|max_length[100]');
    $field['estr_clsrel_mob'] = array('varchar', '10', '', 'Close Relative Mob', 'numeric|min_length[10]|callback_db_query');
    $field['estr_fmly'] = array('varchar', '100', '', 'Family Name', 'callback_db_query|max_length[100]');
    $field['estr_fmly_mob'] = array('varchar', '10', '', 'Family Mob', 'numeric|min_length[10]|callback_db_query');
    $field['estr_bank_name'] = array('varchar', '100', '', 'Bank Account Name', 'callback_db_query|max_length[100]');
    $field['estr_bank_acc'] = array('varchar', '100', '', 'Bank Account No', 'callback_db_query|max_length[100]');
    $field['estr_ifsc'] = array('varchar', '10', '', 'IFSC Code', 'max_length[10]|callback_db_query');
    $field['estr_uid'] = array('varchar', '50', '', 'UID', 'required|max_length[50]|callback_db_query');
    $field['estr_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}


function getFields_user_central_stores($id = true, $action = '', $validation = '')
{
    if ($id)
        $field['ucs_id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', 'callback_db_query');
    $field['ucs_fk_users'] = array('int', '11', 'unsigned NOT NULL', 'Users', 'callback_required|callback_db_query');
    $field['ucs_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'Tasks', 'callback_required|callback_db_query');

    return $field;
}

function getFields_tags($id = true, $action = '')
{
    if ($id)
        $field['tg_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['tg_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['tg_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['tg_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_product_tags($id = true, $action = '')
{
    if ($id)
        $field['ptg_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['ptg_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');
    $field['ptg_fk_tags'] = array('int', '11', 'unsigned NOT NULL', 'Category', 'callback_required|callback_db_query');

    return $field;
}

function getFields_product_categories($id = true, $action = '')
{
    if ($id)
        $field['pct_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['pct_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['pct_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');

    // Its value will be the value of pct_id
    $field['pct_parent'] = array('int', '11', 'unsigned NOT NULL DEFAULT 0', 'Parent', 'callback_db_query');
    $field['pct_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_product_photos($id = true, $action = '')
{
    if ($id)
        $field['prdpt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['prdpt_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['prdpt_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['prdpt_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');
    $field['prdpt_sort'] = array('tinyint', '4', 'NOT NULL DEFAULT 1', 'Sort', 'callback_db_query');

    return $field;
}

function getFields_products($id = true, $action = '')
{
    if ($id)
        $field['prd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['prd_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['prd_fk_product_categories'] = array('int', '11', 'unsigned NOT NULL', 'Category', 'callback_db_query');
    $field['prd_fk_companies'] = array('int', '11', 'unsigned NOT NULL', 'Company', 'callback_db_query');
    $field['prd_fk_brands'] = array('int', '11', 'unsigned NOT NULL', 'Brand', 'callback_db_query');
    $field['prd_hsn_code'] = array('varchar', '30', 'NULL', 'HSN Code', 'callback_db_query|max_length[30]');
    $field['prd_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['prd_code'] = array('varchar', '30', 'NULL', 'Product Code', 'callback_db_query|max_length[30]');
    $field['prd_barcode'] = array('varchar', '100', 'NULL', 'Barcode', 'callback_db_query|max_length[100]');
    $field['prd_disc'] = array('text', '', 'NULL', 'Description', 'required|callback_db_query');
    $field['prd_madein'] = array('tinyint', '2', 'NOT NULL DEFAULT 1', 'Made in', 'required|callback_db_query');

    // array(1 => 'MRP', 2 => 'Daily Varient', 3 => 'Frequently Varient')
    $field['prd_rate_type'] = array('tinyint', '2', 'NOT NULL DEFAULT 1', 'Rate Type', 'required|callback_db_query');

    // array(1 => 'Company', 2 => 'SSI', 3 => 'Farmers')
    $field['prd_prod_type'] = array('tinyint', '2', 'NOT NULL DEFAULT 1', 'Production Type', 'required|callback_db_query');

    // array(1 => 'Organic', 2 => 'Inorganic')
    $field['prd_organic'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Organic', 'required|callback_db_query');

    // array(1 => 'Batch', 2 => 'No Batch')
    $field['prd_batch'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Batch', 'required|callback_db_query');

    // array(1 => 'Varients', 2 => 'No Varients')
    $field['prd_varient'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Varients', 'required|callback_db_query');

    // Hypernym for “veg” and “non-veg”
    // array(1 => 'Veg', 2 => 'Fish', 3 => 'Egg',  4 => 'Non-Veg')
    $field['prd_dietary'] = array('tinyint', '2', 'NOT NULL DEFAULT 1', 'Dietary Type', 'required|callback_db_query');

    // array(1 => 'Yes', 2 => 'No')
    $field['prd_zeta'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Zeta / Sodexo', 'callback_db_query');

    $field['prd_estr_cmsn_p'] = array('decimal', '13,2', 'NOT NULL', 'Estore Commission %', 'numeric|callback_db_query');
    $field['prd_exp_p'] = array('decimal', '13,2', 'NOT NULL', 'Expense %', 'numeric|callback_db_query');

    $field['prd_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}



// function getFields_product_varients($id = true, $action = '')
// {
//     if ($id)
//         $field['pdvnt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
//     $field['pdvnt_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
//     $field['pdvnt_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');
//     $field['pdvnt_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
//     $field['pdvnt_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

//     return $field;
// }

function getFields_product_batches($id = true, $action = '')
{
    if ($id)
        $field['pdbch_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['pdbch_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['pdbch_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_required|callback_db_query');
    $field['pdbch_name'] = array('varchar', '100', 'NULL', 'Batch No', 'required|callback_db_query|max_length[100]');
    $field['pdbch_date'] = array('date', '', '', 'Date', 'callback_valid_date|callback_db_query');
    $field['pdbch_mfg'] = array('date', '', '', 'Manufacturing Date', 'required|callback_valid_date|callback_db_query');
    $field['pdbch_exp'] = array('date', '', '', 'Expiry Date', 'required|callback_valid_date|callback_db_query');
    $field['pdbch_dt'] = array('varchar', '200', 'NULL', 'Details', 'callback_db_query|max_length[200]');

    // 1 => Farmer, 2 => Not
    $field['pdbch_farmer'] = array('tinyint', '1', 'NOT NULL DEFAULT 2', 'Is Farmer', 'callback_db_query');
    $field['pdbch_avg'] = array('decimal', '13,4', 'NOT NULL', 'Average Purchase Rate', 'numeric|callback_db_query');
    $field['pdbch_bp'] = array('decimal', '13,4', 'NOT NULL', 'Billing price', 'required|numeric|callback_db_query');
    $field['pdbch_sp'] = array('decimal', '13,4', 'NOT NULL', 'Selling price', 'required|numeric|callback_db_query|callback_check_prices');
    $field['pdbch_ecp'] = array('decimal', '13,4', 'NOT NULL', 'Estore commission%', 'numeric|callback_db_query');
    $field['pdbch_eca'] = array('decimal', '13,4', 'NOT NULL', 'Estore commission amount', 'numeric|callback_db_query');
    $field['pdbch_expp'] = array('decimal', '13,4', 'NOT NULL', 'Expense%', 'numeric|callback_db_query');
    $field['pdbch_expa'] = array('decimal', '13,4', 'NOT NULL', 'Expense amount', 'numeric|callback_db_query');
    $field['pdbch_ba'] = array('decimal', '13,4', 'NOT NULL', 'Balance of eternal', 'numeric|callback_db_query');
    $field['pdbch_mrp'] = array('decimal', '13,4', 'NOT NULL', 'MRP', 'numeric|callback_db_query');
    $field['pdbch_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_companies($id = true, $action = '')
{
    if ($id)
        $field['cmp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['cmp_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['cmp_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['cmp_cmsn'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Commission %', 'numeric|callback_db_query');
    $field['cmp_exp'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Expense %', 'numeric|callback_db_query');
    $field['cmp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_brands($id = true, $action = '')
{
    if ($id)
        $field['brnd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['brnd_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['brnd_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['brnd_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}


function getFields_parties($id = true, $action = '')
{
    // Member id 1-100 is reserved for Developers
    if ($id)
        $field['prty_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['prty_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['prty_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'callback_db_query');
    $field['prty_address2'] = array('varchar', '100', 'NULL', 'Address 2', 'callback_db_query|max_length[100]');
    $field['prty_address3'] = array('varchar', '100', 'NULL', 'Address 3', 'callback_db_query|max_length[100]');
    $field['prty_mob1'] = array('varchar', '10', '', 'Mobile No:', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['prty_mob2'] = array('varchar', '10', '', 'Mobile No:', 'numeric|max_length[10]|min_length[10]|callback_db_query');
    $field['prty_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_gstnumbers($id = true, $action = '')
{
    if ($id)
        $field['gst_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['gst_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['gst_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'required|callback_db_query');
    $field['gst_name'] = array('varchar', '100', 'NULL', 'GST', 'required|callback_db_query|max_length[100]');
    $field['gst_fks_states'] = array('tinyint', '1', 'NOT NULL', 'State Code', 'callback_db_query');

    // A member can add more than one gst details. Here we specifing the default one.
    $field['gst_default'] = array('tinyint', '1', 'NOT NULL', 'State Code', 'callback_db_query|callback_check_default');
    $field['gst_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_godowns($id = true, $action = '')
{
    if ($id)
        $field['gdn_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['gdn_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['gdn_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'Central Store', 'callback_db_query');
    $field['gdn_name'] = array('varchar', '100', 'NULL', 'GDN Code No', 'required|callback_db_query|max_length[100]');
    $field['gdn_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

// Only done by developer
function getFields_units($id = true, $action = '')
{
    if ($id)
        $field['unt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['unt_name'] = array('varchar', '10', 'NULL', 'UNT', 'required|callback_db_query|max_length[10]');
    $field['unt_dsc'] = array('varchar', '100', 'NULL', 'Description', 'required|callback_db_query|max_length[100]');
    $field['unt_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

// Group of units
// It may a single unit.
function getFields_unit_groups($id = true, $action = '')
{
    if ($id)
        $field['ugp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['ugp_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['ugp_name'] = array('varchar', '100', 'NULL', 'Group Name', 'required|callback_db_query|max_length[100]');
    $field['ugp_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'callback_db_query');
    $field['ugp_is_basic'] = array('tinyint', '1', 'NOT NULL', 'Is Basic Unit', 'callback_db_query'); // 1=>Basic Unit, 2 => Not

    // Is the default unit of the group
    $field['ugp_default'] = array('tinyint', '1', 'NOT NULL', 'Is Default Unit', 'callback_db_query');

    // If it is not a basic unit itself, give the basic unit (Tbl: units.unt_id)
    $field['ugp_fk_bunits'] = array('int', '11', 'unsigned NOT NULL DEFAULT 0', 'Basic Unit', 'callback_db_query');

    // Relation with basic unit
    $field['ugp_rel'] = array('decimal', '13,5', 'unsigned NOT NULL DEFAULT 0', 'Relation with Basic Unit', 'numeric|callback_db_query');

    // Number represents a group of units (Child - Parent unit set). it will be incremented by 1 on each unit_groups entry.
    $field['ugp_group_no'] = array('int', '11', 'unsigned NOT NULL DEFAULT 0', 'Unit Group No', 'callback_db_query');

    // Status applied to all units in a set (ugp_group_no)
    $field['ugp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_product_units($id = true, $action = '')
{
    // Don't use punt_id as foreignkey for product unit in other talbes. rather use ugp_group_no.
    if ($id)
        $field['punt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['punt_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['punt_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Product', 'required|callback_db_query');
    $field['punt_group_no'] = array('int', '11', 'unsigned NOT NULL', 'Unit Group', 'callback_db_query');
    $field['punt_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_hsn_details($id = true, $action = '')
{
    if ($id)
        $field['hsn_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['hsn_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['hsn_name'] = array('varchar', '100', 'NULL', 'HSN Code No', 'required|callback_db_query|max_length[100]');
    $field['hsn_name_4_digit'] = array('int', '11', 'NULL', 'HSN Code 4 Digit', 'required|callback_db_query|max_length[100]');
    $field['hsn_commodity'] = array('varchar', '100', 'NULL', 'Commodity Name', 'required|callback_db_query|max_length[100]');
    $field['hsn_chapter'] = array('int', '11', 'NULL', 'Chapter No', 'required|numeric|callback_db_query');
    $field['hsn_sch'] = array('int', '11', 'NULL', 'SCH', 'required|numeric|callback_db_query');
    $field['hsn_gst'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'GST Rate', 'required|numeric|callback_db_query');
    $field['hsn_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_bill_types($id = true, $action = '')
{
    if ($id)
        $field['btp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');

    // PURCHASE QUOTATIONS,PURCHASE ORDERS, PURCHASE BILLS, PURCHASE RETURNS, 
    // SALES QUOTATIONS, SALES ORDERS, SALES BILLS, SALES RETURNS
    $field['btp_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');

    // purchase_quotations,purchase_orders, purchase_bills, purchase_returns, 
    // sales_quotations, sales_orders, sales_bills, sales_returns
    $field['btp_for'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');

    // pchs_qtn, pchs_odr, pchs_bls, pchs_rtn
    // sls_qtn, sls_odr, sls_bls, sls_rtn
    // Note: Use this for tsk_key when adding task
    //  Eg:- when adding task for 'purchase_quotations', put tsk_key = tsk_pchs_qtn
    $field['btp_key'] = array('varchar', '10', 'NULL', 'Prefix', 'required|callback_db_query|max_length[10]');

    // pchs,sls
    $field['btp_type'] = array('varchar', '15', 'NULL', 'Name', 'required|callback_db_query|max_length[15]');
    $field['btp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}



// Bill no will be restarted for each Bill batch.
function getFields_bill_batches($id = true, $action = '')
{
    if ($id)
        $field['blb_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['blb_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['blb_date'] = array('date', '', '', 'Date', 'required|callback_valid_date|callback_db_query');

    // Eg: From 01-Aug-2019, 2018-2019
    $field['blb_name'] = array('varchar', '100', 'NULL', 'Batch Name', 'required|callback_db_query|max_length[100]');

    // For why this batch?
    // Values will be like pchs_bls, sls_odr, stock_transfers ect.
    // For Purchase/Sale, value will be taken from Tbl:bill_types.btp_key
    $field['blb_for'] = array('varchar', '100', 'NULL', 'Batch For', 'required|callback_db_query|max_length[100]');

    // Is Non-Tax/Tax.
    $field['blb_type'] = array('tinyint', '1', '', 'Batch Type', 'required|callback_db_query'); // 1 => Non-Tax, 2 => Tax

    $field['blb_prefix'] = array('varchar', '10', 'NULL', 'Batch Prefix', 'callback_db_query|max_length[10]');
    $field['blb_sufix'] = array('varchar', '10', 'NULL', 'Batch Sufix', 'callback_db_query|max_length[10]');

    // Is current active batch. For each 'blb_for' & 'blb_type', only one active batch is allowed.
    $field['blb_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

// Bill no will be restarted for each Bill batch.
function getFields_bill_nos($id = true, $action = '')
{
    if ($id)
        $field['bln_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['bln_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['bln_fk_bill_batches'] = array('int', '11', 'unsigned NOT NULL', 'Member', 'required|callback_db_query');
    $field['bln_name'] = array('int', '11', 'NOT NULL', 'Bill No', 'required|callback_db_query|max_length[100]');

    return $field;
}

function getFields_bills($id = true, $action = '')
{
    if ($id)
        $field['bls_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['bls_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');

    // mbr_id of Central Store
    $field['bls_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'central Store', 'callback_db_query');

    // pchs => Purchase, sls => Sales. (It should match Tbl: bill_types.btp_type)
    $field['bls_bill_cat'] = array('varchar', '15', 'NULL', 'Category', 'callback_db_query|max_length[15]');

    // sls_bls, pchs_bls, sls_odr Ect. (It should match Tbl: bill_batches.blb_for OR bill_types.btp_key)
    $field['bls_bill_type'] = array('varchar', '100', 'NULL', 'Reference', 'required|callback_db_query');

    // Is Taxbill.
    $field['bls_taxable'] = array('tinyint', '1', '', 'Taxable', 'callback_db_query'); // 1 => Tax, 2 => Non-Tax

    // 1 => Intra-State (CGST|SGST)
    // 2 => Inter-State (IGST)
    $field['bls_tax_state'] = array('tinyint', '1', '', 'Tax State', 'callback_db_query');

    // The Reference Key (Value of bls_id)
    // It is used when we converting an Order to Bill or a Bill to Return ect.
    $field['bls_ref_key'] = array('bigint', '20', 'unsigned NOT NULL', 'Ref Key', 'callback_db_query');

    $field['bls_from_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Billed From', 'callback_db_query');
    $field['bls_from_fk_gstnumbers'] = array('int', '11', 'unsigned NOT NULL', 'GSTIN of Billed From', 'callback_db_query');

    $field['bls_to_fk_members'] = array('int', '11', 'unsigned NOT NULL', 'Billed To', 'callback_db_query');
    $field['bls_to_fk_gstnumbers'] = array('int', '11', 'unsigned NOT NULL', 'GSTIN of Billed To', 'callback_db_query');

    $field['bls_fk_bill_nos'] = array('bigint', '20', 'unsigned NOT NULL', 'Bill No:', 'callback_db_query');
    $field['bls_date'] = array('datetime', '', '', 'Date', 'required|callback_valid_date|callback_db_query');


    // Total of Amount
    $field['bls_amt_total'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Amount Total', 'numeric|callback_db_query');

    // Total of Tax
    $field['bls_cgst_total'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'CGST Total', 'numeric|callback_db_query');
    $field['bls_sgst_total'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'SGST Total', 'numeric|callback_db_query');
    $field['bls_igst_total'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'IGST Total', 'numeric|callback_db_query');
    $field['bls_cess_total'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'CESS Total', 'numeric|callback_db_query');

    // Total of Gross Amount
    $field['bls_gross_total'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Gross Total', 'numeric|callback_db_query');

    // Discount on total Gross Amount
    $field['bls_gross_disc'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Gross Discount', 'numeric|callback_db_query');

    $field['bls_round'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Round', 'numeric|callback_db_query');
    $field['bls_net_amount'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Net Amount', 'numeric|callback_db_query');
    $field['bls_paid'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Paid', 'numeric|callback_db_query');
    $field['bls_balance'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Balance', 'numeric|callback_db_query');
    $field['bls_rem'] = array('varchar', '200', 'NULL', 'Remarks', 'callback_db_query|max_length[200]');
    // $field['bls_del_date'] = array('datetime', '', '', 'Delivery Date', 'callback_db_query|callback_valid_date');
    // $field['bls_del_addr'] = array('varchar', '300', 'NULL', 'Delivery Address', 'callback_db_query|max_length[300]');



    // 1=>Active, 2=>Converted, 3=>Cancelled
    $field['bls_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    return $field;
}

function getFields_bill_products($id = true, $action = '')
{
    if ($id)
        $field['blp_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['blp_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['blp_fk_bills'] = array('bigint', '20', 'unsigned NOT NULL', 'Bill No', 'callback_db_query');
    $field['blp_fk_godowns'] = array('int', '11', 'unsigned NOT NULL', 'Godown', 'callback_db_query');
    $field['blp_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Product', 'callback_db_query');
    $field['blp_fk_product_batches'] = array('int', '11', 'unsigned NOT NULL', 'Batches', 'callback_db_query');
    $field['blp_fk_hsn_details'] = array('int', '11', 'unsigned NOT NULL', 'HSN Code', 'callback_db_query');
    $field['blp_mrp'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'MRP', 'numeric|callback_db_query');

    // Billed Quantity and Unit
    $field['blp_qty'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'Quantity', 'numeric|callback_db_query');
    $field['blp_fk_unit_groups'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'callback_db_query');
    $field['blp_ugp_group_no'] = array('int', '11', 'unsigned NOT NULL', 'Unit Group', 'callback_db_query');

    // Rate (Exclude Tax)
    $field['blp_rate'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'Rate', 'numeric|callback_db_query');

    // Rate (Include Tax)
    $field['blp_trate'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'Rate', 'numeric|callback_db_query');


    // Convert to basic unit
    $field['blp_basic_qty'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'Quantity', 'numeric|callback_db_query');

    // Unt_id of Basic unit of blp_fk_unit_groups.
    $field['blp_basic_unt_id'] = array('int', '11', 'unsigned NOT NULL', 'Basic Unit', 'callback_db_query');

    // Relation with basic unit
    $field['blp_basic_ugp_rel'] = array('decimal', '13,5', 'unsigned NOT NULL DEFAULT 0', 'Relation with Basic Unit', 'callback_db_query');

    // Rate (without tax) in basic unit
    $field['blp_basic_rate'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Rate', 'callback_db_query');

    // Rate (with tax) in basic unit
    $field['blp_basic_trate'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Taxed Rate', 'callback_db_query');

    // ugp_id of basic unit of the unit group.
    $field['blp_basic_ugp_id'] = array('int', '11', 'unsigned NOT NULL', 'Relation with Basic Unit', 'callback_db_query');

    // blp_rate_apld * blp_qty
    $field['blp_amount'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Amount', 'numeric|callback_db_query');



    // CGST Rate (In %)
    $field['blp_cgst_p'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'CGST %', 'numeric|callback_db_query');

    // CGST amount applied
    $field['blp_cgst'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'CGST', 'numeric|callback_db_query');

    // SGST Rate (In %)
    $field['blp_sgst_p'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'SGST %', 'numeric|callback_db_query');

    // SGST amount applied
    $field['blp_sgst'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'SGST', 'numeric|callback_db_query');

    // IGST Rate (In %)
    $field['blp_igst_p'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'IGST %', 'numeric|callback_db_query');

    // IGST amount applied
    $field['blp_igst'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'IGST', 'numeric|callback_db_query');



    // Amount After GST
    $field['blp_gross_amt'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'GST', 'numeric|callback_db_query');

    return $field;
}


// It is side table of Tbl: bills. It is used to keep the status details of Sales orders
function getFields_order_status($id = true, $action = '')
{
    if ($id)
        $field['ost_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['ost_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['ost_fk_bills'] = array('bigint', '20', 'unsigned NOT NULL', 'Bill', 'callback_db_query');
    $field['ost_date'] = array('datetime', '', '', 'Date', 'callback_db_query');
    $field['ost_fk_users'] = array('int', '11', 'unsigned NOT NULL', 'User', 'callback_db_query');

    // 1=>Pending, 2=>Picked, 3=>Billed, 4=>Packed, 5 => At Estore, 6=>Delivered, 7=> Paid
    $field['ost_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');

    // To identify ffinal status of an order
    $field['ost_final'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Is final Status', 'callback_db_query'); // 1 => Yes, 2 => 'No'

    return $field;
}



function getFields_stock_avg($id = true, $action = '')
{
    if ($id)
        $field['stkavg_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['stkavg_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['stkavg_date'] = array('datetime', '', '', 'Date', 'required|callback_valid_date|callback_db_query');

    // Used to identify which stock data is first when two or more stock data have the same stkavg_date.
    // Suppose We need to find previous stock data of current stock.
    // there are three rows with same datetime as current stock. So to identify their order we can use p_key.
    // But p_key may disordered when user entering a bill of previous date after current bills.
    // So we can't take p_key to identify the order of the bills in the same datetime.
    // So for each set of datetime, 'stkavg_order' will be incremented by 1.
    $field['stkavg_order'] = array('tinyint', '4', 'NOT NULL', 'Status', 'callback_db_query');

    // In the case of billing it is 'bills' not 'bill_products'
    $field['stkavg_ref_tbl'] = array('varchar', '100', '', 'Referance Table', 'callback_db_query');

    // In the case of billing its value is the value of bls_id not blp_id
    $field['stkavg_ref_id'] = array('bigint', '20', 'unsigned NOT NULL', 'Reference Id', 'callback_db_query');

    // mbr_id of Central Store
    $field['stkavg_cstr_mbr_id'] = array('int', '11', 'unsigned NOT NULL', 'central Store', 'callback_db_query');
    $field['stkavg_fk_godowns'] = array('int', '11', 'unsigned NOT NULL', 'Godown', 'callback_db_query');
    $field['stkavg_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Product', 'required|callback_db_query');
    $field['stkavg_fk_product_batches'] = array('int', '11', 'unsigned NOT NULL', 'Batch', 'required|callback_db_query');

    // Input Quantity in basic unit (In case of Purchase)
    $field['stkavg_qty_in'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Input Quantity', 'numeric|callback_db_query');

    // Output Quantity in basic unit (In the case of sale)
    $field['stkavg_qty_out'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Output Quantity', 'numeric|callback_db_query');

    // Rate in basic unit
    $field['stkavg_rate'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'MRP', 'callback_db_query');

    // ugp_id
    $field['stkavg_ugp_id'] = array('int', '11', 'unsigned NOT NULL', 'Relation with Basic Unit', 'callback_db_query');

    $field['stkavg_ugp_group_no'] = array('int', '11', 'unsigned NOT NULL', 'Unit Group', 'callback_db_query');

    // Unt_id of unit represented by stkavg_ugp_id.
    $field['stkavg_unt_id'] = array('int', '11', 'unsigned NOT NULL', 'Basic Unit', 'callback_db_query');

    // Balance Stock Quantity @ Central Store
    $field['stkavg_bal_qty'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Balance Stock Quantity', 'numeric|callback_db_query');

    // Balance Stock Rate @ Central Store
    $field['stkavg_bal_rate'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Balance Stock Avg Rate', 'callback_db_query');

    // Balance Stock Quantity @ Godown. Rate will be taken Central Store Rate.
    $field['stkavg_bal_gdn_qty'] = array('decimal', '13,5', 'NOT NULL DEFAULT 0', 'Godown Balance Stock Quantity', 'numeric|callback_db_query');

    return $field;
}

function getFields_price_groups($id = true, $action = '')
{
    if ($id)
        $field['pgp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['pgp_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['pgp_date'] = array('date', '', '', 'Date', 'callback_db_query');
    $field['pgp_name'] = array('varchar', '100', 'NULL', 'Name', 'required|callback_db_query|max_length[100]');
    $field['pgp_disc'] = array('varchar', '200', 'NULL', 'Description', 'required|callback_db_query|max_length[200]');
    $field['pgp_status'] = array('tinyint', '1', 'NOT NULL DEFAULT 1', 'Status', 'callback_db_query');
    return $field;
}

function getFields_price_groups_products($id = true, $action = '')
{
    if ($id)
        $field['pgprd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['pgprd_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['pgprd_date'] = array('date', '', '', 'Date', 'callback_db_query');
    $field['pgprd_fk_price_groups'] = array('int', '11', 'unsigned NOT NULL', 'Price Group', 'required|callback_db_query');
    $field['pgprd_fk_products'] = array('int', '11', 'unsigned NOT NULL', 'Product', 'required|callback_db_query');
    $field['pgprd_fk_product_batches'] = array('int', '11', 'unsigned NOT NULL', 'Batches', 'required|callback_db_query');

    // Minimum Quantity (Price group will be applicable only if the product occuppies minimum quantity)
    $field['pgprd_qty'] = array('decimal', '13,2', 'NOT NULL DEFAULT 0', 'Input Quantity', 'numeric|callback_db_query');

    $field['pgprd_fk_unit_groups'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'required|callback_db_query');
    $field['pgprd_ugp_group_no'] = array('int', '11', 'unsigned NOT NULL', 'Unit Group', 'callback_db_query');

    // Discount
    $field['pgprd_dsc'] = array('decimal', '13,2', 'NOT NULL', 'Discount', 'numeric|callback_db_query');

    // Discount Percentage
    $field['pgprd_dscp'] = array('decimal', '13,2', 'NOT NULL', 'Discount %', 'numeric|callback_db_query');
    $field['pgprd_rate'] = array('decimal', '13,3', 'NOT NULL DEFAULT 0', 'Rate', 'numeric|callback_db_query');

    return $field;
}


// First we look up for the price group in Wards, then Area / Central Store, then District, then state
// If there is a price group for both Area and Central Store, The lowest rate will be taken
function getFields_price_group_locations($id = true, $action = '')
{
    if ($id)
        $field['pgpl_id'] = array('bigint', '20', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', 'Id', 'callback_db_query');
    $field['pgpl_fk_clients'] = array('int', '11', 'unsigned NOT NULL', 'Client', 'callback_db_query');
    $field['pgpl_date'] = array('date', '', '', 'Date', 'callback_db_query');
    $field['pgpl_vf'] = array('date', '', '', 'Valid From', 'required|callback_valid_date|callback_db_query');
    $field['pgpl_vt'] = array('date', '', '', 'Valid Up to', 'required|callback_valid_date|callback_compare_dates|callback_db_query');
    $field['pgpl_fk_price_groups'] = array('int', '11', 'unsigned NOT NULL', 'Price Group', 'required|callback_db_query');
    $field['pgpl_fk_states'] = array('int', '11', 'unsigned NOT NULL', 'State', 'callback_db_query');
    $field['pgpl_fk_districts'] = array('int', '11', 'unsigned NOT NULL', 'District', 'callback_db_query');
    $field['pgpl_fk_areas'] = array('int', '11', 'unsigned NOT NULL', 'Taluk', 'callback_db_query');
    $field['pgpl_fk_central_stores'] = array('int', '11', 'unsigned NOT NULL', 'Central Store', 'callback_db_query');
    $field['pgpl_fk_wards'] = array('int', '11', 'unsigned NOT NULL', 'Wark', 'callback_db_query');

    return $field;
}
