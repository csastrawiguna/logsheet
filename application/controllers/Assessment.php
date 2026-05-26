<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assessment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Assessment_model', 'assessment');
        $this->load->model('Productivity_model', 'productivity');
        $this->load->model('Csindex_model', 'csindex');
        $this->load->model('Elearning_model', 'elearning');
        $this->load->model('Absence_model', 'absence');
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Summary Result';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        if (!$this->input->post('selectSummaryAssessmentStartPeriod') || !$this->input->post('selectSummaryAssessmentEndPeriod')) {
            if( (int)date("m") > 4 && (int)date("m") < 10 ) {
                $startPeriod = date("Y-04-01");
                $endPeriod = date("Y-09-01");
            } else if ((int)date("m") < 4) {
                $startPeriod = date("Y-10-01", strtotime("-1 year"));
                $endPeriod = date("Y-03-01");
            } else {
            	 $startPeriod = date("Y-10-01");
                $endPeriod = date("Y-03-01", strtotime("+1 year"));
            }         
        } else {
            $startPeriod = $this->input->post('selectSummaryAssessmentStartPeriod');
            $endPeriod = $this->input->post('selectSummaryAssessmentEndPeriod');            
        }
        $fiscal = $this->_dateToFiscal($startPeriod, $endPeriod);

        $data['fiscal'] = $fiscal;
        $jobcode = 'cs-ccc-cc10';
        $data['target'] = $this->assessment->getTargetByJobcode($jobcode, $fiscal);
        $data['totalWeight'] = $this->assessment->getTotalTarget($jobcode, $fiscal);
        $data['targetDetail'] = [];
        foreach($data['target'] as $key ) {
            $data['targetDetail'][$key['item']] = $key['weight'];
        }

        $data['result'] = $this->assessment->getAverageByPeriod($startPeriod, $endPeriod);
        $data['csindexSurveyQtyByAgent'] = $this->assessment->getCsindexSurveyQtyByAgent($jobcode, $startPeriod, $endPeriod);
        $data['measure'] = $this->db->get_where('kpi_measurement', ['jobcode' => $jobcode])->result_array();
        $data['jobcode'] = $jobcode;
        $data['kpiResult'] = [];     
        $data['agents'] = $this->assessment->getAllAgentsByPeriod($startPeriod, $endPeriod);
          
        $data['forKpiMeasurement'] = [];
        foreach( $data['result'] as $row ) {
            $data['forKpiMeasurement'][] = [
                'agent' => $row['agent'],
                //'jobcode' => $this->_getJobcode($row['agent']),
                'fiscal' => $this->_dateToFiscal($row['period'], $row['period']),
                'productivity' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'productivity', $row['productivity']),
                'csindex' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'csindex', $row['csindex'] * 100),
                'elearning' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'elearning', $row['elearning']),
                'absence' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'absence', $row['absence'] * 100),
                'knowledge_sharing' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'knowledge_sharing', $row['knowledge_sharing']),
                'skape_draft' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'skape_draft', $row['skape_draft']),
                'part_code' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'part_code', $row['part_code']),                
                'part_callback' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'part_callback', $row['part_callback']),
                'complaint_forward' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'complaint_forward', $row['complaint_forward']),
                'complaint_completion' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'complaint_completion', $row['complaint_completion']),
                'complaint_report' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'complaint_report', $row['complaint_report']),
                'email_reply' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'email_reply', $row['email_reply']),
                'promo_inquiry' => $this->_kpiMeasurement($this->_dateToFiscal($row['period'], $row['period']), $this->_getJobcode($row['agent']), 'promo_inquiry', $row['promo_inquiry']),
            ];
        }
      
        $data['kpiResultSummary'] = [];

        foreach ($data['agents'] as $row) {
            $productivity = 0;
            $csindex = 0;
            $elearning = 0;
            $absence = 0;
            $skape_draft = 0;
            $part_code = 0;
            $part_callback = 0;
            $complaint_forward = 0;
            $complaint_completion = 0;
            $complaint_report = 0;
            $knowledge_sharing = 0;
            $email_reply = 0;
            $promo_inquiry = 0;
            $n = 0;
            foreach ($data['forKpiMeasurement'] as $col) {
                if($col['agent'] == $row['agent']) {
                    $productivity += $col['productivity'];
                    $csindex += $col['csindex'];
                    $absence += $col['absence'];
                    $elearning += $col['elearning'];
                    $skape_draft += $col['skape_draft'];
                    $knowledge_sharing += $col['knowledge_sharing'];
                    $part_code += $col['part_code'];
                    $part_callback += $col['part_callback'];
                    $complaint_report += $col['complaint_report'];
                    $complaint_completion += $col['complaint_completion'];
                    $complaint_forward += $col['complaint_forward'];
                    $email_reply += $col['email_reply'];
                    $promo_inquiry += $col['promo_inquiry'];
                    ++$n;
                }
            }            
            if ($n > 0) {
                $data['kpiResultSummary'][] = [
                    'agent' => $row['agent'],
                    'fullname' => $row['fullname'],
                    'npk' => $row['npk'],
                    'status' => $row['status'],
                    'fiscal' => $this->_dateToFiscal($row['period'], $row['period']),
                    'jobcode' => $this->_getJobcode($row['agent']),
                    'productivity' => $productivity / $n,
                    'csindex' => $csindex / $n,
                    'absence' => $absence / $n,
                    'elearning' => $elearning / $n,
                    'skape_draft' => $skape_draft / $n,
                    'knowledge_sharing' => $knowledge_sharing / $n,
                    'part_code' => $part_code / $n,
                    'part_callback' => $part_callback / $n,
                    'complaint_forward' => $complaint_forward / $n,
                    'complaint_completion' => $complaint_completion / $n,
                    'complaint_report' => $complaint_report / $n,
                    'email_reply' => $email_reply / $n,
                    'promo_inquiry' => $promo_inquiry / $n,
                    'kpi' => ($productivity / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'productivity') / 100 + 
                        ($csindex / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'csindex') / 100 + 
                        ($absence / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'absence') / 100 + 
                        ($elearning / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'elearning') / 100 + 
                        ($skape_draft / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'skape_draft') / 100 + 
                        ($knowledge_sharing / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'knowledge_sharing') / 100 + 
                        ($part_code / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'part_code') / 100 + 
                        ($part_callback / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'part_callback') / 100 + 
                        ($complaint_forward / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_forward') / 100 + 
                        ($complaint_completion / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_completion') / 100 + 
                        ($complaint_report / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_report') / 100 +
                        ($email_reply / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'email_reply') / 100 +
                        ($promo_inquiry / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'promo_inquiry') / 100,
                    'kpi_result' => $this->_kpiAchievementToAlphabet(($productivity / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'productivity') / 100 + 
                        ($csindex / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'csindex') / 100 + 
                        ($absence / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'absence') / 100 + 
                        ($elearning / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'elearning') / 100 + 
                        ($skape_draft / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'skape_draft') / 100 + 
                        ($knowledge_sharing / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'knowledge_sharing') / 100 + 
                        ($part_code / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'part_code') / 100 + 
                        ($part_callback / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'part_callback') / 100 + 
                        ($complaint_forward / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_forward') / 100 + 
                        ($complaint_completion / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_completion') / 100 + 
                        ($complaint_report / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'complaint_report') / 100 + 
                        ($email_reply / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'email_reply') / 100 +
                        ($promo_inquiry / $n) * $this->_getKpiWeight($this->_getJobcode($row['agent']), $this->_dateToFiscal($row['period'], $row['period']), 'promo_inquiry') / 100)
                ];
            }
        }

        $keys = array_column($data['kpiResultSummary'], 'kpi');
        $data['coba'] = $keys;
        array_multisort($keys, SORT_DESC, $data['kpiResultSummary']);
        // $data['assessmentSummary'] = $this->assessment->getSummaryByPeriod($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/index', $data);
        $this->load->view('templates/footer');
    }

    public function byagent()
    {
        $data['title'] = 'Performance Assessment By Agent';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();        
        $data['allAgent'] = $this->assessment->getAllAgents();       

        if (!$this->input->post('selectAssessmentByAgentStartPeriod') || !$this->input->post('selectAssessmentByAgentEndPeriod') || !$this->input->post('selectAssessmentByAgentSelectAgent')) {            
            if( (int)date("m") > 4 && (int)date("m") < 10 ) {
                $startPeriod = date("Y-04-01");
                $endPeriod = date("Y-09-01");
            } else if ((int)date("m") < 4) {
                $startPeriod = date("Y-10-01", strtotime("-1 year"));
                $endPeriod = date("Y-03-01");
            } else {
                 $startPeriod = date("Y-10-01");
                $endPeriod = date("Y-03-01", strtotime("+1 year"));
            }      
            // $fiscal = $this->assessment->getLatestFiscal();
            $agent = $this->session->userdata('user_id');
        } else {
            $startPeriod = $this->input->post('selectAssessmentByAgentStartPeriod');
            $endPeriod = $this->input->post('selectAssessmentByAgentEndPeriod');
            $agent = $this->input->post('selectAssessmentByAgentSelectAgent');
        }
        
        $fiscal = $this->_dateToFiscal($startPeriod, $endPeriod);
        $jobcode = $this->_getJobcode($agent);
        
        // $data['fiscal'] = $fiscal;
        $data['target'] = $this->assessment->getTargetByJobcode($jobcode, $fiscal);
        $data['totalWeight'] = $this->assessment->getTotalTarget($jobcode, $fiscal);
        $data['targetDetail'] = [];
        foreach($data['target'] as $key ) {
            $data['targetDetail'][$key['item']] = $key['weight'];
        }

        $kpiTarget = $this->db->get_where('kpi_target', ['jobcode' => $agent])->result_array();

        foreach ($kpiTarget as $key) {
            $data['kpiTargetItem'][$key['item']] = $key['target'];
        }
        // var_dump($data['kpiTargetItem']); die;

        $data['result'] = $this->assessment->getKpiItems($agent, $startPeriod, $endPeriod);
        //$data['result'] = $this->assessment->getKpiItems('Aliahmad', '2022-03-01', '2022-05-01');
        $data['csindexSurveyQtyByAgent'] = $this->assessment->getCsindexSurveyQtyByAgent($agent, $startPeriod, $endPeriod);
        $data['measure'] = $this->db->get_where('kpi_measurement', ['jobcode' => $jobcode])->result_array();
        $data['jobcode'] = $jobcode;
        $data['kpiResult'] = [];

        switch ($jobcode) {
            case 'cs-ccc-cc10':
            case 'cs-ccc-cc11':
            case 'cs-ccc-cc12':
                foreach ($data['result'] as $row) {
                    $data['kpiResult'][] = [
                        'period' => $row['period'],
                        'productivity' => $this->_kpiMeasurement($fiscal, $jobcode, 'productivity', $row['productivity']),
                        'csindex' => $this->_kpiMeasurement($fiscal, $jobcode, 'csindex', $row['csindex'] * 100),
                        'absence' => $this->_kpiMeasurement($fiscal, $jobcode, 'absence', $row['absence'] * 100),
                        'elearning' => $this->_kpiMeasurement($fiscal, $jobcode, 'elearning', $row['elearning'])
                    ];
                }
            break;

            case 'cs-ccc-cc20':
                foreach ($data['result'] as $row) {
                    $data['kpiResult'][] = [
                        'period' => $row['period'],
                        'productivity' => $this->_kpiMeasurement($fiscal, $jobcode, 'productivity', $row['productivity']),
                        'csindex' => $this->_kpiMeasurement($fiscal, $jobcode, 'csindex', $row['csindex'] * 100),
                        'absence' => $this->_kpiMeasurement($fiscal, $jobcode, 'absence', $row['absence'] * 100),
                        'skape_draft' => $this->_kpiMeasurement($fiscal, $jobcode, 'skape_draft', $row['skape_draft']),
                        'knowledge_sharing' => $this->_kpiMeasurement($fiscal, $jobcode, 'knowledge_sharing', $row['knowledge_sharing'])
                    ];
                }            
            break;

            case 'cs-ccc-cc30':
                foreach ($data['result'] as $row) {
                    $data['kpiResult'][] = [
                        'period' => $row['period'],
                        'productivity' => $this->_kpiMeasurement($fiscal, $jobcode, 'productivity', $row['productivity']),
                        'csindex' => $this->_kpiMeasurement($fiscal, $jobcode, 'csindex', $row['csindex'] * 100),
                        'absence' => $this->_kpiMeasurement($fiscal, $jobcode, 'absence', $row['absence'] * 100),
                        'elearning' => $this->_kpiMeasurement($fiscal, $jobcode, 'elearning', $row['elearning']),
                        'part_code' => $this->_kpiMeasurement($fiscal, $jobcode, 'part_code', $row['part_code']),
                        'part_callback' => $this->_kpiMeasurement($fiscal, $jobcode, 'part_callback', $row['part_callback'])
                    ];
                }
                break;

            case 'cs-ccc-cc40':
                foreach ($data['result'] as $row) {
                    $data['kpiResult'][] = [
                        'period' => $row['period'],
                        'productivity' => $this->_kpiMeasurement($fiscal, $jobcode, 'productivity', $row['productivity']),
                        'csindex' => $this->_kpiMeasurement($fiscal, $jobcode, 'csindex', $row['csindex'] * 100),
                        'absence' => $this->_kpiMeasurement($fiscal, $jobcode, 'absence', $row['absence'] * 100),
                        'email_reply' => $this->_kpiMeasurement($fiscal, $jobcode, 'email_reply', $row['email_reply']),
                        'promo_inquiry' => $this->_kpiMeasurement($fiscal, $jobcode, 'promo_inquiry', $row['promo_inquiry']),
                        'part_code' => $this->_kpiMeasurement($fiscal, $jobcode, 'part_code', $row['part_code']),
                        'part_callback' => $this->_kpiMeasurement($fiscal, $jobcode, 'part_callback', $row['part_callback'])
                    ];
                }
                break;

            case 'cs-ccc-cc50':
                foreach ($data['result'] as $row) {
                    $data['kpiResult'][] = [
                        'period' => $row['period'],
                        'csindex' => $this->_kpiMeasurement($fiscal, $jobcode, 'csindex', $row['csindex'] * 100),
                        'absence' => $this->_kpiMeasurement($fiscal, $jobcode, 'absence', $row['absence'] * 100),
                        'complaint_forward' => $this->_kpiMeasurement($fiscal, $jobcode, 'complaint_forward', $row['complaint_forward']),
                        'complaint_completion' => $this->_kpiMeasurement($fiscal, $jobcode, 'complaint_completion', $row['complaint_completion']),
                        'complaint_report' => $this->_kpiMeasurement($fiscal, $jobcode, 'complaint_report', $row['complaint_report'])
                    ];
                }
                break;
            
            default:
                $data['kpiResult'] = [
                    'period' => 'n/a'
                ];
                break;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/byagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-assesment');
    }

    private function _dateToFiscal($dateStart, $dateEnd)
    {
        $monthStart = (int) date("m", strtotime($dateStart));
        $monthEnd = (int) date("m", strtotime($dateEnd));

        if( $monthStart >= 4 && $monthStart <= 9 && $monthEnd <= 9 ) {
            $fiscal = date("Y", strtotime($dateStart)) . 'F';
            return $fiscal;
        } else if ( $monthStart >= 10 && $monthEnd <= 3 ) {
            $fiscal = date("Y", strtotime($dateStart)) . 'L';
            return $fiscal;
        } else if ( $monthStart >= 10 && $monthEnd >= 10) {
            $fiscal = date("Y", strtotime($dateStart)) . 'L';
            return $fiscal;
        } else {
            $fiscal = date("Y", strtotime("$dateStart -1 years")) . 'L';
            return $fiscal;
        }
    }

    public function others()
    {
        check_access();
        $data['title'] = "Others Agent's KPI Item";

        if (!$this->input->post('selectOthersKpiStart') || !$this->input->post('selectOthersKpiEnd')) {
            // if( (int)date("m") > 4 && (int)date("m") < 10 ) {
            //     $data['startPeriod'] = date("Y-04-01");
            //     $data['endPeriod'] = date("Y-09-01");
            // } else {
            //     $data['startPeriod'] = date("Y-04-01");
            //     $data['endPeriod'] = date("Y-09-01");
            // } 
            if( (int)date("m") > 4 && (int)date("m") < 10 ) {
                $data['startPeriod'] = date("Y-04-01");
                $data['endPeriod'] = date("Y-09-01");
            } else if ((int)date("m") > 10) {
            	$data['startPeriod'] = date("Y-10-01");
                $data['endPeriod'] = date("Y-03-01", strtotime("+1 year"));
            } else {
                $data['startPeriod'] = date("Y-10-01", strtotime("-1 year"));
                $data['endPeriod'] = date("Y-03-01");
            }         
        } else {
            $data['startPeriod'] = $this->input->post('selectOthersKpiStart');
            $data['endPeriod'] = $this->input->post('selectOthersKpiEnd');
        }

        $this->form_validation->set_rules('addSingleOthersKpiPeriod', 'Period', 'required');
        $this->form_validation->set_rules('addSingleOthersKpiAgent', 'Agen', 'required|trim');

        if($this->form_validation->run() == false) {
            $data['othersKpiByPeriod'] = $this->assessment->getOthersKpiByPeriod($data['startPeriod'], $data['endPeriod']);
            $data['allAgents'] = $this->assessment->getAllAgents();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('assessment/others-item', $data);
            $this->load->view('templates/footer');            
        } else {
            $data = [
                'period' => date("Y-m-01", strtotime($this->input->post('addSingleOthersKpiPeriod'))),
                'agent' => $this->input->post('addSingleOthersKpiAgent'),
                'skape_draft' => $this->input->post('addSingleOthersKpiSkapeDraft'),
                'skape_solution' => $this->input->post('addSingleOthersKpiSkapeSolution'),
                'knowledge_sharing' => $this->input->post('addSingleOthersKpiKnowledgeSharing'),
                'part_callback' => $this->input->post('addSingleOthersKpiPartCallback'),
                'complaint_forward' => $this->input->post('addSingleOthersKpiComplaintForward'),
                'complaint_completion' => $this->input->post('addSingleOthersKpiComplaintCompletion'),
                'complaint_report' => $this->input->post('addSingleOthersKpiComplaintReport'),
                'email_reply' => $this->input->post('addSingleOthersKpiEmailReply'),
                'promo_inquiry' => $this->input->post('addSingleOthersKpiPromoInquiry')
            ];

            if($this->assessment->addSingleOthers($data) > 0) {
                $this->session->set_flashdata('message', 'Success added|success|KPI data successly added!');
                redirect('assessment/others');
            }
        }
    }

    public function delete()
    {
        $id = $this->uri->segment(3);
        if($this->assessment->deleteOthersKpiById($id) > 0 ) {
            $this->session->set_flashdata('message', 'Success deleted|info|Others KPI item deleted!');
                redirect('assessment/others');
        }
    }

    public function addMultipleKpi()
    {
        //check_access();
        $data['title'] = 'Add Multiple Staff Others KPI Item';

        $data['period'] = $this->input->post('addMultipleOthersKpiPeriod');
        $data['numRows'] = $this->input->post('addMultipleOthersKpiRows');
        $data['allAgents'] = $this->assessment->getAllAgents();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/add-multiple', $data);
        $this->load->view('templates/footer');

    }

    public function getSingleOthersKpi()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('kpi_other', ['id' => $id])->row_array());
    }

    public function editOthersKpiData()
    {
        $data = [
                'id' => $this->input->post('addSingleOthersKpiId'),
                'period' => date("Y-m-01", strtotime($this->input->post('addSingleOthersKpiPeriod'))),
                'agent' => $this->input->post('addSingleOthersKpiAgent'),
                'skape_draft' => $this->input->post('addSingleOthersKpiSkapeDraft'),
                'skape_solution' => $this->input->post('addSingleOthersKpiSkapeSolution'),
                'knowledge_sharing' => $this->input->post('addSingleOthersKpiKnowledgeSharing'),
                'part_callback' => $this->input->post('addSingleOthersKpiPartCallback'),
                'complaint_forward' => $this->input->post('addSingleOthersKpiComplaintForward'),
                'complaint_completion' => $this->input->post('addSingleOthersKpiComplaintCompletion'),
                'complaint_report' => $this->input->post('addSingleOthersKpiComplaintReport'),
                'email_reply' => $this->input->post('addSingleOthersKpiEmailReply'),
                'promo_inquiry' => $this->input->post('addSingleOthersKpiPromoInquiry')
            ];

        if($this->assessment->editSingleOthers($data) > 0) {
            $this->session->set_flashdata('message', 'Success|success|KPI data successly updated!');
            redirect('assessment/others');
        }
    }

    public function submitMultipleKpi()
    {        
        $rows = $this->input->post('inputMultipleNumrows');
        $submitData = [];

        for($i = 0; $i < $rows; $i++) {
            $submitData[$i] = [
                        'period' => date("Y-m-01", strtotime($this->input->post('inputMultiplePeriod'.$i))),
                        'agent' => $this->input->post('inputMultipleAgent'.$i),
                        'skape_draft' => $this->input->post('inputMultipleSkapeDraft'.$i),
                        'skape_solution' => $this->input->post('inputMultipleSkapeSolution'.$i),
                        'knowledge_sharing' => $this->input->post('inputMultipleKnowledgeSharing'.$i),
                        'part_callback' => $this->input->post('inputMultiplePartCallback'.$i),
                        'complaint_forward' => $this->input->post('inputMultipleComplaintForward'.$i),
                        'complaint_completion' => $this->input->post('inputMultipleComplaintCompletion'.$i),
                        'complaint_report' => $this->input->post('inputMultipleComplaintReport'.$i),
                        'email_reply' => $this->input->post('addSingleOthersKpiEmailReply'),
                        'promo_inquiry' => $this->input->post('addSingleOthersKpiPromoInquiry'),
                        'saved_by' => $this->session->userdata('user_id'),
                        'saved_at' => date("Y-m-d H:i:s")
            ];
        }
        if ( $this->assessment->submitMultipleOthersKpi($submitData) > 0 ){
            $this->session->set_flashdata('message', 'Suucess added|success|Others KPI successly added!');
            redirect('assessment/others');
        }
    }

    private function _getKpiWeight($jobcode, $fiscal, $item)
    {
        $result = $this->assessment->getWeightKpiTarget($jobcode, $fiscal, $item);
        if (is_null($result)) {
            return 0;
        } else {
            return $result['weight'];
        }
    }

    public function getKpiWeight()
    {
        $result = $this->assessment->getWeightKpiTarget('cs-ccc-cc30', '2021L', 'part_callback');
        echo "<pre>";
        var_dump($result);
        if (is_null($result)) {
            echo 0;
        } else {
            echo $result['weight'];
        }
    }

    public function getJobcode()
    {
        var_dump($this->assessment->getJobcode('Aliahmad'));
    }

    private function _getJobcode($user_id)
    {
        return $this->assessment->getJobcode($user_id);
    }

    private function _kpiAchievementToAlphabet($kpi)
    {
        $val = $kpi * 1.67;
        if ($val > 111) {
            return 'S';
        } else if ($val >= 106 && $val < 111) {
            return 'A';
        } else if ($val >= 100 && $val < 106) {
            return 'B';
        } else if ($val >= 80 && $val < 100) {
            return 'C';
        } else {
            return 'D';
        }
    }

    private function _kpiMeasurement($fiscal, $jobcode, $item, $result)
    {
        return $this->assessment->performKpiMeasurement($fiscal, $jobcode, $item, $result);
    }

    public function byagentnew()
    {
        check_access();
        $data['title'] = 'Best by Agent';

        if (!$this->input->post('selectByAgentNewStartPeriod')) {
            $startPeriod = date("Y-m-01", strtotime("-3 months"));
            $endPeriod = date("Y-m-01");
            $selectedAgent = $this->session->userdata('user_id');
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('selectByAgentNewStartPeriod')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('selectByAgentNewEndPeriod')));
            $selectedAgent = $this->input->post('selectByAgentNewAgent');
        }

        $data['allAgents'] = $this->assessment->getAllAgents();
        $data['sourceData'] = $this->assessment->getSourceDataByAgent($selectedAgent, $startPeriod, $endPeriod);
        //$data['resultData'] = $this->assessment->getResultDataByAgent($selectedAgent, $startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/byagent-new', $data);
        $this->load->view('templates/footer');
    }

    public function bestbymonth()
    {
        check_access();
        $data['title'] = 'Best Agent by Month';

        if (!$this->input->post('selectMonthyBestByMonth')) {
            $data['selectPeriod'] = date("Y-m-01", strtotime("-1 months"));
        } else {
            $data['selectPeriod'] = date("Y-m-01", strtotime($this->input->post('selectMonthyBestByMonth')));    
        }

        $data['allAgents'] = $this->assessment->getAllAgents();

        if (count($this->assessment->getResultBestAgentByMonth($data['selectPeriod'])) < 1) {
            $data['sourceByMonth'] = $this->assessment->getDetailSourceByPeriod($data['selectPeriod']);
            $data['resultByMonth'] = $this->assessment->getResultBestAgentByMonth($data['selectPeriod']);
        } else {
            $data['sourceByMonth'] = $this->assessment->getSourceBestAgentByMonth($data['selectPeriod']);
            $data['resultByMonth'] = $this->assessment->getResultBestAgentByMonth($data['selectPeriod']);
        }

        $data['checkSourceData'] = $this->assessment->checkSourceData($data['selectPeriod']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/best-bymonth', $data);
        $this->load->view('templates/footer');
    }

    public function processbymonth($month)
    {
        $sourceData = $this->assessment->getDetailSourceByPeriod($month);
        if (count($sourceData) < 1) {
            $this->session->set_flashdata('message', 'Error|error|No data or incomplete data to be processed!');
            redirect('assessment/bestbymonth');
        }
        
        $output = [];
        foreach($sourceData as $row) {
            $output[] = [
                'month' => $month,
                'agent' => $row['agent'],
                'productivity_result' => $row['prod_hour'],
                'productivity_score' => $this->_scoreIndex($row['agent'], 'productivity', $row['prod_hour']),
                'smilevoice_result' => $row['csindex_ratio'],
                'smilevoice_score' => $this->_scoreIndex($row['agent'], 'smilevoice', $row['csindex_ratio']),
                'attendance_result' => $row['attendance'],
                'attendance_score' => $this->_scoreIndex($row['agent'], 'attendance', $row['attendance']),
                'elearning_result' => $row['elearning_score'],
                'elearning_score' => $this->_scoreIndex($row['agent'], 'elearning', $row['elearning_score']),
                'teamwork_result' => $row['auxratio'],
                'teamwork_score' => $this->_scoreIndex($row['agent'], 'teamwork', $row['auxratio']),
                'total_score' => 
                            ($this->_scoreIndex($row['agent'], 'productivity', $row['prod_hour']) * $this->_bestAgentItemsWeight('productivity') / 100) + 
                            ($this->_scoreIndex($row['agent'], 'smilevoice', $row['csindex_ratio']) * $this->_bestAgentItemsWeight('smilevoice') / 100) + 
                            ($this->_scoreIndex($row['agent'], 'attendance', $row['attendance']) * $this->_bestAgentItemsWeight('attendance') / 100) + 
                            ($this->_scoreIndex($row['agent'], 'elearning', $row['elearning_score']) * $this->_bestAgentItemsWeight('elearning') / 100) + 
                            ($this->_scoreIndex($row['agent'], 'teamwork', $row['auxratio']) * $this->_bestAgentItemsWeight('teamwork') / 100)
            ];
        }

        $keys = array_column($output, 'total_score');
        array_multisort($keys, SORT_DESC, $output);
        
        if ($this->db->get_where('kpi_best_agent_detail', ['month' => $month])->num_rows() > 0) {
            $this->db->delete('kpi_best_agent_detail', ['month' => $month]);
            if ($this->assessment->addNewResultByMonth($output) > 0 ) {
                $this->session->set_flashdata('message', 'Suucessly Updated|success|Best agent result already updated!');
                redirect('assessment/bestbymonth');
            }
        } else {
            if ($this->assessment->addNewResultByMonth($output) > 0 ) {
                $this->session->set_flashdata('message', 'Suucessly added|success|Please see the best agent result!');
                redirect('assessment/bestbymonth');
            }    
        }
    }

    private function _bestAgentItemsWeight($item)
    {
        return $this->db->get_where('kpi_best_agent_target', ['item' => $item])->row_array()['weight'];
    }

    private function _scoreIndex($agent, $item, $source)
    {
        $jobcode = $this->_getJobcode($agent);
        return $this->assessment->getScoreIndex($jobcode, $item, $source);
    }

    public function bestagent()
    {
        check_access();
        $data['title'] = 'Best Agent Summary';

        if (!$this->input->post('summaryBestAgentStartPeriod') || !$this->input->post('summaryBestAgentEndPeriod')) {
            $startPeriod = date("Y-m-01", strtotime("-2 months"));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('summaryBestAgentStartPeriod')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('summaryBestAgentEndPeriod')));
        }
        $results = $this->assessment->getBestAgentDetailByPeriod($startPeriod, $endPeriod);
        $data['target'] = $this->assessment->getBestAgentTarget();

        $data['output'] = [];
        foreach($results as $row) {
            $data['output'][] = [
                'agent' => $row['agent'],
                'productivity' => $row['productivity'],
                'smilevoice' => $row['smilevoice'],
                'attendance' => $row['attendance'],
                'elearning' => $row['elearning'],
                'teamwork' => $row['teamwork'],
                'total_score' => 
                            ($row['productivity']) * $this->_bestAgentItemsWeight('productivity') / 100 + 
                            ($row['smilevoice']) * $this->_bestAgentItemsWeight('smilevoice') / 100 + 
                            ($row['attendance']) * $this->_bestAgentItemsWeight('attendance') / 100 + 
                            ($row['elearning']) * $this->_bestAgentItemsWeight('elearning') / 100 + 
                            ($row['teamwork']) * $this->_bestAgentItemsWeight('teamwork') / 100

            ];
        }

        $keys = array_column($data['output'], 'total_score');
        array_multisort($keys, SORT_DESC, $data['output']);
        $data['bestAgentDetails'] = $this->assessment->getBestAgentDetailByPeriod($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('assessment/bestagent', $data);
        $this->load->view('templates/footer');
    }

    public function resetBestAgentResult($month)
    {
        $this->db->delete('kpi_best_agent_detail', ['month' => $month]);
        $this->session->set_flashdata('message', 'Reseted|info|Result data successly reseted!');
                redirect('assessment/bestbymonth');
    }
}
