<?php
defined('BASEPATH') or exit('No direct script access allowed');

function is_active_nav($menu, $this_menu)
{
    return strtolower($menu) == strtolower($this_menu) ? ' active' : '';
}
function is_active_subnav($menu, $this_menu)
{
    return strtolower($menu) == strtolower($this_menu) ? ' active' : '';
}
function get_active_subnav_icon($menu, $this_menu)
{
    // $i = '<i class="fas fa-caret-left text-success" style="font-size: 30px;"></i>';
    // $i = '<i class="fal fa-check-square text-success"></i>';
    // $i = '<i class="fas fa-check text-success"></i>';
    // $i = '<i class="far fa-arrow-alt-circle-left text-success"></i>';
    // $i = '<i class="fas fa-arrow-alt-from-right text-success"></i>';
    // $i = '<i class="fas fa-arrow-alt-left text-success"></i>';
    $i = '&nbsp; <i class="fal fa-check" style="font-size: 20px; color:goldenrod"></i>';
    return strtolower($menu) == strtolower($this_menu) ? $i : '';
}
function is_open_menu($menu, $this_menu, $has_subnavs = FALSE)
{
    return (strtolower($menu) == strtolower($this_menu)) && $has_subnavs ? ' has-treeview menu-open' : '';
}

function get_encription_key($usr_id)
{
    $CI = get_instance();
    return (($CI->master_key * $usr_id) + $CI->master_key);
}

function get_encripted($usr_id, $pwd)
{
    return password_hash(get_encription_key($usr_id) . $pwd, PASSWORD_DEFAULT);
}

function check_encription($user_id, $pwd, $pwd_enc)
{
    return password_verify(get_encription_key($user_id) . $pwd, $pwd_enc);
}

function set_user_session($user_id, $unm)
{
    $CI = get_instance();
    $key = get_encripted($user_id, base_url() . time() . $unm);
    $CI->session->user = $key;
    return $key;
}


/**
 * $user_id: 
 * $unm: 
 * $remember: TRUE/FALSE. Whether to remember on this device or not.
 */
function make_logged_in($user_id, $unm, $remember, $old_cookie = '')
{
    $CI = get_instance();

    // Create user session
    $key = set_user_session($user_id, $unm);

    // Reseting login attempts
    $CI->login->delete_all_attempts($user_id);

    if ($remember) {
        $remember_cookie = remember_me($user_id);
    } else {
        $remember_cookie = '';
        $CI->login->forgot_me($user_id, $CI->remember_me);
    }

    // Saving session to DB.
    $CI->login->save_db_session($user_id, $key);

    // Deleting all expired "Remember Me" cookie data.
    $CI->login->delete_expired_remembers();

    // Saving "Remember Me"on the Tbl: device.
    $CI->login->save_remember_me($user_id, $remember_cookie, $old_cookie);

    return;
}

/**
 * https://stackoverflow.com/questions/3984313/how-to-create-remember-me-checkbox-using-codeigniter-session-library
 */
function remember_me($user_id)
{
    // Set application/config/config.php
    // $config['cookie_httponly']     = TRUE; // Cookie will only be accessible via HTTP(S) (no javascript)
    $CI = get_instance();
    $remember_key = get_encripted($user_id, time());
    $cookie = array(
        'name'   => encript_cookie_name($CI->remember_me),
        'value'  => $remember_key,
        'expire' => 60 * 60 * 24 * 30  // One month
    );

    set_cookie($cookie);
    return $remember_key;
}

function encript_cookie_name($cookie_name)
{
    return sha1(md5(base_url()) . $cookie_name);
}


// Checking the $val contain any of the following database keywords.
function check_db_chars($val)
{
    $count = 0;
    $badwords = array('SELECT', 'WHERE', 'FROM', 'UPDATE', 'INSERT', 'DELETE', 'DROP', 'TABLE', 'SHOW', 'DATABASE', 'EXISTS');
    foreach ($badwords as $badword)
        if (stripos($val, $badword) !== false)
            $count++;
    return $count;
}

// Checking the $val contain any of the following database keywords more than one times or quotes.
function check_quotes($val)
{
    $count = 0;
    if ((stripos($val, '"') !== false) || (stripos($val, "'") !== false))
        $count++;

    return $count;
}


/** 	SQL Compatible Formate of Date
 * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
 * 	@params : 	$date -> Date should be formated. If no $date current date should be taken.
 * 	@return : 	Formated date as string.
 * 	@access public
 */
function get_sql_date($date = '')
{
    if ($date)
        return date('Y-m-d', strtotime($date));
    return date('Y-m-d');
}

