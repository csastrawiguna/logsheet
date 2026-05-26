<?php

function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('user_id')) {
        redirect('auth');
    } else {
        $role_access = $ci->session->userdata('role_access');
        $menu = $ci->uri->segment(1);
        $query = "SELECT menu.link AS link, menu.menu_id AS id, menu_access.role_access AS role_access FROM menu JOIN menu_access ON menu.menu_id = menu_access.menu_id WHERE menu.link = '$menu' AND menu_access.role_access = '$role_access'";
        $queryMenu = $ci->db->query($query);

        $active = 1;
        if($ci->db->get('survey_setting')->row_array()['show_survey'] == $active) {
        } else {            
            if ($queryMenu->num_rows() < 1) {
                redirect('dashboard');
            }        
        }
    }
}

function check_accessold()
{
    $ci = get_instance();
    $role_access = $ci->session->userdata('role_access');
    $menu = $ci->uri->segment(1);
    $submenu = $ci->uri->segment(2);

    $queryMenu = $ci->db->get_where('menu', ['link' => $menu])->row_array();
    $menu_id = $queryMenu['menu_id'];

    $query = "SELECT submenu.id AS id, submenu.menu_id AS menu_id, submenu.submenu_link AS submenu_link, submenu_access.role_access AS role_access FROM submenu JOIN submenu_access ON submenu.id = submenu_access.submenu_id WHERE submenu.menu_id = '$menu_id' AND submenu_access.role_access = '$role_access'";

    $querySubmenu = $ci->db->query($query)->row_array();
    $userAccess = $ci->db->get_where('submenu_access', ['role_access' => $role_access]);

    if (empty($submenu)) {
        redirect($querySubmenu['submenu_link']);
        // var_dump($submenu);
        // die;
        // redirect($submenu);
    } else {
        $submenu_link = $querySubmenu['submenu_link'];
        $submenu_id = $ci->db->get_where('submenu', ['submenu_link' => $submenu_link])->row_array()['id'];
        $submenuAccess = $ci->db->get_where('submenu_access', ['role_access' => $role_access, 'submenu_id' => $submenu_id]);
        
        if ($submenuAccess->num_rows() < 1) {
            redirect($querySubmenu['submenu_link']);
        }
    }
}

function check_access()
{
    $ci = get_instance();
    $role_access = $ci->session->userdata('role_access');
    $menu = $ci->uri->segment(1);
    $submenu = $ci->uri->segment(2);

    $queryMenu = $ci->db->get_where('menu', ['link' => $menu])->row_array();
    $menu_id = $queryMenu['menu_id'];

    // Check is have access to menu or not
    $queryCheckMenuAccess = "SELECT menu.menu_id, menu.menu_name, menu.link FROM menu_access JOIN menu ON menu.menu_id = menu_access.menu_id WHERE menu_access.role_access = '$role_access' AND menu_access.menu_id = '$menu_id'";

    if ($ci->db->query($queryCheckMenuAccess)->num_rows() < 1) {
        redirect('dashboard');
    } else {
        // Check is have access to submenu or not
        $accessedSubmenu = $menu . "/" . $submenu;

        // Get submenu id
        $submenu_id = $ci->db->get_where('submenu', ['submenu_link' => $accessedSubmenu])->row_array()['id'];

        // Check existing on submenu access or not
        $queryCheckSubmenuAccess = "SELECT submenu.id, submenu.submenu_link, submenu_access.role_access FROM submenu_access JOIN submenu ON submenu.id = submenu_access.submenu_id WHERE submenu_access.role_access = '$role_access' AND submenu_access.submenu_id = '$submenu_id'";

        // Get allowed submenu access
        $queryAllowedSubmenu = "SELECT submenu.id, submenu.submenu_link, submenu_access.role_access FROM submenu_access JOIN submenu ON submenu.id = submenu_access.submenu_id WHERE submenu_access.role_access = '$role_access' AND submenu.menu_id = '$menu_id'";
        $allowedSubmenu = $ci->db->query($queryAllowedSubmenu)->row_array()['submenu_link'];

        if ($ci->db->query($queryCheckSubmenuAccess)->num_rows() < 1) {
            redirect($allowedSubmenu);
        }
    }
}

function check_submenu_access($submenu, $role_access)
{
    $ci = get_instance();
    $ci->db->where('submenu_id', $submenu);
    $ci->db->where('role_access', $role_access);
    $result = $ci->db->get('submenu_access')->num_rows();

    if ($result > 0) {
        return 'checked = "checked"';
    }
}

function check_menu_access($menu_id, $role_access)
{
    $ci = get_instance();
    $ci->db->where('menu_id', $menu_id);
    $ci->db->where('role_access', $role_access);
    $result = $ci->db->get('menu_access')->num_rows();

    if ($result > 0) {
        return 'checked = "checked"';
    }
}

function admin_access()
{
    $ci = get_instance();
    $role_access = $ci->session->userdata('role_access');
    $allowed = ['1', '5', '9'];
    if (in_array($role_access, $allowed)) {
        return;
    } else {
        redirect('dashboard');
    }
}

function backoffice_access()
{
    $ci = get_instance();
    $role_access = $ci->session->userdata('role_access');
    $allowed = ['1', '2', '4', '5', '6', '9'];
    if (in_array($role_access, $allowed)) {
        return;
    } else {
        redirect('dashboard');
    }
}

function logrecord()
{
    $ci = get_instance();
    
}
