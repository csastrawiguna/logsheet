<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Obidience_model extends CI_Model
{
	public function getAllObidienceData($startPeriod = '1900-01-01', $endPeriod = '3000-12-31')
	{
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->where('agent_scheduled <> actual_overtime');
		$this->db->order_by('date', 'DESC');
		$this->db->order_by('agent_scheduled', 'ASC');
		return $this->db->get('obidience')->result_array();
	}

	public function checkExistingData($agent, $date)
	{
		$this->db->where('date', $date);
		$this->db->where('actual_overtime', $agent);
		return $this->db->get('obidience')->num_rows();
	}

	public function checkExistingReplacedBy($agent, $date)
	{
		$this->db->where('date', $date);
		$this->db->where('actual_overtime', $agent);
		return $this->db->get('obidience')->row_array();
	}

	public function addSingleIncompliance($data)
	{
		$this->db->insert('obidience', $data);
		return $this->db->affected_rows();
	}

	public function getAllAgents()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

	public function deleteById($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('obidience');
		return $this->db->affected_rows();
	}

	public function getUnscheduledAgentsByDate($data)
	{
        $this->db->distinct();
        $this->db->join('user', 'user.user_id = obidience.actual_overtime');
        $this->db->select('obidience.actual_overtime AS agent');        
        $this->db->where('obidience.date !=', $data);
        $this->db->where('user.is_active', 1);
        $this->db->order_by('obidience.actual_overtime', 'ASC');
        return $this->db->get('obidience')->result_array();
	}

	public function getObidienceByAgent($agent, $startPeriod = '1900-01-01', $endPeriod = '3000-12-31')
	{
		$this->db->where('agent_scheduled', $agent);
		$this->db->where('date >=', $startPeriod);
    	$this->db->where('date <=', $endPeriod);
    	$this->db->where('agent_scheduled != actual_overtime');
    	$this->db->order_by('date', 'DESC');
		return $this->db->get('obidience')->result_array();
	}

	public function getObidienceSummary($startPeriod = '1900-01-01', $endPeriod = '3000-12-31')
	{
    	$this->db->select('COUNT(agent_scheduled) AS total_schedule');
    	$this->db->select('agent_scheduled AS agent');
    	$this->db->select("COUNT(CASE WHEN LOWER(replace_mark) = 'replace_request' THEN 1 END) AS replace_request");
    	$this->db->select("COUNT(CASE WHEN agent_scheduled != actual_overtime AND reason NOT LIKE '%urang%' THEN 1 END) AS incompliance");
    	$this->db->select("COUNT(CASE WHEN LOWER(replace_mark) = 'swap' THEN 1 END) AS swap");
    	$this->db->select("COUNT(CASE WHEN LOWER(replace_mark) = 'replace_request' THEN replaced_by END) AS replaced_to");
    	$this->db->select('SUM(obidience_index) AS obidience_index');
		$this->db->where('date >=', $startPeriod);
    	$this->db->where('date <=', $endPeriod);
    	$this->db->group_by('agent_scheduled');
    	$this->db->order_by('total_schedule', 'DESC');
		return $this->db->get('obidience')->result_array();
	}

	public function getObidienceReplacerSummary($startPeriod = '1900-01-01', $endPeriod = '3000-12-31')
	{
    	$this->db->select('actual_overtime AS agent');
    	$this->db->select("COUNT(CASE WHEN LOWER(replace_mark) = 'replace_request' THEN replaced_by END) AS replaced_for");
		$this->db->where('date >=', $startPeriod);
    	$this->db->where('date <=', $endPeriod);
    	$this->db->group_by('actual_overtime');
		return $this->db->get('obidience')->result_array();
	}

	public function getAllSchedule($startPeriod, $endPeriod)
	{
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->order_by('last_modified_at', 'DESC');
		$this->db->order_by('date', 'ASC');
		$this->db->order_by('agent_scheduled', 'ASC');
		return $this->db->get('obidience')->result_array();
	}

	public function getScheduleExchangeById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('obidience')->row_array();
	}

	public function getScheduleByDateAgent($agent, $date)
	{
		$this->db->where('actual_overtime', $agent);
		$this->db->where('date', $date);
		return $this->db->get('obidience')->row_array();
	}

	public function checkOvertimeHourByAgent($agent, $startPeriod, $endPeriod)
	{
		$this->db->select("SUM(duration) AS duration");
		$this->db->where('actual_overtime', $agent);
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		return $this->db->get('obidience')->row_array()['duration'];
	}

	public function countOvertimeHour($start, $end)
	{
		$this->db->where('start', $start);
		$this->db->where('end', $end);
		return $this->db->get('overtime_hour')->row_array()['duration'];
	}

	public function performScheduleExchange($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->set('replaced_by', $data['replaced_by']);
		$this->db->set('actual_overtime', $data['actual_overtime']);
		$this->db->set('reason', $data['reason']);
		$this->db->set('replace_mark', $data['replace_mark']);
		$this->db->set('remark', $data['remark']);
		$this->db->set('actual_start', $data['actual_start']);
		$this->db->set('actual_end', $data['actual_end']);
		$this->db->set('obidience_index', -1);
		$this->db->set('last_modified_by', $data['last_modified_by']);
		$this->db->set('last_modified_at', $data['last_modified_at']);
		$this->db->update('obidience');
		return $this->db->affected_rows();
	}

	public function performShecduleSwap($data)
	{
		$this->db->update_batch('obidience', $data, 'id');
		return $this->db->affected_rows();
	}

	public function performScheduleUpdate($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->set('date', $data['date']);
		$this->db->set('agent_scheduled', $data['agent_scheduled']);
		$this->db->set('time_start', $data['time_start']);
		$this->db->set('time_end', $data['time_end']);
		$this->db->set('duration', $data['duration']);
		$this->db->set('actual_overtime', $data['actual_overtime']);
		$this->db->set('actual_start', $data['actual_start']);
		$this->db->set('actual_end', $data['actual_end']);
		$this->db->set('actual_duration', $data['actual_duration']);
		$this->db->set('reason', $data['reason']);
		$this->db->set('remark', $data['remark']);
		$this->db->update('obidience');
		return $this->db->affected_rows();
	}

	public function performAddSingleSchedule($data)
	{
		$this->db->insert('obidience', $data);
		return $this->db->affected_rows();
	}

	public function setMaximumOvertimeHour($data)
	{		
		$this->db->update_batch('overtime_setting', $data, 'employement');
		return $this->db->affected_rows();
	}

	public function getAllAgentOvertimeDurationByPeriod($startPeriod, $endPeriod)
	{
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->select('actual_overtime AS agent');
		$this->db->select('SUM(actual_duration) AS duration_actual');
		$this->db->group_by('actual_overtime');
		$this->db->order_by('SUM(duration)', 'DESC');
		return $this->db->get('obidience')->result_array();
	}

	public function getAllAgentOvertimeDurationPlanByPeriod($startPeriod, $endPeriod)
	{
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->select('agent_scheduled AS agent');
		$this->db->select('SUM(duration) AS duration_plan');
		$this->db->group_by('agent_scheduled');
		$this->db->order_by('SUM(duration)', 'DESC');
		return $this->db->get('obidience')->result_array();
	}

	public function getAllAgentsByPeriod($startPeriod, $endPeriod)
	{
		$this->db->distinct('agent_scheduled');
		$this->db->select('agent_scheduled');
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		return $this->db->get('obidience')->result_array();
	}

	public function getSingleAgentOvertimeDurationByPeriod($agent, $startPeriod, $endPeriod)
	{
		$this->db->where('actual_overtime', $agent);
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->select('SUM(duration) AS duration');
		return $this->db->get('obidience')->row_array()['duration'];
	}

	public function toExcelDetailObidienceData($startDate, $endDate)
	{
		$this->db->select('obidience.date AS date');
		$this->db->select('obidience.agent_scheduled AS agent_scheduled');
		$this->db->select('(SELECT npk FROM user WHERE user_id = obidience.agent_scheduled) AS npk_scheduled');
		$this->db->select('(SELECT fullname FROM user WHERE user_id = obidience.agent_scheduled) AS fullname_scheduled');
		$this->db->select('obidience.time_start AS time_start');
		$this->db->select('obidience.time_end AS time_end');
		$this->db->select('obidience.duration AS duration');		
		$this->db->select('obidience.replaced_by AS replaced_by');
		$this->db->select('obidience.actual_overtime AS actual_overtime');
		$this->db->select('user.npk AS npk');
		$this->db->select('user.fullname AS fullname');
		$this->db->select('obidience.actual_start AS actual_start');
		$this->db->select('obidience.actual_end AS actual_end');
		$this->db->select('obidience.actual_duration AS actual_duration');
		$this->db->select('obidience.prod_call AS prod_call');
		$this->db->select('obidience.prod_whatsapp AS prod_whatsapp');
		$this->db->select('obidience.prod_followup AS prod_followup');
		$this->db->select('obidience.prod_others AS prod_others');
		$this->db->select('(obidience.prod_call + obidience.prod_whatsapp + obidience.prod_followup + obidience.prod_others) AS prod_total');
		$this->db->select('obidience.reason AS reason');
		$this->db->select('obidience.remark AS remark');
		$this->db->select('obidience.replace_mark AS replace_mark');
		$this->db->select('obidience.obidience_index AS obidience_index');
		$this->db->join('user', 'ON user.user_id = obidience.actual_overtime');
		$this->db->where('date >=', $startDate);
		$this->db->where('date <=', $endDate);
		return $this->db->get('obidience')->result_array();
	}

	public function uploadOvertimeExcel($data)
	{
		$this->db->insert_batch('obidience', $data);
		return $this->db->affected_rows();
	}

	public function getActualOvertimeByAgent($agent, $startDate, $endDate)
	{
		$this->db->select('date, overtime_type, actual_start, actual_end, actual_duration');
		$this->db->where('actual_overtime', $agent);
		$this->db->where('date >=', $startDate);
		$this->db->where('date <=', $endDate);
		return $this->db->get('obidience')->result_array();
	}

	public function getOvertimeAllowanceByAgent($agent, $type)
	{
		$this->db->select('overtime_allowance.meal AS meal, overtime_allowance.transport AS transport');
		$this->db->where('user.user_id', $agent);
		$this->db->where('overtime_allowance.overtime_type', $type);
		$this->db->join('user', 'ON user.status = overtime_allowance.employement');
		return $this->db->get('overtime_allowance')->row_array();
	}

	public function isWageExisting($agent, $years)
	{
		$this->db->where('year', $years);
		$this->db->where('user_id', $agent);
		return $this->db->get('user_wage')->num_rows();
	}

	public function updatePersonalWage($agent, $years, $amount)
	{
		$this->db->set('wage', $amount);
		$this->db->set('updated_at', date("Y-m-d H:i:s"));
		$this->db->where('year', $years);
		$this->db->where('user_id', $agent);
		$this->db->update('user_wage');
	}

	public function insertPersonalWage($data)
	{
		$this->db->insert('user_wage', $data);
	}

	public function getUpdatedWage($year)
	{
		$this->db->where('year', $year);
		$this->db->where('updated_at !=', NULL);
		$this->db->order_by('user_id', 'ASC');
		return $this->db->get('user_wage')->result_array();	
	}

	public function getActualOvertimeByPeriod($startPeriod, $endPeriod)
	{
		$this->db->select('id');
		$this->db->select('actual_overtime');
		$this->db->select('date');
		$this->db->select('actual_start');
		$this->db->select('actual_end');
		$this->db->select('actual_duration');
		$this->db->select('prod_call');
		$this->db->select('prod_whatsapp');
		$this->db->select('prod_followup');
		$this->db->select('prod_others');
		$this->db->select('prod_remark');
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		return $this->db->get('obidience')->result_array();
	}

	public function updateGroupOvertimeProductivity($data)
	{
		$this->db->update_batch('obidience', $data, 'id');
		return $this->db->affected_rows();
	}

	public function showAllSchedule($startPeriod, $endPeriod)
	{
		$this->db->select('date, agent_scheduled, actual_overtime, time_start, time_end');
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->order_by('date', 'ASC');
		$this->db->order_by('actual_end', 'DESC');
		$this->db->order_by('actual_start', 'ASC');
		return $this->db->get('obidience')->result_array();
	}

	public function getDates($startPeriod, $endPeriod)
	{
		$this->db->select('date');
		$this->db->where('date >=', $startPeriod);
		$this->db->where('date <=', $endPeriod);
		$this->db->order_by('date', 'ASC');
		$this->db->group_by('date');
		return $this->db->get('obidience')->result_array();
	}
}

