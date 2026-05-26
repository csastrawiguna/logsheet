<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Csindex_model extends CI_Model
{
    public function getSurveyDataByPeriod($period)
    {
        $this->db->where('period', $period);
        $this->db->order_by('is_done, agent', 'ASC');
        return $this->db->get('csindex_survey')->result_array();
    }

    public function getLatestPeriodSurveyData()
    {
        $query = "SELECT * FROM csindex_survey WHERE period IN (SELECT MAX(period) FROM csindex_survey) ORDER BY is_done, agent ASC";
        return $this->db->query($query)->result_array();
    }

    public function getSurveyDataById($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by('period', 'DESC');
        return $this->db->get('csindex_survey')->row_array();
    }

    public function submitSurvey($data)
    {
        $this->db->set('questioner_1', $data['questioner_1']);
        $this->db->set('questioner_2', $data['questioner_2']);
        $this->db->set('survey_datetime', $data['survey_datetime']);
        $this->db->set('survey_by', $data['survey_by']);
        $this->db->set('is_done', $data['is_done']);
        $this->db->where('id', $data['id']);
        $this->db->update('csindex_survey');
        return $this->db->affected_rows();
    }

    public function deleteSurvey($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('csindex_survey');
        return $this->db->affected_rows();
    }

    public function deleteSurveyByPeriod($period)
    {
        $this->db->where('period', $period);
        $this->db->delete('csindex_survey');
        return $this->db->affected_rows();
    }

    public function getAllAgents()
    {
        $this->db->select('user_id');
        $this->db->where('is_active', 1);
        return $this->db->get('user')->result_array();
    }

    public function numRowsAgent()
    {
        $this->db->select('agent');
        return $this->db->get('csindex_survey')->num_rows();
    }

    public function numRowsQuestioner()
    {
        $this->db->select('agent');
        return $this->db->get('csindex_survey')->num_rows();
    }

    public function getSurveyQtyByAgent()
    {
        return $this->numRowsQuestioner() / $this->numRowsAgent();
    }

    public function getAllPeriod()
    {
        $this->db->distinct();
        $this->db->order_by('period', 'DESC');
        $this->db->select('period');
        return $this->db->get('csindex_survey')->result_array();
    }

    public function getSurveyResultByPeriod($period)
    {
        $query = "SELECT agent, count(agent) AS qty_agent,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) AS q1_3,
        COUNT(CASE WHEN questioner_1=2 THEN 1 END) AS q1_2,
        COUNT(CASE WHEN questioner_1=1 THEN 1 END) AS q1_1,
        COUNT(CASE WHEN questioner_1=-1 THEN 1 END) AS q1__1,
        COUNT(CASE WHEN questioner_1=-2 THEN 1 END) AS q1__2,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 1 + COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * -1 + COUNT(CASE WHEN questioner_1=-2 THEN 1 END) * -2 AS q1_point,
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) AS q2_3,
        COUNT(CASE WHEN questioner_2=2 THEN 1 END) AS q2_2,
        COUNT(CASE WHEN questioner_2=1 THEN 1 END) AS q2_1,
        COUNT(CASE WHEN questioner_2=-1 THEN 1 END) AS q2__1,
        COUNT(CASE WHEN questioner_2=-2 THEN 1 END) AS q2__2,
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 1 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * -1 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * -2 AS q2_point,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 1 + COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * -1 + COUNT(CASE WHEN questioner_1=-2 THEN 1 END) * -2 + COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 1 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * -1 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * -2 AS total_point,
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
                (COUNT(questioner_1) + COUNT(questioner_2)) AS cs_ratio
        FROM csindex_survey WHERE period = '$period' AND is_done = 1 GROUP BY agent";
        return $this->db->query($query)->result_array();
    }

    public function getCsareaResultByPeriod($period)
    {
        $query = "SELECT COUNT(questioner_1) AS q1_qty, COUNT(questioner_2) AS q2_qty,
        COUNT(questioner_1)/COUNT(questioner_1) AS p_q1, COUNT(questioner_2)/COUNT(questioner_2) AS p_q2,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) AS q1_3,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) / COUNT(questioner_1) AS p_q1_3,
        COUNT(CASE WHEN questioner_1=2 THEN 1 END) AS q1_2,
        COUNT(CASE WHEN questioner_1=2 THEN 1 END) / COUNT(questioner_1) AS p_q1_2,
        COUNT(CASE WHEN questioner_1=1 THEN 1 END) AS q1_1,
        COUNT(CASE WHEN questioner_1=1 THEN 1 END) / COUNT(questioner_1) AS p_q1_1,
        COUNT(CASE WHEN questioner_1=-1 THEN 1 END) AS q1__1,
        COUNT(CASE WHEN questioner_1=-1 THEN 1 END) / COUNT(questioner_1) AS p_q1__1,
        COUNT(CASE WHEN questioner_1=-2 THEN 1 END) AS q1__2,
        COUNT(CASE WHEN questioner_1=-2 THEN 1 END) / COUNT(questioner_1) AS p_q1__2,
        (COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_1=-2 THEN 1 END) * 1) / (COUNT(questioner_1) * 5) AS q1_result, 
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) AS q2_3,
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) / COUNT(questioner_2) AS p_q2_3,
        COUNT(CASE WHEN questioner_2=2 THEN 1 END) AS q2_2,
        COUNT(CASE WHEN questioner_2=2 THEN 1 END) / COUNT(questioner_2) AS p_q2_2,
        COUNT(CASE WHEN questioner_2=1 THEN 1 END) AS q2_1,
        COUNT(CASE WHEN questioner_2=1 THEN 1 END) / COUNT(questioner_2) AS p_q2_1,
        COUNT(CASE WHEN questioner_2=-1 THEN 1 END) AS q2__1,
        COUNT(CASE WHEN questioner_2=-1 THEN 1 END) / COUNT(questioner_2) AS p_q2__1,
        COUNT(CASE WHEN questioner_2=-2 THEN 1 END) AS q2__2,
        COUNT(CASE WHEN questioner_2=-2 THEN 1 END) / COUNT(questioner_2) AS p_q2__2,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) + COUNT(CASE WHEN questioner_1=2 THEN 1 END) AS q1_csarea,
        (COUNT(CASE WHEN questioner_1=3 THEN 1 END) + COUNT(CASE WHEN questioner_1=2 THEN 1 END)) / COUNT(questioner_1) AS p_q1_csarea,
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) + COUNT(CASE WHEN questioner_2=2 THEN 1 END) AS q2_csarea,
        (COUNT(CASE WHEN questioner_2=3 THEN 1 END) + COUNT(CASE WHEN questioner_2=2 THEN 1 END)) / COUNT(questioner_2) AS p_q2_csarea,
        (COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * 1) / (COUNT(questioner_2) * 5) AS q2_result,      
        (((COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_1=-2 THEN 1 END)* 1) / (COUNT(questioner_1) * 5) * COUNT(questioner_1)) + ((COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * 1) / (COUNT(questioner_2) * 5) * COUNT(questioner_2))) / (COUNT(questioner_1) + COUNT(questioner_2)) AS total_result
        FROM csindex_survey WHERE period = '$period' AND is_done = 1";
        return $this->db->query($query)->row_array();
    }

    public function getResultByAgentByPeriod($agent, $startPeriod, $endPeriod)
    {
        $query = "SELECT SUM(questioner_1) AS questioner_1, SUM(questioner_2) AS questioner_2, SUM(questioner_1) + SUM(questioner_2) AS total_point, period, agent, COUNT(survey_datetime) AS qty,
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
                (COUNT(questioner_1) + COUNT(questioner_2)) AS cs_ratio        
            FROM csindex_survey WHERE agent = '$agent' AND period <= '$endPeriod' AND period >= '$startPeriod'  AND is_done = '1' GROUP BY period ORDER BY period DESC";
        return $this->db->query($query)->result_array();
    }

    public function getAverageResultByAgentByPeriod($agent, $startPeriod, $endPeriod)
    {
        $query = "SELECT AVG(questioner_1) AS questioner_1, AVG(questioner_2) AS questioner_2, AVG(questioner_1) + AVG(questioner_2) AS total_point, period, agent, AVG(survey_datetime) AS qty,
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
                (COUNT(questioner_1) + COUNT(questioner_2)) AS cs_ratio        
            FROM csindex_survey WHERE agent = '$agent' AND period <= '$endPeriod' AND period >= '$startPeriod'  AND is_done = '1' ";
        return $this->db->query($query)->result_array();
    }

    public function getAverageResultByAgentByPeriod2($agent, $startPeriod, $endPeriod)
    {
        $query = "SELECT 
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
                (COUNT(questioner_1) + COUNT(questioner_2)) AS cs_ratio
            FROM csindex_survey WHERE agent = '$agent' AND period <= '$endPeriod' AND period >= '$startPeriod'  AND is_done = '1' ";
        return $this->db->query($query)->row_array()['cs_ratio'];
    }

    public function autoDeleteLastSixMonthsData($period)
    {
        $this->db->where('period', $period);
        $this->db->where('is_done', 0);
        $this->db->delete('csindex_survey');
    }

    public function getResultByPeriod($period)
    {
        $this->db->select('agent');
        $this->db->select('questioner_1');
        $this->db->select('questioner_2');
        $this->db->where('period', $period);
        $this->db->where('is_done', 1);
        return $this->db->get('csindex_survey')->result_array();
    }

    public function getSummary($startPeriod, $endPeriod, $order = 'DESC')
    {
        $query = "SELECT period,
        COUNT(CASE WHEN questioner_1=3 THEN 1 END) AS q1_3,        
        COUNT(CASE WHEN questioner_1=2 THEN 1 END) AS q1_2,
        COUNT(CASE WHEN questioner_1=1 THEN 1 END) AS q1_1,
        COUNT(CASE WHEN questioner_1=-1 THEN 1 END) AS q1__1,
        COUNT(CASE WHEN questioner_1=-2 THEN 1 END) AS q1__2,
        COUNT(questioner_1) AS q1_qty,
        (COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 4 + (COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 3) + (COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * 2) + (COUNT(CASE WHEN questioner_1=-2 THEN 1 END) * 1)) / (COUNT(questioner_1) * 5) AS q1_result,
        COUNT(CASE WHEN questioner_2=3 THEN 1 END) AS q2_3,
        COUNT(CASE WHEN questioner_2=2 THEN 1 END) AS q2_2,
        COUNT(CASE WHEN questioner_2=1 THEN 1 END) AS q2_1,
        COUNT(CASE WHEN questioner_2=-1 THEN 1 END) AS q2__1,
        COUNT(CASE WHEN questioner_2=-2 THEN 1 END) AS q2__2,
        COUNT(questioner_2) AS q2_qty,
        (COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * 1) / (COUNT(questioner_2) * 5) AS q2_result,        
        (((COUNT(CASE WHEN questioner_1=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_1=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_1=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_1=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_1=-2 THEN 1 END)* 1) / (COUNT(questioner_1) * 5) * COUNT(questioner_1)) + ((COUNT(CASE WHEN questioner_2=3 THEN 1 END) * 5 + COUNT(CASE WHEN questioner_2=2 THEN 1 END) * 4 + COUNT(CASE WHEN questioner_2=1 THEN 1 END) * 3 + COUNT(CASE WHEN questioner_2=-1 THEN 1 END) * 2 + COUNT(CASE WHEN questioner_2=-2 THEN 1 END) * 1) / (COUNT(questioner_2) * 5) * COUNT(questioner_2))) / (COUNT(questioner_1) + COUNT(questioner_2)) AS total_result,
        COUNT(questioner_1) / COUNT(DISTINCT(agent)) AS surveyQty           
        FROM csindex_survey WHERE period >= '$startPeriod' AND period <= '$endPeriod' AND is_done = 1 GROUP BY period ORDER BY period $order";
        return $this->db->query($query)->result_array();
    }

    public function getTransitionByAgent($startPeriod, $endPeriod)
    {
        //STRING QUERY UNTUK PIVOT TABLE DINAMIS
        //Query #1
        $query1 = 'SET @sql = NULL';

        //Query #2
        $query2_1 = "SELECT GROUP_CONCAT(DISTINCT CONCAT('";
        $query2_2 = 'MAX(IF(csindex_survey.period = "';
        $query2_3 = "',elearning_category.period, '";
        $query2_4 = '", elearning_assignment.score, 0)) AS `';
        $query2_5 = "', elearning_category.period,";
        $query2_6 = "'`')) INTO @sql FROM elearning_category";
        $query2_7 = " WHERE elearning_category.period <= '$endPeriod' AND elearning_category.period >= '$startPeriod'";
        $query2 = $query2_1 . $query2_2 . $query2_3 . $query2_4 . $query2_5 . $query2_6 . $query2_7;

        //Query #3
        $query3_1 = 'SET @sql =  CONCAT(';
        $query3_2 = "'SELECT user_id, ', @sql,'";
        // $query3_3 = ", elearning_assignment.score";
        $query3_4 = " FROM elearning_assignment GROUP BY agent')";
        // $query3_5 = " WHERE elearning_category.period <= `$endPeriod` AND elearning_category.period >= `$startPeriod`";
        // $query3_6 = "GROUP BY elearning_assignment.user_id ORDER BY user_id')";
        $query3 = $query3_1 . $query3_2 . $query3_4;

        //Query #4
        $query4 = 'PREPARE stmt FROM @sql';

        //Query #5
        $query5 = 'EXECUTE stmt';

        $this->db->query($query1);
        $this->db->query($query2);
        $this->db->query($query3);
        $this->db->query($query4);
        return $this->db->query($query5)->result_array();
    }

    public function getAllListedData()
    {
        $this->db->select('period');
        $this->db->select('count(agent) AS dataQty');
        $this->db->select('count(questioner_1) AS q1');
        $this->db->where('is_done', 1);
        $this->db->order_by('period', 'DESC');
        $this->db->group_by('period');
        return $this->db->get('csindex_survey')->result_array();
    }

    public function getCsindexTransition($startPeriod, $endPeriod)
    {
        //STRING QUERY UNTUK PIVOT TABLE DINAMIS
        //Query #1
        $query1 = 'SET @sql = NULL';

        //Query #2
        $query2_1 = "SELECT GROUP_CONCAT(DISTINCT '(((
            SUM(questioner_1 = 3 AND period = ''', period, ''') * 5 + 
            SUM(questioner_1 = 2 AND period = ''', period, ''') * 4 + 
            SUM(questioner_1 = 1 AND period = ''', period, ''') * 3 + 
            SUM(questioner_1 = -1 AND period = ''', period, ''') * 2 + 
            SUM(questioner_1 = -2 AND period = ''', period, ''') * 1 ) / 
            (SUM(questioner_1 != 0 AND period = ''', period, ''') * 5) * 
           SUM(questioner_1 != 0 AND period = ''', period, ''')) +
            ((SUM(questioner_2 = 3 AND period = ''', period, ''') * 5 + 
            SUM(questioner_2 = 2 AND period = ''', period, ''') * 4 + 
            SUM(questioner_2 = 1 AND period = ''', period, ''') * 3 + 
            SUM(questioner_2 = -1 AND period = ''', period, ''') * 2 + 
            SUM(questioner_2 = -2 AND period = ''', period, ''') * 1 ) / 
            (SUM(questioner_2 != 0 AND period = ''', period, ''') * 5) *
            SUM(questioner_2 != 0 AND period = ''', period, '''))) / 
            (SUM(questioner_1 != 0 AND period = ''', period, ''') + 
            SUM(questioner_2 != 0 AND period = ''', period, ''') ) AS `', period, '`') INTO @sql FROM user JOIN csindex_survey ON user.user_id = csindex_survey.agent ";
        // $query2_2 = " WHERE user.is_active = '1' AND csindex_survey.period BETWEEN '$startPeriod' AND '$endPeriod' ";
        $query2_2 = " WHERE user.is_active = 1 AND csindex_survey.period BETWEEN '$startPeriod' AND '$endPeriod' ";
        $query2 = $query2_1 . $query2_2;
        $query3_1 = "SET @sql =  CONCAT('SELECT agent, ', @sql, ' FROM csindex_survey ";
        $query3_2 = "GROUP BY csindex_survey.agent')";
        $query3 = $query3_1 . $query3_2;
        $query4 = 'PREPARE stmt FROM @sql';
        $query5 = 'EXECUTE stmt';

        $this->db->query($query1);
        $this->db->query($query2);
        $this->db->query($query3);
        $this->db->query($query4);
        return $this->db->query($query5)->result_array();
    }
}
