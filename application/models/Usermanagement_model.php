<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usermanagement_model extends CI_Model
{
    public function getAllUser($user_access)
    {
        $query = '';
        if ($user_access != 9) {
            $query = "SELECT * FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access WHERE user.role_access != '9' ORDER BY user.is_active DESC, user.user_id";
        } else {
            $query = "SELECT * FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access ORDER BY user.is_active DESC, user.user_id";
        }
        return $this->db->query($query)->result_array();
    }

    public function getAllActiveUsers()
    {
        $this->db->select('user_id');
        $this->db->select('npk');
        $this->db->select('fullname');
        $this->db->select('birthdate');
        $this->db->select('joindate');
        $this->db->select('email_personal');
        $this->db->select('email_address');
        $this->db->select('status');
        $this->db->select('photo');
        $this->db->select('replacement_for');
        $this->db->where('is_active', 1);
        return $this->db->get('user')->result_array();
    }

    public function getWholeUsers()
    {
        $this->db->select('user_id');
        $this->db->select('npk');
        $this->db->select('fullname');
        $this->db->select('birthdate');
        $this->db->select('joindate');
        $this->db->select('email_personal');
        $this->db->select('email_address');
        $this->db->select('status');
        $this->db->select('photo');
        $this->db->select('replacement_for');
        return $this->db->get('user')->result_array();
    }

    public function getAllUserDesc($user_id)
    {
        if ($user_id != '9') {
            $query = "SELECT * FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access WHERE user.role_access != '9' ORDER BY joindate DESC, user.user_id";
            return $this->db->query($query)->result_array();
        } else {
            $query = "SELECT * FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access WHERE jobdesk.dept_code = 'cs-ccc' ORDER BY joindate DESC, user.is_active DESC, user.user_id";
            return $this->db->query($query)->result_array();
        }        
    }

    public function getAllUserBirthdate($user_id)
    {
        if ($user_id != '9') {
            $query = "SELECT user.user_id AS user_id, user.fullname AS fullname, user.birthdate AS birthdate FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access WHERE user.role_access != '9' AND jobdesk.dept_code = 'cs-ccc' AND user.is_active = '1' ORDER BY DATE_FORMAT(user.birthdate, '%m%d%y') ASC, user.user_id";
            return $this->db->query($query)->result_array();
        } else {
            $query = "SELECT user.user_id AS user_id, user.fullname AS fullname, user.birthdate AS birthdate FROM user_role JOIN user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code AND user.role_access = user_role.role_access WHERE user.is_active = '1' ORDER BY DATE_FORMAT(user.birthdate, '%m%d%y') ASC, user.user_id";
            return $this->db->query($query)->result_array();
        }        
    }

    public function getAllDepartment()
    {
        $query = "SELECT jobdesk.jobcode AS jobcode, jobdesk.jobdesk AS jobdesk, department.department_name AS department FROM jobdesk JOIN department ON jobdesk.dept_code = department.dept_code";
        return $this->db->query($query)->result_array();
    }

    public function getAllAccess($user_id)
    {
        if ($user_id != '9') {
            $this->db->where('role_access !=', '9');
            $this->db->where('role_access !=', '5');
            return $this->db->get('user_role')->result_array();
        } else {
            return $this->db->get('user_role')->result_array();
        }
    }

    public function addUser($data)
    {
        $this->db->insert('user', $data);
        return $this->db->affected_rows();
    }

    public function getUserById($user_id)
    {
        $this->db->select('*');
        $this->db->select('user_role.role_name AS role_name');
        $this->db->where('user_id', $user_id);
        $this->db->join('jobdesk', 'ON user.jobcode = jobdesk.jobcode');
        $this->db->join('user_role', 'ON user.role_access = user_role.role_access');
        return $this->db->get('user')->row_array();
    }

    public function updateUser($data)
    {
        $this->db->update('user', $data, ['user_id' => $data['user_id']]);
        return $this->db->affected_rows();
    }

    public function deleteUserById($user_id)
    {
        $this->db->delete('user', ['user_id' => $user_id]);
        return $this->db->affected_rows();
    }

    public function getAllLockedUser()
    {
        $query = "SELECT password_reset.id AS id, password_reset.user_id AS user_id, password_reset.ip_address AS ip_address, password_reset.datetime AS datetime, password_reset.reason AS reason, password_reset.is_reseted AS is_reseted, password_reset.status AS status, password_reset.reset_by AS reset_by, password_reset.reset_on AS reset_on, user.is_locked AS is_locked FROM password_reset JOIN user ON password_reset.user_id = user.user_id WHERE password_reset.is_reseted = '0' OR password_reset.is_unlocked = '0' ORDER BY datetime DESC";
        return $this->db->query($query)->result_array();
    }

    public function resetUser($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->set('is_unlocked', $data['is_unlocked']);
        $this->db->set('reset_by', $data['reset_by']);
        $this->db->set('reset_on', $data['reset_on']);
        $this->db->update('password_reset');
        return $this->db->affected_rows();
    }

    public function unlockUser($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->set('is_locked', 0);
        $this->db->update('user');
        return $this->db->affected_rows();
    }

    public function resetForgotPassword($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->set('is_reseted', $data['is_reseted']);
        $this->db->set('reset_by', $data['reset_by']);
        $this->db->set('reset_on', $data['reset_on']);
        $this->db->update('password_reset');
        return $this->db->affected_rows();
    }

    public function resetUserPassword($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->set('password', $data['password']);
        $this->db->update('user');
        return $this->db->affected_rows();
    }

    public function dismissResetRequest($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->set('reason', $data['reason']);
        $this->db->set('is_reseted', $data['is_reseted']);
        $this->db->set('status', $data['status']);
        $this->db->set('reset_by', $data['reset_by']);
        $this->db->set('reset_on', $data['reset_on']);
        $this->db->update('password_reset');
        return $this->db->affected_rows();
    }

    public function getAllUserId()
    {
        $this->db->select('user.user_id as user_id');
        $this->db->select('user.npk as npk');
        $this->db->select('user.fullname as fullname');
        $this->db->order_by('user.status');
        return $this->db->get('user')->result_array();
    }

    public function addUserToCccinfo($data)
    {
        $cccinfo = $this->load->database('cccinfo', TRUE);
        $cccinfo->insert('user', $data);
        return $cccinfo->affected_rows();
    }
    
}
