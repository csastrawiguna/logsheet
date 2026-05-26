<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Elearning_model extends CI_Model
{
	public function getAllElearningCategory()
	{
		// $this->db->select('id');
		// $this->db->select('name');
		// $this->db->select('period');
		$this->db->order_by('startdate', 'DESC');
		return $this->db->get('elearning_category')->result_array();
	}

	public function getActiveElearningCategory()
	{
		$this->db->where('status', 1);
		$this->db->order_by('period', 'DESC');
		return $this->db->get('elearning_category')->result_array();
	}

	public function getLatestElearningId()
	{
		$this->db->order_by('id', 'DESC');
		return $this->db->get('elearning_category')->row_array();
	}

	public function getAllUserId()
	{
		$this->db->select('user_id');
		return $this->db->get('user')->result_array();
	}

	public function getActiveUserId()
	{
		$this->db->select('user_id');
		$this->db->where('is_active', 1);
		return $this->db->get('user')->result_array();
	}

	public function addCategory($data)
	{		
		$this->db->insert('elearning_category', $data);
		return $this->db->affected_rows();
	}

	public function deleteCategory($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('elearning_category');
		return $this->db->affected_rows();
	}

	public function getElearningById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('elearning_category')->row_array();
	}

	public function getLatestElearningCategory()
	{
		$query = "SELECT MAX(id) AS id FROM elearning_category WHERE status = '1'";
		return $this->db->query($query)->row_array();
	}

	public function editCategory($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('elearning_category', $data);
		return $this->db->affected_rows();
	}

	public function getAllQuestionaire()
	{
		$query = "SELECT elearning_questionaire.id AS qid, category, question,option_a, option_b, option_c, option_d, option_e, correct_key, elearning_questionaire.status AS status, elearning_questionaire.picture_link AS picture_link, elearning_questionaire.saved_by AS saved_by, elearning_questionaire.saved_at AS saved_at, elearning_questionaire.updated_by AS updated_by, elearning_questionaire.updated_at AS updated_at FROM elearning_questionaire ORDER BY elearning_questionaire.id DESC";
		return $this->db->query($query)->result_array();
	}

	public function getAllQuestionaireById($elearning_id, $limit)
	{
		$query = "SELECT elearning_questionaire.id AS qid,  elearning_questionaire.picture_link AS picture_link, category, question,option_a, option_b, option_c, option_d, option_e, correct_key, elearning_category.name AS elearning_name, elearning_category.test_duration AS test_duration FROM elearning_questionaire JOIN elearning_questionaire_assignment JOIN elearning_category ON elearning_questionaire.id = elearning_questionaire_assignment.questionaire_id AND elearning_questionaire_assignment.elearning_id = elearning_category.id WHERE elearning_id = '$elearning_id' ORDER BY rand() LIMIT $limit";
		return $this->db->query($query)->result_array();
	}

	public function getActiveQuestionaire()
	{
		$query = "SELECT elearning_questionaire.id AS qid, elearning_id, question,option_a, option_b, option_c, option_d, option_e, correct_key, elearning_questionaire.status, elearning_category.name AS elearning_name, elearning_category.test_duration AS test_duration FROM elearning_questionaire JOIN elearning_questionaire_assignment JOIN elearning_category ON elearning_questionaire_assignment.elearning_id = elearning_category.id AND elearning_questionaire_assignment.questionaire_id = elearning_questionaire.id AND elearning_category.status = 1";
		return $this->db->query($query)->result_array();
	}

	public function addQuestionaire($newdata)
	{
		$this->db->insert('elearning_questionaire', $newdata);
	}

	public function deleteQuestionaire($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('elearning_questionaire');
	}

	public function deleteQuestionaireByElearning($elearning_id)
	{
		$this->db->where('elearning_id', $elearning_id);
		$this->db->delete('elearning_questionaire_assignment');
		return $this->db->affected_rows();
	}
	public function getQuestionaireById($qid)
	{
		// $query = "SELECT 
		// 			elearning_questionaire.id AS id, elearning_questionaire.category AS category, elearning_questionaire.question AS question, elearning_questionaire.option_a AS option_a, elearning_questionaire.option_b AS option_b,elearning_questionaire.option_c AS option_c, elearning_questionaire.option_d AS option_d, elearning_questionaire.option_e AS option_e,elearning_questionaire.correct_key AS correct_key, elearning_questionaire.status AS status, elearning_category.period AS period, elearning_category.name AS name FROM elearning_questionaire JOIN elearning_category JOIN elearning_questionaire_assignment ON elearning_questionaire.id = elearning_questionaire_assignment.questionaire_id AND elearning_questionaire_assignment.elearning_id = elearning_category.id WHERE elearning_questionaire.id = '$qid'";
		// return $this->db->query($query)->row_array();
		$this->db->where('id', $qid);
		return $this->db->get('elearning_questionaire')->row_array();
	}

	public function editQuestionaireById($data)
	{
		$this->db->set('category', $data['category']);
		$this->db->set('period', $data['period']);
		$this->db->set('question', $data['question']);
		$this->db->set('option_a', $data['option_a']);
		$this->db->set('option_b', $data['option_b']);
		$this->db->set('option_c', $data['option_c']);
		$this->db->set('option_d', $data['option_d']);
		$this->db->set('option_e', $data['option_e']);
		$this->db->set('correct_key', $data['correct_key']);
		$this->db->set('status', $data['status']);
		$this->db->set('updated_by', $data['updated_by']);
		$this->db->set('updated_at', $data['updated_at']);
		$this->db->where('id', $data['id']);
		$this->db->update('elearning_questionaire');
		return $this->db->affected_rows();
	}

	public function getAllQuestionaireAssignedById($id)
	{
		$this->db->select('elearning_questionaire_assignment.id AS id');
		$this->db->select('elearning_questionaire.id AS qid');
		$this->db->select('elearning_category.period AS elearning_period');
		$this->db->select('elearning_category.name AS elearning_category');
		$this->db->select('elearning_questionaire.category AS questionaire_category');
		$this->db->select('elearning_questionaire.question AS questionaire');
		$this->db->join('elearning_category', 'elearning_category.id = elearning_questionaire_assignment.elearning_id');
		$this->db->join('elearning_questionaire', 'elearning_questionaire.id = elearning_questionaire_assignment.questionaire_id');
		$this->db->where('elearning_id', $id);
		return $this->db->get('elearning_questionaire_assignment')->result_array();
	}

	public function getAllQuestionaireUnassigned($id)
	{
		$query = "SELECT elearning_questionaire.id AS qid, 
		                 elearning_questionaire.category AS category, 
		                 elearning_questionaire.period AS period, 
		                 elearning_questionaire.question AS question
				FROM elearning_questionaire 
				WHERE elearning_questionaire.id NOT IN
				(SELECT elearning_questionaire_assignment.questionaire_id FROM elearning_questionaire_assignment JOIN elearning_questionaire
				ON elearning_questionaire.id = elearning_questionaire_assignment.questionaire_id WHERE elearning_questionaire_assignment.elearning_id = '$id') AND elearning_questionaire.status = 1
				ORDER BY elearning_questionaire.id DESC";

		return $this->db->query($query)->result_array();	
	}

	public function assignQuestionaire($data)
	{
		$this->db->insert_batch('elearning_questionaire_assignment', $data);
		return $this->db->affected_rows();
	}

	public function unassignQuestionaire($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('elearning_questionaire_assignment');
		return $this->db->affected_rows();
	}

	public function getAssignedUser()
	{
		$this->db->select('elearning_category.name as el_name, elearning_assignment.user_id, elearning_assignment.elearning_id, elearning_assignment.posttest_done, user.fullname, user.npk');
		$this->db->join('elearning_category', 'elearning_category.id = elearning_assignment.elearning_id');
		$this->db->join('user', 'user.user_id = elearning_assignment.user_id');
		$this->db->order_by('el_name', 'ASC');
		$this->db->order_by('user_id', 'ASC');
		return $this->db->get('elearning_assignment')->result_array();
	}

	public function getAssignedUserByEl($id)
	{
		$this->db->select('elearning_assignment.elearning_id AS elearning_id, elearning_category.name AS el_name, elearning_category.posttest_attemp AS posttest_attemp, elearning_assignment.user_id AS user_id, elearning_assignment.pretest_score AS pretest_score, elearning_assignment.pretest_done AS pretest_done, elearning_assignment.pretest_start AS pretest_start, elearning_assignment.pretest_date AS pretest_date, (elearning_assignment.pretest_date - elearning_assignment.pretest_start) AS pretest_duration, elearning_assignment.posttest_remedial AS remedial, elearning_assignment.posttest_score AS posttest_score, elearning_assignment.posttest_done AS posttest_done, elearning_assignment.posttest_start AS posttest_start, elearning_assignment.posttest_date AS posttest_date, elearning_assignment.is_pass AS is_pass, user.fullname AS fullname, department.department_name AS department, (elearning_assignment.posttest_date - elearning_assignment.posttest_start) AS exam_duration');
		$this->db->join('elearning_category', 'elearning_category.id = elearning_assignment.elearning_id');
		$this->db->join('user', 'user.user_id = elearning_assignment.user_id');
		$this->db->join('jobdesk', 'jobdesk.jobcode = user.jobcode');
		$this->db->join('department', 'department.dept_code = jobdesk.dept_code');
		$this->db->where('elearning_id', $id);
		$this->db->order_by('el_name', 'DESC');
		$this->db->order_by('user_id', 'ASC');
		return $this->db->get('elearning_assignment')->result_array();
	}

	public function getAllElearningAssigned($user_id)
	{
		$query = "SELECT elearning_category.id AS id, elearning_category.name AS name, elearning_category.period AS period, elearning_category.startdate AS startdate, elearning_category.enddate AS enddate, elearning_category.status AS is_active, elearning_category.passing_score AS passing_score, elearning_category.elearning_material AS material, elearning_category.pretest AS pretest, elearning_category.test_duration AS test_duration, elearning_assignment.pretest_done AS pretest_done, elearning_assignment.pretest_score AS pretest_score, elearning_assignment.posttest_done AS posttest_done, elearning_assignment.posttest_score AS posttest_score, elearning_assignment.pretest_date AS pretest_date, elearning_assignment.posttest_date AS posttest_date FROM elearning_category JOIN elearning_assignment ON elearning_category.id = elearning_assignment.elearning_id WHERE elearning_assignment.user_id = '$user_id' ORDER BY elearning_category.startdate DESC";

		return $this->db->query($query)->result_array();
	}

	public function getUnassignedUser($id)
	{
		$query = "SELECT user_id, npk, department_name AS department FROM user JOIN jobdesk JOIN department ON user.jobcode = jobdesk.jobcode AND jobdesk.dept_code = department.dept_code WHERE user.user_id NOT IN (SELECT user_id FROM elearning_assignment WHERE elearning_assignment.elearning_id = '$id') AND user.is_active = '1'";
		return $this->db->query($query)->result_array();
	}

	public function assignUser($data)
	{
		$this->db->insert_batch('elearning_assignment', $data);
		return $this->db->affected_rows();
	}

	public function unassignUser($data)
	{		
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('elearning_id', $data['elearning_id']);
		return $this->db->delete('elearning_assignment');
	}

	public function resetPosttest($data)
	{
		$this->db->set('score_remedial', $data['score_remedial']);
		$this->db->set('posttest_remedial', $data['posttest_remedial']);
		$this->db->set('posttest_done', 0);
		//$this->db->set('posttest_score', 0);
		$this->db->set('is_pass', 0);
		$this->db->set('posttest_start', NULL);
		$this->db->set('posttest_date', NULL);
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('elearning_id', $data['elearning_id']);
		$this->db->update('elearning_assignment');
		return $this->db->affected_rows();
	}

	public function resetPretest($data)
	{
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('elearning_id', $data['elearning_id']);
		$this->db->update('elearning_assignment', ['pretest_done' => 0, 'pretest_score' => 0, 'pretest_start' => NULL, 'pretest_date' => NULL]);
		return $this->db->affected_rows();
	}

	//FUNGSI OTOMATIS STATUS ASSIGNMENT AKTIF NONAKTIF
	public function autoSetElearningStatus($id, $status)
	{
		$this->db->set('status', $status);
		$this->db->where('id', $id);
		$this->db->update('elearning_category');
	}


	public function submitExam($data)
	{
		$this->db->insert_batch('elearning_examination', $data);
		return $this->db->affected_rows();
	}

	public function deleteExamResult($data, $prepost)
	{
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('elearning_id', $data['elearning_id']);
		$this->db->where('pre_post', $prepost);
		$this->db->delete('elearning_examination');
		return $this->db->affected_rows();
	}

	public function countQuestionaireById($user_id, $elearning_id, $prepost)
	{
		$query = "SELECT COUNT(user_id) AS qty FROM elearning_examination WHERE user_id = '$user_id' AND elearning_id = '$elearning_id' AND pre_post = '$prepost'";
		$getQty = $this->db->query($query)->row_array();
		return $getQty['qty'];
	}

	public function countCorrectAnswer($user_id, $elearning_id, $prepost)
	{
		$query = "SELECT COUNT(user_id) AS correct FROM elearning_examination WHERE user_id = '$user_id' AND elearning_id = '$elearning_id' AND is_correct = '1' AND pre_post = '$prepost'";
		$getCorrect = $this->db->query($query)->row_array();
		return $getCorrect['correct'];
	}

	public function submitScore($exam_date, $passing_score, $posttestStart, $out)
	{
		// $countQuestionaire = (int) $this->countQuestionaireById($user_id, $elearning_id, $prepost);
		// $correctAnswer = (int) $this->countCorrectAnswer($user_id, $elearning_id, $prepost);
		// $score = $correctAnswer * 100 / $countQuestionaire;
		if($out['prepost'] == 'posttest') {
			if ($out['score'] < (int) $passing_score) {
				$data = [
					'is_pass' => 0,
					'posttest_done' => 1,
					'posttest_score' => $out['score'],
					'posttest_start' => $posttestStart,
					'posttest_date' => $exam_date
				];
			} else {
				$data = [
					'is_pass' => 1,
					'posttest_done' => 1,
					'posttest_score' => $out['score'],
					'posttest_start' => $posttestStart,
					'posttest_date' => $exam_date
				];
			}		
		} else {
			if ($out['score'] < (int) $passing_score) {
				$data = [
					'pretest_done' => 1,
					'pretest_score' => $out['score'],
					'pretest_start' => $posttestStart,
					'pretest_date' => $exam_date
				];
			} else {
				$data = [
					'pretest_done' => 1,
					'pretest_score' => $out['score'],
					'pretest_start' => $posttestStart,
					'pretest_date' => $exam_date
				];
			}		
		} 

		$this->db->where('user_id', $out['user_id']);
		$this->db->where('elearning_id', $out['elearning_id']);
		$this->db->update('elearning_assignment', $data);
		return $this->db->affected_rows();
	}

	public function submitPretestScore($user_id, $elearning_id, $exam_date, $passing_score)
	{
		$countQuestionaire = (int) $this->countQuestionaireById($user_id, $elearning_id);
		$correctAnswer = (int) $this->countCorrectAnswer($user_id, $elearning_id);
		$score = $correctAnswer * 100 / $countQuestionaire;

		if ($score < (int) $passing_score) {
			$data = [
				'pretest_done' => 1,
				'pretest_score' => $score,
				'pretest_date' => $exam_date
			];
		} else {
			$data = [
				'pretest_done' => 1,
				'pretest_score' => $score,
				'pretest_date' => $exam_date
			];
		}
		$this->db->where('user_id', $user_id);
		$this->db->where('elearning_id', $elearning_id);
		$this->db->update('elearning_assignment', $data);
		return $this->db->affected_rows();
	}

	public function examQuestionaireById($user_id, $el_id, $pre_post)
	{
		$query = "SELECT elearning_questionaire.question AS question, elearning_examination.answer AS answer, elearning_examination.is_correct AS is_correct, elearning_examination.user_id AS user_id FROM elearning_examination JOIN elearning_questionaire ON elearning_questionaire.id = elearning_examination.questionaire_id WHERE elearning_examination.user_id = '$user_id' AND elearning_examination.elearning_id = '$el_id' AND elearning_examination.pre_post = '$pre_post' ";

		return $this->db->query($query)->result_array();
	}

	public function resultByCategoryByAgent($user_id, $elearning_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('elearning_id', $elearning_id);
		return $this->db->get('elearning_assignment')->row_array();
	}

	public function resultByCategoryByAgentDetail($user_id, $elearning_id, $pre_post)
	{
		$query = "SELECT elearning_examination.elearning_id, elearning_questionaire.question, elearning_examination.answer, elearning_examination.is_correct, elearning_assignment.posttest_score AS posttest_score, elearning_assignment.posttest_date AS posttest_date, elearning_assignment.pretest_score AS pretest_score, elearning_assignment.pretest_date AS pretest_date, (elearning_assignment.pretest_date - elearning_assignment.pretest_start) AS pretest_duration, (elearning_assignment.posttest_date - elearning_assignment.posttest_start) AS posttest_duration FROM elearning_examination JOIN elearning_questionaire JOIN elearning_assignment ON elearning_examination.elearning_id = elearning_assignment.elearning_id AND elearning_examination.user_id = elearning_assignment.user_id AND elearning_examination.questionaire_id = elearning_questionaire.id WHERE elearning_examination.user_id = '$user_id' AND elearning_examination.elearning_id = '$elearning_id' AND elearning_examination.pre_post = '$pre_post' ORDER BY elearning_examination.id ASC";

		return $this->db->query($query)->result_array();
	}

	public function checkExistingElearningExamination($elearning_id)
	{
		$this->db->where('elearning_id', $elearning_id);
		return $this->db->get('elearning_examination')->num_rows();
	}

	public function resultByCategoryByAgentSummary($user_id, $elearning_id, $pre_post)
	{
		$this->db->select($pre_post . '_score AS score');
		$this->db->select($pre_post . '_date AS date');
		$this->db->where('elearning_id', $elearning_id);
		$this->db->where('user_id', $user_id);
		return $this->db->get('elearning_assignment')->row_array();
	}

	public function summaryResult($startPeriod, $endPeriod)
	{
		//STRING QUERY UNTUK PIVOT TABLE DINAMIS
		//Query #1
		$query1 = 'SET @sql = NULL';

		//Query #2
		$query2_1 = "SELECT GROUP_CONCAT(DISTINCT CONCAT('";
		$query2_2 = 'MAX(IF(elearning_category.period = "';
		$query2_3 = "',elearning_category.period, '";
		$query2_4 = '", elearning_assignment.posttest_score, 0)) AS `';
		$query2_5 = "', elearning_category.period,";
		$query2_6 = "'`')) INTO @sql FROM elearning_category";
		$query2_7 = " WHERE elearning_category.name NOT LIKE '%General%' AND elearning_category.period <= '$endPeriod' AND elearning_category.period >= '$startPeriod'";
		$query2 = $query2_1 . $query2_2 . $query2_3 . $query2_4 . $query2_5 . $query2_6 . $query2_7;

		//Query #3
		$query3_1 = 'SET @sql =  CONCAT(';
		$query3_2 = "'SELECT user.user_id AS user_id, ', @sql,'";
		$query3_3 = ", elearning_assignment.posttest_score";
		$query3_4 = " FROM user JOIN elearning_assignment JOIN elearning_category ON elearning_assignment.elearning_id = elearning_category.id AND user.user_id = elearning_assignment.user_id WHERE user.is_active = 1 ";
		// $query3_5 = " WHERE elearning_category.period <= `$endPeriod` AND elearning_category.period >= `$startPeriod`";
		$query3_6 = "GROUP BY elearning_assignment.user_id ORDER BY user_id')";
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

	public function getLatestElearningResult()
	{
	}

	public function getPeriodSummary()
	{
		$query = 'SELECT DISTINCT elearning_category.period AS period FROM elearning_category JOIN elearning_assignment ON elearning_assignment.elearning_id = elearning_category.id';
		return $this->db->query($query)->result_array();
	}

	public function summaryByCategory($elearning_id)
	{
		$this->db->select('elearning_category.id AS elearning_id, elearning_category.name AS el_name, elearning_assignment.user_id AS user_id, elearning_assignment.posttest_score AS posttest_score, elearning_assignment.posttest_remedial AS remedial, elearning_assignment.pretest_score AS pretest_score, elearning_assignment.posttest_date AS posttest_date, elearning_assignment.pretest_date AS pretest_date, elearning_assignment.is_pass AS is_pass, user.npk AS npk, user.fullname AS fullname, department.department_name AS department, elearning_assignment.pretest_start AS pretest_start, elearning_assignment.posttest_start AS posttest_start, (elearning_assignment.pretest_date - elearning_assignment.pretest_start) AS pretest_duration, (elearning_assignment.posttest_date - elearning_assignment.posttest_start) AS exam_duration, elearning_assignment.posttest_remedial AS remedial, elearning_assignment.score_remedial AS score_remedial');
		$this->db->join('elearning_category', 'elearning_category.id = elearning_assignment.elearning_id');
		$this->db->join('user', 'user.user_id = elearning_assignment.user_id');
		$this->db->join('jobdesk', 'jobdesk.jobcode = user.jobcode');
		$this->db->join('department', 'department.dept_code = jobdesk.dept_code');
		$this->db->where('elearning_id', $elearning_id);
		$this->db->order_by('fullname', 'ASC');
		return $this->db->get('elearning_assignment')->result_array();
	}

	public function getSelectedElearningCategory($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('elearning_category')->row_array();
	}

	public function summaryByAgent($agent, $startPeriod, $endPeriod)
	{
		$query = "SELECT elearning_category.period AS period, elearning_assignment.posttest_score AS score FROM elearning_category JOIN elearning_assignment ON elearning_category.id = elearning_assignment.elearning_id WHERE elearning_assignment.user_id = '$agent' AND elearning_category.period >= '$startPeriod' AND elearning_category.period <= '$endPeriod' GROUP BY elearning_category.id";
		return $this->db->query($query)->result_array();
	}

	public function getEducationMaterialProduct()
	{
		$this->db->where('group_category', 'Product');
		return $this->db->get('education_material')->result_array();
	}

	public function getEducationMaterialNonproduct()
	{
		$this->db->where('group_category !=', 'Product');
		return $this->db->get('education_material')->result_array();
	}

	public function getQuestionsById($id)
	{
		$this->db->select('elearning_category.period AS period');
		$this->db->select('elearning_questionaire.category AS category');
		$this->db->select('elearning_questionaire.question AS question');
		$this->db->select('elearning_questionaire.option_a AS option_a');
		$this->db->select('elearning_questionaire.option_b AS option_b');
		$this->db->select('elearning_questionaire.option_c AS option_c');
		$this->db->select('elearning_questionaire.option_d AS option_d');
		$this->db->select('elearning_questionaire.option_e AS option_e');
		$this->db->select('elearning_questionaire.correct_key AS correct_key');
		$this->db->JOIN('elearning_questionaire', 'ON elearning_questionaire.id = elearning_questionaire_assignment.questionaire_id');
		$this->db->JOIN('elearning_category', 'ON elearning_category.id = elearning_questionaire_assignment.elearning_id');
		$this->db->where('elearning_questionaire_assignment.elearning_id', $id);
		return $this->db->get('elearning_questionaire_assignment')->result_array();
	}

	public function checkPretest($elearning_id, $user_id)
	{
		// $this->db->select('elearning_category.pretest AS pretest');
		// $this->dn->where('elearning_assignment.user_id', $user);
		// $this->dn->where('elearning_category.id', $id);
		// $this->db->where('elearning_category.pretest', 1);
		// $this->db->join('elearning_assignment', 'ON elearning_assignment.elearning_id = elearning_category.id');
		// return $this->db->get('elearning_category')->row_array();

		$query = "SELECT elearning_category.pretest AS pretest, elearning_assignment.pretest_done AS pretest_done FROM elearning_category JOIN elearning_assignment ON elearning_category.id = elearning_assignment. elearning_id WHERE elearning_category.id = '$elearning_id' AND elearning_assignment.user_id = '$user_id'";
		return $this->db->query($query)->row_array();
	}

	public function getAllSkapeFeedback($startPeriod, $endPeriod)
	{
		$this->db->where("DATE_FORMAT(saved_at, '%Y-%m-%d') >=", $startPeriod);
		$this->db->where("DATE_FORMAT(saved_at, '%Y-%m-%d') <=", $endPeriod);
		return $this->db->get('skape_feedback')->result_array();
	}

	public function addNewSkapeFeedback($data)
    {
        $this->db->insert('skape_feedback', $data);
        return $this->db->affected_rows();
    }

    public function deleteSkapeFeedback($id)
    {
    	$this->db->where('id', $id);
    	$this->db->delete('skape_feedback');
        return $this->db->affected_rows();
    }

    public function getFeedbackById($id)
    {
    	return $this->db->get_where('skape_feedback', ['id' => $id])->row_array();
    }

    public function performResponseFeedback($data)
    {
    	$this->db->where('id', $data['id']);
    	$this->db->update('skape_feedback', $data);
    	return $this->db->affected_rows();
    }
}