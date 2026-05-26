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
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        return $this->db->get('voice_assesment_25f')->row_array();
    }

    // summary by agent new version
    public function getVoiceSummaryResultByAgent($startPeriod, $endPeriod)
    {
        // $this->db->select('user.user_id AS agent');
        // $this->db->select('COUNT(voice_assesment_25f.agent) AS qty');
        // $this->db->select('AVG(voice_assesment_25f.greeting) AS greeting');
        // $this->db->select('COUNT(CASE WHEN voice_assesment_25f.greeting = 3 THEN 1 END) AS greeting_good');
        // $this->db->select('AVG(voice_assesment_25f.smile_voice) AS smile');
        // $this->db->select('COUNT(CASE WHEN voice_assesment_25f.smile_voice = 10 THEN 1 END) AS smile_good');
        // $this->db->select('COUNT(CASE WHEN voice_assesment_25f.smile_voice = 5 THEN 1 END) AS smile_less');
        // $this->db->select('AVG(voice_assesment_25f.accuracy) AS accuracy');
        // $this->db->select('COUNT(CASE WHEN voice_assesment_25f.accuracy = 10 THEN 1 END) AS accuracy_good');
        // $this->db->select('AVG(voice_assesment_25f.closing) AS closing');
        // $this->db->select('COUNT(CASE WHEN voice_assesment_25f.closing = 2 THEN 1 END) AS closing_good');
        // $this->db->select('(AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) AS total');
        // $this->db->where('voice_assesment_25f.period >=', $startPeriod);
        // $this->db->where('voice_assesment_25f.period <=', $endPeriod);
        // $this->db->where('user.is_active', 1);
        // $this->db->where('user.jobcode', 'cs-ccc-cc10');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc11');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc12');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc13');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc14');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc15');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc16');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc17');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc18');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc20');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc30');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc40');
        // $this->db->or_where('user.jobcode', 'cs-ccc-cc50');
        // $this->db->group_by('voice_assesment_25f.agent');
        // $this->db->order_by('(AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing))', 'DESC');
        // $this->db->join('voice_assesment_25f', 'user.user_id = voice_assesment_25f.agent', 'LEFT');
        // return $this->db->get('user1')->result_array();

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
            WHERE user.is_active 
            AND user.jobcode IN ('cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc13', 'cs-ccc-cc14', 'cs-ccc-cc15', 'cs-ccc-cc16', 'cs-ccc-cc17', 'cs-ccc-cc18', 'cs-ccc-cc20', 'cs-ccc-cc30', 'cs-ccc-cc40')
            GROUP BY user.user_id 
            ORDER BY (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) DESC";
        return $this->db->query($query)->result_array();   
    }

    // unprover new version
    public function getUnproperSummaryByPeriod($startPeriod, $endPeriod)
    {   
        $this->db->where('greeting <', 3);
        $this->db->or_where('smile_voice <', 5);
        $this->db->or_where('accuracy <', 5);
        $this->db->or_where('closing <', 2);
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('(greeting + smile_voice + accuracy + closing)', 'DESC');
        return $this->db->get('voice_assesment_25f')->result_array();
    }

    // unproper new version by agent
    public function getUnproperListByAgentByPeriod($startPeriod, $endPeriod, $agent)
    {
        $query = "SELECT * FROM voice_assesment_25f WHERE agent LIKE '$agent' AND (greeting < 3 OR smile_voice < 5 OR accuracy < 5 OR closing < 2) AND period BETWEEN '$startPeriod' AND '$endPeriod'";
        return $this->db->query($query)->result_array();
        // $this->db->where('agent', $agent);
        // $this->db->where('greeting <', 3);
        // $this->db->or_where('smile_voice <', 5);
        // $this->db->or_where('accuracy <', 5);
        // $this->db->or_where('closing <', 2);
        // $this->db->where('period >=', $startPeriod);
        // $this->db->where('period <=', $endPeriod);
        // $this->db->order_by('(greeting + smile_voice + accuracy + closing)', 'DESC');
        // return $this->db->get('voice_assesment_25f')->result_array();
    }

    // old version
    public function getVoiceSummaryByFindings($startPeriod, $endPeriod)
    {
        $this->db->select('count(greeting_complete) AS qty');
        $this->db->select('SUM(greeting_complete) AS greeting_complete');
        $this->db->select('SUM(greeting_smile) AS greeting_smile');
        $this->db->select('SUM(intonation_straight) AS intonation_straight');
        $this->db->select('SUM(intonation_clear) AS intonation_clear');
        $this->db->select('SUM(intonation_not_flat) AS intonation_not_flat');
        $this->db->select('SUM(intonation_not_weak) AS intonation_not_weak ');
        $this->db->select('SUM(intonation_not_high) AS intonation_not_high');
        $this->db->select('SUM(handling_no_jargon) AS handling_no_jargon');
        $this->db->select('SUM(handling_customer_name) AS handling_customer_name');
        $this->db->select('SUM(handling_communicative) AS handling_communicative');
        $this->db->select('SUM(handling_accuracy) AS handling_accuracy');
        $this->db->select('SUM(handling_ask_help) AS handling_ask_help');
        $this->db->select('SUM(closing) AS closing');
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        return $this->db->get('voice_assesment')->row_array();
    }

    // old version
    public function getVoiceSummaryByPeriod($startPeriod, $endPeriod)
    {
        $this->db->select('period');
        $this->db->select('COUNT(period) AS survey_qty');
        $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) AS greeting_complete');
        $this->db->select('(COUNT(CASE WHEN greeting_complete < 3 THEN 1 END)) / COUNT(period) AS ratio_greeting_complete');
        $this->db->select('COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) AS greeting_smile');
        $this->db->select('(COUNT(CASE WHEN greeting_smile < 1 THEN 1 END)) / COUNT(period) AS ratio_greeting_smile');
        $this->db->select('COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) AS intonation_straight');
        $this->db->select('(COUNT(CASE WHEN intonation_straight < 1 THEN 1 END)) /COUNT(period) AS ratio_intonation_straight');
        $this->db->select('COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) AS intonation_clear');
        $this->db->select('(COUNT(CASE WHEN intonation_clear < 1 THEN 1 END)) /COUNT(period) AS ratio_intonation_clear');
        $this->db->select('COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) AS intonation_not_flat');
        $this->db->select('(COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END)) /COUNT(period) AS ratio_intonation_not_flat');
        $this->db->select('COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) AS intonation_not_weak');
        $this->db->select('(COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END)) /COUNT(period) AS ratio_intonation_not_weak');
        $this->db->select('COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) AS intonation_not_high');
        $this->db->select('(COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END)) / COUNT(period) AS ratio_intonation_not_high');
        $this->db->select('COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) AS handling_no_jargon');
        $this->db->select('(COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END)) / COUNT(period) AS ratio_handling_no_jargon');
        $this->db->select('COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) AS handling_customer_name');
        $this->db->select('(COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END)) / COUNT(period) AS ratio_handling_customer_name');
        $this->db->select('COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) AS handling_communicative');
        $this->db->select('(COUNT(CASE WHEN handling_communicative < 1 THEN 1 END)) /COUNT(period) AS ratio_handling_communicative');
        $this->db->select('COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) AS handling_accuracy');
        $this->db->select('(COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END)) / COUNT(period) AS ratio_handling_accuracy');
        $this->db->select('COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) AS handling_ask_help');
        $this->db->select('(COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END)) / COUNT(period) AS ratio_handling_ask_help');
        $this->db->select('COUNT(CASE WHEN closing < 5 THEN 1 END) AS closing');
        $this->db->select('(COUNT(CASE WHEN closing < 5 THEN 1 END)) / COUNT(period) AS ratio_closing');
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->group_by('period');
        $this->db->order_by('period', 'DESC');
        return $this->db->get('voice_assesment')->result_array();
    }

    public function getUnproperVoiceSummaryByPeriod($startPeriod, $endPeriod)
    {        
        $this->db->select('COUNT(period) AS survey_qty');
        $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) AS greeting_incomplete');
        $this->db->select('COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) AS greeting_nosmile');
        $this->db->select('COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) AS intonation_nostraight');
        $this->db->select('COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) AS intonation_noclear');
        $this->db->select('COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) AS intonation_flat');
        $this->db->select('COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) AS intonation_weak');
        $this->db->select('COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) AS intonation_high');
        $this->db->select('COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) AS handling_jargon');
        $this->db->select('COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) AS handling_nocustomer_name');
        $this->db->select('COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) AS handling_nocommunicative');
        $this->db->select('COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) AS handling_inaccurate');
        $this->db->select('COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) AS handling_noask_help');
        $this->db->select('COUNT(CASE WHEN closing = 2 THEN 1 END) AS closing_unstandard');
        $this->db->select('COUNT(CASE WHEN closing = 1 THEN 1 END) AS closing_incomplete');
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        // $this->db->group_by('period');
        $this->db->order_by('period', 'DESC');
        return $this->db->get('voice_assesment')->result_array();
    }

    public function getUnproperVoiceSummaryByAgent($startPeriod, $endPeriod)
    {
        // $this->db->select('COUNT(period) AS survey_qty');
        $this->db->select('agent');
        $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) AS greeting_incomplete');
        $this->db->select('COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) AS greeting_nosmile');
        $this->db->select('COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) AS intonation_nostraight');
        $this->db->select('COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) AS intonation_noclear');
        $this->db->select('COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) AS intonation_flat');
        $this->db->select('COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) AS intonation_weak');
        $this->db->select('COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) AS intonation_high');
        $this->db->select('COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) AS handling_jargon');
        $this->db->select('COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) AS handling_nocustomer_name');
        $this->db->select('COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) AS handling_nocommunicative');
        $this->db->select('COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) AS handling_inaccurate');
        $this->db->select('COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) AS handling_noask_help');
        $this->db->select('COUNT(CASE WHEN closing = 2 THEN 1 END) AS closing_unstandard');
        $this->db->select('COUNT(CASE WHEN closing = 1 THEN 1 END) AS closing_incomplete');

        $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) + COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) +  COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) + COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) + COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) + COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) + COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) + COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) + COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) + COUNT(CASE WHEN closing = 2 THEN 1 END) + COUNT(CASE WHEN closing = 1 THEN 1 END) AS total_finding');        
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('total_finding', 'DESC');
        return $this->db->get('voice_assesment')->result_array();
    }

    public function getUnproperVoiceSummaryNotes($startPeriod, $endPeriod)
    {
        // $this->db->select('agent');
        // $this->db->select('voice_remark');
    
        // $this->db->where('period >=', $startPeriod);
        // $this->db->where('period <=', $endPeriod);        
        // return $this->db->get('voice_assesment')->result_array();
    }

    public function getUnproperVoiceSummaryClearAgent($startPeriod, $endPeriod)
    {
        $query = "SELECT voice_assesment.agent AS agent, user.photo AS photo, SUM(greeting_complete + greeting_smile + intonation_straight + intonation_clear + intonation_not_flat + intonation_not_weak + intonation_not_high + handling_no_jargon + handling_customer_name + handling_communicative + handling_accuracy + handling_ask_help + closing) AS total FROM voice_assesment JOIN user ON voice_assesment.agent = user.user_id 
            WHERE
            period BETWEEN '$startPeriod' AND '$endPeriod'
            GROUP BY voice_assesment.agent
            HAVING AVG(greeting_complete + greeting_smile + intonation_straight + intonation_clear + intonation_not_flat + intonation_not_weak + intonation_not_high + handling_no_jargon + handling_customer_name + handling_communicative + handling_accuracy + handling_ask_help + closing) = 20";

        return $this->db->query($query)->result_array();
    }

    // public function getUnproperVoiceSummaryTotal($startPeriod, $endPeriod)
    // {
    //     // $this->db->select('COUNT(period) AS survey_qty');
    //     $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) AS greeting_incomplete');
    //     $this->db->select('COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) AS greeting_nosmile');
    //     $this->db->select('COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) AS intonation_nostraight');
    //     $this->db->select('COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) AS intonation_noclear');
    //     $this->db->select('COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) AS intonation_flat');
    //     $this->db->select('COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) AS intonation_weak');
    //     $this->db->select('COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) AS intonation_high');
    //     $this->db->select('COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) AS handling_jargon');
    //     $this->db->select('COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) AS handling_nocustomer_name');
    //     $this->db->select('COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) AS handling_nocommunicative');
    //     $this->db->select('COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) AS handling_inaccurate');
    //     $this->db->select('COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) AS handling_noask_help');
    //     $this->db->select('COUNT(CASE WHEN closing = 2 THEN 1 END) AS closing_unstandard');
    //     $this->db->select('COUNT(CASE WHEN closing = 1 THEN 1 END) AS closing_incomplete');

    //     $this->db->select('COUNT(CASE WHEN greeting_complete < 3 THEN 1 END) + COUNT(CASE WHEN greeting_smile < 1 THEN 1 END) +  COUNT(CASE WHEN intonation_straight < 1 THEN 1 END) + COUNT(CASE WHEN intonation_clear < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_flat < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_weak < 1 THEN 1 END) + COUNT(CASE WHEN intonation_not_high < 1 THEN 1 END) + COUNT(CASE WHEN handling_no_jargon < 1 THEN 1 END) + COUNT(CASE WHEN handling_customer_name < 1 THEN 1 END) + COUNT(CASE WHEN handling_communicative < 1 THEN 1 END) + COUNT(CASE WHEN handling_accuracy < 1 THEN 1 END) + COUNT(CASE WHEN handling_ask_help < 1 THEN 1 END) + COUNT(CASE WHEN closing = 2 THEN 1 END) + COUNT(CASE WHEN closing = 1 THEN 1 END) AS total_finding');        
    //     $this->db->where('period >=', $startPeriod);
    //     $this->db->where('period <=', $endPeriod);
    //     return $this->db->get('voice_assesment')->result_array();
    // }

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
            AND user.jobcode IN ('cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc13', 'cs-ccc-cc14', 'cs-ccc-cc15', 'cs-ccc-cc16', 'cs-ccc-cc17', 'cs-ccc-cc18', 'cs-ccc-cc20', 'cs-ccc-cc30', 'cs-ccc-cc40')
            GROUP BY period 
            ORDER BY (AVG(voice_assesment_25f.greeting) + AVG(voice_assesment_25f.smile_voice) + AVG(voice_assesment_25f.accuracy) + AVG(voice_assesment_25f.closing)) DESC";
        return $this->db->query($query)->result_array();  
    }


    //OLD by agent
    public function getDetailVoiceAssesmentByAgentByPeriod($startPeriod, $endPeriod, $agent)
    {
        $this->db->select('*');
        $this->db->select('(greeting_complete + greeting_smile + intonation_straight + intonation_clear + intonation_not_flat + intonation_not_weak + intonation_not_high + handling_no_jargon + handling_customer_name + handling_communicative + handling_accuracy + handling_ask_help + closing) AS totalScore');
        $this->db->where('agent', $agent);
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        $this->db->order_by('agent', 'DESC');
        return $this->db->get('voice_assesment')->result_array();
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
        $this->db->update('voice_assesment', $data);
        return $this->db->affected_rows();
    }
}
