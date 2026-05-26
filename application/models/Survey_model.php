<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survey_model extends CI_Model
{
	public function getFeedbackByAgent($userid, $access, $allowed)
	{
		if (in_array($access, $allowed)) {
			return $this->db->get('survey_newskape_feedback')->result_array();
		} else {
			$this->db->where('agent', $userid);
			return $this->db->get('survey_newskape_feedback')->result_array();
		}
	}

	public function getFeedbackById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('survey_newskape_feedback')->row_array();
	}

	public function insertFeedback($data)
	{
		$this->db->insert('survey_newskape_feedback', $data);
		return $this->db->affected_rows();	
	}

	public function updateFeedback($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->set('category', $data['category']);
		$this->db->set('detail', $data['detail']);
		$this->db->set('updated_by', $data['updated_by']);
		$this->db->set('updated_at', $data['updated_at']);
		$this->db->update('survey_newskape_feedback');
		return $this->db->affected_rows();	
	}

	public function deleteFeedback($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->delete('survey_newskape_feedback');
		return $this->db->affected_rows();	
	}

	public function toExcelDetailFeedback()
	{
		return $this->db->get('survey_newskape_feedback')->row_array();
	}

	public function countSurveySkapeByUserid($userid)
	{
		$this->db->where('agent', $userid);
		return $this->db->get('survey_newskape_feedback')->num_rows();
	}

	public function countNewSurveySkapeByUserid($userid)
	{
		$this->db->where('agent', $userid);
		$this->db->where('saved_at >=', '2022-06-03');
		return $this->db->get('survey_newskape_feedback')->num_rows();
	}
}