/**
 * 
 * @param type $date:   Date should be formated. If no date current date should be taken.
 * @param type $time:   Time in hh:mm:ss format. If no time current time should be taken.
 * @param type $mode:   It may be NULL, first, last
 * 
 * @return type
 */
function get_sql_date_time($date = '', $mode = '')
{
    if (strtotime($date)) {
        if ($mode == 'first')
            return date('Y-m-d', strtotime($date)) . ' 00:00:00';
        else if ($mode == 'last')
            return date('Y-m-d', strtotime($date)) . ' 23:59:59';
        else {
            // If time is not included in date
            if (date('H:i:s', strtotime($date)) == '00:00:00')
                return date('Y-m-d', strtotime($date)) . ' ' . date('H:i:s');
            else
                return date('Y-m-d H:i:s', strtotime($date));
        }
    } else {
        if ($mode == 'first')
            return date('Y-m-d') . ' 00:00:00';
        else if ($mode == 'last')
            return date('Y-m-d') . ' 23:59:59';
        else
            return date('Y-m-d H:i:s');
    }
}

function get_first_option($option, $return = 'key')
{
    if (!$option)
        return;

    $k = array_keys($option)[0];
    if ($return == 'key')
        return $k;

    if ($return == 'val')
        return $option[$k];

    if ($return == 'option')
        return array($k => $option[$k]);
}



/**
 * 
 * @param type $options    :  Array of options. array(key=>val,key=>val,key=>val);
 * @param type $sel_value  :  Selected value in $options array.
 * @param type $title      :  The Text (having no value) should be place at the top of the list in the <select> element.
 * @param type $flag       :  TRUE  => show $title/$empty_title message.
 *                            FALSE => Hide $title/$empty_title message.
 * @param type $sel_one    :  If there are only one <option> under the select, It will be selected automatically.
 * @param type $empty_title:  The Text (having no value) should be place at the top of the list in the <select> element
 *                            if list is empty.
 * @return string
 */
function get_options($options = '', $sel_value = NULL, $title = 'Select', $flag = true, $sel_one = FALSE, $empty_title = "No Options")
{
    # #	where $sel_value can hold;
    #	1.	A single value for single select options.
    #			$sel_value	=	12;
    #	2.	An array for multiselect options.
    #			$sel_value	=	array(12,15,2); 
    #	Where $append/$preppend are function names which returns reserved values that to be appended/preppended with options.
    $option = "";
    if (!$options || !is_array($options)) {
        $option .= '<option value="">' . $empty_title . '</option>';
        //$option .= '<option disabled>' . $empty_title . '</option>';
        return $option;
    }
    if ($flag) {
        $option .= '<option value="">' . $title . '</option>';
        //$option .= '<option disabled style="color:#FFF;background-color:#000;">' . $title . '</option>';
    }
    foreach ($options as $key => $value) {
        $selected = false;

        if (is_array($sel_value)) {
            if (in_array($key, $sel_value))
                $selected = true;
        } else if ($sel_value == $key)
            $selected = true;

        // If there is only one option, it become selected automatically.
        if (count($options) == 1 && $sel_one)
            $selected = true;

        $option .= $selected ? '<option value="' . $key . '"  selected="">' . $value . '</option>' : '<option value="' . $key . '" >' . $value . '</option>';
    }
    return $option;
}

function today()
{
    return date('d-m-Y');
}

function ifSetInput($input, $field, $default = '', $trim = true)
{
    $val = $default;
    if (isset($input[$field])) {
        if ($trim) {
            if (is_array($input[$field]))
                $val = array_filter($input[$field], 'trim');
            else
                $val = trim($input[$field]);
        } else
            $val = $input[$field];
    }

    return $val;
}


/**
 * Extracting values for attributes id & class from 'idClass' array
 * @param {*} $idClass : Array of classes & id. 
 * 						Eg: array('.cls1','.cls2','.cls3','#myId')
 * 
 * Return: array('class' => ' cls1 cls2 cls3', 'id' => 'myId')
 */
function extractIdClass($idClass)
{
    if (!$idClass)
        return array('class' => '', 'id' => '');

    $cls = '';
    $id = '';

    foreach ($idClass as $index => $value) {

        // If first character is '.', it is a class.
        if (substr($value, 0, 1) == ".") {

            // Taking class name after removing '.'
            $cls .= ' ' . substr($value, 1);
        }

        // If first character is '#', it is an id.
        else if (substr($value, 0, 1) == "#") {

            // Taking id name after removing '#'
            // There should be only one id. If more ids provided, taking only the last one.
            $id = substr($value, 1);
        }
    }

    return array('class' => $cls, 'id' => $id);
}

