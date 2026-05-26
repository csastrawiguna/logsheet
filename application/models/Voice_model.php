<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Voice_model extends CI_Model
{
    public function addNewSurvey($data)
    {
        $this->db->insert('voice_assesment', $data);
        return $this->db->affected_rows();
    }

    public function addNewSurveyData($data)
    {
        $this->db->insert('voice_assesment_25f', $data);
        return $this->db->affected_rows();
    }

    public function getAllActiveAgent()
    {
        $this->db->distinct();
        $this->db->select('user_id');
        $this->db->order_by('user_id', 'ASC');
        $this->db->where('is_active', 1);
        $this->db->where('role_access !=', 1);
        $this->db->where('role_access !=', 5);
        $this->db->where('role_access !=', 9);
        return $this->db->get('user')->result_array();
    }

    public function getNumberVoiceByAgentByPeriod($agent, $period)
    {
        $this->db->where('agent', $agent);
        $this->db->where('period', $period);
        return $this->db->get('voice_assesment_25f')->num_rows();
    }

    // summary new version
    public function getVoiceSummaryResultByPeriod($startPeriod, $endPeriod)
    {
        $this->db->select('COUNT(agent) AS qty');
        $this->db->select('COUNT(CASE WHEN greeting = 3 THEN 1 END) AS greeting_good');
        $this->db->select('COUNT(CASE WHEN greeting = 1 THEN 1 END) AS greeting_bad');
        $this->db->select('COUNT(CASE WHEN smile_voice = 10 THEN 1 END) AS smile_good');
        $this->db->select('COUNT(CASE WHEN smile_voice = 5 THEN 1 END) AS smile_less');
        $this->db->select('COUNT(CASE WHEN smile_voice = 3 THEN 1 END) AS smile_flat');
        $this->db->select('COUNT(CASE WHEN smile_voice = 1 THEN 1 END) AS smile_bad');
        $this->db->select('COUNT(CASE WHEN accuracy = 10 THEN 1 END) AS accuracy_good');
        $this->db->select('COUNT(CASE WHEN accuracy = 5 THEN 1 END) AS accuracy_less');
        $this->db->select('COUNT(CASE WHEN accuracy = 1 THEN 1 END) AS accuracy_bad');
        $this->db->select('COUNT(CASE WHEN closing = 2 THEN 1 END) AS closing_good');
        $this->db->select('COUNT(CASE WHEN closing = 1 THEN 1 END) AS closing_bad');
        $this->db->select('(SUM(greeting) + SUM(smile_voice) + SUM(accuracy) + SUM(closing)) AS overall_score');
        $this->db->select('(COUNT(agent) * 25) AS overall_divider');
        $this->db->select('(SUM(greeting) + SUM(smile_voice) + SUM(accuracy) + SUM(closing)) / (COUNT(agent) * 25) AS overall_ratio');
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        return $this->db->get('voice_assesment_25f')->row_array();
    }

    // summary by agent new version
    public function getVoiceSummaryResultByAgent($startPeriod, $endPeriod)
    {
        $query = "SELECT 
            user.user_id AS agent,
            COUNT(voice_assesment_25f.agent) AS qty, 
            AVG(voice_assesment_25f.greeting) AS greeting, 
            COUNT(CASE WHEN voice_assesment_25f.greeting = 3 THEN 1 END) AS greeting_good, 
            AVG(voice_assesment_25f.smile_voice) AS smile, 
            COUNT(CASE WHEN voice_assesment_25f.smile_voice = 10 THEN 1 END) AS smile_good, 
            COUNT(CASE WHEN voice_assesment_25f.smile_voice = 5 THEN 1 END) AS smile_less, 
            AVG(voice_assesment_25f.accuracy) AS accuracy, 
            COUNT(CASE WHEN voice_assesment_25f.accuracy = 10 THEN 1 END) AS accuracy_good, 
            AVG(voice_assesment_25f.closing) AS closing, 
            COUNT(CASE WHEN voice_assesment_25f.closing = 2 THEN 1 END) AS closing_good, 
            (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) AS total 
            FROM user 
            LEFT JOIN voice_assesment_25f
            ON user.user_id = voice_assesment_25f.agent
            AND voice_assesment_25f.period BETWEEN '$startPeriod' AND '$endPeriod'
            WHERE user.is_active = 1
            AND user.jobcode IN ('cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc13', 'cs-ccc-cc14', 'cs-ccc-cc15', 'cs-ccc-cc16', 'cs-ccc-cc20', 'cs-ccc-cc30', 'cs-ccc-cc40')
            GROUP BY user.user_id 
            ORDER BY (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) DESC";
        return $this->db->query($query)->result_array();
    }

    // unprover new version
    public function getUnproperSummaryByPeriod($startPeriod, $endPeriod)
    {
        $query = "SELECT * FROM voice_assesment_25f WHERE (greeting < 3 OR smile_voice < 5 OR accuracy < 5 OR closing < 2) AND period BETWEEN '$startPeriod' AND '$endPeriod' ORDER BY (greeting + smile_voice + accuracy + closing) DESC";
        return $this->db->query($query)->result_array();
    }

    // unproper new version by agent
    public function getUnproperListByAgentByPeriod($startPeriod, $endPeriod, $agent)
    {
        $query = "SELECT * FROM voice_assesment_25f WHERE agent = '$agent' AND (greeting < 3 OR smile_voice < 5 OR accuracy < 5 OR closing < 2) AND period BETWEEN '$startPeriod' AND '$endPeriod'";
        return $this->db->query($query)->result_array();
    }

    public function getTransitionVoiceByPeriod($startPeriod, $endPeriod)
    {
        //STRING QUERY UNTUK PIVOT TABLE DINAMIS
        //Query #1
        $query1 = 'SET @sql = NULL';

        //Query #2
        $query2_1 = "SELECT GROUP_CONCAT(DISTINCT '(
            SUM(greeting_complete < 3 AND period = ''', period, ''')  + 
            SUM(greeting_smile < 1 AND period = ''', period, ''') + 
            SUM(intonation_straight < 1 AND period = ''', period, ''') + 
            SUM(intonation_clear < 1 AND period = ''', period, ''') + 
            SUM(intonation_not_flat < 1 AND period = ''', period, ''') + 
            SUM(intonation_not_flat < 1 AND period = ''', period, ''') + 
            SUM(intonation_not_weak < 1 AND period = ''', period, ''') + 
            SUM(intonation_not_flat < 1 AND period = ''', period, ''') + 
            SUM(intonation_not_high < 1 AND period = ''', period, ''') + 
            SUM(handling_no_jargon < 1 AND period = ''', period, ''') + 
            SUM(handling_customer_name < 1 AND period = ''', period, ''') + 
            SUM(handling_no_jargon < 1 AND period = ''', period, ''') + 
            SUM(handling_communicative < 1 AND period = ''', period, ''') + 
            SUM(handling_accuracy < 1 AND period = ''', period, ''') + 
            SUM(closing < 3 AND period = ''', period, ''') 
            ) AS `', period, '`') INTO @sql FROM voice_assesment JOIN user ON voice_assesment.agent = user.user_id ";
        // $query2_2 = " WHERE user.is_active = '1' AND csindex_survey.period BETWEEN '$startPeriod' AND '$endPeriod' ";
        $query2_2 = " WHERE user.is_active = '1' AND voice_assesment.period BETWEEN '$startPeriod' AND '$endPeriod'";
        $query2 = $query2_1 . $query2_2;
        $query3_1 = "SET @sql =  CONCAT('SELECT agent, ', @sql, ' , is_active FROM voice_assesment JOIN user ON voice_assesment.agent = user.user_id  ";
        $query3_2 = "GROUP BY voice_assesment.agent')";
        $query3 = $query3_1 . $query3_2;
        $query4 = 'PREPARE stmt FROM @sql';
        $query5 = 'EXECUTE stmt';

        $this->db->query($query1);
        $this->db->query($query2);
        $this->db->query($query3);
        $this->db->query($query4);
        return $this->db->query($query5)->result_array();
    }

    public function getNewAverageScoreByAgentByPeriod($agent, $period)
    {
        $this->db->select('AVG(greeting)+ AVG(smile_voice)+ AVG(accuracy)+ AVG(closing) AS averageScore');
        $this->db->where('agent', $agent);
        $this->db->where('period', $period);
        return $this->db->get('voice_assesment_25f')->row_array()['averageScore'];
    }

    public function getAverageScoreByAgentByPeriod($agent, $period)
    {
        $this->db->select('AVG(greeting_complete)+ AVG(greeting_smile)+ AVG(intonation_straight)+ AVG(intonation_clear)+ AVG(intonation_not_flat)+ AVG(intonation_not_weak)+ AVG(intonation_not_high)+ AVG(handling_no_jargon)+ AVG(handling_customer_name)+ AVG(handling_communicative)+ AVG(handling_accuracy)+ avg(handling_ask_help)+ AVG(closing) AS averageScore');
        $this->db->where('agent', $agent);
        $this->db->where('period', $period);
        return $this->db->get('voice_assesment')->row_array()['averageScore'];
    }

    public function getDetailNewVoiceAssesmentByPeriod($startPeriod, $endPeriod)
    {
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('call_date', 'DESC');
        return $this->db->get('voice_assesment_25f')->result_array();
    }

    // OLD version
    public function getDetailVoiceAssesmentByPeriod($startPeriod, $endPeriod)
    {
        $this->db->select('*');
        $this->db->select('(greeting_complete + greeting_smile + intonation_straight + intonation_clear + intonation_not_flat + intonation_not_weak + intonation_not_high + handling_no_jargon + handling_customer_name + handling_communicative + handling_accuracy + handling_ask_help + closing) AS totalScore');
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('call_date', 'DESC');
        return $this->db->get('voice_assesment')->result_array();
    }

    // by agent new version
    public function getDetailNewVoiceAssesmentAgentByPeriod($startPeriod, $endPeriod, $agent)
    {
        $this->db->where('agent', $agent);
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('call_date', 'DESC');
        return $this->db->get('voice_assesment_25f')->result_array();
    }

    // by agent summary new version
    public function getSummaryNewVoiceAssesmentAgentByPeriod($startPeriod, $endPeriod, $agent)
    {
        $query = "SELECT 
            period AS month,
            COUNT(voice_assesment_25f.agent) AS qty, 
            AVG(voice_assesment_25f.greeting) AS greeting, 
            COUNT(CASE WHEN voice_assesment_25f.greeting = 3 THEN 1 END) AS greeting_good, 
            AVG(voice_assesment_25f.smile_voice) AS smile, 
            COUNT(CASE WHEN voice_assesment_25f.smile_voice = 10 THEN 1 END) AS smile_good, 
            COUNT(CASE WHEN voice_assesment_25f.smile_voice = 5 THEN 1 END) AS smile_less, 
            AVG(voice_assesment_25f.accuracy) AS accuracy, 
            COUNT(CASE WHEN voice_assesment_25f.accuracy = 10 THEN 1 END) AS accuracy_good, 
            AVG(voice_assesment_25f.closing) AS closing, 
            COUNT(CASE WHEN voice_assesment_25f.closing = 2 THEN 1 END) AS closing_good, 
            (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) AS total 
            FROM user
            LEFT JOIN voice_assesment_25f
            ON user.user_id = voice_assesment_25f.agent
            AND voice_assesment_25f.period BETWEEN '$startPeriod' AND '$endPeriod'
            WHERE user.user_id = '$agent'
            GROUP BY period 
            ORDER BY period DESC";
        // ORDER BY (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) DESC";
        return $this->db->query($query)->result_array();
    }

    public function deleteVoiceById($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('voice_assesment_25f');
        return $this->db->affected_rows();
    }

    public function performEditVoiceById($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('voice_assesment_25f', $data);
        return $this->db->affected_rows();
    }
}
