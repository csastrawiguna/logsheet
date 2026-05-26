<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile_model extends CI_Model
{
    public function updatePassword($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->set('password', $data['password']);
        $this->db->update('user');
        return $this->db->affected_rows();
    }

    public function getUserById($user_id)
    {
        $query = "SELECT * FROM user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code WHERE user.user_id = '$user_id'";
        return $this->db->query($query)->row_array();
    }

    public function getCurrentProfileData($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get('user')->row_array();
    }

    public function updatePersonalInfo($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->update('user', $data);
        return $this->db->affected_rows();
    }

    public function removeProfilePhoto($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->set('photo', 'nophoto.png');
        $this->db->update('user');
        return $this->db->affected_rows();   
    }

    public function getAllBackground()
    {
        $this->db->distinct('bg');
        $this->db->select('bg');
        return $this->db->get('user')->result_array();
    }

    public function updateBackground($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->set('bg', $data['bg']);
        $this->db->set('bg_position', $data['bg_position']);
        $this->db->update('user');
        return $this->db->affected_rows();      
    }
}
