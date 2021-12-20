<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'My_controller.php';

class Tasks extends My_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_allowed();
        $this->table = 'tasks';
    }

    public function index()
    {
        $data['active_nav'] = 'Development';  // This Must Match with tsk_name @ Tbl: tasks. It should be the parent menu name
        $data['active_subnav'] = 'tasks'; // This Must Match with tsk_name @ Tbl: tasks
        $data['page_head'] = 'tasks';
        $data['icon'] = $this->tasks->get_icon(6);

        // For settings window
        $data['user_settings_reftbl'] = ''; // Value of st_ref_tbl. '' => General settings.
        $this->_render_page('tasks/index', $data);
    }

    function save_many()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $input = $this->input->post(); // Don't use $this->get_inputs()

        $err = array();
        foreach ($input as $r => $i) {
            foreach ($i as $f => $j)
                if (!$j)
                    $err[] = "No value found for '$f' @ row: $r";
        }

        if ($err) {
            $json['status'] = 2;
            $json['o_error'] = '<div>' . implode('</div><div>', $err) . '</div>';
            echo json_encode($json);
            return;
        }

        $q = '';
        foreach ($input as $r => $i) {
            $this->tasks->save($i);
            $q .= $this->tasks->get_last_query();
        }

        $json['query'] = $q;
        echo json_encode($json);
        return;
    }

    function save()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        // Validating Task Fields
        $v_config = validationConfigs($this->table);

        $this->form_validation->set_rules($v_config);

        if (!$this->form_validation->run()) {
            $json['status'] = 2; // Failure;

            $json['v_error'] = get_val_errors($this->table); // Validation Errors;

            echo json_encode($json);
            return;
        }

        $input = $this->get_inputs();
        $tsk_id = $input['tsk_id'];

        $input['tsk_sort'] = $input['tsk_sort'] ? $input['tsk_sort'] : $this->tasks->next_sort($input['tsk_parent']);

        if (!$this->tasks->save($input, $tsk_id)) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t save task';
        }
        $json['query'] = $this->tasks->get_last_query();
        echo json_encode($json);
        return;
    }

    function get_tasks()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $table = $this->tasks->get_tasks('', '', $this->usr_type);

        $json['html'] = $this->create_menu($table, 'class="pl-0 pl-md-1 pl-lg-3 pl-xl-5 list-container"');
        echo json_encode($json);
    }


    function create_menu($data, $ul_attr)
    {
        $str = "<ul $ul_attr>";
        foreach ($data as $d) {
            $children = $this->tasks->get_children($d['tsk_id'], $this->usr_type);
            $str .= $this->create_li($d, $children);

            if ($children) {
                $ul_attr = 'class="list-container child-container collapse" id="' . $this->create_collapse_id($d['tsk_id']) . '"';
                $str .=  $this->create_menu($children, $ul_attr);
            }
            $str .= '</li>';
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
        if ($children) {
            $anchor_attr = 'data-toggle="collapse" href="#' . $this->create_collapse_id($menu['tsk_id']) . '" role="button" aria-expanded="false" aria-controls="collapseExample"';
            $handler_class =  'handler pr-3 fas fa-caret-down';
        } else {
            $anchor_attr = '';
            $handler_class =  'pr-3 far fa-hand-point-right';
        }

        $anchor_html = '<i class="' . $handler_class . '"></i>';
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
        $anchor_html .= '<span class="li-name">' . $menu['tsk_name'] . '&nbsp;(ID: ' . $menu['tsk_id'] . ')</span>';


        $edit_btn = edit_btn('Task', array('.edit_tsk'));
        $status_btn = $menu['tsk_status'] == ACTIVE ? delete_btn('Task', array('.deactivate_tsk'), 'Deactivate') : activate_btn('Task', array('.activate_tsk'));

        $url = $menu['tsk_url'] ? '&nbsp;<span class="cursor-pointer right badge badge-warning"title="Url">' . $menu['tsk_url'] . '</span>' : '';
        $is_menu = $menu['tsk_menu'] == 1 ? '&nbsp;<span class="cursor-pointer right badge bg-purple" title="Show in Menu">Menu</span>' : '';
        $key = $menu['tsk_key'] ? '&nbsp;<span class="cursor-pointer right badge bg-success" title="Key">' . $menu['tsk_key'] . '</span>' : '';
        $sort = '&nbsp;<span class="cursor-pointer right badge bg-info" title="Sort"><i class="fas fa-sort"></i>&nbsp;&nbsp;' . $menu['tsk_sort'] . '</span>';

        $str = '
    <li>
        <input type="hidden" class="tsk_id" value="' . $menu['tsk_id'] . '">
        <input type="hidden" class="tsk_name" value="' . $menu['tsk_name'] . '">
        <a ' . $anchor_attr . '>' . $anchor_html . '</a>
        ' . $key . $url . $is_menu . $sort . '
        <div class="d-inline-block d-md-block float-md-right mr-2"><button class="btn btn-sm btn-info add_many" >ADD CHILDREN</button></div>
        <div class="d-inline-block d-md-block float-md-right mr-2">' . $edit_btn . $status_btn . ' </div>

    </li>';
        return $str;
    }








    function before_edit()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $tsk_id = $this->input->post('tsk_id');
        $row = $this->tasks->get_by_id($tsk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find task';
        }

        $json = array_merge($json, $row);
        echo json_encode($json);
        return;
    }

    function deactivate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $tsk_id = $this->input->post('tsk_id');
        $row = $this->tasks->get_by_id($tsk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find task';
            echo json_encode($json);
            return;
        }

        $this->tasks->deactivate($tsk_id);
        echo json_encode($json);
        return;
    }

    function activate()
    {
        // 1 -> Succes;   2 -> Failure;
        $json['status'] = 1;

        // Validation Errors;
        $json['v_error'] = array();

        // Other Errors;
        $json['o_error'] = '';

        $tsk_id = $this->input->post('tsk_id');
        $row = $this->tasks->get_by_id($tsk_id);

        if (!$row) {
            $json['status'] = 2;
            $json['o_error'] = 'Couldn\'t find task';
            echo json_encode($json);
            return;
        }

        $this->tasks->activate($tsk_id);
        echo json_encode($json);
        return;
    }
}
