<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function getUser($user_id)
    {
        $query = "SELECT user.user_id AS user_id, user.fullname AS fullname, user.jobcode AS jobcode, user.password AS password, user.is_active AS is_active, user.is_locked AS is_locked, user.role_access AS role_access, user.status AS status, user_role.role AS role, user_role.role_name AS role_name, view_theme.theme_text AS theme_text, view_theme.theme_name AS theme_name, view_theme.id AS theme_id, user.photo AS profile_photo FROM user_role JOIN user JOIN view_theme ON user.role_access = user_role.role_access AND user.view_theme = view_theme.id WHERE user.user_id = '$user_id'";

        // $this->db->where('user_id', $user_id);
        return $this->db->query($query)->row_array();
    }

    public function requestResetAccount($data)
    {
        $this->db->insert('password_reset', $data);
        return $this->db->affected_rows();
    }

    public function updateLoginIp($user_id, $ip) {
        $this->db->where('user_id', $user_id);
        $this->db->set('login_ip', $ip);
        $this->db->set('login_at', date("Y-m-d H:i:s"));
        $this->db->update('user');
        return $this->db->affected_rows();
    }
}
