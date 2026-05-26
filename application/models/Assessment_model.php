<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assessment_model extends CI_Model
{
    public function addSingleOthers($data)
    {
        $this->db->insert('kpi_other', $data);
        return $this->db->affected_rows();
    }

    public function submitMultipleOthersKpi($data)
    {
        $this->db->insert_batch('kpi_other', $data);
        return $this->db->affected_rows();
    }

    public function editSingleOthers($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('kpi_other', $data);
        return $this->db->affected_rows();
    }

	public function getProductivity($agent, $startPeriod, $endPeriod)
	{
		$this->db->where('agent', $agent);
		$this->db->where('period >=', $startPeriod);
		$this->db->where('period <=', $endPeriod);
		$this->db->select('AVG (icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others) AS productivity_total');
		return $this->db->get('productivity')->row_array()['productivity_total'];
	}

	public function getCsindexRatio($agent, $startPeriod, $endPeriod)
	{
		$this->db->where('agent', $agent);
		$this->db->where('period >=', $startPeriod);
		$this->db->where('period <=', $endPeriod);
		$this->db->select('AVG (icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others) AS productivity_total');
		return $this->db->get('csindex_survey')->row_array();
	}

	public function getTarget($jobcode)
	{
		$this->db->where('jobcode', $jobcode);
		return $this->db->get('kpi_target')->result_array();
	}

	public function getTotalTarget($jobcode, $fiscal)
	{
		$this->db->where('jobcode', $jobcode);
        $this->db->where('fiscal', $fiscal);
		$this->db->select('SUM(weight) AS totalWeight');
		return $this->db->get('kpi_target')->row_array();
	}

	public function getKpiItems($agent, $startPeriod, $endPeriod)
	{		
        $query = "SELECT productivity.period AS period, (productivity.icall + productivity.callback + productivity.follow_up + productivity.sms + productivity.webchat + productivity.whatsapp + productivity.sharp_id + productivity.email + productivity.notif_sap + productivity.complaint + productivity.part_code + productivity.others) AS productivity, productivity.part_code AS part_code,
            (((
                COUNT(CASE WHEN csindex_survey.questioner_1 = 3 THEN 1 END) * 5 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 2 THEN 1 END) * 4 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_1) * 5) * COUNT(csindex_survey.questioner_1)) + 
                ((
                COUNT(CASE WHEN csindex_survey.questioner_2 = 3 THEN 1 END) * 5 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 2 THEN 1 END) * 4 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_2) * 5) * COUNT(csindex_survey.questioner_2))) / 
                (COUNT(csindex_survey.questioner_1) + COUNT(csindex_survey.questioner_2)) AS csindex,
                (working_calendar.working_day - (COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END)) / 3 ) / working_calendar.working_day AS absence,
                working_calendar.working_day AS working_day,
                elearning_assignment.posttest_score AS elearning,
                kpi_other.skape_draft AS skape_draft,
                kpi_other.skape_solution AS skape_solution,
                kpi_other.knowledge_sharing AS knowledge_sharing,
                kpi_other.part_callback AS part_callback,
                kpi_other.email_reply AS email_reply,
                kpi_other.promo_inquiry AS promo_inquiry,
                kpi_other.complaint_forward AS complaint_forward,
                kpi_other.complaint_completion AS complaint_completion, 
                kpi_other.complaint_report AS complaint_report
                FROM daily_absence RIGHT JOIN productivity 
                    ON daily_absence.cti_id = productivity.agent AND 
                    DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') = productivity.period
                JOIN working_calendar JOIN csindex_survey JOIN elearning_category JOIN elearning_assignment ON 
                    working_calendar.working_month = productivity.period AND
                    productivity.period = csindex_survey.period AND
                    productivity.agent = csindex_survey.agent AND
                    productivity.period = elearning_category.period AND
                    productivity.agent = elearning_assignment.user_id AND
                    elearning_category.id = elearning_assignment.elearning_id 
                LEFT JOIN kpi_other ON
                    productivity.agent = kpi_other.agent AND
                    productivity.period = kpi_other.period 
                WHERE productivity.agent = '$agent' AND 
                    productivity.period BETWEEN '$startPeriod' AND '$endPeriod'
                GROUP BY productivity.period";
        return $this->db->query($query)->result_array();
	}

    public function getSummaryByPeriod($startPeriod, $endPeriod)
    {
        $query = "SELECT productivity.agent AS agent, (productivity.icall + productivity.callback + productivity.follow_up + productivity.sms + productivity.webchat + productivity.whatsapp + productivity.sharp_id + productivity.email + productivity.notif_sap + productivity.complaint + productivity.part_code + productivity.others) AS productivity, productivity.part_code AS part_code,
            (((
                COUNT(CASE WHEN csindex_survey.questioner_1 = 3 THEN 1 END) * 5 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 2 THEN 1 END) * 4 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_1) * 5) * COUNT(csindex_survey.questioner_1)) + 
                ((
                COUNT(CASE WHEN csindex_survey.questioner_2 = 3 THEN 1 END) * 5 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 2 THEN 1 END) * 4 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_2) * 5) * COUNT(csindex_survey.questioner_2))) / 
                (COUNT(csindex_survey.questioner_1) + COUNT(csindex_survey.questioner_2)) AS csindex,
                (working_calendar.working_day - (COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END)) / 3 ) / working_calendar.working_day AS absence,
                working_calendar.working_day AS working_day,
                elearning_assignment.posttest_score AS elearning,
                kpi_other.skape_draft AS skape_draft,
                kpi_other.skape_solution AS skape_solution,
                kpi_other.knowledge_sharing AS knowledge_sharing,
                kpi_other.part_callback AS part_callback,
                kpi_other.email_reply AS email_reply,
                kpi_other.promo_inquiry AS promo_inquiry,
                kpi_other.complaint_forward AS complaint_forward,
                kpi_other.complaint_completion AS complaint_completion,
                kpi_other.complaint_report AS complaint_report
                FROM daily_absence RIGHT JOIN productivity 
                    ON daily_absence.cti_id = productivity.agent AND 
                    DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') = productivity.period
                JOIN working_calendar JOIN csindex_survey JOIN elearning_category JOIN elearning_assignment ON 
                    working_calendar.working_month = productivity.period AND
                    productivity.period = csindex_survey.period AND
                    productivity.agent = csindex_survey.agent AND
                    productivity.period = elearning_category.period AND
                    productivity.agent = elearning_assignment.user_id AND
                    elearning_category.id = elearning_assignment.elearning_id 
                LEFT JOIN kpi_other ON
                    productivity.agent = kpi_other.agent AND
                    productivity.period = kpi_other.period 
                WHERE 
                    productivity.period BETWEEN '$startPeriod' AND '$endPeriod'
                GROUP BY productivity.agent";
        return $this->db->query($query)->result_array();
    }

    public function getAverageByPeriod($startPeriod, $endPeriod)
    {
        $query = "SELECT productivity.agent AS agent, productivity.period AS period, (productivity.icall + productivity.callback + productivity.follow_up + productivity.sms + productivity.webchat + productivity.whatsapp + productivity.sharp_id + productivity.email + productivity.notif_sap + productivity.complaint + productivity.part_code + productivity.others) AS productivity, productivity.part_code AS part_code,
            (((
                COUNT(CASE WHEN csindex_survey.questioner_1 = 3 THEN 1 END) * 5 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 2 THEN 1 END) * 4 +
                COUNT(CASE WHEN csindex_survey.questioner_1 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_1 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_1) * 5) * COUNT(csindex_survey.questioner_1)) + 
                ((
                COUNT(CASE WHEN csindex_survey.questioner_2 = 3 THEN 1 END) * 5 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 2 THEN 1 END) * 4 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = 1 THEN 1 END) * 3 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -1 THEN 1 END) * 2 + 
                COUNT(CASE WHEN csindex_survey.questioner_2 = -2 THEN 1 END) * 1) / (COUNT(csindex_survey.questioner_2) * 5) * COUNT(csindex_survey.questioner_2))) / 
                (COUNT(csindex_survey.questioner_1) + COUNT(csindex_survey.questioner_2)) AS csindex,
                (working_calendar.working_day - (COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) + COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END)) / 3 ) / working_calendar.working_day AS absence,
                working_calendar.working_day AS working_day,
                elearning_assignment.posttest_score AS elearning,
                kpi_other.skape_draft AS skape_draft,
                kpi_other.skape_solution AS skape_solution,
                kpi_other.knowledge_sharing AS knowledge_sharing,
                kpi_other.part_callback AS part_callback,
                kpi_other.email_reply AS email_reply,
                kpi_other.promo_inquiry AS promo_inquiry,
                kpi_other.complaint_forward AS complaint_forward,
                kpi_other.complaint_completion AS complaint_completion,
                kpi_other.complaint_report AS complaint_report
                FROM daily_absence RIGHT JOIN productivity 
                    ON daily_absence.cti_id = productivity.agent AND 
                    DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01') = productivity.period
                JOIN working_calendar JOIN csindex_survey JOIN elearning_category JOIN elearning_assignment ON 
                    working_calendar.working_month = productivity.period AND
                    productivity.period = csindex_survey.period AND
                    productivity.agent = csindex_survey.agent AND
                    productivity.period = elearning_category.period AND
                    productivity.agent = elearning_assignment.user_id AND
                    elearning_category.id = elearning_assignment.elearning_id 
                LEFT JOIN kpi_other ON
                    productivity.agent = kpi_other.agent AND
                    productivity.period = kpi_other.period 
                WHERE 
                    productivity.period BETWEEN '$startPeriod' AND '$endPeriod'
                GROUP BY productivity.agent, productivity.period";
        return $this->db->query($query)->result_array();
    }

    public function getAllAgentsByPeriod($startPeriod, $endPeriod)
    {
        // $this->db->distinct('productivity.agent');
        $this->db->select('productivity.agent AS agent');
        $this->db->select('user.jobcode AS jobcode');
        $this->db->select('productivity.period AS period');
        $this->db->select('user.fullname AS fullname');
        $this->db->select('user.npk AS npk');
        $this->db->select('user.status AS status');
        $this->db->join('user', 'ON productivity.agent = user.user_id');
        $this->db->where('productivity.period >=', $startPeriod);
        $this->db->where('productivity.period <=', $endPeriod);
        $this->db->where('user.is_active', 1);
        $this->db->group_by('productivity.agent');
        return $this->db->get('productivity')->result_array();        
    }

    public function getCsindexSurveyQtyByAgent($agent, $startPeriod, $endPeriod)
    {
        $this->db->where('agent', $agent);
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->select('period');
        $this->db->select('COUNT(period) AS qty');
        $this->db->group_by('period');
        return $this->db->get('csindex_survey')->result_array();
    }

    public function performKpiMeasurement($fiscal, $jobcode, $item, $result)
    {        
        $query = "SELECT criteria FROM kpi_measurement WHERE fiscal = '$fiscal' AND jobcode = '$jobcode' AND item = '$item' AND '$result' BETWEEN range_min AND range_max";
        if ($this->db->query($query)->num_rows() == 0 ) {
            return 0;
        } else {
            return $this->db->query($query)->row_array()['criteria'];
        }
    }

    public function getWeightKpiTarget($jobcode, $fiscal, $item)
    {
        $this->db->where('jobcode', $jobcode);
        $this->db->where('fiscal', $fiscal);
        $this->db->where('item', $item);
        return $this->db->get('kpi_target')->row_array();
    }

    public function getAllAgents()
    {
        $this->db->select('user_id');
        $this->db->where('is_active', 1);
        return $this->db->get('user')->result_array();
    }

    public function getOthersKpiByPeriod($startPeriod, $endPeriod)
    {
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('period', 'DESC');
        $this->db->order_by('agent', 'ASC');
        return $this->db->get('kpi_other')->result_array();
    }

    public function getTargetByJobcode($jobcode, $fiscal)
    {
        $this->db->where('jobcode', $jobcode);
        $this->db->where('fiscal', $fiscal);
        $this->db->order_by('fiscal', 'DESC');
        return $this->db->get('kpi_target')->result_array();
    }

    public function getLatestFiscal()
    {
        $this->db->select('fiscal');
        $this->db->distinct();
        $this->db->order_by('fiscal', 'DESC');
        return $this->db->get('kpi_target')->row_array()['fiscal'];
    }

    public function getJobcode($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get('user')->row_array()['jobcode'];
    }

    public function deleteOthersKpiById($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kpi_other');
        return $this->db->affected_rows();
    }

    public function getBestAgentDetailByPeriod($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select('AVG(productivity_score) AS productivity');
        $this->db->select('AVG(smilevoice_score) AS smilevoice');
        $this->db->select('AVG(attendance_score) AS attendance');
        $this->db->select('AVG(elearning_score) AS elearning');
        $this->db->select('AVG(teamwork_score) AS teamwork');
        $this->db->where('month >=', $startPeriod);
        $this->db->where('month <=', $endPeriod);
        $this->db->group_by('agent');
        return $this->db->get('kpi_best_agent_detail')->result_array();
    }

    public function getDetailSourceByPeriod($period)
    {
        $workdays = $this->_getWorkDays($period);
        $query = "SELECT 
                    productivity.agent AS agent,
                    (icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others)/work_hour AS prod_hour,
                    (((
                                    COUNT(CASE WHEN questioner_1 = 3 THEN 1 END) * 5 +
                                    COUNT(CASE WHEN questioner_1 = 2 THEN 1 END) * 4 +
                                    COUNT(CASE WHEN questioner_1 = 1 THEN 1 END) * 3 + 
                                    COUNT(CASE WHEN questioner_1 = -1 THEN 1 END) * 2 + 
                                    COUNT(CASE WHEN questioner_1 = -2 THEN 1 END)* 1) / (COUNT(questioner_1) * 5) * COUNT(questioner_1)) + 
                                ((
                                    COUNT(CASE WHEN questioner_2 = 3 THEN 1 END) * 5 + 
                                    COUNT(CASE WHEN questioner_2 = 2 THEN 1 END) * 4 + 
                                    COUNT(CASE WHEN questioner_2 = 1 THEN 1 END) * 3 + 
                                    COUNT(CASE WHEN questioner_2 = -1 THEN 1 END) * 2 + 
                                    COUNT(CASE WHEN questioner_2 = -2 THEN 1 END) * 1) / (COUNT(questioner_2) * 5) * COUNT(questioner_2))) / 
                                    (COUNT(questioner_1) + COUNT(questioner_2)) AS csindex_ratio,
                    (COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) / 3) AS sick,
                    (COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) / 3 ) AS unpaid_leave,
                    ('$workdays' - ((COUNT(CASE WHEN daily_absence.permit_type = 'Sick' THEN 1 END) / 3) + (COUNT(CASE WHEN daily_absence.permit_type = 'Unpaid leave' THEN 1 END) / 3 ))) / '$workdays' AS attendance,
                    elearning_assignment.posttest_score AS elearning_score,
                    (aux_monthly.aux_1 + aux_monthly.aux_2 + aux_monthly.aux_3 + aux_monthly.aux_6) AS aux,
                    1 - (aux_monthly.aux_1 + aux_monthly.aux_2 + aux_monthly.aux_3 + aux_monthly.aux_6) / aux_monthly.staffed_time AS auxratio,
                    aux_monthly.staffed_time  AS login
                    FROM
                    daily_absence
                    RIGHT JOIN
                    productivity
                    ON productivity.agent = daily_absence.cti_id AND productivity.period = DATE_FORMAT(daily_absence.absent_date, '%Y-%m-01')
                    JOIN
                    csindex_survey
                    ON productivity.agent = csindex_survey.agent AND productivity.period = csindex_survey.period
                    JOIN aux_monthly
                    ON productivity.agent = aux_monthly.agent AND productivity.period = aux_monthly.month
                    LEFT JOIN elearning_assignment
                    ON elearning_assignment.user_id = productivity.agent
                    JOIN elearning_category
                    ON elearning_category.id = elearning_assignment.elearning_id AND elearning_category.period = productivity.period
                    WHERE productivity.period = '$period'
                    GROUP BY productivity.agent";
        return $this->db->query($query)->result_array();
    }

    public function checkSourceData($period)
    {
        $data = [
            'productivity' => $this->_checkProductivity($period),
            'csindex' => $this->_checkCsindex($period),
            'attendance' => $this->_checkAttendance($period),
            'elearning' => $this->_checkElearning($period),
            'teamwork' => $this->_checkTeamwork($period),
        ];
        return $data;
    }

    private function _checkProductivity($period)
    {
        $this->db->select('agent');
        $this->db->where('period', $period);
        return $this->db->get('productivity')->num_rows();
    }

    private function _checkCsindex($period)
    {
        $this->db->select('agent');
        $this->db->where('period', $period);
        return $this->db->get('csindex_survey')->num_rows() / 3;
    }

    private function _checkAttendance($period)
    {
        $query = "SELECT absent_date FROM daily_absence WHERE DATE_FORMAT(absent_date, '%Y-%m-01') = '$period'";
        return $this->db->query($query)->num_rows();
    }

    private function _checkElearning($period)
    {
        $this->db->join('elearning_category', 'ON elearning_category.id = elearning_assignment.elearning_id');
        $this->db->where('elearning_category.period', $period);
        $this->db->where('elearning_assignment.posttest_score >', 0);
        return $this->db->get('elearning_assignment')->num_rows();
    }

    private function _checkTeamwork($period)
    {
        $this->db->select('staffed_time');
        $this->db->where('month', $period);
        return $this->db->get('aux_monthly')->num_rows();
    }

    private function _getWorkDays($period)
    {
        $this->db->where('working_month', $period);
        return $this->db->get('working_calendar')->row_array()['working_day'];
    }

    public function getResultBestAgentByMonth($period)
    {
        $this->db->where('month', $period);
        $this->db->order_by('total_score', 'DESC');
        return $this->db->get('kpi_best_agent_detail')->result_array();
    }

    public function getSourceBestAgentByMonth($period)
    {
        $this->db->select('agent');
        $this->db->select('productivity_result as prod_hour');
        $this->db->select('smilevoice_result as csindex_ratio');
        $this->db->select('attendance_result as attendance');
        $this->db->select('elearning_result as elearning_score');
        $this->db->select('teamwork_result as auxratio');
        $this->db->where('month', $period);
        return $this->db->get('kpi_best_agent_detail')->result_array();
    }

    public function getScoreIndex($jobcode, $item, $source)
    {
        $query = "SELECT score FROM kpi_best_agent_measurement WHERE jobcode = '$jobcode' AND item = '$item' AND '$source' BETWEEN range_min AND range_max";
        return $this->db->query($query)->row_array()['score'];
    }

    public function getBestAgentItemsWeight($item)
    {
        $this->db->where('item', $item);
        $this->db->select('weight');
        return $this->db->get('kpi_best_agent_target')->row_array()['weight'];
    }

    public function getBestAgentTarget()
    {
        $this->db->select('weight');
        $this->db->select('item');
        return $this->db->get('kpi_best_agent_target')->result_array();
    }

    public function addNewResultByMonth($data)
    {
        $this->db->insert_batch('kpi_best_agent_detail', $data);
        return $this->db->affected_rows();
    }

    public function getSourceDataByAgent($agent, $startPeriod, $endPeriod)
    {
        $this->db->where('agent', $agent);
        $this->db->where('month >=', $startPeriod);
        $this->db->where('month <=', $endPeriod);
        $this->db->order_by('month', 'DESC');
        return $this->db->get('kpi_best_agent_detail')->result_array();
    }

}
