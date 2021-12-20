<?php
defined('BASEPATH') or exit('No direct script access allowed');
function format_unit_group($groups, $input = array())
{
    $CI = get_instance();

    $CI->load->model("unit_groups_mdl", 'unit_groups');
    $CI->load->model('units_mdl', 'units');

    $unt_option = $CI->units->get_unit_option(ACTIVE, 2);
    $unit_groups = array();
    $toggle_colors = array('btn btn-info', 'btn btn-success', 'btn btn-danger', 'btn btn-primary', 'btn btn-warning');


    foreach ($groups as $i => $gp) {
        $input['ugp_group_no'] = $gp['ugp_group_no'];
        $units = $CI->unit_groups->index($input);
        $ugp_name = '';
        if ($units) {
            $arr = array();
            foreach ($units as $j => $r) {
                $ugp_name = $r['ugp_name'];
                $def_icon = $r['ugp_default'] == 1 ? other_btn('Defult Unit', '', 'fal fa-check', '', 'color:green') : '';
                // If the basic unit
                if ($r['ugp_is_basic'] == 1)
                    $arr[] = '<span class="' . toggle2($j, $toggle_colors) . '" style="height:45px">' . $def_icon . $unt_option[$r['ugp_fk_units']] . "</span>";
                else {
                    $rel = (float)$r['ugp_rel'];  // Removing trailing zeros
                    $arr[] = '<span class="' . toggle2($j, $toggle_colors) . '" style="height:45px">' . $def_icon . '1 ' . $unt_option[$r['ugp_fk_units']] . " = " . $rel . " " . $unt_option[$r['ugp_fk_bunits']] . "</span>";
                }
            }
            $unit_groups[$i]['ugp_name'] = $ugp_name;
            $unit_groups[$i]['text'] = implode('&nbsp;<button class="btn btn-secondary" style="height:45px"><i class="far fa-chevron-right"></i></button>&nbsp;', $arr);
            $unit_groups[$i]['ugp_group_no'] = $input['ugp_group_no']; // value of ugp_group_no.
            $unit_groups[$i]['ugp_status'] = $input['ugp_status']; // value of ugp_group_no.
        }
    }
    return $unit_groups;
}

function get_product_units($prd_id, $grp_nos = array())
{
    if (!$prd_id)
        return array();

    $CI = get_instance();
    $CI->load->model("unit_groups_mdl", 'unit_groups');
    $CI->load->model('units_mdl', 'units');
    $CI->load->model('product_units_mdl', 'product_units');

    // Collecting array of group numbers of the given product
    if (!$grp_nos)
        $grp_nos = $CI->product_units->get_unit_goups_no($prd_id);

    //print_r($grp_nos);

    // print_pre($grp_nos);
    $ugps = array();
    foreach ($grp_nos as $g)
        $ugps[] = $CI->unit_groups->get_by_group_no($g);

    return $ugps;
}

/**
 * get_formated_product_units
 *
 * @param  mixed $prd_id        :   Product id
 * @param  mixed $ugp_group_no  :   array of ugp_group_no. Eg: array(4,2)
 * @param  mixed $tag           :   Container element to wrap each units in a unit group (Tbl: unit_group). 
 *                                      Eg: 'span', 'div' ect
 *                                      Output: <span></span>
 * 
 * @param  mixed $sep           :   Seperator string between each Container element 
 *                                      Eg: ' >> ', '<i class="fas fa-chevron-double-right"></i>' ect
 *                                      Output: <span></span>  >> <span></span>
 * 
 * @param  mixed $fixed_class   :   Class name for Container element
 *                                      Eg: 'btn btn-info' 
 * @param  mixed $fixed_style   :   Style for Container element.
 *                                      Eg: 'padding: 2px; margin: 5px';
 * @param  mixed $toggle_class  :   array('btn btn-info', 'btn btn-success', 'btn btn-danger', 'btn btn-primary', 'btn btn-warning');
 * @param  mixed $toggle_style  :   array('background-color: red; color: white', 'background-color: green; color: red');
 * @param  mixed $def_icon      :   Icon HTML that will be presented infront of the default-unit of the unit group.
 *                                      Eg: 1. '<i class="fal fa-check" title="Default Unit"></i>'
 *                                          2. other_btn('Defult Unit', '', 'fal fa-check', '', 'color:green'); // returns html
 * 
 * @param  mixed $def_tag_open  :   Container open tag for default unit.
 *                                      Eg: '<span class="">'
 * 
 * @param  mixed $def_tag_close :   Container open tag for default unit.
 *                                      Eg: '<span class="">'
 * 
 * @param  mixed $show_rel      :   Show the relation of the unit with the basic unit. TRUE/FALSE;
 * 
 * 
 *      For example;
        $sep = '<i class="fas fa-chevron-double-right"></i>';
        $fixed_class =  'border border-danger'; 
        $fixed_style = 'padding: 5px; margin: 5px';
        $toggle_class = array('text-info', 'text-success', 'text-danger', 'text-primary', 'text-warning');
        $toggle_style = array('font-size:20px;', 'font-size:40px;', 'font-size:60px;');
        $def_icon = '<i class="fal fa-check" style="color:#0df611" title="Default Unit"></i> &nbsp;';
        $dto = "<span class='btn btn-danger'>";
        $dtc = "</span>";
        $prd_id = 8;
        get_formated_product_units($prd_id,'', 'span', $sep, $fixed_class, $fixed_style, $toggle_class, $toggle_style, $def_icon, $dto, $dtc);
 * 
 * 
 * @return void
 */
