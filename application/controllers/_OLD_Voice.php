<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Voice_model', 'voice');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Summary of Voice Assesment';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        if (!$this->input->post()) {
            $startPeriod = date("Y-m-01", strtotime('-6 months'));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceEnd')));
        }

        $data['voiceSummary'] = $this->voice->getVoiceSummaryResultByPeriod($startPeriod, $endPeriod);
        $data['voiceSummaryByAgent'] = $this->voice->getVoiceSummaryResultByAgent($startPeriod, $endPeriod);
        $data['voiceUnproperSummary'] = $this->voice->getUnproperSummaryByPeriod($startPeriod, $endPeriod);
        // $data['voiceUnproperByAgent'] = $this->voice->getUnproperVoiceSummaryByAgent($startPeriod, $endPeriod);
        // $data['voiceUnproperClearAgent'] = $this->voice->getUnproperVoiceSummaryClearAgent($startPeriod, $endPeriod);
        // $data['voiceUnproperNotes'] = $this->voice->getUnproperVoiceSummaryNotes($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-summary-new', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function prevsummary()
    {
        $data['title'] = 'Summary of Voice Assesment';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        if (!$this->input->post()) {
            $startPeriod = date("Y-m-01", strtotime('-6 months'));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceEnd')));
        }

        $data['voiceSummaryByFindings'] = $this->voice->getVoiceSummaryByFindings($startPeriod, $endPeriod);
        $data['voiceSummaryByCategory'] = $this->voice->getVoiceSummaryByPeriod($startPeriod, $endPeriod);
        $data['voiceUnproperSummary'] = $this->voice->getUnproperVoiceSummaryByPeriod($startPeriod, $endPeriod);
        $data['voiceUnproperByAgent'] = $this->voice->getUnproperVoiceSummaryByAgent($startPeriod, $endPeriod);
        $data['voiceUnproperClearAgent'] = $this->voice->getUnproperVoiceSummaryClearAgent($startPeriod, $endPeriod);
        $data['voiceUnproperNotes'] = $this->voice->getUnproperVoiceSummaryNotes($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-summary', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function unproperSummary()
    {
        // if (!$this->input->post('selectSummaryVoiceStart')) {
        //     $startPeriod = date("Y-m-01", strtotime('-6 months'));
        //     $endPeriod = date("Y-m-01");
        // } else {
        //     $startPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceStart')));
        //     $endPeriod = date("Y-m-01", strtotime($this->input->post('selectSummaryVoiceEnd')));
        // }

        $startPeriod = date("Y-m-01", strtotime($this->input->post('startPeriod')));
        $endPeriod = date("Y-m-01", strtotime($this->input->post('endPeriod')));
        $result = $this->voice->getUnproperVoiceSummaryByPeriod($startPeriod, $endPeriod)[0]; 

        $json_data = [];        
        $json_data['labels'] = [
        	'Greeting incomplete',
        	'Greeting no smile', 
        	'Intonation not straight', 
        	'Intonation not clear', 
        	'Intonation flat', 
        	'Intonation weak', 
        	'Intonation high', 
        	'Jargon',
        	'No customer name',
        	'Not communicative',
        	'Unaccurate info',
        	'Not ask help',
        	'Closing not standard',
        	'Closing incomplete'
        ];
        $json_data['values'] = [
            $result['greeting_incomplete'], 
            $result['greeting_nosmile'], 
            $result['intonation_nostraight'], 
            $result['intonation_noclear'], 
            $result['intonation_flat'], 
            $result['intonation_weak'], 
            $result['intonation_high'], 
            $result['handling_jargon'],
            $result['handling_nocustomer_name'],
            $result['handling_nocommunicative'],
            $result['handling_inaccurate'],
            $result['handling_noask_help'],
            $result['closing_unstandard'],
            $result['closing_incomplete']
        ];
        echo json_encode($json_data);
    }

    public function detail()
    {
        check_access();
        $data['title'] = 'Voice Assesment Detail Sheet';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        if (!$this->input->post('voiceSummaryDateStart')) {
            $startPeriod = date("Y-m-01", strtotime('-1 months'));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('voiceSummaryDateStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('voiceSummaryDateEnd')));
        }        
        $data['voiceAssesmentByPeriod'] = $this->voice->getDetailNewVoiceAssesmentByPeriod($startPeriod, $endPeriod);
        

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-detail', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function byagent()
    {
        $data['title'] = 'Voice Assesment By Agent';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        $this->form_validation->set_rules('', 'absenceSummaryDateEnd', 'required');
        if (!$this->input->post()) {
            $startPeriod = date("Y-m-01", strtotime('-1 months'));
            $endPeriod = date("Y-m-01");
            $agent = $this->session->userdata('user_id');
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('voiceByAgentDateStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('voiceByAgentDateEnd')));
            $agent = $this->input->post('voiceByAgentSelectAgent');
        } 

        // var_dump($this->input->post());
        // die;

        $data['allAgent'] = $this->voice->getAllActiveAgent();
        $data['voiceSummaryByAgent'] = $this->voice->getSummaryNewVoiceAssesmentAgentByPeriod($startPeriod, $endPeriod, $agent);
        $data['voiceDetailByAgent'] = $this->voice->getDetailNewVoiceAssesmentAgentByPeriod($startPeriod, $endPeriod, $agent);
        $data['voiceUnproperListByAgent'] = $this->voice->getUnproperListByAgentByPeriod($startPeriod, $endPeriod, $agent);
        // getDetailVoiceAssesmentByAgentByPeriod

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-byagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function transition()
    {
        check_access();
        $data['title'] = 'Transition of Voice assesment';

        if (!$this->input->post()) {
            $startPeriod = date("Y-m-01", strtotime('-3 months'));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('selectTransitionVoiceStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('selectTransitionVoiceEnd')));
        }

        $data['transitionVoiceByPeriod'] = $this->voice->getTransitionVoiceByPeriod($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-transition', $data);
        $this->load->view('templates/footer');
    }

    public function info()
    {
        $data['title'] = 'Voice assesment info';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-info', $data);
        $this->load->view('templates/footer');
    }

    public function survey()
    {
        check_access();
        $data['title'] = "Assesment for Agent's Voice (Tapping)";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allActiveAgent'] = $this->voice->getAllActiveAgent();

        $this->form_validation->set_rules('voiceSurveyPeriod', 'Period', 'required');
        $this->form_validation->set_rules('voiceSurveyAgent', 'Agent Name', 'required');
        // $this->form_validation->set_rules('voiceSurveyVoiceNumber', 'Voice Number', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('voice/voice-survey-new', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-voice');
        } else {
            // check voice file uploaded
            $voiceLink = NULL;
            $uploadVoiceNotif = ' (without voice file uploaded)';
            if ($_FILES['voiceSurveyVoiceFile']['error'] !== 4) {
                // check upload folder
                $yrs = date("Y", strtotime($this->input->post('voiceSurveyCallDate')));
                $mth = date("Y-m", strtotime($this->input->post('voiceSurveyCallDate')));
                if (!is_dir('./assets/voices/'.$yrs)) {
                    mkdir('./assets/voices/' . $yrs, 0777, TRUE);
                }
                if (!is_dir('./assets/voices/'.$yrs.'/'.$mth)) {
                    mkdir('./assets/voices/'.$yrs.'/'.$mth, 0777, TRUE);
                }
                $ext = '.' . explode('.', $_FILES['voiceSurveyVoiceFile']['name'])[1];

                $config['upload_path'] = './assets/voices/' . $yrs . '/' . $mth;
                $config['allowed_types'] = 'wav|mp3|aac';
                $config['max_size'] = 9128;
                $config['file_name'] = 'voice_' . strtolower($this->input->post('voiceSurveyAgent')) . '_' . $this->input->post('voiceSurveyPhone') . '_' . date("Ymd", strtotime($this->input->post('voiceSurveyCallDate'))) . $ext;
                $config['overwrite'] = true;
                $voiceLink = $yrs . '/'. $mth . '/'. $config['file_name'];

                $this->load->library('upload', $config);
                $this->upload->do_upload('voiceSurveyVoiceFile');
                $uploadVoiceNotif = ' - with Voice Record';
            }

            $newData = [
                'period' => date("Y-m-d", strtotime($this->input->post('voiceSurveyPeriod'))),
                'agent' => $this->input->post('voiceSurveyAgent'),
                'customer_phone' => $this->input->post('voiceSurveyPhone'),
                'survey_source' => $this->input->post('voiceSurveySource'),
                'call_date' => date("Y-m-d", strtotime($this->input->post('voiceSurveyCallDate'))),
                'greeting' => $this->input->post('voiceSurveyGreeting'),
                'greeting_remark' => $this->input->post('voiceSurveyGreetingRemark') == '' ? NULL : $this->input->post('voiceSurveyGreetingRemark'),
                'smile_voice' => $this->input->post('voiceSurveySmile'),
                'smile_voice_remark' => $this->input->post('voiceSurveySmileRemark') == '' ? NULL : $this->input->post('voiceSurveySmileRemark'),
                'accuracy' => $this->input->post('voiceSurveyAccuracy'),
                'accuracy_remark' => $this->input->post('voiceSurveyAccuracyRemark') == '' ? NULL : $this->input->post('voiceSurveyAccuracyRemark'),
                'closing' => $this->input->post('voiceSurveyClosing'),
                'closing_remark' => $this->input->post('voiceSurveyClosingRemark') == '' ? NULL : $this->input->post('voiceSurveyClosingRemark'),
                'voice_remark' => $this->input->post('voiceSurveyRemark') == '' ? NULL : $this->input->post('voiceSurveyRemark'),
                'voice_link' => $voiceLink,
                'survey_by' => $this->session->userdata('user_id'),
                'survey_at' => date("Y-m-d H:i:s")
            ];

            if ($this->voice->addNewSurveyData($newData) > 0 ) {
                $this->session->set_flashdata('message', 'Success!|success|Voice tapping saved' . $uploadVoiceNotif);
                redirect('voice/survey');
            }
        }
    }

    public function surveyold()
    {
        check_access();
        $data['title'] = "Assesment for Agent's Voice (Tapping)";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allActiveAgent'] = $this->voice->getAllActiveAgent();
        // $data['qtyVoiceAgentInPeriod'] = $this->voice->getqtyVoiceAgentInPeriod();

        $this->form_validation->set_rules('voiceSurveyPeriod', 'Period', 'required');
        $this->form_validation->set_rules('voiceSurveyAgent', 'Agent Name', 'required');
        $this->form_validation->set_rules('voiceSurveyVoiceNumber', 'Voice Number', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('voice/voice-survey', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-voice');
        } else {
            $data = [
                'period' => date("Y-m-01", strtotime($this->input->post('voiceSurveyPeriod'))),
                'agent' => $this->input->post('voiceSurveyAgent'),
                'voice_number' => $this->input->post('voiceSurveyVoiceNumber'),
                'call_date' => $this->input->post('voiceSurveyCallDate'),
                'greeting_complete' => $this->input->post('voiceSurveyGreetingComplete'),
                'greeting_smile' => $this->_checkPoint($this->input->post('voiceSurveyGreetingSmile')),
                'intonation_straight' => $this->_checkPoint($this->input->post('voiceSurveyIntonasiLugas')),
                'intonation_clear' => $this->_checkPoint($this->input->post('voiceSurveyIntonasiJelas')),
                'intonation_not_flat' => $this->_checkPoint($this->input->post('voiceSurveyIntonasiTidakDatar')),
                'intonation_not_weak' => $this->_checkPoint($this->input->post('voiceSurveyIntonasiTidakLemas')),
                'intonation_not_high' => $this->_checkPoint($this->input->post('voiceSurveyIntonasiTidakTinggi')),
                'handling_no_jargon' => $this->_checkPoint($this->input->post('voiceSurveyHandlingTidakJargon')),
                'handling_customer_name' => $this->_checkPoint($this->input->post('voiceSurveyHandlingSebutNamaKonsumen')),
                'handling_communicative' => $this->_checkPoint($this->input->post('voiceSurveyHandlingKomunikatif')),
                'handling_accuracy' => $this->_checkPoint($this->input->post('voiceSurveyHandlingAkurasiInformasi')),
                'handling_ask_help' => $this->_checkPoint($this->input->post('voiceSurveyHandlingBantuanKembali')),
                'closing' => $this->input->post('voiceSurveyClosing'),
                'voice_remark' => $this->input->post('voiceSurveyRemark'),
                'voice_link' => $this->input->post('voiceSurveyVoiceLink'),
                'survey_by' => $this->session->userdata('user_id'),
                'survey_at' => date("Y-m-d H:i:s")
            ];
            if ($this->voice->addNewSurvey($data) > 0) {
                $this->session->set_flashdata('message', 'Success!|success|New voice tapping saved!');
                redirect('voice/detail');
            }
        }
    }

    public function deleteVoiceById()
    {
        if(!$this->input->post('id')){
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('id');
        }

        if ($this->voice->deleteVoiceById($id) > 0) {
            $this->session->set_flashdata('message', 'Deleted!|info|Voice tapping data deleted!');
            redirect('voice/detail');
        }
    }

    public function editVoice()
    {
        if(!$this->input->post('id')){
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('id');
        }

        $data['voiceData'] = $this->db->get_where('voice_assesment', ['id' => $id])->row_array();
        $data['voiceDataJson'] = json_encode($this->db->get_where('voice_assesment', ['id' => $id])->row_array());
        $data['title'] = "Assesment for Agent's Voice (Tapping)";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-edit-survey', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function performEditVoice()
    {
        $data = [
                'id' => $this->input->post('voiceSurveyEditId'),
                'voice_number' => $this->input->post('voiceSurveyEditVoiceNumber'),
                'call_date' => $this->input->post('voiceSurveyEditCallDate'),
                'greeting_complete' => $this->input->post('voiceSurveyEditGreetingComplete'),
                'greeting_smile' => $this->_checkPoint($this->input->post('voiceSurveyEditGreetingSmile')),
                'intonation_straight' => $this->_checkPoint($this->input->post('voiceSurveyEditIntonasiLugas')),
                'intonation_clear' => $this->_checkPoint($this->input->post('voiceSurveyEditIntonasiJelas')),
                'intonation_not_flat' => $this->_checkPoint($this->input->post('voiceSurveyEditIntonasiTidakDatar')),
                'intonation_not_weak' => $this->_checkPoint($this->input->post('voiceSurveyEditIntonasiTidakLemas')),
                'intonation_not_high' => $this->_checkPoint($this->input->post('voiceSurveyEditIntonasiTidakTinggi')),
                'handling_no_jargon' => $this->_checkPoint($this->input->post('voiceSurveyEditHandlingTidakJargon')),
                'handling_customer_name' => $this->_checkPoint($this->input->post('voiceSurveyEditHandlingSebutNamaKonsumen')),
                'handling_communicative' => $this->_checkPoint($this->input->post('voiceSurveyEditHandlingKomunikatif')),
                'handling_accuracy' => $this->_checkPoint($this->input->post('voiceSurveyEditHandlingAkurasiInformasi')),
                'handling_ask_help' => $this->_checkPoint($this->input->post('voiceSurveyEditHandlingBantuanKembali')),
                'closing' => $this->input->post('voiceSurveyEditClosing'),
                'voice_remark' => htmlspecialchars($this->input->post('voiceSurveyEditRemark')),
                'voice_link' => htmlspecialchars($this->input->post('voiceSurveyEditVoiceLink'))                
            ];

        if ($this->voice->performEditVoiceById($data)){
            $this->session->set_flashdata('message', 'Updated!|success|Voice tapping data successly updated!');
            redirect('voice/detail');
        }
    }

    public function numberVoiceByAgentByPeriod()
    {
        $user_id = $this->input->post('user_id');
        $period = date("Y-m-01", strtotime($this->input->post('period')));
        $data = [
            'voiceNumber' => $this->voice->getNumberVoiceByAgentByPeriod($user_id, $period),
            'averageScore' => floatval($this->voice->getNewAverageScoreByAgentByPeriod($user_id, $period))
        ];
        echo json_encode($data);
    }

    private function _checkPoint($val)
    {
        if ($val == null) {
            return 0;
        } else {
            return $val;
        }
    }

    public function detailVoiceExportToExcel()
    {
        $startPeriod = date("Y-m-01", strtotime($this->uri->segment(3)));
        $endPeriod = date("Y-m-01", strtotime($this->uri->segment(4)));

        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Quality Assurance')->setLastModifiedBy('Quality Assurance')->setTitle("Detail of Voice Assesment")->setSubject("Voice Assesment")->setDescription("Detail of Voice Assesment by Period")->setKeywords("Detail of Voice Assesment");

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
        $style_data_left = array(
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

        $style_data_center = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis 
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL OF VOICE ASSESMENT PERIOD: " . strtoupper(date("F Y", strtotime($startPeriod))) . " TO " . strtoupper(date("F Y", strtotime($endPeriod))) ); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Header 1 tabel nya pada baris ke-3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No");
        $excel->getActiveSheet()->mergeCells('A3:A4');
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Period");
        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Agent");
        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Cust. Phone");
        $excel->getActiveSheet()->mergeCells('D3:D4');
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Call date");
        $excel->getActiveSheet()->mergeCells('E3:E4');
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Greeting");
        $excel->getActiveSheet()->mergeCells('F3:G3');
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Smile Voice");
        $excel->getActiveSheet()->mergeCells('H3:I3');
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Accuracy");
        $excel->getActiveSheet()->mergeCells('J3:K3');
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Closing");
        $excel->getActiveSheet()->mergeCells('L3:M3');
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Score");
        $excel->getActiveSheet()->mergeCells('N3:N4');
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Remark");
        $excel->getActiveSheet()->mergeCells('O3:O4');

        // Header 2 tabel nya pada baris ke-4
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Result");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Result");
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "Result");
        $excel->setActiveSheetIndex(0)->setCellValue('K4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('L4', "Result");
        $excel->setActiveSheetIndex(0)->setCellValue('M4', "Remark");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3:A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3:B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3:c4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3:D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3:F3')->applyFromArray($style_col);        
        $excel->getActiveSheet()->getStyle('G3:K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3:O3')->applyFromArray($style_col);
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

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya        
        $voiceDetail = $this->voice->getDetailNewVoiceAssesmentByPeriod($startPeriod, $endPeriod);
        $no = 1;
        $numrow = 5;
        foreach ($voiceDetail as $vd) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, date("M Y", strtotime($vd['period'])));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $vd['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $vd['customer_phone']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, date("d-M-y", strtotime($vd['call_date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $vd['greeting']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $vd['greeting_remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $vd['smile_voice']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $vd['smile_voice_remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $vd['accuracy']);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $vd['accuracy_remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $vd['closing']);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $vd['closing_remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, ($vd['greeting'] + $vd['smile_voice'] + $vd['accuracy'] + $vd['closing']));
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $vd['remark']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_data_center);
            $excel->getActiveSheet()->getStyle('O' . $numrow)->applyFromArray($style_data_center);
            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(55);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of Voice Assesment");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Detail of Voice Assesment.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
