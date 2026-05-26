<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Absence_model extends CI_Model
{
	public function getAllAbsentData($startPeriod, $endPeriod)
	{
		$this->db->where('absent_date >=', $startPeriod);
		$this->db->where('absent_date <=', $endPeriod);
		$this->db->order_by('absent_date', 'DESC');
		$this->db->order_by('permit_type', 'ASC');
		$this->db->order_by('cti_id', 'ASC');
		return $this->db->get('daily_absence')->result_array();
	}

	public function getAllActiveAgent()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

	public function addNewAbsentData($data)
	{
		$this->db->insert('daily_absence', $data);
		return $this->db->affected_rows();
	}

	public function deleteAbsenceById($id)
	{
		$this->db->where('absent_id', $id);
		$this->db->delete('daily_absence');
		return $this->db->affected_rows();
	}

	public function checkExistingAbsence($agent, $date)
	{
		$this->db->where('cti_id', $agent);
		$this->db->where('absent_date', $date);
		return $this->db->get('daily_absence')->num_rows();
	}

	public function getAllAbsentDataByAgentByPeriodDetail($agent, $startPeriod, $endPeriod)
	{
		$this->db->where('absent_date >=', $startPeriod);
		$this->db->where('absent_date <=', $endPeriod);
		$this->db->where('cti_id', $agent);
		$this->db->order_by('absent_date', 'DESC');
		$this->db->order_by('permit_type', 'ASC');
		$this->db->order_by('cti_id', 'ASC');
		return $this->db->get('daily_absence')->result_array();
	}

	public function getAllAbsentDataByAgentByPeriod($agent, $startPeriod, $endPeriod)
	{
		$query = "SELECT
					working_calendar.working_month AS working_month,
					working_calendar.working_day AS working_day,
					daily_absence.cti_id AS agent,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) AS permit_sick,
					COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_unpaid_leave,
					COUNT(CASE WHEN daily_absence.permit_type = '3 Hours-permit' THEN 1 END) AS permit_3hour,
					COUNT(CASE WHEN daily_absence.permit_type = 'Covid' THEN 1 END) AS permit_covid,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_total
				  FROM working_calendar
				  LEFT JOIN daily_absence
				  ON working_calendar.working_month = DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') AND daily_absence.cti_id = '$agent'
				  WHERE working_calendar.working_month BETWEEN '$startPeriod' AND '$endPeriod'
				  GROUP BY working_calendar.working_month
				  ";
		return $this->db->query($query)->result_array();
	}

	public function getAllAbsentDataByAgentTotal($agent, $startPeriod, $endPeriod)
	{
		$query = "SELECT
					SUM(working_calendar.working_day) AS working_days,
					daily_absence.cti_id AS agent,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) AS permit_sick,
					COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_unpaid_leave,
					COUNT(CASE WHEN daily_absence.permit_type = '3 Hours-permit' THEN 1 END) AS permit_3hour,
					COUNT(CASE WHEN daily_absence.permit_type = 'Covid' THEN 1 END) AS permit_covid,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_total
				  FROM working_calendar
				  LEFT JOIN daily_absence
				  ON working_calendar.working_month = DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') AND daily_absence.cti_id = '$agent'
				  WHERE working_calendar.working_month BETWEEN '$startPeriod' AND '$endPeriod'
				  ";
		return $this->db->query($query)->row_array();
	}

	public function getSummaryByPeriod($startPeriod, $endPeriod)
	{
		$query = "SELECT
					user.user_id AS agent,
					user.npk AS npk,
					user.fullname AS fullname,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) AS permit_sick,
					COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_unpaid_leave,
					COUNT(CASE WHEN daily_absence.permit_type = '3 Hours-permit' THEN 1 END) AS permit_3hour,
					COUNT(CASE WHEN daily_absence.permit_type = 'Covid' THEN 1 END) AS permit_covid,
					COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) AS permit_total
				  FROM working_calendar
				  JOIN user
				  LEFT JOIN daily_absence ON user.user_id = daily_absence.cti_id
				  WHERE DATE_FORMAT(working_calendar.working_month, '%Y-%m-01') BETWEEN '$startPeriod' AND '$endPeriod'
				  AND user.is_active = 1
				  AND LEFT(user.jobcode, 6) LIKE 'cs-ccc' 
				  AND DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') BETWEEN '$startPeriod' AND '$endPeriod'
				  GROUP BY user.user_id
				  ORDER BY user.status, user.user_id
				  ";
		return $this->db->query($query)->result_array();
	}

	public function getTotalWorkingDays($startPeriod, $endPeriod)
	{
		$this->db->select('SUM(working_day) AS working_day');
		$this->db->where('working_month >=', $startPeriod);
		$this->db->where('working_month <=', $endPeriod);
		return $this->db->get('working_calendar')->row_array();
	}

	public function getAllAgent()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

	public function toExcelDetailAbsentData($startPeriod, $endPeriod)
	{
		$this->db->select('daily_absence.absent_date AS absent_date');
		$this->db->select('daily_absence.cti_id AS cti_id');
		$this->db->select('daily_absence.permit_type AS permit_type');
		$this->db->select('daily_absence.permit_reason AS permit_reason');
		$this->db->select('daily_absence.permit_remark AS permit_remark');
		$this->db->select('user.fullname AS fullname');
		$this->db->select('user.npk AS npk');
		$this->db->join('user', 'daily_absence.cti_id = user.user_id');
		$this->db->where('absent_date >=', $startPeriod);
		$this->db->where('absent_date <=', $endPeriod);		
		$this->db->order_by('absent_date', 'DESC');
		$this->db->order_by('permit_type', 'ASC');
		$this->db->order_by('cti_id', 'ASC');
		return $this->db->get('daily_absence')->result_array();
	}

	public function updateAbsenceById($data)
	{
		$this->db->set('absent_date', $data['absent_date']);
		$this->db->set('cti_id', $data['cti_id']);
		$this->db->set('permit_type', $data['permit_type']);
		$this->db->set('permit_reason', $data['permit_reason']);
		$this->db->set('permit_remark', $data['permit_remark']);
		$this->db->set('last_modified_by', $data['last_modified_by']);
		$this->db->set('last_modified_at', $data['last_modified_at']);
		$this->db->where('absent_id', $data['absent_id']);
		$this->db->update('daily_absence');
		return $this->db->affected_rows();
	}

	public function getSummaryByAgent($startPeriod, $endPeriod)
	{
		return $this->db->get('daily_absence')->result_array();
	}
}
