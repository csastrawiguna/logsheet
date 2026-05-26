<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Blackbook_model extends CI_Model
{
	public function getAllActiveAgent()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

	public function addNewSingleNote($data)
	{        
		$this->db->insert('blackbook', $data);
		return $this->db->affected_rows();
	}
   
    public function deleteById($id)
    {
    	$this->db->where('id', $id);
    	$this->db->delete('blackbook');
    	return $this->db->affected_rows();
    }

    public function getBlackbookById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('blackbook')->row_array();
    }

    public function editBlackbookById($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('blackbook', $data);
        return $this->db->affected_rows();
    }

    public function getBlackNotesByPeriod($startPeriod, $endPeriod)
    {
    	$this->db->where('date >=', $startPeriod);
    	$this->db->where('date <=', $endPeriod);
        $this->db->order_by('saved_at', 'DESC');
    	return $this->db->get('blackbook')->result_array();
    }

    public function getBlackNotesByPeriodByAgent($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select('COUNT(agent) as blacknote');
        $this->db->select('SUM(score) as scores');
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('SUM(score)', 'DESC');
        $this->db->order_by('COUNT(agent)', 'DESC');
        $this->db->order_by('agent', 'ASC');
        return $this->db->get('blackbook')->result_array();
    }

    public function getBlackNotesType()
    {
        $this->db->select('id, type, bahasa');
        $this->db->where('is_active', 1);
        $this->db->order_by('bahasa', 'ASC');
        return $this->db->get('blackbook_scoring')->result_array();
    }

    public function getAllBlackbookScoring()
    {
        $this->db->order_by('is_active', 'DESC');
        $this->db->order_by('type', 'ASC');
        return $this->db->get('blackbook_scoring')->result_array();
    }

    public function getAllBlackbookScoringLevel()
    {
        $this->db->order_by('score', 'DESC');
        return $this->db->get('blackbook_scoring_level')->result_array();
    }

    public function updateBatchBlackbookScoreLevel($data)
    {
        $this->db->update_batch('blackbook_scoring_level', $data, 'level');
        return $this->db->affected_rows();
    }

    public function getScoreLevelByType($type)
    {
        $this->db->where('type', $type);
        return $this->db->get('blackbook_scoring')->row_array();
    }

    public function updateBatchBlackbookScoring($data)
    {
        $this->db->update_batch('blackbook_scoring', $data, 'id');
        return $this->db->affected_rows();
    }

    public function addBlackbookScoringSingleRow($data)
    {
        $this->db->insert('blackbook_scoring', $data);
        return $this->db->affected_rows();
    }

    public function deleteBlackbookScoringSingle($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('blackbook_scoring');
        return $this->db->affected_rows();
    }

    public function updateBlackbookScoringSingleRow($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('blackbook_scoring', $data);
        return $this->db->affected_rows();
    }

    public function getBlackNotesByAgentByPeriod($agent, $startPeriod, $endPeriod)
    {
    	$this->db->where('agent', $agent);
    	$this->db->where('date >=', $startPeriod);
    	$this->db->where('date <=', $endPeriod);
        $this->db->order_by('date', 'DESC');
    	return $this->db->get('blackbook')->result_array();
    }

    public function getSummaryBlackbookByPeriod($startPeriod, $endPeriod)
    {
        $query = "SELECT agent,
                    COUNT(CASE WHEN type = 'CTI address incomplete' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI address inappropriate' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI messy address' THEN 1 END)  AS messy_address,
                    COUNT(CASE WHEN type = 'CTI no address' THEN 1 END)  AS no_address,
                    COUNT(CASE WHEN type = 'Wrong service area' THEN 1 END)  AS wrong_service_area,
                    COUNT(CASE WHEN type = 'Wrong information' THEN 1 END)  AS wrong_info,
                    COUNT(CASE WHEN type = 'Wrong system code (call)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Wrong system code (SMS)' THEN 1 END)  AS wrong_system_code,
                    COUNT(CASE WHEN type = 'Notif wrong equipment' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Notif wrong others' THEN 1 END)  AS unproper_notif,
                    COUNT(CASE WHEN type = 'Unproper reply (email)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SharpID)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SMS)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (WA)' THEN 1 END)  AS unproper_reply,
                    COUNT(CASE WHEN type = 'CTI no find customer' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no check history' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no dummy' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI wrong entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Others' THEN 1 END)  AS others,
                    COUNT(agent) AS total
                FROM blackbook
                WHERE date BETWEEN '$startPeriod' AND '$endPeriod'
                GROUP BY agent
                ORDER BY COUNT(agent) DESC";
        return $this->db->query($query)->result_array();
    }

    public function getSummaryBlackbookByCategory($startPeriod, $endPeriod)
    {
        $query = "SELECT 
                    COUNT(CASE WHEN type = 'CTI address incomplete' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI address inappropriate' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI messy address' THEN 1 END)  AS messy_address,
                    COUNT(CASE WHEN type = 'CTI no address' THEN 1 END)  AS no_address,
                    COUNT(CASE WHEN type = 'Wrong service area' THEN 1 END)  AS wrong_service_area,
                    COUNT(CASE WHEN type = 'Wrong information' THEN 1 END)  AS wrong_info,
                    COUNT(CASE WHEN type = 'Wrong system code (call)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Wrong system code (SMS)' THEN 1 END)  AS wrong_system_code,
                    COUNT(CASE WHEN type = 'Notif wrong equipment' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Notif wrong others' THEN 1 END)  AS unproper_notif,
                    COUNT(CASE WHEN type = 'Unproper reply (email)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SharpID)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SMS)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (WA)' THEN 1 END)  AS unproper_reply,
                    COUNT(CASE WHEN type = 'CTI no find customer' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no check history' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no dummy' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI wrong entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Others' THEN 1 END)  AS others
                FROM blackbook
                WHERE date BETWEEN '$startPeriod' AND '$endPeriod'
                ORDER BY COUNT(agent) DESC";
        return $this->db->query($query)->result_array();
    }

    public function getSummaryBlackbookByPeriodSubtotal($startPeriod, $endPeriod)
    {
        $query = "SELECT 
                    COUNT(CASE WHEN type = 'CTI address incomplete' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI address inappropriate' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI messy address' THEN 1 END)  AS messy_address,
                    COUNT(CASE WHEN type = 'CTI no address' THEN 1 END)  AS no_address,
                    COUNT(CASE WHEN type = 'Wrong service area' THEN 1 END)  AS wrong_service_area,
                    COUNT(CASE WHEN type = 'Wrong information' THEN 1 END)  AS wrong_info,
                    COUNT(CASE WHEN type = 'Wrong system code (call)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Wrong system code (SMS)' THEN 1 END)  AS wrong_system_code,
                    COUNT(CASE WHEN type = 'Notif wrong equipment' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Notif wrong others' THEN 1 END)  AS unproper_notif,
                    COUNT(CASE WHEN type = 'Unproper reply (email)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SharpID)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SMS)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (WA)' THEN 1 END)  AS unproper_reply,
                    COUNT(CASE WHEN type = 'CTI no find customer' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no check history' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no dummy' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI wrong entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Others' THEN 1 END)  AS others,
                    COUNT(CASE WHEN type = 'CTI address incomplete' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI address inappropriate' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI messy address' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI no address' THEN 1 END) +
                    COUNT(CASE WHEN type = 'Wrong service area' THEN 1 END) +
                    COUNT(CASE WHEN type = 'Wrong information' THEN 1 END) +
                    COUNT(CASE WHEN type = 'Wrong system code (call)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Wrong system code (SMS)' THEN 1 END) +
                    COUNT(CASE WHEN type = 'Notif wrong equipment' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Notif wrong others' THEN 1 END) +
                    COUNT(CASE WHEN type = 'Unproper reply (email)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SharpID)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (SMS)' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Unproper reply (WA)' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI no find customer' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no check history' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no dummy' THEN 1 END) +
                    COUNT(CASE WHEN type = 'CTI wrong entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'CTI no entry' THEN 1 END) + 
                    COUNT(CASE WHEN type = 'Others' THEN 1 END)  AS total
                FROM blackbook
                WHERE date BETWEEN '$startPeriod' AND '$endPeriod'";
        return $this->db->query($query)->result_array();
    }

    public function toExcelDetailBlackbookData($startPeriod, $endPeriod)
    {
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('Blackbook')->result_array();
    }

    public function getSummaryRepeatQuestion($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'SKAPE' THEN 1 END) AS skape");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'CCC FLOW' THEN 1 END) AS ccc_flow");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'CS FLOW' THEN 1 END) AS cs_flow");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'OTHERS' THEN 1 END) AS others");
        $this->db->select("COUNT(agent) AS total");
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('COUNT(agent)', 'DESC');
        return $this->db->get('repeat_question')->result_array();
    }

    public function getSummaryRepeatQuestionSubtotal($startPeriod, $endPeriod)
    {
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'SKAPE' THEN 1 END) AS skape");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'CCC FLOW' THEN 1 END) AS ccc_flow");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'CS FLOW' THEN 1 END) AS cs_flow");
        $this->db->select("COUNT(CASE WHEN UPPER(category) = 'OTHERS' THEN 1 END) AS others");
        $this->db->select("COUNT(category) AS total");
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('repeat_question')->row_array();
    }

    public function getDetailsRepeatQuestion($startPeriod, $endPeriod)
    {
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('repeat_question')->result_array();
    }

    public function addNewRepeatQuestion($data)
    {
        $this->db->insert('repeat_question', $data);
        return $this->db->affected_rows();
    }

    public function getRepeatQuestionById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('repeat_question')->row_array();
    }

    public function editRepeatQuestion($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('repeat_question', $data);
        return $this->db->affected_rows();
    }

    public function deleterepeatquestion($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('repeat_question');
        return $this->db->affected_rows();
    }

    public function getBlackbookItems()
    {
        $this->db->distinct();
        $this->db->select('type');
        $this->db->order_by('type', 'ASC');
        return $this->db->get('blackbook')->result_array();
    }

    public function getDetailMonitoring($startPeriod, $endPeriod)
    {
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('daily_agent_info_monitoring')->result_array();
    }

    public function getSummaryMonitoring($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select("COUNT(CASE WHEN LOWER(source) = 'call' THEN 1 END) AS call_qty");
        $this->db->select("COUNT(CASE WHEN (LOWER(source) = 'call' AND done_by_agent = 1) THEN 1 END) AS call_done");
        $this->db->select("COUNT(CASE WHEN LOWER(source) = 'whatsapp' THEN 1 END) AS whatsapp_qty");
        $this->db->select("COUNT(CASE WHEN (LOWER(source) = 'whatsapp' AND done_by_agent = 1) THEN 1 END) AS whatsapp_done");
        $this->db->select("COUNT(CASE WHEN done_by_agent = 1 THEN 1 END) / COUNT(agent) AS done_ratio");
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('done_ratio', 'DESC');
        return $this->db->get('daily_agent_info_monitoring')->result_array();
    }

    public function getSummaryMonitoringTotal($startPeriod, $endPeriod)
    {
        $this->db->select("COUNT(CASE WHEN LOWER(source) = 'call' THEN 1 END) AS call_qty");
        $this->db->select("COUNT(CASE WHEN (LOWER(source) = 'call' AND done_by_agent = 1) THEN 1 END) AS call_done");
        $this->db->select("COUNT(CASE WHEN LOWER(source) = 'whatsapp' THEN 1 END) AS whatsapp_qty");
        $this->db->select("COUNT(CASE WHEN (LOWER(source) = 'whatsapp' AND done_by_agent = 1) THEN 1 END) AS whatsapp_done");
        $this->db->select("COUNT(CASE WHEN done_by_agent = 1 THEN 1 END) / COUNT(agent) AS done_ratio");
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->order_by('done_ratio', 'DESC');
        return $this->db->get('daily_agent_info_monitoring')->row_array();
    }

    public function addNewAgentCashlessMonitoring($data)
    {
        $this->db->insert('daily_agent_info_monitoring', $data);
        return $this->db->affected_rows();
    }

    public function updateGeneralSetting($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('general_setting', $data);
        return $this->db->affected_rows();
    }

    public function deleteAgentCashlessMonitoring($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('daily_agent_info_monitoring');
        return $this->db->affected_rows();
    }

    public function editNewAgentCashlessMonitoring($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('daily_agent_info_monitoring', $data);
        return $this->db->affected_rows();
    }
}