function get_formated_product_units($prd_id, $ugp_group_no = array(), $tag = 'span', $sep = '', $fixed_class = '', $fixed_style = '', $toggle_class = array(), $toggle_style = array(), $def_icon = '', $def_tag_open = '', $def_tag_close = '', $show_rel = TRUE)
{
    $ugps = get_product_units($prd_id, $ugp_group_no);

    $f = array();

    if (!$ugps)
        return $f;

    $CI = get_instance();
    $CI->load->model('units_mdl', 'units');
    $CI->load->model('product_units_mdl', 'product_units');
    $unt_option = $CI->units->get_unit_option(ACTIVE, 2);
    foreach ($ugps as $ugp) {
        // print_pre($ugp);
        $punt_row = array();
        $group_no = '';
        $status = '';
        $str_array = array();
        $a = array();
        $temp = array();
        foreach ($ugp as $i => $u) {
            $str = '';
            $dico = $u['ugp_default'] == 1 ? $def_icon : ''; // Default unit icon
            $dto = $u['ugp_default'] == 1 ? $def_tag_open : ''; // Default unit container opening tag
            $dtc = $u['ugp_default'] == 1 ? $def_tag_close : ''; // Default unit container clossing tag

            // Array format
            $a['is_basic'] = $u['ugp_is_basic'];
            $a['is_default'] = $u['ugp_default'];
            $a['unit_id'] = $u['ugp_fk_units'];
            $a['unit_name'] = $unt_option[$u['ugp_fk_units']];
            $a['basic_unit_id'] = $u['ugp_fk_bunits'];
            $a['basic_unit_name'] = $unt_option[$u['ugp_fk_bunits']];
            $a['relation'] = (float)$u['ugp_rel'];  // Removing trailing zeros

            // Text Format
            $fixed_class = $toggle_class && $fixed_class ? ' ' . $fixed_class : $fixed_class;
            $fixed_style = $toggle_style && $fixed_style ? ' ' . $fixed_style : $fixed_style;
            $class = ' class="' . toggle2($i, $toggle_class) .  $fixed_class . '"';
            $style = ' style="' . toggle2($i, $toggle_style) . $fixed_style . '"';


            // If Relation of the unit with basic unit is not showing, Showing the relation in title.
            $title = array();
            if (!$show_rel) {
                if ($u['ugp_is_basic'] == 1)
                    $title[] = 'Basic Unit';
                else
                    $title[] = '1 ' . $unt_option[$u['ugp_fk_units']] . " = " . (float)$u['ugp_rel'] . " " . $unt_option[$u['ugp_fk_bunits']];
            }

            $title = 'title="' . implode(', ', $title) . '"';

            if ($tag) {
                $str .= "<$tag $class $style $title>";

                $str .= '<input type="hidden" class="ugp_is_basic" value="' . $u['ugp_is_basic'] . '">';
                $str .= '<input type="hidden" class="ugp_default" value="' . $u['ugp_default'] . '">';
                $str .= '<input type="hidden" class="ugp_fk_units" value="' . $u['ugp_fk_units'] . '">';
                $str .= '<input type="hidden" class="unit_name" value="' . $a['unit_name'] . '">';
                $str .= '<input type="hidden" class="ugp_fk_bunits" value="' . $u['ugp_fk_bunits'] . '">';
                $str .= '<input type="hidden" class="basic_unit_name" value="' . $a['basic_unit_name'] . '">';
                $str .= '<input type="hidden" class="ugp_rel" value="' . $a['relation'] . '">';

                $str .= $dico;
                $str .= $dto;

                // If the basic unit
                if ($u['ugp_is_basic'] == 1)
                    $str .=  $unt_option[$u['ugp_fk_units']];
                else {
                    $rel = (float)$u['ugp_rel'];  // Removing trailing zeros
                    $str .= $show_rel ? '1 ' . $unt_option[$u['ugp_fk_units']] . " = " . $rel . " " . $unt_option[$u['ugp_fk_bunits']] : $unt_option[$u['ugp_fk_units']];
                }
                $str .= $dtc;

                $str .= "</$tag>";
            } else {
                // If the basic unit
                if ($u['ugp_is_basic'] == 1)
                    $str .=  $unt_option[$u['ugp_fk_units']];
                else {
                    $rel = (float)$u['ugp_rel'];  // Removing trailing zeros
                    $str .= $show_rel ? '1 ' . $unt_option[$u['ugp_fk_units']] . " = " . $rel . " " . $unt_option[$u['ugp_fk_bunits']] : $unt_option[$u['ugp_fk_units']];
                }
            }

            // The following assignment is same for all units of unit group. So doing only onece.
            if (!$punt_row) {
                $group_no = $u['ugp_group_no'];
                $status = $u['ugp_status'];
                $punt_row = $CI->product_units->get_row(array('punt_fk_products' => $prd_id, 'punt_group_no' => $u['ugp_group_no']));
            }

            $temp[] = $a;
            $str_array[] = $str;
        }
        // ugp_status => Status of all units in the unit group. 
        $f[] = array('punt_id' => $punt_row['punt_id'], 'punt_fk_products' => $punt_row['punt_fk_products'], 'punt_group_no' => $punt_row['punt_group_no'], 'punt_status' => $punt_row['punt_status'], 'ugp_status' => $status, 'array_format' => $temp, 'text_format' => implode($sep, $str_array));
    }

    return $f;
}