/**
 * 
 * @param {*} $title  	: 	Value for title attribute
 * @param {:} $idClass 	:	Array of classes & id. 
 * 							Eg: array('.cls1','.cls2','.cls3','#myId')
 * @param {*} $prefix 	: 	Prefix text for title. Default is "Add"
 */
function add_btn($title, $idClass = '', $prefix = 'Add')
{
    $idClass = extractIdClass($idClass);
    $btn = '<span id="' . $idClass['id'] . '" class="fa-stack' . $idClass['class'] . '" title="' . $prefix . ' ' . $title . '">';
    $btn .= '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-success"></i>';
    $btn .= '  <i class="fas fa-plus cursor-pointer fa-stack-1x fa-inverse"></i>';
    $btn .= '</span>';
    return $btn;
}

function edit_btn($title, $idClass = '', $prefix = 'Edit')
{
    $idClass = extractIdClass($idClass);
    $btn = '<span id="' . $idClass['id'] . '" class="fa-stack' . $idClass['class'] . '" title="' . $prefix . ' ' . $title . '">';
    $btn .= '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-info"></i>';
    $btn .= '  <i class="fas fa-pencil-alt cursor-pointer fa-stack-1x fa-inverse"></i>';
    $btn .= '</span>';
    return $btn;
}

function activate_btn($title, $idClass = '', $prefix = 'Activate')
{
    $idClass = extractIdClass($idClass);
    $btn = '<span id="' . $idClass['id'] . '" class="fa-stack' . $idClass['class'] . '" title="' . $prefix . ' ' . $title . '">';
    $btn .= '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-success"></i>';
    $btn .= '  <i class="fas fa-check cursor-pointer fa-stack-1x fa-inverse"></i>';
    $btn .= '</span>';
    return $btn;
}

function delete_btn($title, $idClass = '', $prefix = 'Delete')
{
    $idClass = extractIdClass($idClass);
    $btn = '<span id="' . $idClass['id'] . '" class="fa-stack' . $idClass['class'] . '" title="' . $prefix . ' ' . $title . '">';
    $btn .= '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-danger"></i>';
    $btn .= '  <i class="fas fa-trash-alt cursor-pointer fa-stack-1x fa-inverse"></i>';
    $btn .= '</span>';
    return $btn;
}

/**
 * You can use any fontawesome icon here
 * @param {*} title  	: 	Value for title attribute
 * @param {:} idClass 	:	Array of classes & id. 
 * 							Eg: ['.cls1','.cls2','.cls3','#myId']
 * @param {*} iconClass :  Fontawesome icon's class name. Eg: fas fa-unlock
 */
function other_btn($title, $idClass = '', $iconClass = '', $style = '', $iconstyle = '')
{
    $idClass = extractIdClass($idClass);
    $btn = '<span id="' . $idClass['id'] . '" class="fa-stack' . $idClass['class'] . '" title="' .  $title . '">';
    $btn .= '  <i class="fas fa-circle cursor-pointer fa-stack-2x" style="' . $style . '"></i>';
    $btn .= '  <i class="' . $iconClass . ' cursor-pointer fa-stack-1x fa-inverse" style="' . $iconstyle . '"></i>';
    $btn .= '</span>';
    return $btn;
}

function get_wage_options($index = '')
{
    // Don't change array keys.
    $wg_options = array('salary' => 'Salary', 'daily' => 'Daily', 'commission' => 'Commission');
    if ($index)
        return  $wg_options[$index];
    return  $wg_options;
}

function get_wage_options_string()
{
    $CI = get_instance();
    $wg_opt = get_wage_options();
    $str = array();
    foreach ($wg_opt as $i => $w)
        if ($CI->input->post($i))
            $str[] =  $i;
    return implode(', ', $str);
}


function get_wage_options_array($str, $format = true)
{
    $wgopt = array();
    if (!$str)
        return $wgopt;

    if (!$format)
        $wgopt = explode(',', $str);
    else {
        foreach (explode(',', $str) as $w)
            $wgopt[$w] = 1;
    }
    return $wgopt;
}

/**  
 * $data            :   
 * $ul_attr         :   
 * $active_nav      :   
 * $active_subnav   :   
 * $level           :   We have two levels of menu. 1 => parent menu, 2=>child menu
 */
