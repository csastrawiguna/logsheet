<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function getAllUserBirthdate()
    {
        $this->db->select('user_id');
        $this->db->select('fullname');
        $this->db->select('birthdate');
        $this->db->select('role_access');
        // $this->db->select('DAYOFYEAR(birthdate) AS daybirth');
        // $this->db->select('DAYOFYEAR(CURDATE()) AS dayofyear');
        $this->db->select("DAYOFYEAR(birthdate)-DAYOFYEAR(CURDATE()) AS diff");
        $this->db->where('DAYOFYEAR(birthdate)-DAYOFYEAR(CURDATE()) >=', -1);
        $this->db->where('DAYOFYEAR(birthdate)-DAYOFYEAR(CURDATE()) <=', 8);
        $this->db->where('is_active', 1);
        $this->db->order_by('DAYOFYEAR(birthdate)-DAYOFYEAR(CURDATE())', 'ASC');
        return $this->db->get('user')->result_array();
    }

    public function getAssignedElearning()
    {    	
    	$this->db->join('elearning_category', 'elearning_category.id = elearning_assignment.elearning_id');
    	$this->db->where('user_id', $this->session->userdata('user_id'));
    	$this->db->where('elearning_category.status', '1');
    	$this->db->select('elearning_category.id AS elearning_id');
    	$this->db->select('elearning_category.enddate AS enddate');
    	$this->db->select('elearning_assignment.posttest_done AS is_done');
    	return $this->db->get('elearning_assignment')->result_array();
    }

    public function getQuote()
    {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->get('user')->row_array()['quote'];
    }

    public function getLeaveBalance($userid, $access)
    {
        if ($access != 9) {
            return $this->_getLeaveBalancePersonal($userid);
        } else {
            return $this->_getLeaveBalanceAll();
        }        
    }

    private function _getLeaveBalancePersonal($userid)
    {
        $query = "SELECT 
                    daily_absence.cti_id AS agent,
                    leave_info.long_leave AS long_leave,
                    leave_info.annual_leave AS annual_leave,
                    COUNT(CASE WHEN daily_absence.permit_type = 'Annual leave' THEN 1 END) AS annual_taken, 
                    COUNT(CASE WHEN daily_absence.permit_type = 'Long leave' THEN 1 END) AS long_taken, 
                    COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS unpaid_taken 
                FROM daily_absence 
                JOIN leave_info ON daily_absence.cti_id = leave_info.agent 
                WHERE leave_info.year LIKE DATE_FORMAT(NOW(), '%Y') 
                AND DATE_FORMAT(daily_absence.absent_date, '%Y') = DATE_FORMAT(NOW(), '%Y')
                AND leave_info.agent = '$userid'
                GROUP BY leave_info.agent";        
        return $this->db->query($query)->result_array();
    }

    private function _getLeaveBalanceAll()
    {
        $query = "SELECT 
                    daily_absence.cti_id AS agent,
                    leave_info.long_leave AS long_leave,
                    leave_info.annual_leave AS annual_leave,
                    COUNT(CASE WHEN daily_absence.permit_type = 'Annual leave' THEN 1 END) AS annual_taken, 
                    COUNT(CASE WHEN daily_absence.permit_type = 'Long leave' THEN 1 END) AS long_taken, 
                    COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS unpaid_taken 
                FROM daily_absence 
                JOIN leave_info ON daily_absence.cti_id = leave_info.agent 
                WHERE leave_info.year LIKE DATE_FORMAT(NOW(), '%Y') 
                AND DATE_FORMAT(daily_absence.absent_date, '%Y') = DATE_FORMAT(NOW(), '%Y')
                GROUP BY leave_info.agent";        
        return $this->db->query($query)->result_array();
    }

    public function getOvertimeLeft($userid)
    {
        $this->db->where('date >=', date("Y-m-d"));
        $this->db->where('agent_scheduled', $userid);
        return $this->db->get('obidience')->result_array();
    }

    public function getPraySchedule()
    {
        return $this->db->get('pray_schedule')->result_array();
    }

    public function getPrayScheduleTimes()
    {
        $this->db->distinct('pray_time');
        $this->db->select('pray_time');
        return $this->db->get('pray_schedule')->result_array();
    }

    public function getAllQueue()
    {
        $this->db->order_by('status');
        $this->db->order_by('saved_at', 'ASC');
        $this->db->order_by('agent');
        return $this->db->get('general_queue')->result_array();
    }

    public function addToQueue($data)
    {
        $this->db->where('agent', $data['agent']);
        $this->db->update('general_queue', $data);
        return $this->db->affected_rows();
    }

    public function addToFinish($data)
    {
        $this->db->where('agent', $data['agent']);
        $this->db->update('general_queue', $data);
        return $this->db->affected_rows();
    }

    public function toReset($data)
    {
        $this->db->where('agent', $data['agent']);
        $this->db->update('general_queue', $data);
        return $this->db->affected_rows();
    }

    public function getProductivityInterval()
    {
        $this->db->order_by('(icall + whatsapp + follow_up)', 'DESC');
        return $this->db->get('productivity_interval')->result_array();
    }

    public function getRamadhanFirstDate()
    {
        return $this->db->get_where('general_setting', ['item' => 'ramadhan_date'])->row_array()['value'];
    }

    public function addNewLebaranReport($data)
    {
        $this->db->insert('lebaran_operation', $data);
        return $this->db->affected_rows();
    }

    public function getLastLebaranYear()
    {
        $query = 'SELECT MAX(year) AS year FROM lebaran_operation';
        return $this->db->query($query)->row_array()['year'];
    }

    public function getLebaranOperationByYear($year)
    {
        $this->db->group_by('date');
        return $this->db->get_where('lebaran_operation', ['year' => $year])->result_array();
    }

    public function updateLebaranReport($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('lebaran_operation', $data);
        return $this->db->affected_rows();
    }
}
