<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    // Fungsi keur narik menu utama dumasar role
    public function getMenuByRole($role_access) {
        $query = "SELECT menu_access.menu_id AS id, menu.menu_name AS menu, menu.link AS link, menu.icon AS icon
                  FROM menu_access JOIN menu ON menu.menu_id = menu_access.menu_id
                  WHERE menu_access.role_access = '$role_access'
                  ORDER BY menu_access.menu_id ASC";
        return $this->db->query($query)->result_array();
    }

    // Fungsi keur narik submenu dumasar menu utama jeung role
    public function getSubmenuByMenu($menu_id, $role_access) {
        $query = "SELECT submenu.id AS id, submenu.submenu_name AS submenu_name, submenu.submenu_link AS submenu_link
                  FROM submenu JOIN submenu_access ON submenu.id = submenu_access.submenu_id
                  WHERE submenu.menu_id = '$menu_id' AND submenu_access.role_access = '$role_access'
                  ORDER BY submenu_id ASC";
        return $this->db->query($query)->result_array();
    }
}