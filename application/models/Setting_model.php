<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function getAllTarget($fiscal)
    {
        $this->db->where('fiscal', $fiscal);
        $this->db->order_by('jobcode, id', 'ASC');
        return $this->db->get('kpi_target')->result_array();
    }

    public function addNewKpiTarget($data)
    {
        $this->db->insert_batch('kpi_target', $data);
        return $this->db->affected_rows();
    }

    public function getAllKpiMeasurement($fiscal)
    {
        $this->db->select('kpi_target.description AS kpi_description');
        $this->db->select('kpi_measurement.item AS kpi_item');
        $this->db->select('kpi_measurement.range_min AS range_min');
        $this->db->select('kpi_measurement.range_max AS range_max');
        $this->db->select('kpi_measurement.criteria AS criteria');        
        $this->db->select('kpi_measurement.jobcode AS jobcode');
        $this->db->select('kpi_measurement.id AS id');
        $this->db->join('kpi_target', 'kpi_target.fiscal = kpi_measurement.fiscal AND kpi_target.item = kpi_measurement.item AND kpi_target.jobcode = kpi_measurement.jobcode');
        $this->db->where('kpi_measurement.fiscal', $fiscal);
        $this->db->order_by('kpi_measurement.jobcode, kpi_measurement.id', 'ASC');
        return $this->db->get('kpi_measurement')->result_array();
    }

    public function getMaxLeavePerDay()
    {
        return $this->db->get('leave_setting')->row_array()['max_leave'];
    }

    public function setMaxLeavePerDay($data)
    {
        $this->db->set('max_leave', $data);
        $this->db->update('leave_setting');
        return $this->db->affected_rows();
    }

    public function setDashboardItemStatus($data)
    {                
        $this->db->where('id', $data['id']);
        $this->db->set('is_active', $data['is_active']);
        $this->db->update('dashboard_item');
        return $this->db->affected_rows();
    }

    public function performToggleSurveyDisplay($status)
    {
        $this->db->set('show_survey', $status);
        $this->db->update('survey_setting');
        return $this->db->affected_rows();
    }

    public function updateSurveyActiveness($data)
    {
        $this->db->set('show_survey', $data['show_survey']);
        $this->db->set('qty_min', $data['qty_min']);
        $this->db->update('survey_setting');
        return $this->db->affected_rows();
    }

    public function getAllFiscals()
    {
        $this->db->distinct();
        $this->db->select('fiscal');
        $this->db->order_by('fiscal', 'DESC');
        return $this->db->get('kpi_target')->result_array();
    }

    public function getLatestFiscals()
    {        
        $this->db->order_by('fiscal', 'DESC');
        return $this->db->get('kpi_target')->row_array();
    }

    public function getAllJobdesk()
    {
        $this->db->select('jobcode');
        $this->db->select('jobdesk');
        return $this->db->get('jobdesk')->result_array();
    }

    public function getBreakDate()
    {
        $this->db->limit('7');
        $this->db->order_by('date_end', 'DESC');
        return $this->db->get('break_date')->result_array();
    }

    public function getBreakSchedule($date)
    {
        $query = "SELECT break_schedule.id AS id, break_schedule.break_date_id AS break_date_id, break_schedule.break_group AS break_group, break_schedule.name AS name, LEFT(break_schedule.name, 1) AS initial, break_date.date_start AS date_start, break_date.date_end AS date_end FROM break_schedule JOIN break_date ON break_schedule.break_date_id = break_date.id WHERE '$date' BETWEEN break_date.date_start AND break_date.date_end ORDER BY break_schedule.name ASC";
        return $this->db->query($query)->result_array();
    }
    
    public function getBreakdateById($id)
    {
        return $this->db->get_where('break_date', ['id' => $id])->row_array();;
    }

    public function performUpdateBreakdate($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('break_date', $data);
        return $this->db->affected_rows();
    }

    public function getAllAgent()
    {
        $this->db->select('user_id AS user');
        $this->db->where('is_active', 1);
        $this->db->order_by('user_id', 'ASC');
        return $this->db->get('user')->result_array();
    }

    public function getLatestDate()
    {
        $this->db->select('date_end');
        $this->db->limit(1);
        $this->db->order_by('date_end', 'DESC');
        return $this->db->get('break_date')->row_array()['date_end'];
    }

    public function getUnallocatedBreak($date)
    {
        // $query = "SELECT user_id FROM user";
        $query = "SELECT user_id AS name, LEFT(user_id, 1) AS initial FROM user WHERE user.user_id NOT IN (SELECT user_id FROM user JOIN break_schedule JOIN break_date ON break_date.id = break_schedule.break_date_id WHERE user.user_id = break_schedule.name AND '$date' BETWEEN break_date.date_start AND break_date.date_end) AND user.is_active = 1  ORDER BY name ASC";
        return $this->db->query($query)->result_array();
    }

    public function insertNewBreakDate($data)
    {
        $this->db->insert('break_date', $data);
        return $this->db->insert_id();
    }

    public function copyBreakSchedule($new, $latest)
    {
        $query = "INSERT INTO break_schedule (break_date_id, break_group, name) SELECT '$new', break_group, name FROM break_schedule WHERE break_date_id = '$latest'";
        $this->db->query($query);
        return $this->db->affected_rows();
    }

    public function deleteScheduleGroup($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('break_date');
        return $this->db->affected_rows();
    }

    public function deleteScheduleDetail($breakDateId)
    {
        $this->db->where('break_date_id', $breakDateId);
        $this->db->delete('break_schedule');
        return $this->db->affected_rows();
    }

    public function insertBatchScheduleDetail($data)
    {
        $this->db->insert_batch('break_schedule', $data);
        return $this->db->affected_rows();
    }

    public function performDeleteBreackScheduleById($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('break_schedule');
        return $this->db->affected_rows();
    }

    public function performAddNewBreakSchedule($data)
    {
        $this->db->insert('break_schedule', $data);
        return $this->db->affected_rows();
    }

    public function performUpdateBreakSchedule($data)
    {
        $this->db->update_batch('break_schedule', $data, 'id');
        return $this->db->affected_rows();
    }    

    public function getBreaktime()
    {
        return $this->db->get('break_time')->result_array();
    }

    public function getAllGeneralInfo()
    {
        $this->db->order_by('status', 'ASC');
        $this->db->order_by('saved_at', 'DESC');
        $this->db->where('status !=', 3);
        return $this->db->get('general_info')->result_array();
    }

    public function getAllRawGeneralInfo()
    {
        $this->db->order_by('status', 'ASC');
        $this->db->order_by('saved_at', 'DESC');
        return $this->db->get('general_info')->result_array();
    }

    public function insertSingleGeneralInfo($data)
    {
        $this->db->insert('general_info', $data);
        return $this->db->affected_rows();
    }

    public function getGeneralInfoById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('general_info')->row_array();
    }

    public function editSingleGeneralInfo($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->set('detail_info', $data['detail_info']);
        $this->db->set('status', $data['status']);
        $this->db->set('updated_by', $data['updated_by']);
        $this->db->set('updated_at', $data['updated_at']);
        $this->db->update('general_info');
        return $this->db->affected_rows();
    }

    public function deleteSingleGeneralInfo($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('general_info');
        return $this->db->affected_rows();
    }

    public function getAllWorkingCalendar()
    {
        // $this->db->order_by('working_month', 'DESC');
        // eturn $this->db->get('working_calendar')->result_array();
        $query = "SELECT * FROM working_calendar ORDER BY DATE_FORMAT(working_month, '%Y') DESC, DATE_FORMAT(working_month, '%m') ASC";
        return $this->db->query($query)->result_array();
    }

    public function addSingleWorkingMonth($data)
    {
        $this->db->insert('working_calendar', $data);
        return $this->db->affected_rows();
    }

    public function getSingleWorkingMonth($month)
    {
        $this->db->where('working_month', $month);
        return $this->db->get('working_calendar')->row_array();
    }

    public function deleteWorkingMonth($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('working_calendar');
        return $this->db->affected_rows();
    }

    public function getSingleWorkingMonthById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('working_calendar')->row_array();
    }

    public function performEditWorkingMonth($updateData)
    {
        $this->db->where('id', $updateData['id']);
        $this->db->where('working_month', $updateData['working_month']);
        $this->db->set('working_day', $updateData['working_day']);
        $this->db->update('working_calendar');
        return $this->db->affected_rows();
    }

    // VOTE
    public function getAllVotes()
    {
        return $this->db->get('vote_list')->result_array();
    }

    public function addNewGeneralVote($data)
    {
        $this->db->insert('vote_list', $data);
        return $this->db->affected_rows();
    }

    public function toggleVoteStatus($id, $stts)
    {
        $this->db->where('id', $id);
        $this->db->set('is_active', $stts);
        $this->db->update('vote_list');
        return $this->db->affected_rows();
    }

    public function getVoteList($status = 1, $agent)
    {
        $query = "SELECT 
                    vote_list.id AS id,
                    vote_list.vote_name AS vote_name,
                    vote_list.vote_desc AS vote_desc,
                    vote_detail.vote_to AS vote_to, 
                    vote_detail.voted_by AS voted_by, 
                    vote_detail.voted_at AS voted_at
                    FROM vote_list
                    LEFT JOIN vote_detail ON vote_list.id = vote_detail.vote_id 
                    AND vote_detail.voted_by = '$agent'
                    WHERE vote_list.is_active = 1  
                    GROUP BY vote_list.id";
        return $this->db->query($query)->result_array();
    }

    public function getVoteListByIdByAgent($id, $agent)
    {
        $query = "SELECT 
                    vote_list.id AS id,
                    vote_list.vote_name AS vote_name,
                    vote_list.vote_desc AS vote_desc,
                    vote_list.data_list AS data_list,
                    vote_detail.vote_to AS vote_to, 
                    vote_detail.voted_by AS voted_by, 
                    vote_detail.voted_at AS voted_at
                    FROM vote_list
                    LEFT JOIN vote_detail ON vote_list.id = vote_detail.vote_id 
                    AND vote_detail.voted_by = '$agent'
                    WHERE vote_list.id = '$id'  
                    GROUP BY vote_list.id";
        return $this->db->query($query)->row_array();
    }

    public function submitNewVote($data)
    {
        $this->db->insert('vote_detail', $data);
        return $this->db->affected_rows();
    }

    public function submitRevisedVote($data)
    {
        $this->db->where('vote_id', $data['vote_id']);
        $this->db->where('voted_by', $data['voted_by']);
        $this->db->update('vote_detail', $data);
        return $this->db->affected_rows();
    }

    public function getVoteSummaryById($id)
    {
        $this->db->select('COUNT(vote_to) AS qty');
        $this->db->select('vote_to');
        $this->db->where('vote_id', $id);
        $this->db->group_by('vote_to');
        $this->db->order_by('COUNT(vote_to)', 'DESC');
        return $this->db->get('vote_detail')->result_array();
    }

    public function getVoteResultDetailById($id)
    {
        $this->db->select('vote_to');
        $this->db->select('voted_by');
        $this->db->select('voted_at');
        $this->db->where('vote_id', $id);
        $this->db->order_by('vote_to', 'ASC');
        $this->db->order_by('voted_at', 'DESC');
        return $this->db->get('vote_detail')->result_array();
    }

    public function editGeneralVote($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('vote_list', $data);
        return $this->db->affected_rows();
    }

}