function create_menu($data, $ul_attr, $active_nav, $active_subnav, $level = 2)
{
    $CI = get_instance();
    $str = "<ul $ul_attr>";
    foreach ($data as $d) {

        $children = $CI->tasks->get_children($d['tsk_id'], $CI->usr_type, 1, ACTIVE);
        $str .= has_task('', $d['tsk_id']) ? create_li($d, $children, $active_nav, $active_subnav, $level) : '';

        if ($children) {
            $ul_attr = 'class="nav nav-treeview"';
            $str .= create_menu($children, $ul_attr, '', $active_subnav);
        }

        $str .= '</li>';
    }
    $str .= '</ul>';
    return $str;
}



function create_li($menu, $children, $active_nav, $active_subnav, $level)
{
    $has_children = $children ? TRUE : FALSE;
    $is_active = is_active_nav($active_nav, $menu['tsk_name']);
    $is_active_subnv = is_active_subnav($active_subnav, $menu['tsk_name']);
    $subnv_active_ico = get_active_subnav_icon($active_subnav, $menu['tsk_name']);
    $LI = $level == 1 ? '<li title="' . ucfirst(strtolower($menu['tsk_name'])) . '" class="nav-item ' . is_open_menu($active_nav, $menu['tsk_name'], $has_children) . '">' : '<li class="nav-item sr-sub-nav" title="' . ucfirst(strtolower($menu['tsk_name'])) . '">';
    $anchor_name = $has_children ? '<div class="sr-menu-label"><p>' . strtoupper($menu['tsk_name']) . '<i class="fas fa-angle-left right"></i></p></div>' : '<div class="sr-menu-label"><p>' . strtoupper($menu['tsk_name']) . '</p></div>';
    $anchor_attr = $level == 1 ? array('class' => 'nav-link sr-menu-nav ' . $is_active) : array('class' => 'nav-link sr-menu-nav' . $is_active_subnv);

    $style = '';
    if ($menu['tsk_color'])
        $style .= 'color:' . $menu['tsk_color'] . ';';
    if ($menu['tsk_primary'])
        $style .= '--fa-primary-color:' . $menu['tsk_primary'] . ';';
    if ($menu['tsk_secondary'])
        $style .= '--fa-secondary-color:' . $menu['tsk_secondary'] . ';';

    $anchor =  anchor(
        $menu['tsk_url'],
        '<i class="nav-icon sr-menu-i ' . $menu['tsk_icon'] . '" style="' .  $style . ';"></i>' . $anchor_name . $subnv_active_ico,
        $anchor_attr
    );

    $str = $LI . $anchor;

    return $str;
}

function has_task($keys = '', $tsk_id = '')
{
    $CI = get_instance();
    return $CI->user_tasks->has_task($CI->usr_type, $CI->usr_id, $keys, $tsk_id);
}


function get_index_icon($icon)
{
    $i = '';
    if ($icon['tsk_icon']) {
        $style = '';
        if ($icon['tsk_color'])
            $style .= 'color:' . $icon['tsk_color'] . ';';
        if ($icon['tsk_primary'])
            $style .= '--fa-primary-color:' . $icon['tsk_primary'] . ';';
        if ($icon['tsk_secondary'])
            $style .= '--fa-secondary-color:' . $icon['tsk_secondary'] . ';';
        $i = '<i class="' . $icon['tsk_icon'] . ' fa-inverse" style="' . $style . '"></i>';
    }
    return $i;
}


function get_add_icon($icon)
{
    $i = '';
    if ($icon['tsk_icon']) {
        $style = '';
        if ($icon['tsk_color'])
            $style .= 'color:' . $icon['tsk_color'] . ';';
        if ($icon['tsk_primary'])
            $style .= '--fa-primary-color:' . $icon['tsk_primary'] . ';';
        if ($icon['tsk_secondary'])
            $style .= '--fa-secondary-color:' . $icon['tsk_secondary'] . ';';
        $i = '<i class="' . $icon['tsk_icon'] . '  fa-stack-1x fa-inverse" style="' . $style . '"></i>';
    }
    return $i;
}

/**
 * Note: Use bellow function toggle2() rather this.
 * Toggle values in $possible_vals array.
 * If $cur_val = 1, returns $possible_vals[2]
 * If $cur_val is @ last index then reutns $possible_vals[0]
 * 
 * $cur_val         :   
 * $possible_vals   : array("Red","Green","Blue)
 */
function toggle($cur_val, $possible_vals)
{
    if (!isset($possible_vals[0]))
        return '';

    $key = array_search($cur_val, $possible_vals);

    // If key of curresponding value is not found
    if ($key === FALSE) return $possible_vals[0];

    if (isset($possible_vals[++$key]))
        return $possible_vals[$key];
    else
        return $possible_vals[0];
}




