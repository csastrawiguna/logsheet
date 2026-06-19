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

    public function getAuxSummaryByPeriodByAgent($startPeriod, $endPeriod, $agent = NULL, $goupBy = NULL, $isoh = NULL, $orderBy, $orderMethod)
    {
        $this->db->select('agent');
        $this->db->select('DATE_FORMAT(date, "%Y-%m-01") AS period');
        $this->db->select('AVG(staffed_time) AS staffed_time');
        $this->db->select('(AVG(aux_0) + AVG(aux_1) + AVG(aux_2) + AVG(aux_3) + AVG(aux_4) + AVG(aux_5) + AVG(aux_6) + AVG(aux_7) + AVG(aux_8) + AVG(aux_9) + AVG(aux_1099)) AS aux_total');
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

        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        if (!is_null($agent) || $agent !== 'NULL' || $agent !== NULL){
            $this->db->where_in('agent', $agent);
        }
        if (!is_null($goupBy) || $goupBy !== 'NULL' || $goupBy !== NULL){
            $this->db->group_by($goupBy);
        }
        if (!is_null($isoh) || $isoh !== 'NULL' || $isoh !== NULL){
            $this->db->where_in('is_oh', $isoh);
        }
        $this->db->order_by($orderBy, $orderMethod);
        return $this->db->get('aux_daily')->result_array();
    }

    public function uploadAuxSummaryFromExcel($data)
    {
        $this->db->insert_batch('aux_monthly', $data);
		return $this->db->affected_rows();
    }

    public function getAuxDailyAllByPeriodByAgent($startPeriod, $endPeriod, $agent = NULL, $isoh = NULL)
    {
        if (!is_null($agent) || $agent !== 'NULL' || $agent !== NULL){
            $this->db->where_in('agent', $agent);
        }
        if (!is_null($isoh) || $isoh !== 'NULL' || $isoh !== NULL){
            $this->db->where_in('is_oh', $isoh);
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
        $this->db->where('id', $data['id']);
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

    public function getSummaryAuxDailyAllByPeriodByAgent($startPeriod, $endPeriod, $agent = NULL)
    {
        $selects = [];
        $auxColumns = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '1099'];
        
        // 1. Ngitung data OH jeung OT (Ieu geus bener make AVERAGEIF / CASE WHEN)
        $types = [
            'oh' => 1,
            'ot' => 0
        ];

        foreach ($types as $prefix => $val) {
            // Tambahkeun IFNULL di luar AVG na
            $selects[] = "IFNULL(AVG(CASE WHEN is_oh = {$val} THEN staffed_time ELSE NULL END), 0) AS staffed_time_{$prefix}";
            
            $sumAuxFields = [];
            foreach ($auxColumns as $col) {
                $aliasName = ($col == '1099') ? '1099' : $col;
                // Tambahkeun IFNULL di dieu oge
                $selects[] = "IFNULL(AVG(CASE WHEN is_oh = {$val} THEN aux_{$col} ELSE NULL END), 0) AS aux_{$aliasName}_{$prefix}";
                
                $sumAuxFields[] = "IFNULL(aux_{$col}, 0)";
            }
            
            $totalAuxSql = implode(" + ", $sumAuxFields);
            // Tambahkeun IFNULL keur total aux
            $selects[] = "IFNULL(AVG(CASE WHEN is_oh = {$val} THEN ({$totalAuxSql}) ELSE NULL END), 0) AS total_aux_{$prefix}";
        }

        // Keur baris 'ALL' oge sarua dibungkus IFNULL:
        $selects[] = "IFNULL(AVG(staffed_time), 0) AS staffed_time_all";

        $avgAuxFieldsAll = [];
        foreach ($auxColumns as $col) {
            $aliasName = ($col == '1099') ? '1099' : $col;
            $selects[] = "IFNULL(AVG(aux_{$col}), 0) AS aux_{$aliasName}_all";
            
            $avgAuxFieldsAll[] = "IFNULL(AVG(IFNULL(aux_{$col}, 0)), 0)";
        }

        $totalAuxSqlAll = implode(" + ", $avgAuxFieldsAll);
        $selects[] = "({$totalAuxSqlAll}) AS total_aux_all";
        
        // 3. Wangun query murnina
        $sql = "SELECT " . implode(", ", $selects) . " FROM aux_daily WHERE date BETWEEN ? AND ?";
        $bindParams = [$startPeriod, $endPeriod];
        
        // 4. Kondisi filter Agent
        if (!empty($agent) && $agent !== 'NULL') {
            if (is_array($agent)) {
                $agentsEscaped = array_map(function($a) { return $this->db->escape($a); }, $agent);
                $sql .= " AND agent IN (" . implode(",", $agentsEscaped) . ")";
            } else {
                $sql .= " AND agent = ?";
                $bindParams[] = $agent;
            }
        }
        
        return $this->db->query($sql, $bindParams)->row_array();
    }
}
