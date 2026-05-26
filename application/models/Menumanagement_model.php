<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menumanagement_model extends CI_Model
{
    public function getAllAccessLevel()
    {
        return $this->db->get('user_role')->result_array();
    }

    public function getAllMenus($access_level)
    {
        $this->db->where('role_access', $access_level);
        $this->db->where('menu_access.menu_id !=', 1);
        $this->db->where('menu_access.menu_id !=', 99);
        $this->db->where('menu_access.menu_id !=', 98);
        $this->db->where('menu_access.menu_id !=', 97);
        $this->db->join('menu', 'menu.menu_id = menu_access.menu_id');
        $this->db->order_by('menu.menu_id', 'ASC');
        return $this->db->get('menu_access')->result_array();
    }

    public function getRoleByAccessLevel($roleAccess)
    {
        $this->db->where('role_access', $roleAccess);
        return $this->db->get('user_role')->row_array()['role_name'];
    }

    public function getUnassignedMenu($roleAccess)
    {
        $query = "SELECT menu.menu_id AS menu_id, menu.icon AS menu_icon, menu.menu_name AS menu_name FROM menu WHERE menu.menu_id NOT IN (SELECT menu_access.menu_id AS menu_id FROM menu_access WHERE menu_access.role_access = '$roleAccess')";
        return $this->db->query($query)->result_array();
    }

    public function performDismissMenuAccess($menuid, $roleAccess)
    {
        $this->db->where('menu_id', $menuid);
        $this->db->where('role_access', $roleAccess);
        $this->db->delete('menu_access');
        return $this->db->affected_rows();
    }

    public function performAddMenuAccess($data)
    {
        $this->db->insert_batch('menu_access', $data);
        return $this->db->affected_rows();
    }

    public function deleteSubmenuAccess($submenuid, $roleaccess)
    {
        $this->db->where('submenu_id', $submenuid);
        $this->db->where('role_access', $roleaccess);
        $this->db->delete('submenu_access');
        return $this->db->affected_rows();
    }

    public function addSubmenuAccess($submenuid, $roleaccess)
    {
        $data = [
            'submenu_id' => $submenuid,
            'role_access' => $roleaccess
        ];
        $this->db->insert('submenu_access', $data);
        return $this->db->affected_rows();
    }
}
