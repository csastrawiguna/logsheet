<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Aux_model extends CI_Model
{
    public function getByAgentAuxByMonth($startPeriod, $endPeriod, $agent)
    {
        $this->db->where('agent', $agent);
        $this->db->where('month >=', $startPeriod);
        $this->db->where('month <=', $endPeriod);
        $this->db->order_by('month', 'DESC');
        return $this->db->get('aux_monthly')->result_array();
    }

    public function getAllActiveAgent()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

    public function getSummaryAuxByMonth($startPeriod, $endPeriod)
    {
        $this->db->select('agent, ext');
        $this->db->select('AVG(staffed_time) AS staffed_time');
        $this->db->select('AVG(aux_0) AS aux_0');
        $this->db->select('AVG(aux_1) AS aux_1');
        $this->db->select('AVG(aux_2) AS aux_2');
        $this->db->select('AVG(aux_3) AS aux_3');
        $this->db->select('AVG(aux_4) AS aux_4');
        $this->db->select('AVG(aux_5) AS aux_5');
        $this->db->select('AVG(aux_6) AS aux_6');
        $this->db->select('AVG(aux_7) AS aux_7');
        $this->db->select('AVG(aux_8) AS aux_8');
        $this->db->select('AVG(aux_9) AS aux_9');
        $this->db->select('AVG(aux_1099) AS aux_1099');
        $this->db->where('month >=', $startPeriod);
        $this->db->where('month <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('AVG(aux_6)', 'DESC');
        return $this->db->get('aux_monthly')->result_array();
    }

    public function uploadAuxSummaryFromExcel($data)
    {
        $this->db->insert_batch('aux_monthly', $data);
		return $this->db->affected_rows();
    }

    public function getAuxDailyAllByPeriodByAgent($startPeriod, $endPeriod, $agent = NULL)
    {
        if (!is_null($agent) || $agent !== 'NULL' || $agent !== NULL){
            $this->db->where_in('agent', $agent);
        }
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('aux_daily')->result_array();
    }

    public function addNewAuxDailySingleData($data)
    {
        $this->db->insert('aux_daily', $data);
        return $this->db->affected_rows();
    }

    public function editAuxDailySingleData($data)
    {
        $this->db->where('agent', $data['agent']);
        $this->db->where('date', $data['date']);
        $this->db->update('aux_daily', $data);
        return $this->db->affected_rows();
    }

    public function deleteSingleAuxdaily($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('aux_daily');
        return $this->db->affected_rows();
    }

    public function uploadAuxDailyFromExcel($data)
    {
        $this->db->insert_batch('aux_daily', $data);
        return $this->db->affected_rows();
    }
}
