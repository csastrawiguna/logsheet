<?php defined('BASEPATH') or exit('No direct script access allowed');

class Leave_model extends CI_Model
{
	public function getAllLeaveData()
	{
		return $this->db->get('calendar')->result_array();
	}

	public function addNewLeave($data)
	{
		$this->db->insert('calendar', $data);
		return $this->db->affected_rows();
	}

	public function checkExistingLeave($data)
	{
		$this->db->where('start_date', $data['start_date']);
		$this->db->where('agent', $data['agent']);
		return $this->db->get('calendar')->num_rows();
	}

	public function checkLeaveOnDate($startDate, $endDate)
	{					
		if(strtotime($endDate)-strtotime($startDate) <= 86400){
			// $query = "SELECT * FROM calendar WHERE '$startDate' BETWEEN start_date AND end_date AND permit_status = 'approved'";
			$query = "SELECT * FROM calendar WHERE '$startDate' = start_date AND permit_status = 'approved'";
			return $this->db->query($query)->num_rows();
		}
		else{
			if($this->_performCheckLeaveOnDate($startDate) > 0 || $this->_performCheckLeaveOnDate($endDate) >0 ){
				return 1;
			}
		}		
	}

	private function _performCheckLeaveOnDate($date)
	{
		$query = "SELECT * FROM calendar WHERE '$date' BETWEEN start_date AND end_date AND permit_status = 'approved'";
		return $this->db->query($query)->num_rows();
	}

	public function getEventById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('calendar')->row_array();
	}

	public function getEventByDate($date)
	{
		$this->db->where('start_date', $date);
		return $this->db->get('calendar')->result_array();
	}

	public function deleteEventById($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('calendar');
		return $this->db->affected_rows();
	}

	public function dropEventById($id)
	{
		$this->db->where('id', $id);
		$this->db->set('permit_status', 'cancelled');
		$this->db->set('color', '#6c757d');
		$this->db->update('calendar');
		return $this->db->affected_rows();
	}

	public function updateEventById($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('calendar', $data);
		return $this->db->affected_rows();
	}

	public function allLongHolidayMonitoring()
	{
		return $this->db->get('calendar_longholiday')->result_array();
	}

	public function addNewLongHoliday($data)
	{
		$this->db->insert('calendar_longholiday', $data);
		return $this->db->affected_rows();
	}

	public function getEventLongHolidayById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('calendar_longholiday')->row_array();
	}
	public function checkExistingHolidaySlot($data){
		$this->db->where('start_date', $data['start_date']);
		$this->db->where('agent', $data['agent']);
		return $this->db->get('calendar_longholiday')->num_rows();		
	}

	public function updateLongHolidayById($data)
	{
		$this->db->set('start_date', $data['start_date']);
		$this->db->set('end_date', $data['end_date']);
		$this->db->set('color', $data['color']);
		$this->db->set('last_modified_by', $data['last_modified_by']);
		$this->db->set('last_modified_at', $data['last_modified_at']);
		$this->db->where('id', $data['id']);
		$this->db->update('calendar_longholiday');
		return $this->db->affected_rows();
	}

	
}
