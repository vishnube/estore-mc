<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class user_tasks extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'user_tasks';
    }

    function save()
    {
        //Checking tasks
        if (!has_task('tsk_usr_utsk')) {
            $json['status'] = 2;
            $json['o_error'] = 'No task found';
            echo json_encode($json);
            return;
        }



        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Task Fields
        // $v_config = validationConfigs($this->table);

        // $this->form_validation->set_rules($v_config);

        // if (!$this->form_validation->run()) {
        //     $json['status'] = 2; // Failure;

        //     $json['v_error'] = get_val_errors($this->table); // Validation Errors;

        //     echo json_encode($json);
        //     return;
        // }

        $inputs = $this->input->post();

        if (!$inputs[0]['utsk_fk_groups']) {
            $json['status'] = 2; // Failure;

            $json['o_error'] = 'User group not found';

            echo json_encode($json);
            return;
        }

        foreach ($inputs as $input) {
            $row = $this->user_tasks->get_user_task($input['utsk_fk_groups'], $input['utsk_fk_tasks']);

            // If task set
            if ($input['status'] && !$row)
                $this->user_tasks->save($input);
            else if (!$input['status'] && $row)
                $this->user_tasks->remove($row->utsk_id);
        }

        echo json_encode($json);
        return;
    }

    function get_user_tasks()
    {

        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $table = $this->tasks->get_tasks('', '', 2);

        $json['html'] = $this->create_menu($table, 'class="pl-0 pl-md-1 pl-lg-3 pl-xl-5 list-container"');
        echo json_encode($json);
    }


    function create_menu($data, $ul_attr)
    {
        $str = "<ul $ul_attr>";
        foreach ($data as $d) {
            $children = $this->tasks->get_children($d['tsk_id'], 2);
            $str .= $this->create_li($d, $children);

            if ($children) {
                $ul_attr = 'class="list-container child-container collapse" id="' . $this->create_collapse_id($d['tsk_id']) . '"  data-parent-id="' . $d['tsk_id'] . '"';
                $str .=  $this->create_menu($children, $ul_attr);
            }
            // $str .= '</li>';
        }
        $str .= '</ul>';
        return $str;
    }

    function create_collapse_id($p_key)
    {
        return "parent-" . $p_key;
    }

    function create_li($menu, $children)
    {
        $grp_id = $this->input->post('grp_id');
        if ($children) {
            $anchor_attr = 'data-toggle="collapse" href="#' . $this->create_collapse_id($menu['tsk_id']) . '" role="button" aria-expanded="false" aria-controls="collapseExample"';
            $handler_class = '<i class="handler pr-3 fas fa-caret-down"></i>';
            //$switch = '';
        } else {
            $anchor_attr = '';
            $handler_class =  '';
        }
        // If the given uiser have the task, Check. and if no uncheck
        $checked = $this->user_tasks->get_user_task($grp_id, $menu['tsk_id']) ? " checked" : '';

        $switch = '<input type="checkbox" class="switch_utsk" name="utsk_' . $menu['tsk_id'] . '" ' . $checked . ' data-bootstrap-switch data-off-color="danger" data-on-color="success">';

        $anchor_html = $handler_class;
        if ($menu['tsk_icon']) {
            $style = '';
            if ($menu['tsk_color'])
                $style .= 'color:' . $menu['tsk_color'] . ';';
            if ($menu['tsk_primary'])
                $style .= '--fa-primary-color:' . $menu['tsk_primary'] . ';';
            if ($menu['tsk_secondary'])
                $style .= '--fa-secondary-color:' . $menu['tsk_secondary'] . ';';
            $anchor_html .= '<i class="icon ' . $menu['tsk_icon'] . ' fa-inverse" style="' . $style . ';"></i>';
        } else
            $anchor_html .= '<span class="pl-3"></span>';
        $anchor_html .= '<span class="li-name">' . $menu['tsk_name'] . '</span>';


        $str = '
                <li class="clearfix">
                    <div class="float-left left-pan">
                        <input type="hidden" class="tsk_id" value="' . $menu['tsk_id'] . '">
                        <input type="hidden" class="tsk_name" value="' . $menu['tsk_name'] . '">
                        <a ' . $anchor_attr . '>' . $anchor_html . '</a>
                    </div>
                    <div class="float-right">' . $switch . '</div>                    
                </li>';
        return $str;
    }
}