function get_product_unit_options($prd_id, $return = 'html')
{
    $CI = get_instance();
    $units =  get_product_units($prd_id);
    $unt_option = array();
    $sel = '';
    foreach ($units as $unt_group) {
        foreach ($unt_group as $unt) {
            $unt_option[$unt['ugp_id']] = $CI->units->get_name_by_id($unt['ugp_fk_units']);
            if ($unt['ugp_is_basic'] == 1)
                $sel = $unt['ugp_id'];
        }
    }

    if ($return == 'html')
        return get_options($unt_option, $sel, "", FALSE, TRUE, 'NO UNITS');
    else if ($return == 'option_array')
        return $unt_option;
    else if ($return == 'id_array')
        return array_keys($unt_option);
}




/**
 * Same as get_product_unit_options(), 
 * But added an extra variable $sel to select a Predefined unit by default
 *
 * @param  mixed $prd_id
 * @param  mixed $return
 * @param  mixed $sel       :   ugp_id
 * @return void
 */
function get_product_unit_options_2($prd_id, $sel = '', $return = 'html')
{
    $CI = get_instance();
    $units =  get_product_units($prd_id);
    $unt_option = array();
    $selOffset = '';
    $found = false; // Not found $sel
    foreach ($units as $unt_group) {
        foreach ($unt_group as $unt) {
            $unt_option[$unt['ugp_id']] = $CI->units->get_name_by_id($unt['ugp_fk_units']);
            if ($sel && $sel == $unt['ugp_id'])
                $found = true;
        }
    }

    if ($return == 'html') {
        if ($sel && !$found)
            return get_options($unt_option, $sel);
        else
            return get_options($unt_option, $sel, "", FALSE, TRUE, 'NO UNITS');
    } else if ($return == 'option_array')
        return $unt_option;
    else if ($return == 'id_array')
        return array_keys($unt_option);
}