/**
 * Best than above (toggle()) function.
 * Toggling between $toggle_values
 *
 * @param  mixed $i             :   Any index, if it is out of range of $toggle_values, it will re index automatically.
 * @param  mixed $toggle_values :   Eg: array('btn btn-info', 'btn btn-success', 'btn btn-danger', 'btn btn-primary', 'btn btn-warning');
 * @return void
 */
function toggle2($i, $toggle_values)
{
    if (!$toggle_values)
        return '';

    if ($i < 0)
        $i = 0;

    if (isset($toggle_values[$i]))
        return $toggle_values[$i];
    else {
        $max = count($toggle_values);
        if ($i >= $max)
            $i = $i % $max;
        if (isset($toggle_values[$i]))
            return $toggle_values[$i];
        else
            return $toggle_values[0];
    }
}


/**
 * GST State List with Code
 * Source: https://www.mastersindia.co/gst/list-of-state-codes-under-gst/
 * 
 * Format array(GST State Code => GST State Name);
 */
function get_GST_state_codes($state_code = '', $sort = TRUE)
{
    // $st['01'] = 'JAMMU AND KASHMIR';
    // $st['02'] = 'HIMACHAL PRADESH';
    // $st['03'] = 'PUNJAB';
    // $st['04'] = 'CHANDIGARH';
    // $st['05'] = 'UTTARAKHAND';
    // $st['06'] = 'HARYANA';
    // $st['07'] = 'DELHI';
    // $st['08'] = 'RAJASTHAN';
    // $st['09'] = 'UTTAR  PRADESH';
    // $st['10'] = 'BIHAR';
    // $st['11'] = 'SIKKIM';
    // $st['12'] = 'ARUNACHAL PRADESH';
    // $st['13'] = 'NAGALAND';
    // $st['14'] = 'MANIPUR';
    // $st['15'] = 'MIZORAM';
    // $st['16'] = 'TRIPURA';
    // $st['17'] = 'MEGHLAYA';
    // $st['18'] = 'ASSAM';
    // $st['19'] = 'WEST BENGAL';
    // $st['20'] = 'JHARKHAND';
    // $st['21'] = 'ODISHA';
    // $st['22'] = 'CHATTISGARH';
    // $st['23'] = 'MADHYA PRADESH';
    // $st['24'] = 'GUJARAT';
    // $st['25'] = 'DAMAN AND DIU';
    // $st['26'] = 'DADRA AND NAGAR HAVELI';
    // $st['27'] = 'MAHARASHTRA';
    // $st['28'] = 'ANDHRA PRADESH (old)';
    // $st['29'] = 'KARNATAKA';
    // $st['30'] = 'GOA';
    // $st['31'] = 'LAKSHWADEEP';
    // $st['32'] = 'KERALA';
    // $st['33'] = 'TAMIL NADU';
    // $st['34'] = 'PUDUCHERRY';
    // $st['35'] = 'ANDAMAN AND NICOBAR ISLANDS';
    // $st['36'] = 'TELANGANA';
    // $st['37'] = 'ANDHRA PRADESH (NEW)';


    $CI = get_instance();
    $CI->load->model('states_mdl', 'states');
    $st = $CI->states->get_active_option();

    if ($sort)
        asort($st);

    if ($state_code) {
        if (isset($st[$state_code]))
            return $st[$state_code];
        else
            return '';
    }

    return $st;
}



function print_pre($arr)
{
    echo "<div style='margin-left:300px;'>";
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    echo "</div>";
}

function echo_div($s)
{
    echo "<div style='margin-left:300px;'>";
    echo $s;
    echo "</div>";
}


function get_area_types($index = 0)
{
    $a = array(1 => 'Corporation', 2 => 'Municipality', 3 => 'Panchayath');
    if ($index && isset($a[$index]))
        return $a[$index];
    else
        return $a;
}

function get_clients_upload_dir($sub_folder = '')
{
    $CI = get_instance();
    $upload_dir = './' . UPLOAD_DIR . '/CLI_' . $CI->clnt_id;
    if ($sub_folder)
        $upload_dir .= "/$sub_folder";
    return $upload_dir;
}




// function if_set($d, $a, $b, $flag = false)
// {
//     if ($flag) {
//         if (isset($d) && $d)
//             return $a;
//         else
//             return $b;
//     } else {
//         if (isset($d))
//             return $a;
//         else
//             return $b;
//     }
// }
