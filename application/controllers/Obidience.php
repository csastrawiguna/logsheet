<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// DomPDF
// require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
// use Dompdf\Dompdf;
// use Dompdf\Options;

defined('BASEPATH') or exit('No direct script access allowed');

class Obidience extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pdfgenerator');
        $this->load->model('Obidience_model', 'obidience');
        $this->load->model('Survey_model', 'survey');
        $this->load->model('Setting_model', 'setting');
        is_logged_in(); 
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Summary of Overtime Obidience';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        if(!$this->input->post('obidienceSummaryDateStart')) {
            $startPeriod = date("Y-m-01", strtotime("-1 months"));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = $this->input->post('obidienceSummaryDateStart');
            $endPeriod = $this->input->post('obidienceSummaryDateEnd');
        }

        $summaryObid = $this->obidience->getObidienceSummary($startPeriod, $endPeriod);
        $replacer = $this->obidience->getObidienceReplacerSummary($startPeriod, $endPeriod);

        $data['summaryObidienceByPeriod'] = [];
        foreach ($replacer as $row) {
            for ($i = 0; $i < count($summaryObid); $i++) {
                if ($row['agent'] == $summaryObid[$i]['agent']) {
                    $data['summaryObidienceByPeriod'][] = [
                        'agent' => $summaryObid[$i]['agent'],
                        'total_schedule' => $summaryObid[$i]['total_schedule'],
                        'replace_request' => $summaryObid[$i]['replace_request'],
                        'swap' => $summaryObid[$i]['swap'],
                        'incompliance' => $summaryObid[$i]['incompliance'],
                        'obidience_index' => $summaryObid[$i]['obidience_index'],
                        'replaced_to' => $row['replaced_for']
                    ];
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('obidience/index', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-obidience');
    }

    public function byagent()
    {
        $data['title'] = 'Obidience/Incompliance by Agent';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAgents'] = $this->obidience->getAllAgents();        

        if($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9 ){
            $data['title'] = "Overtime incompliance by agent";
            $agent = $this->obidience->getAllAgents()[0]['user_id'];            
        } else{            
            $data['title'] = "Overtime incompliance of " . $this->session->userdata('user_id');
            $agent = $this->session->userdata('user_id');
        }

        if(!$this->input->post('obidienceByAgentDateStart') && !$this->input->post('obidienceByAgentDateEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-1 months"));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = $this->input->post('obidienceByAgentDateStart');
            $endPeriod = $this->input->post('obidienceByAgentDateEnd');
            $agent = $this->input->post('obidienceByAgentSelectAgent');
        }

        $data['obidienceByAgent'] = $this->obidience->getObidienceByAgent($agent, $startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('obidience/byagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-obidience');
    }

    public function schedule()
    {
        $data['title'] = 'Overtime Schedule';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('obidience/schedule', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-obidience');
    }

    public function detail()
    {
        check_access();
        $data['title'] = 'Detail of Incompliance';
    	if(!$this->input->post('obidienceDetailDateStart') && !$this->input->post('obidienceDetailDateEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-1 months"));
            $endPeriod = date("Y-m-d", strtotime("+2 days"));
        } else {
            $startPeriod = $this->input->post('obidienceDetailDateStart');
            $endPeriod = $this->input->post('obidienceDetailDateEnd');
        }

    	$data['obidienceData'] = $this->obidience->getAllObidienceData($startPeriod, $endPeriod);
    	$data['allAgents'] = $this->obidience->getAllAgents();

    	$this->form_validation->set_rules('addIncomplianceDate', 'Incompliance date', 'required');
    	$this->form_validation->set_rules('addIncomplianceAgentScheduled', 'Agent scheduled', 'required|trim');
    	$this->form_validation->set_rules('addIncomplianceReplacedBy', 'Actual Agent', 'required|trim');
    	$this->form_validation->set_rules('addIncomplianceReason', 'Reason', 'required|trim');

    	if ( $this->form_validation->run() == false ) {
	        $this->load->view('templates/header', $data);
	        $this->load->view('templates/navbar', $data);
	        $this->load->view('templates/sidebar', $data);
	        $this->load->view('obidience/detail', $data);
	        $this->load->view('templates/footer');    		
    	} else {
    		$data = [
    			'date' => $this->input->post('addIncomplianceDate'),
    			'agent_scheduled' => $this->input->post('addIncomplianceAgentScheduled'),
    			'replaced_by' => $this->input->post('addIncomplianceReplacedBy'),
    			'reason' => $this->input->post('addIncomplianceReason'),
    			'remark' => $this->input->post('addIncomplianceRemark'),
    			'saved_by' => $this->session->userdata('user_id'),
    			'saved_at' => date("Y-m-d h:i:s")
    		];

    		if( $this->obidience->checkExistingData($this->input->post('addIncomplianceAgentScheduled'), $this->input->post('addIncomplianceDate')) > 0 ){
    			$this->session->set_flashdata('message', 'Data existing|error|Agent and date were existing!');
    			redirect('obidience/detail');
    		}

    		if ( $this->obidience->addSingleIncompliance($data) > 0 ) {
    			$this->session->set_flashdata('message', 'Incompliance of Obidience|success|New incompliance data successly added!');
    			redirect('obidience/detail');
    		} else {
                $this->session->set_flashdata('message', 'Failed to add Incompliance|error|There was form column leave empty!');
                redirect('obidience/detail');
            }
    	}
    }

    public function deleteObidience()
    {
    	if(!$this->input->post('complianceId')) {
            $complianceId = $this->uri->segment(3);
        } else {
            $complianceId = $this->input->post('complianceId');
        }

        if( $this->obidience->deleteById($complianceId) > 0 ) {
            $this->session->set_flashdata('message', "Succesly deleted!|info|An Incompliance data deleted!");
            redirect('obidience/exchange');
        } else {
            $this->session->set_flashdata('message', "Failed to delete!|error|Failed to deleted data'!");
            redirect('obidience/exchange');
        }

    }

    public function getSummary()
    {
        $startPeriod = $this->input->post('startPeriod');
        $endPeriod = $this->input->post('endPeriod');
        $result = $this->obidience->getObidienceSummary($startPeriod, $endPeriod);
        $json_data = [];
        foreach($result as $row){
            $json_data['labels'][] = $row['agent'];
            $json_data['incompliance'][] = number_format(($row['total_schedule'] - $row['incompliance']) / $row['total_schedule'] * 100, 1);
        }
        echo json_encode($json_data);
    }

     private function _toCutoffDate($date)
    {
        if(date("d", strtotime($date)) > 15 ) {
            $data['startPeriod'] = date("Y-m-16", strtotime($date));
            $data['endPeriod'] = date("Y-m-15", strtotime($date . "+16 days"));
        } else {
            $data['startPeriod'] = date("Y-m-16", strtotime($date . "-1 months"));
            $data['endPeriod'] = date("Y-m-15", strtotime($date));
        }
        return $data;
    }

    public function exchange()
    {
        check_access();
    	$data['title'] = 'Overtime Schedule Exchange List';
        $data['isdonesurvey'] = $this->survey->countNewSurveySkapeByUserid($this->session->userdata('user_id'));
        $data['surveyTreshold'] = $this->db->get('survey_setting')->row_array()['qty_min'];
        // $data['isdonesurvey'] = $this->db->get_where('survey_newskape_feedback', ['agent' => $this->session->userdata('user_id')])->num_rows();

    	// $this->form_validation->set_rules('scheduleExchangeDate', 'Overtime date', 'required');
    	$this->form_validation->set_rules('scheduleExchangeReason', 'Alasan tidak lembur', 'trim|required');
    	$this->form_validation->set_rules('scheduleExchangeReplacedBy', 'Replaced by', 'trim|required');

    	if($this->form_validation->run() == false) {
            if(!$this->input->post('obidienceDetailDateStart') && !$this->input->post('obidienceDetailDateEnd')) {
                $data['startPeriod'] = $this->_toCutoffDate(date("Y-m-d"))['startPeriod'];
                $data['endPeriod'] = $this->_toCutoffDate(date("Y-m-d"))['endPeriod'];
            } else {
                $data['startPeriod'] = $this->input->post('obidienceDetailDateStart');
                $data['endPeriod'] = $this->input->post('obidienceDetailDateEnd');
            }            

            // $data['allAgentsByPeriod'] = $this->obidience->getAllAgentsByPeriod($data['startPeriod'], $data['endPeriod']);
	    	$data['allSchedule'] = $this->obidience->getAllSchedule($data['startPeriod'], $data['endPeriod']);
	    	$data['allAgents'] = $this->obidience->getAllAgents();
            $planOvertime = $this->obidience->getAllAgentOvertimeDurationPlanByPeriod($data['startPeriod'], $data['endPeriod']);
            $actualOvertime = $this->obidience->getAllAgentOvertimeDurationByPeriod($data['startPeriod'], $data['endPeriod']);
            $data['overtimeDurationData'] = [];
            
            foreach($actualOvertime as $row) {
                for ($x = 0; $x < count($planOvertime); $x++) {
                    if ($planOvertime[$x]['agent'] == $row['agent']) {
                        $data['overtimeDurationData'][] = [
                            'agent' => $row['agent'],
                            'duration_plan' => $planOvertime[$x]['duration_plan'],
                            'duration_actual' => $row['duration_actual']
                        ];
                    }
                }
            }

	    	$this->load->view('templates/header', $data);
	        $this->load->view('templates/navbar', $data);
	        $this->load->view('templates/sidebar', $data);
	        $this->load->view('obidience/exchange', $data);
	        $this->load->view('templates/footer');    		
    	} else {
    		$this->_updateSchedule();
    	}
    }

    public function scheduleExchangeById()
    {
    	echo json_encode($this->obidience->getScheduleExchangeById($this->input->post('id')));
    }

    public function getScheduleExchangeByDateAgent()
    {
        // if ($this->obidience->getScheduleByDateAgent($this->input->post('agent'), $this->input->post('date')))
        echo json_encode($this->obidience->getScheduleByDateAgent($this->input->post('agent'), $this->input->post('date')));
    }

    private function _getScheduleByDateAgent($agent, $date)
    {
        return $this->obidience->getScheduleByDateAgent($agent, $date);
    }

    public function getAgentsByDate()
    {
        $date = $this->input->post('date');        
        $this->db->select('actual_overtime AS agent');
        $this->db->where('date', $date);
        echo json_encode($this->db->get('obidience')->result_array());
    }

    public function unscheduledAgentsByDate()
    {
        $date = $this->input->post('date');        
        echo json_encode($this->obidience->getUnscheduledAgentsByDate($date));
    }

    private function _updateSchedule()
    {
    	$data = [
    		'id' => $this->input->post('scheduleExchangeId'),
    		'date' => $this->input->post('scheduleExchangeDate'),
            'agent_scheduled' => $this->input->post('scheduleExchangeAgentScheduled'),
    		'replaced_by' => $this->input->post('scheduleExchangeReplacedBy'),
            'actual_overtime' => $this->input->post('scheduleExchangeReplacedBy'),
    		'actual_start' => $this->_getScheduleByDateAgent($this->input->post('scheduleExchangeAgentScheduled'), $this->input->post('scheduleExchangeDate'))['time_start'],
    		'actual_end' => $this->_getScheduleByDateAgent($this->input->post('scheduleExchangeAgentScheduled'), $this->input->post('scheduleExchangeDate'))['time_end'],
    		'actual_duration' => $this->_getScheduleByDateAgent($this->input->post('scheduleExchangeAgentScheduled'), $this->input->post('scheduleExchangeDate'))['duration'],
    		'reason' => $this->input->post('scheduleExchangeReason'),
    		'remark' => $this->input->post('scheduleExchangeRemark'),
            'replace_mark' => $this->input->post('replacemark'),
            'obidience_index' => $this->_getObidienceIndex($this->input->post('scheduleExchangeAgentScheduled'), $this->input->post('scheduleExchangeDate')) - 1,
    		'last_modified_by' => $this->session->userdata('user_id'),
    		'last_modified_at' => date("Y-m-d h:i:s")
    	];

        if (strpos($data['reason'], 'pribadi') || strpos($data['reason'], 'peribadi') || strpos($data['reason'], 'keluarga')) {
            $this->session->set_flashdata('message', "GAGAL!|error|TOLONG JELASKAN ALASAN PRIBADI/KELUARGA KENAPA?!!");
            redirect('obidience/exchange');
        }

    	// check user access
        $allowedExecuteExchange = [1, 5, 9];
    	if( $data['agent_scheduled'] != $this->session->userdata('user_id') && $data['actual_overtime'] != $this->session->userdata('user_id') ) {    		
    		if(in_array($this->session->userdata('role_access'), $allowedExecuteExchange)){
    			$this->_executeUpdate($data);
    		} else {
    			$this->session->set_flashdata('message', "GAGAL!|error|Tidak punya otoritas untuk mengganti!");
             	redirect('obidience/exchange');
    		}
    	} else {    		
    		$this->_executeUpdate($data);
    	}
    }

    private function _executeUpdate($data)
    {
        $startPeriod = $this->_toCutoffDate(date($data['date']))['startPeriod'];
        $endPeriod = $this->_toCutoffDate(date($data['date']))['endPeriod'];
        $employement = $this->db->get_where('user', ['user_id' => $data['replaced_by']])->row_array()['status'];

    	// variables for count overtime hour
        $max_hour = $this->db->get_where('overtime_setting', ['employement' => $employement])->row_array()['upper_limit'];
    	$proposedOvertimeHour = $this->obidience->countOvertimeHour($data['actual_start'],  $data['actual_end']);
    	$existingOvertimeHour = $this->obidience->checkOvertimeHourByAgent($data['replaced_by'], $startPeriod, $endPeriod);
        
    	// check agent will replaced existing or not
		if($this->obidience->checkExistingReplacedBy($data['replaced_by'], $data['date']) > 0) {
			$this->session->set_flashdata('message', "GAGAL!|error|Agent pengganti sudah ada di tanggal tersebut!");
        	redirect('obidience/exchange');
		} else {
			// check if overtime duration of agent who will replace over the limit
	    	if(($proposedOvertimeHour + $existingOvertimeHour) > $max_hour) {
	    		$this->session->set_flashdata('message', "GAGAL!|error|Jumlah jam lembur agent pengganti melebihi batas!");
	            redirect('obidience/exchange');
	    	} else {
	    		if ($this->obidience->performScheduleExchange($data) > 0 ) {
	    			$this->session->set_flashdata('message', "BERHASIL!|success|Berhasil update jadwal lembur!");
	            	redirect('obidience/exchange');
	    		}
	    	}
		}
    }

    public function swapSchedule()
    {
        // get start and end period by date input
        if(date("d", strtotime($this->input->post('scheduleSwapDateFrom'))) > 15 ) {
            $startPeriod = date("Y-m-16", strtotime($this->input->post('scheduleSwapDateFrom')));
            $endPeriod = date("Y-m-15", strtotime($this->input->post('scheduleSwapDateFrom') . "+16 days"));
        } else {
            $startPeriod = date("Y-m-16", strtotime($this->input->post('scheduleSwapDateFrom') . "-1 months"));
            $endPeriod = date("Y-m-15", strtotime($this->input->post('scheduleSwapDateFrom')));
        }

        $dataFrom = [            
            'id' => $this->input->post('scheduleSwapIdFrom'),
            'date' => $this->input->post('scheduleSwapDateFrom'),
            'agent_scheduled' => $this->input->post('scheduleSwapAgentFrom'),
            'actual_overtime' => $this->input->post('scheduleSwapAgentFrom'),
            'actual_start' => $this->input->post('scheduleSwapTimeStartFrom'),
            'actual_end' => $this->input->post('scheduleSwapTimeEndFrom'),
            'duration' => $this->input->post('scheduleSwapDurationFrom'),
            'reason' => $this->input->post('scheduleSwapReasonFrom'),
            'replace_mark' => $this->input->post('swapmark'),
            'obidience_index' => $this->_getObidienceIndex($this->input->post('scheduleSwapAgentFrom'), $this->input->post('scheduleSwapDateFrom')) - 1
        ];
        $dataTo = [
            'id' => $this->input->post('scheduleSwapIdTo'),
            'date' => $this->input->post('scheduleSwapDateTo'),
            'actual_overtime' => $this->input->post('scheduleSwapAgentTo'),
            'actual_start' => $this->input->post('scheduleSwapTimeStartTo'),
            'actual_end' => $this->input->post('scheduleSwapTimeEndTo'),
            'duration' => $this->input->post('scheduleSwapDurationTo'),
            'replace_mark' => $this->input->post('swapmark'),
            'obidience_index' => $this->_getObidienceIndex($this->input->post('scheduleSwapAgentTo'), $this->input->post('scheduleSwapDateTo')) - 1
        ];

        if (preg_match('(pribadi|peribadi|keluarga)', $dataFrom['reason']) === 1 ) {
            $this->session->set_flashdata('message', "GAGAL!|error|TOLONG JELASKAN ALASAN PRIBADI/KELUARGA KENAPA?!!");
            redirect('obidience/exchange');
        }
        // check maximum overtime hour
        $max_hour = $this->db->get_where('overtime_setting', ['employement' => $this->session->userdata('employement')])->row_array()['upper_limit'];
        
        // check existing overtime duration
        $existingOvertimeHour1 = $this->obidience->checkOvertimeHourByAgent($this->input->post('scheduleSwapAgentFrom'), $startPeriod, $endPeriod);
        $existingOvertimeHour2 = $this->obidience->checkOvertimeHourByAgent($this->input->post('scheduleSwapAgentTo'), $startPeriod, $endPeriod);

        // check duration if swap schedule - data 1 (From)
        $changedOvertimeHour1 = $existingOvertimeHour1 - $dataFrom['duration'] + $dataTo['duration'];
        $changedOvertimeHour2 = $existingOvertimeHour2 - $dataTo['duration'] + $dataFrom['duration'];
        
        if( $changedOvertimeHour1 > $max_hour || $changedOvertimeHour2 > $max_hour ) {
            $this->session->set_flashdata('message', "Gagal!|error|Jumlah jam lembur yang mengajukan atau pengganti melebihi batas");            
            redirect('obidience/exchange');            
        } else {
            $data = [
                [
                    'id' => $dataFrom['id'],
                    'date' => $dataFrom['date'],
                    'actual_overtime' => $dataTo['actual_overtime'],
                    'actual_start' => $dataFrom['actual_start'],
                    'actual_end' => $dataFrom['actual_end'],
                    'actual_duration' => $dataFrom['duration'],
                    'reason' => $dataFrom['reason'],
                    'replace_mark' => $dataFrom['replace_mark'],
                    'obidience_index' => $dataFrom['obidience_index'],
                    'last_modified_by' => $this->session->userdata('user_id'),
                    'last_modified_at' => date("Y-m-d h:i:s")
                ],
                [
                    'id' => $dataTo['id'],
                    'date' => $dataTo['date'],
                    'actual_overtime' => $dataFrom['agent_scheduled'],
                    'actual_start' => $dataTo['actual_start'],
                    'actual_end' => $dataTo['actual_end'],
                    'actual_duration' => $dataTo['duration'],
                    'reason' => $dataFrom['reason'],
                    'replace_mark' => $dataTo['replace_mark'],
                    'obidience_index' => $dataTo['obidience_index'],
                    'last_modified_by' => $this->session->userdata('user_id'),
                    'last_modified_at' => date("Y-m-d h:i:s")   
                ]
            ];

            if ($this->obidience->performShecduleSwap($data) > 0) {
                $this->session->set_flashdata('message', "Selamat...!|success|Berhasil tukar jadwal lembur");
                redirect('obidience/exchange');
            }
        }       
    }

    private function _getObidienceIndex($agent, $date)
    {
        return $this->db->get_where('obidience', ['agent_scheduled' => $agent, 'date' => $date])->row_array()['obidience_index'];
    }

    public function productivityfilling()
    {
        $data['title'] = 'Fill OT Productivity';
        if (!$this->input->post('productivityFillingStartdate')) {
            $startPeriod = $this->uri->segment(3);
            $endPeriod = $this->uri->segment(4);
        } else {
            $startPeriod = $this->input->post('productivityFillingStartdate');
            $endPeriod = $this->input->post('productivityFillingEnddate');
        }

        $data['overtimeData'] = $this->obidience->getActualOvertimeByPeriod($startPeriod, $endPeriod);
    
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('obidience/productivity-filling', $data);
        $this->load->view('templates/footer');
    }

    public function updateOvertimeProductivity()
    {
        $startPeriod = $this->input->post('prodStartPeriod');
        $endPeriod = $this->input->post('prodEndPeriod');

        $this->form_validation->set_rules('prodCall-0', 'Call', 'required|trim|greater_than[0]');
        $this->form_validation->set_rules('prodWhatsapp-0', 'Whatsapp', 'required|trim|greater_than[0]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "Error or Zero value|error|Cek & Hitung lagi produktivitas lembur!");
            redirect('obidience/productivityfilling/' . $startPeriod . '/' . $endPeriod);
        } else {
            $rows = $this->input->post('prodRows');
            $data = [];

            for ($i = 0; $i < $rows; $i++) {
                $data[] = [
                    'id' => $this->input->post('id-' . $i),
                    'prod_call' => $this->input->post('prodCall-' . $i),
                    'prod_whatsapp' => $this->input->post('prodWhatsapp-' . $i),
                    'prod_followup' => $this->input->post('prodFollowup-' . $i),
                    'prod_others' => $this->input->post('prodOthers-' . $i),
                    'prod_remark' => $this->input->post('prodRemark-' . $i)
                ];
            }
            $updateNums = $this->obidience->updateGroupOvertimeProductivity($data);
            if ( $updateNums> 0) {
                $this->session->set_flashdata('message', "Success|success|$updateNums . Overtime Productivity Updated!");
                redirect('obidience/exchange');
            }
        }
    }

    public function viewallschedule()
    {
        $data['title'] = 'View All Schedule';
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $data['schedules'] = $this->obidience->showAllSchedule($startPeriod, $endPeriod);
        $data['dates'] = $this->obidience->getDates($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('obidience/view-all', $data);
        $this->load->view('templates/footer');
    }

    public function viewonpdf()
    {
        $this->load->library('pdfgenerator');
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $data['schedules'] = $this->obidience->showAllSchedule($startPeriod, $endPeriod);
        $data['dates'] = $this->obidience->getDates($startPeriod, $endPeriod);

        $data['title'] = 'Jadwal OT ' . date("d M Y", strtotime($startPeriod)) . ' - ' . date("d M Y", strtotime($endPeriod));
        $file_pdf = $data['title'];
        $paper = 'A4';
        $orientation = "potrait";
        $header = $this->load->view('templates/header_pdfview', $data, true);
        $body = $this->load->view('obidience/view-all', $data, true);
        $html = $header . $body;
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function setting()
    {
    	check_access();
    	$data['title'] = 'Overtime Setting';

        $this->form_validation->set_rules('maximumOvertimeHour', 'Maximum overtime hour', 'integer');
        if($this->form_validation->run() == false ) {            
            $data['maximumOvertimeHour'] = $this->db->get('overtime_setting')->row_array()['upper_limit'];
        	$this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('obidience/setting', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->obidience->setMaximumOvertimeHour($this->input->post('maximumOvertimeHour')) > 0) {
                $this->session->set_flashdata('message', "Succesly Updated!|success|Maximum overtime hour updated");
                redirect('obidience/setting');
            }
        }
    }

    public function update()
    {
        $data = [
            'id' => $this->input->post('scheduleUpdateId'),
            'date' => $this->input->post('scheduleUpdateDate'),
            'agent_scheduled' => $this->input->post('scheduleUpdateAgentScheduled'),
            'time_start' => $this->input->post('scheduleUpdateTimeStart'),
            'time_end' => $this->input->post('scheduleUpdateTimeEnd'),
            'duration' => $this->input->post('scheduleUpdateDuration'),
            'replaced_by' => $this->input->post('scheduleUpdateActualOvertime'),
            'actual_overtime' => $this->input->post('scheduleUpdateActualOvertime'),
            'actual_start' => $this->input->post('scheduleUpdateActualStart'),
            'actual_end' => $this->input->post('scheduleUpdateActualEnd'),
            'actual_duration' => $this->input->post('scheduleUpdateActualDuration'),
            'reason' => $this->input->post('scheduleUpdateReason'),
            'remark' => $this->input->post('scheduleUpdateRemark'),
        ];

        if ($this->obidience->performScheduleUpdate($data) > 0) {
            $this->session->set_flashdata('message', "Succesly Updated!|success|Overtime schedule updated");
            redirect('obidience/exchange');
        }
    }

    public function addSingleSchedule()
    {
    	$data = [
            'date' => $this->input->post('addSingleScheduleDate'),
            'overtime_type' => $this->input->post('addSingleScheduleType'),
            'agent_scheduled' => $this->input->post('addSingleScheduleAgentScheduled'),        
            'actual_overtime' => $this->input->post('addSingleScheduleAgentScheduled'),
            'time_start' => $this->input->post('addSingleScheduleTimeStart'),
            'time_end' => $this->input->post('addSingleScheduleTimeEnd'),
            'duration' => $this->input->post('addSingleScheduleDuration'),
            'actual_start' => $this->input->post('addSingleScheduleTimeStart'),
            'actual_end' => $this->input->post('addSingleScheduleTimeEnd'),
            'actual_duration' => $this->input->post('addSingleScheduleDuration'),
            'reason' => $this->input->post('addSingleScheduleReason'),
            'remark' => $this->input->post('addSingleScheduleRemark'),
            'saved_by' => $this->session->userdata('user_id'),
            'saved_at' => date("Y-m-d H:i:s"),
        ];

        if ($this->obidience->checkExistingData($this->input->post('addSingleScheduleAgentScheduled'), $this->input->post('addSingleScheduleDate')) > 0 ) {
        	$this->session->set_flashdata('message', "Schedule existing!|error|Agent were on duty on certain date");
            redirect('obidience/exchange');
        } else {
	        if ($this->obidience->performAddSingleSchedule($data) > 0 ) {
	        	$this->session->set_flashdata('message', "Succesly Added!|success|1 overtime schedule added");
	            redirect('obidience/exchange');
	        }        	
        }

    }

    public function getOtDurationByTimeStartEnd()
    {
        $timeStart = $this->input->post('timeStart');
        $timeEnd = $this->input->post('timeEnd');
        echo json_encode($this->obidience->countOvertimeHour($timeStart, $timeEnd));
        // echo json_encode($timeSEnd);
    }

    // Upload Schedule from excel
    public function uploadScheduleExcel()
    {
        // include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        if (!empty($_FILES['scheduleUploadExcel']['name'])) {
            $extension = pathinfo($_FILES['scheduleUploadExcel']['name'], PATHINFO_EXTENSION);
            if ($extension == 'csv') {
                // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
            } elseif ($extension == 'xlsx') {
                // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            } else {
                // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
            }
            $reader->setReadDataOnly(true);

            // file path
            $spreadsheet = $reader->load($_FILES['scheduleUploadExcel']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet()->toArray(true, true, true, true, true, true, true, true, true, true, true);
            // var_dump($sheet[3]);die;

            // array Count
            $data = []; 
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($numrow > 1) {
                    array_push($data, array(
                        'date' => $row['A'],
                        'agent_scheduled' => $row['B'],
                        'replaced_by' => $this->_booleanToData($row['C']),
                        'actual_overtime' => $this->_booleanToData($row['B']),
                        'reason' => $this->_booleanToData($row['D']),
                        'remark' => $this->_booleanToData($row['E']),
                        'time_start' => $row['G'],
                        'actual_start' => $row['G'],
                        'time_end' => $row['H'],
                        'actual_end' => $row['H'],
                        'duration' => $row['I'],
                        'actual_duration' => $row['I'],
                        'overtime_type' => $row['J'],
                        'leader_in_charge' => $row['K'],
                        'saved_by' => $this->session->userdata('user_id'),
                        'saved_at' => date("Y-m-d h:i:s")
                    ));
                }
                $numrow++;
            }

            // var_dump($data);die;
            
            if ( $this->obidience->uploadOvertimeExcel($data) > 0) {
                //delete file from server
                unlink(realpath('files/' . $data_upload['file_name']));

                //upload success
                $this->session->set_flashdata('message', 'Success|success|Overtime schedule updaloaded...!');
                //redirect halaman
                redirect('obidience/exchange');
            } else {
                $this->session->set_flashdata('message', 'Failed|error|Failed to upload data!');
                //redirect halaman
                redirect('obidience/exchange');
            }
        }        
    }

    private function _excelCellToBoolean($data) 
    {
        if ($data == '') {
            return '';
        } else {
            return $data;
        }
    }

    private function _booleanToData($data) 
    {
        if (is_bool($data)) {
            return '';
        } else {
            return $data;
        }        
    }

    public function toExcelObidienceDetail()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Obidience Administrator')->setLastModifiedBy('Obidience Administrator')->setTitle("Detail of Daily Obidience")->setSubject("Obidience")->setDescription("Detail of Daily Obidience")->setKeywords("Daily Obidience");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font jadi bold

            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border right dengan garis tipis
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis 
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "CCC OBIDIENCE DETAIL"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        // $excel->getActiveSheet()->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->getActiveSheet()->mergeCells('A3:A4');
        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->getActiveSheet()->mergeCells('C3:H3');
        $excel->getActiveSheet()->mergeCells('I3:N3');
        $excel->getActiveSheet()->mergeCells('O3:S3');
        $excel->getActiveSheet()->mergeCells('T3:T4');
        $excel->getActiveSheet()->mergeCells('U3:U4');
        $excel->getActiveSheet()->mergeCells('V3:V4');
        $excel->getActiveSheet()->mergeCells('W3:W4');
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Date"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Scheduled OT"); 
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Actual OT");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Productivity"); 
        $excel->setActiveSheetIndex(0)->setCellValue('T3', "Reason Replaced"); 
        $excel->setActiveSheetIndex(0)->setCellValue('U3', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('V3', "Replace Mark"); 
        $excel->setActiveSheetIndex(0)->setCellValue('W3', "OT idx");
        $excel->setActiveSheetIndex(0)->setCellValue('C4', "UserID");
        $excel->setActiveSheetIndex(0)->setCellValue('D4', "NPK"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E4', "Fullname"); 
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Start");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Finish");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Duration"); 
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "UserID");
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "NPK"); 
        $excel->setActiveSheetIndex(0)->setCellValue('K4', "Fullname"); 
        $excel->setActiveSheetIndex(0)->setCellValue('L4', "Start");
        $excel->setActiveSheetIndex(0)->setCellValue('M4', "Finish");
        $excel->setActiveSheetIndex(0)->setCellValue('N4', "Duration");
        $excel->setActiveSheetIndex(0)->setCellValue('O4', "Call");
        $excel->setActiveSheetIndex(0)->setCellValue('P4', "WA");
        $excel->setActiveSheetIndex(0)->setCellValue('Q4', "FU");
        $excel->setActiveSheetIndex(0)->setCellValue('R4', "Othr");
        $excel->setActiveSheetIndex(0)->setCellValue('S4', "TTL");
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Q3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('R3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('S3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('T3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('U3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('V3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('W3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Q4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('R4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('S4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('T4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('U4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('V4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('W4')->applyFromArray($style_col);

        if (!$this->input->post('obidienceDetailDateStart')) {
            $startPeriod = $this->uri->segment(3);
            $endPeriod = $this->uri->segment(4);
        } else {
            $startPeriod = $this->input->post('obidienceDetailDateStart');
            $endPeriod = $this->input->post('obidienceDetailDateEnd');
        }
        $detailObidienceData = $this->obidience->toExcelDetailObidienceData($startPeriod, $endPeriod);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($detailObidienceData as $row) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, date("d-M-y", strtotime($row['date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $row['agent_scheduled']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row['npk_scheduled']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row['fullname_scheduled']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, date("H:i", strtotime($row['time_start'])));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, date("H:i", strtotime($row['time_end'])));
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $row['duration']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $row['actual_overtime']);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $row['npk']);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $row['fullname']);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, date("H:i", strtotime($row['actual_start'])));
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, date("H:i", strtotime($row['actual_end'])));
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $row['actual_duration']);
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $row['prod_call']);
            $excel->setActiveSheetIndex(0)->setCellValue('P' . $numrow, $row['prod_whatsapp']);
            $excel->setActiveSheetIndex(0)->setCellValue('Q' . $numrow, $row['prod_followup']);
            $excel->setActiveSheetIndex(0)->setCellValue('R' . $numrow, $row['prod_others']);
            $excel->setActiveSheetIndex(0)->setCellValue('S' . $numrow, $row['prod_total']);
            $excel->setActiveSheetIndex(0)->setCellValue('T' . $numrow, $row['reason']);
            $excel->setActiveSheetIndex(0)->setCellValue('U' . $numrow, $row['remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('V' . $numrow, $row['replace_mark']);
            $excel->setActiveSheetIndex(0)->setCellValue('W' . $numrow, $row['obidience_index']);

            // set date and time format 
            $excel->getActiveSheet()->getStyle('B' . $numrow)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('O' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('P' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Q' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('R' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('S' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('T' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('U' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('V' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('W' . $numrow)->applyFromArray($style_row);
            $no++;
            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('R')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('S')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('W')->setWidth(15);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of Overtime Obidience");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CCC Detail of Overtime Obidience.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }

    public function benefit()
    {
        $data['title'] = 'Overtime Salary Simulation';

        if(!$this->input->post('benefitSimulationDateStart')) {
            $data['startPeriod'] = $this->_toCutoffDate(date("Y-m-d"))['startPeriod'];
            $data['endPeriod'] = $this->_toCutoffDate(date("Y-m-d"))['endPeriod'];
        } else {
            $data['startPeriod'] = $this->input->post('benefitSimulationDateStart');
            $data['endPeriod'] = $this->input->post('benefitSimulationDateEnd');
        }

        // $data['actualOvertime'] = $this->obidience->getActualOvertimeByAgent($this->session->userdata('user_id'), $data['startPeriod'], $data['endPeriod']);
        // $data['actualOvertime'] = $this->obidience->getActualOvertimeByAgent('Okti', '2023-04-16', '2023-05-15');
        $result = $this->obidience->getActualOvertimeByAgent($this->session->userdata('user_id'), $data['startPeriod'], $data['endPeriod']);

        $data['actualOvertime'] = [];
        $data['subtotal'] = [];
        $totalDuration = 0;
        $totalCalculated = 0;
        $totalMeal = 0;
        $totalTransport = 0;

        foreach ($result as $row) {
            $data['actualOvertime'][] = [
                'date' => $row['date'],
                'actual_start' => $row['actual_start'],
                'actual_end' => $row['actual_end'],
                'actual_duration' => $row['actual_duration'],
                'calculated' => $this->_durationToCalculated($row['actual_duration']),
                'meal' => $this->obidience->getOvertimeAllowanceByAgent($this->session->userdata('user_id'), $row['overtime_type'])['meal'],
                'transport' => $this->obidience->getOvertimeAllowanceByAgent($this->session->userdata('user_id'), $row['overtime_type'])['transport']
            ];

            $totalDuration += $row['actual_duration'];
            $totalCalculated += $this->_durationToCalculated($row['actual_duration']);
            $totalMeal += $this->obidience->getOvertimeAllowanceByAgent($this->session->userdata('user_id'), $row['overtime_type'])['meal'];
            $totalTransport += $this->obidience->getOvertimeAllowanceByAgent($this->session->userdata('user_id'), $row['overtime_type'])['transport'];
        }

        $data['subtotal'] = [
            'totalDuration' => $totalDuration,
            'totalCalculated' => $totalCalculated,
            'totalMeal' => $totalMeal,
            'totalTransport' => $totalTransport
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('obidience/benefit');
        $this->load->view('templates/footer');
    }

    private function _durationToCalculated($num)
    {
        $result = 0;
        if ($num <= 7) {
            $result = 2 * $num;
        } else if ($num > 7 && $num <= 8) {
            $result = 14 + (($num - 7) * 3);
        } else {
            $result = 17 + (($num - 8) * 4);
        }
        return $result;
    }

    public function wagelist()
    {
        if ($this->session->userdata('role_access') != 9) {
            redirect('obidience/exchange');
        } else {
            $data['title'] = 'Wage List';
            $data['wages'] = $this->obidience->getUpdatedWage(date("Y"));
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('obidience/wage-list');
            $this->load->view('templates/footer');
        }
    }

    public function updateWage()
    {
        $agent = $this->session->userdata('user_id');
        $years = date("Y", strtotime($this->input->post('date')));
        $amount = $this->input->post('personalWage');

        //cek sudah ada gaji based on tahun
        if ($this->obidience->isWageExisting($agent, $years) > 0 ) {
            $this->obidience->updatePersonalWage($agent, $years, $amount);
        } else {
            $data = [
                'user_id' => $agent,
                'year' => $years,
                'wage' => $amount,
                'updated_at' => date("Y-m-d H:i:s")
            ];
            $this->obidience->insertPersonalWage($data);
        }

    }
}
