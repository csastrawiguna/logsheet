<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Productivity_model extends CI_Model
{
    public function getProductivityByPeriodByAgent($agent, $prevPeriod, $latestPeriod)
    {
        $this->db->select('*');
        $this->db->select('(icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others) AS total');
        $this->db->select('(icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others)/work_hour AS prod_hour');
        $this->db->where('agent', $agent);
        $this->db->where('period>=', $prevPeriod);
        $this->db->where('period<=', $latestPeriod);
        $this->db->order_by('period', 'DESC');
        return $this->db->get('productivity')->result_array();
    }

    public function getAverageProductivityByPeriodByAgent($agent, $prevPeriod, $latestPeriod)
    {
        $this->db->select('AVG(icall) AS avg_icall');
        $this->db->select('AVG(callback) AS avg_callback');
        $this->db->select('AVG(follow_up) AS avg_follow_up');
        $this->db->select('AVG(sms) AS avg_sms');
        $this->db->select('AVG(webchat) AS avg_webchat');
        $this->db->select('AVG(whatsapp) AS avg_whatsapp');
        $this->db->select('AVG(sharp_id) AS avg_sharp_id');
        $this->db->select('AVG(email) AS avg_email');
        $this->db->select('AVG(notif_sap) AS avg_notif_sap');
        $this->db->select('AVG(complaint) AS avg_complaint');
        $this->db->select('AVG(part_code) AS avg_part_code');
        $this->db->select('AVG(others) AS avg_others');
        $this->db->select('AVG(work_hour) AS avg_work_hour');
        $this->db->select('AVG(icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others) AS avg_total');
        $this->db->select('AVG((icall + callback + follow_up + sms + webchat + whatsapp + sharp_id + email + notif_sap + complaint + part_code + others)/work_hour) AS avg_prod_hour');
        $this->db->where('agent', $agent);
        $this->db->where('period>=', $prevPeriod);
        $this->db->where('period<=', $latestPeriod);
        return $this->db->get('productivity')->row_array();
    }

    public function getAllAgent()
    {
        $this->db->distinct();
        $this->db->order_by('user_id', 'ASC');
        $this->db->where('is_active', 1);
        $this->db->LIKE('jobcode', 'cs-ccc');
        $this->db->select('user_id');
        return $this->db->get('user')->result_array();
    }

    public function getAllPeriod()
    {
        $this->db->distinct();
        $this->db->select('period');
        $this->db->order_by('period', 'DESC');
        return $this->db->get('productivity')->result_array();
    }

    public function getSummaryProductivityByPeriod($startPeriod, $endPeriod, $orderBy = 'total', $orderType = 'DESC')
    {
        $this->db->select('id');
        $this->db->select('agent');
        $this->db->select('AVG(icall) AS icall');
        $this->db->select('AVG(callback) AS callback');
        $this->db->select('AVG(follow_up) AS follow_up');
        $this->db->select('AVG(sms) AS sms');
        $this->db->select('AVG(webchat) AS webchat');
        $this->db->select('AVG(whatsapp) AS whatsapp');
        $this->db->select('AVG(sharp_id) AS sharp_id');
        $this->db->select('AVG(email) AS email');
        $this->db->select('AVG(notif_sap) AS notif_sap');
        $this->db->select('AVG(complaint) AS complaint');
        $this->db->select('AVG(part_code) AS part_code');
        $this->db->select('AVG(others) AS others');
        $this->db->select('(AVG(icall) + AVG(callback) + AVG(follow_up) + AVG(sms) + AVG(webchat) + AVG(whatsapp) + AVG(sharp_id) + AVG(email) + AVG(notif_sap) + AVG(complaint) + AVG(part_code + others)) AS total');
        $this->db->select('work_hour');
        $this->db->select('(AVG(icall) + AVG(callback) + AVG(follow_up) + AVG(sms) + AVG(webchat) + AVG(whatsapp) + AVG(sharp_id) + AVG(email) + AVG(notif_sap) + AVG(complaint) + AVG(part_code) + AVG(others))/AVG(work_hour) AS prod_hour');
        $this->db->select('jobdesk');
        $this->db->select('user.jobcode AS jobcode');        
        $this->db->join('user', 'user.user_id = productivity.agent');
        $this->db->join('jobdesk', 'user.jobcode = jobdesk.jobcode');
        $this->db->where('period>=', $startPeriod);
        $this->db->where('period<=', $endPeriod);
        $this->db->where('user.is_active', 1);
        $this->db->group_by('agent');
        $this->db->order_by($orderBy, $orderType);
        return $this->db->get('productivity')->result_array();
    }

    public function insertBatchProductivity($data)
    {
        $this->db->insert_batch('productivity', $data);
        return $this->db->affected_rows();
    }

    public function checkExistingPeriod($data)
    {
        $period = $data[0]['period'];
        $this->db->where('period', $period);
        return $this->db->get('productivity')->num_rows();
    }

    public function deleteProductivityByPeriod($data)
    {
        $period = $data[0]['period'];
        $this->db->where('period', $period);
        $this->db->delete('productivity');
        return $this->db->affected_rows();
    }

    public function addSingleProductivity($data)
    {
        $this->db->insert('productivity', $data);
        return $this->db->affected_rows();
    }

    public function deleteSingleProductivity($data)
    {
        $this->db->where('period', $data['period']);
        $this->db->where('agent', $data['agent']);
        $this->db->delete('productivity');
        return $this->db->affected_rows();
    }

    public function editSingleProductivity($data)
    {
        $this->db->where('period', $data['period']);
        $this->db->where('agent', $data['agent']);
        $this->db->set('icall',$data['icall']);
        $this->db->set('callback',$data['callback']);
        $this->db->set('follow_up',$data['follow_up']);
        $this->db->set('sms',$data['sms']);
        $this->db->set('webchat',$data['webchat']);
        $this->db->set('whatsapp',$data['whatsapp']);
        $this->db->set('sharp_id',$data['sharp_id']);
        $this->db->set('email',$data['email']);
        $this->db->set('notif_sap',$data['notif_sap']);
        $this->db->set('complaint',$data['complaint']);
        $this->db->set('part_code',$data['part_code']);
        $this->db->set('others',$data['others']);
        $this->db->set('work_hour',$data['work_hour']);
        $this->db->update('productivity');
        return $this->db->affected_rows();
    }

    public function getJobcodeByAgent($agent)
    {
        $this->db->select('jobcode');
        $this->db->where('user_id', $agent);
        return $this->db->get('user')->row_array()['jobcode'];   
    }

    public function getProductivityDailyTarget($jobcode)
    {
        $this->db->select('target');
        $this->db->where('jobcode', $jobcode);
        return $this->db->get('productivity_daily_target')->row_array();
    }

    public function getAllProductivityDailyTarget()
    {
        $this->db->select('jobdesk.jobdesk as jobdesk');
        $this->db->select('jobdesk.jobcode as jobcode');
        $this->db->select('productivity_daily_target.target as target');
        $this->db->select('productivity_daily_target.icon as icon');
        $this->db->join('jobdesk', 'ON jobdesk.jobcode = productivity_daily_target.jobcode');
        $this->db->order_by('jobdesk.jobcode', 'ASC');
        return $this->db->get('productivity_daily_target')->result_array();
    }

    public function getProductivityDailyData($startPeriod, $endPeriod, $agent)
    {
        $this->db->select('productivity_daily.date AS date');
        $this->db->select('productivity_daily.icall AS icall');
        $this->db->select('productivity_daily.whatsapp_reply AS whatsapp_reply');
        $this->db->select('productivity_daily.sms_email AS sms_email');
        $this->db->select('productivity_daily.followup AS followup');
        $this->db->select('(productivity_daily.icall + productivity_daily.whatsapp_reply + productivity_daily.sms_email + productivity_daily.followup) AS total');
        $this->db->select('productivity_daily.assignment AS assignment');
        $this->db->select('productivity_daily.target AS target');
        $this->db->select('productivity_daily.remark AS remark');
        $this->db->join('user', 'ON user.user_id = productivity_daily.agent');
        $this->db->group_by('productivity_daily.date');
        $this->db->order_by('productivity_daily.date', 'ASC');
        $this->db->where('user.is_active', 1);
        $this->db->where('productivity_daily.agent', $agent);
        $this->db->where('productivity_daily.date >=', $startPeriod);
        $this->db->where('productivity_daily.date <=', $endPeriod);
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getTotalProductivityDailyData($startPeriod, $endPeriod, $agent)
    {
        $this->db->select('AVG(icall) as ave_icall');
        $this->db->select('AVG(whatsapp_reply) as ave_whatsapp_reply');
        $this->db->select('AVG(sms_email) as ave_sms_email');
        $this->db->select('AVG(followup) as ave_followup');
        $this->db->select('(AVG(icall) + AVG(whatsapp_reply) + AVG(sms_email) + AVG(followup)) AS total');        
        $this->db->where('agent', $agent);
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('productivity_daily')->row_array();
    }

    public function getProductivityDailyByDateByAgent($startPeriod, $endPeriod, $agent)
    {
        $this->db->select('date');
        $this->db->select('target');
        $this->db->select('(icall + whatsapp_reply + sms_email + followup) as totalProductivity');
        $this->db->where('agent', $agent);
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('date');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getLatestDate()
    {
        $this->db->distinct();
        $this->db->select('date');
        $this->db->order_by('date', 'DESC');
        return $this->db->get('productivity_daily')->row_array();
    }

    public function setProductivityDailyTarget($data)
    {
        $this->db->update_batch('productivity_daily_target', $data, 'jobcode');
        return $this->db->affected_rows();
    }

    public function getProductivityDailyTransition($startPeriod, $endPeriod)
    {
        // STRING QUERY UNTUK PIVOT TABLE DINAMIS
        // Query #1
        $query1 = 'SET @sql = NULL';

        //Query #2
        $query2_1 = "SELECT GROUP_CONCAT(DISTINCT CONCAT('";
        $query2_2 = 'MAX(IF(date = "';
        $query2_3 = "', date,'";
        $query2_4 = '", icall + whatsapp_reply + sms_email + followup, 0)) AS `';
        $query2_5 = "', date,";
        $query2_6 = " '`')) INTO @sql FROM productivity_daily ";
        $query2_7 = " WHERE date >= '$startPeriod' AND date <= '$endPeriod'";
        $query2 = $query2_1 . $query2_2 . $query2_3 . $query2_4 . $query2_5 . $query2_6 . $query2_7;

        //Query #3
        $query3_1 = 'SET @sql = CONCAT(';
        $query3_2 = "'SELECT agent, ', @sql,'";
        $query3_4 = " FROM productivity_daily JOIN user ON productivity_daily.agent = user.user_id WHERE user.is_active = 1 ";
        $query3_6 = "GROUP BY productivity_daily.agent ORDER BY productivity_daily.agent')";
        $query3 = $query3_1 . $query3_2 . $query3_4 . $query3_6;

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

    public function insertProductivityDaily($data)
    {
        $this->db->insert_batch('productivity_daily', $data);
        return $this->db->affected_rows();
    }

    public function getProductivityDetailByPeriod($startPeriod, $endPeriod)
    {
        $this->db->where('period >=', $startPeriod);
        $this->db->where('period <=', $endPeriod);
        return $this->db->get('productivity')->result_array();
    }

    public function getTargetByJobcode($job)
    {
        $this->db->select('target');
        $this->db->where('jobcode', $job);
        return $this->db->get('productivity_daily_target')->row_array()['target'];
    }

    public function getTargetByName($agent)
    {
        $this->db->select('target');
        $this->db->where('user.user_id', $agent);
        $this->db->join('user', 'ON user.jobcode = productivity_daily_target.jobcode');
        return $this->db->get('productivity_daily_target')->row_array()['target'];
    }

    public function getSummbyProductivityDailyByAgent($startPeriod, $endPeriod)
    {
        $this->db->select('agent, assignment, AVG(icall) AS icall, AVG(whatsapp_reply) AS whatsapp_reply, AVG(sms_email) AS sms_email, AVG(followup) AS followup, (AVG(icall) + AVG(whatsapp_reply) + AVG(sms_email) + AVG(followup)) AS total, AVG(target) AS target, (AVG(icall) + AVG(whatsapp_reply) + AVG(sms_email) + AVG(followup)) / AVG(target) as ratio');
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('ratio', 'DESC');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getSummbyProductivityDailyByAgentNonzero($startPeriod, $endPeriod)
    {
        $this->db->select('
            productivity_daily.agent AS agent, 
            AVG(productivity_daily.icall) AS icall, 
            AVG(productivity_daily.whatsapp_reply) AS whatsapp_reply, 
            AVG(productivity_daily.sms_email) AS sms_email, 
            AVG(productivity_daily.followup) AS followup, 
            (AVG(productivity_daily.icall) + AVG(productivity_daily.whatsapp_reply) + AVG(productivity_daily.sms_email) + AVG(productivity_daily.followup)) AS total, 
            AVG(productivity_daily.target) AS target_daily, 
            (AVG(productivity_daily.icall) + AVG(productivity_daily.whatsapp_reply) + AVG(productivity_daily.sms_email) + AVG(productivity_daily.followup)) / AVG(productivity_daily.target) as ratio_daily, 
            (AVG(productivity_daily.icall) + AVG(productivity_daily.whatsapp_reply) + AVG(productivity_daily.sms_email) + AVG(productivity_daily.followup)) / productivity_daily_target.target as ratio_general');
        $this->db->join('user', 'ON user.user_id = productivity_daily.agent');
        $this->db->join('productivity_daily_target', 'ON productivity_daily_target.jobcode = user.jobcode');
        $this->db->where('(productivity_daily.icall + productivity_daily.whatsapp_reply + productivity_daily.sms_email + productivity_daily.followup) !=', 0);
        $this->db->where('productivity_daily.date >=', $startPeriod);
        $this->db->where('productivity_daily.date <=', $endPeriod);
        $this->db->where('user.is_active', 1);
        $this->db->group_by('productivity_daily.agent');
        $this->db->order_by('ratio_daily', 'DESC');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getDetailProductivityDailyByAgentNonzero($startPeriod, $endPeriod)
    {
        $this->db->select('
            productivity_daily.date AS date, 
            productivity_daily.agent AS agent, 
            productivity_daily.assignment AS assignment,
            productivity_daily.target AS target,
            productivity_daily.icall AS icall,
            productivity_daily.whatsapp_reply AS whatsapp_reply, 
            productivity_daily.sms_email AS sms_email, 
            productivity_daily.followup AS followup, 
            (productivity_daily.icall + productivity_daily.whatsapp_reply + productivity_daily.sms_email + productivity_daily.followup) AS total,
            productivity_daily.remark AS remark');
        $this->db->join('user', 'ON user.user_id = productivity_daily.agent');
        $this->db->join('productivity_daily_target', 'ON productivity_daily_target.jobcode = user.jobcode');
        // $this->db->where('(productivity_daily.icall + productivity_daily.whatsapp_reply + productivity_daily.sms_email + productivity_daily.followup) !=', 0);
        $this->db->where('productivity_daily.date >=', $startPeriod);
        $this->db->where('productivity_daily.date <=', $endPeriod);
        $this->db->order_by('date', 'ASC');
        $this->db->order_by('agent', 'ASC');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getSummbyProductivityDailyByAgentNonzeroMgt($startPeriod, $endPeriod)
    {
        $this->db->select('productivity_daily.agent AS agent, AVG(productivity_daily.icall) AS icall, AVG(productivity_daily.whatsapp_reply) AS whatsapp_reply, AVG(productivity_daily.sms_email) AS sms_email, AVG(productivity_daily.followup) AS followup, (AVG(productivity_daily.icall) + AVG(productivity_daily.whatsapp_reply) + AVG(productivity_daily.sms_email) + AVG(productivity_daily.followup)) AS total, productivity_daily_target.target AS target_general, (AVG(productivity_daily.icall) + AVG(productivity_daily.whatsapp_reply) + AVG(productivity_daily.sms_email) + AVG(productivity_daily.followup)) / productivity_daily_target.target as ratio_general');
        $this->db->join('user', 'ON user.user_id = productivity_daily.agent');
        $this->db->join('productivity_daily_target', 'ON productivity_daily_target.jobcode = user.jobcode');
        $this->db->where('(productivity_daily.icall + productivity_daily.whatsapp_reply + productivity_daily.sms_email + productivity_daily.followup) !=', 0);
        $this->db->where('productivity_daily.date >=', $startPeriod);
        $this->db->where('productivity_daily.date <=', $endPeriod);
        $this->db->where('user.is_active', 1);
        $this->db->group_by('productivity_daily.agent');
        $this->db->order_by('ratio_general', 'DESC');
        return $this->db->get('productivity_daily')->result_array();
    }
    
    public function setTargetZeroByPeriod($startPeriod, $endPeriod)
    {
        $query = "UPDATE productivity_daily SET target = 0 WHERE (icall + whatsapp_reply + sms_email + followup) = 0 AND date BETWEEN '$startPeriod' AND '$endPeriod'";
        $this->db->query($query);
        return $this->db->affected_rows();
    }

    public function updateProductivityInterval($data)
    {
        $this->db->where('agent', $data['agent']);
        $this->db->update('productivity_interval', $data);
        return $this->db->affected_rows();
    }

    public function addProductivityInterval($data)
    {
        $this->db->insert('productivity_interval', $data);
        return $this->db->affected_rows();
    }

    public function getProductivityOhByAgent($startPeriod, $endPeriod, $agent)
    {
        $this->db->like('agent', $agent);
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getFuschedule($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select('COUNT(agent) AS times');
        $this->db->select('SUM(followup) AS totalfu');
        $this->db->select('SUM(followup)/COUNT(agent) AS averagefu');
        $this->db->where('LOWER(assignment)', 'follow up');
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('SUM(followup)/COUNT(agent)', 'DESC');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getWaschedule($startPeriod, $endPeriod)
    {
        $this->db->select('agent');
        $this->db->select('COUNT(agent) AS times');
        $this->db->select('SUM(whatsapp_reply) AS totalwa');
        $this->db->select('SUM(whatsapp_reply)/COUNT(agent) AS averagewa');
        $this->db->where('LOWER(assignment)', 'whatsapp');
        $this->db->where('date >=', $startPeriod);
        $this->db->where('date <=', $endPeriod);
        $this->db->group_by('agent');
        $this->db->order_by('SUM(whatsapp_reply)/COUNT(agent)', 'DESC');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function getAllDailySchedule()
    {
        $this->db->where('assignment <>', 'reguler');
        return $this->db->get('productivity_daily')->result_array();
    }

    public function performDeleteDailySingle($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('productivity_daily');
        return $this->db->affected_rows();
    }

    public function performEditDailySingle($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('productivity_daily', $data);
        return $this->db->affected_rows();
    }

    public function deleteProductivityIntervalByAgent($agent)
    {
        $this->db->where('agent', $agent);
        $this->db->delete('productivity_interval');
        return $this->db->affected_rows();
    }
}
