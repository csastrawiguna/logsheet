<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voice extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Voice_model', 'voice');
        is_logged_in();
    }

    // CALL REVIEW
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

    public function detail()
    {
        check_access();
        $data['title'] = 'Details of Voice Assesment';
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

        $this->form_validation->set_rules('', 'voiceByAgentSelectAgent', 'required');
        if (!$this->input->post('voiceByAgentDateStart')) {
            $startPeriod = date("Y-m-01", strtotime('-1 months'));
            $endPeriod = date("Y-m-01");
            $agent = $this->session->userdata('user_id');
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('voiceByAgentDateStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('voiceByAgentDateEnd')));
            $agent = $this->input->post('voiceByAgentSelectAgent');
        }

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
        $this->load->view('voice/voice-transition-new', $data);
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
        $data['title'] = "Survey Agent's Voice";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allActiveAgent'] = $this->voice->getAllActiveAgent();

        $this->form_validation->set_rules('voiceSurveyFormPeriod', 'Period', 'required');
        $this->form_validation->set_rules('voiceSurveyFormAgent', 'Agent Name', 'required');
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
            if ($_FILES['voiceSurveyFormVoiceFile']['error'] !== 4) {
                // check upload folder
                $yrs = date("Y", strtotime($this->input->post('voiceSurveyFormCallDate')));
                $mth = date("Y-m", strtotime($this->input->post('voiceSurveyFormCallDate')));
                if (!is_dir('./assets/voices/' . $yrs)) {
                    mkdir('./assets/voices/' . $yrs, 0777, TRUE);
                }
                if (!is_dir('./assets/voices/' . $yrs . '/' . $mth)) {
                    mkdir('./assets/voices/' . $yrs . '/' . $mth, 0777, TRUE);
                }
                $ext = '.' . explode('.', $_FILES['voiceSurveyFormVoiceFile']['name'])[1];

                $config['upload_path'] = './assets/voices/' . $yrs . '/' . $mth;
                $config['allowed_types'] = 'wav|mp3|aac';
                $config['max_size'] = 9128;
                $config['file_name'] = 'voice_' . strtolower($this->input->post('voiceSurveyFormAgent')) . '_' . $this->input->post('voiceSurveyFormPhone') . '_' . date("Ymd", strtotime($this->input->post('voiceSurveyFormCallDate'))) . $ext;
                $config['overwrite'] = true;
                $voiceLink = $yrs . '/' . $mth . '/' . $config['file_name'];

                $this->load->library('upload', $config);
                $this->upload->do_upload('voiceSurveyFormVoiceFile');
                $uploadVoiceNotif = ' - with Voice Record';
            }

            $newData = [
                'period' => date("Y-m-01", strtotime($this->input->post('voiceSurveyFormPeriod'))),
                'agent' => $this->input->post('voiceSurveyFormAgent'),
                'customer_phone' => $this->input->post('voiceSurveyFormPhone'),
                'survey_source' => $this->input->post('voiceSurveyTagSource'),
                'call_date' => date("Y-m-d", strtotime($this->input->post('voiceSurveyFormCallDate'))),
                'greeting' => $this->input->post('voiceSurveyFormGreeting'),
                'greeting_remark' => $this->input->post('voiceSurveyFormGreetingRemark') == '' ? NULL : $this->input->post('voiceSurveyFormGreetingRemark'),
                'smile_voice' => $this->input->post('voiceSurveyFormSmile'),
                'smile_voice_remark' => $this->input->post('voiceSurveyFormSmileRemark') == '' ? NULL : $this->input->post('voiceSurveyFormSmileRemark'),
                'accuracy' => $this->input->post('voiceSurveyFormAccuracy'),
                'accuracy_remark' => $this->input->post('voiceSurveyFormAccuracyRemark') == '' ? NULL : $this->input->post('voiceSurveyFormAccuracyRemark'),
                'closing' => $this->input->post('voiceSurveyFormClosing'),
                'closing_remark' => $this->input->post('voiceSurveyFormClosingRemark') == '' ? NULL : $this->input->post('voiceSurveyFormClosingRemark'),
                'voice_remark' => $this->input->post('voiceSurveyFormRemark') == '' ? NULL : $this->input->post('voiceSurveyFormRemark'),
                'voice_link' => $voiceLink,
                'survey_by' => $this->session->userdata('user_id'),
                'survey_at' => date("Y-m-d H:i:s")
            ];

            if ($this->voice->addNewSurveyData($newData) > 0) {
                $this->session->set_flashdata('message', 'Success!|success|Voice tapping saved' . $uploadVoiceNotif);
                redirect('voice/survey');
            }
        }
    }

    public function deleteVoiceById()
    {
        if (!$this->input->post('id')) {
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
        if (!$this->input->post('id')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('id');
        }

        $data['voiceData'] = $this->db->get_where('voice_assesment_25f', ['id' => $id])->row_array();
        $data['voiceDataJson'] = json_encode($this->db->get_where('voice_assesment_25f', ['id' => $id])->row_array());
        $data['title'] = "Edit Assesment (Tapping)";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/voice-edit-survey-new', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-voice');
    }

    public function performEditVoice()
    {
        $this->form_validation->set_rules('voiceSurveyFormPeriod', 'Period', 'required');
        $this->form_validation->set_rules('voiceSurveyFormAgent', 'Agent Name', 'required');

        // check voice file uploaded
        $voiceLink = NULL;
        $uploadVoiceNotif = ' (without voice file uploaded)';
        if ($_FILES['voiceSurveyEditVoiceFile']['error'] !== 4) {
            // check upload folder
            $yrs = date("Y", strtotime($this->input->post('voiceSurveyEditCallDate')));
            $mth = date("Y-m", strtotime($this->input->post('voiceSurveyEditCallDate')));
            if (!is_dir('./assets/voices/' . $yrs)) {
                mkdir('./assets/voices/' . $yrs, 0777, TRUE);
            }
            if (!is_dir('./assets/voices/' . $yrs . '/' . $mth)) {
                mkdir('./assets/voices/' . $yrs . '/' . $mth, 0777, TRUE);
            }
            $ext = '.' . explode('.', $_FILES['voiceSurveyEditVoiceFile']['name'])[1];

            $config['upload_path'] = './assets/voices/' . $yrs . '/' . $mth;
            $config['allowed_types'] = 'wav|mp3|aac';
            $config['max_size'] = 9128;
            $config['file_name'] = 'voice_' . strtolower($this->input->post('voiceSurveyEditAgent')) . '_' . $this->input->post('voiceSurveyEditPhone') . '_' . date("Ymd", strtotime($this->input->post('voiceSurveyEditCallDate'))) . $ext;
            $config['overwrite'] = true;
            $voiceLink = $yrs . '/' . $mth . '/' . $config['file_name'];

            $this->load->library('upload', $config);
            $this->upload->do_upload('voiceSurveyEditVoiceFile');
            $uploadVoiceNotif = ' - with Voice Record';
        } else {
            $voiceLink = $this->input->post('voiceSurveyEditVoiceLink');
        }

        $updateData = [
            'id' => $this->input->post('voiceSurveyEditId'),
            'period' => date("Y-m-01", strtotime($this->input->post('voiceSurveyEditPeriod'))),
            'agent' => $this->input->post('voiceSurveyEditAgent'),
            'customer_phone' => $this->input->post('voiceSurveyEditPhone'),
            'survey_source' => $this->input->post('voiceSurveyTagSource'),
            'call_date' => date("Y-m-d", strtotime($this->input->post('voiceSurveyEditCallDate'))),
            'greeting' => $this->input->post('voiceSurveyEditGreeting'),
            'greeting_remark' => $this->input->post('voiceSurveyEditGreetingRemark') == '' ? NULL : $this->input->post('voiceSurveyEditGreetingRemark'),
            'smile_voice' => $this->input->post('voiceSurveyEditSmile'),
            'smile_voice_remark' => $this->input->post('voiceSurveyEditSmileRemark') == '' ? NULL : $this->input->post('voiceSurveyEditSmileRemark'),
            'accuracy' => $this->input->post('voiceSurveyEditAccuracy'),
            'accuracy_remark' => $this->input->post('voiceSurveyEditAccuracyRemark') == '' ? NULL : $this->input->post('voiceSurveyEditAccuracyRemark'),
            'closing' => $this->input->post('voiceSurveyEditClosing'),
            'closing_remark' => $this->input->post('voiceSurveyEditClosingRemark') == '' ? NULL : $this->input->post('voiceSurveyEditClosingRemark'),
            'voice_remark' => $this->input->post('voiceSurveyEditRemark') == '' ? NULL : $this->input->post('voiceSurveyEditRemark'),
            'voice_link' => $voiceLink,
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        if ($this->voice->performEditVoiceById($updateData)) {
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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL OF VOICE ASSESMENT PERIOD: " . strtoupper(date("F Y", strtotime($startPeriod))) . " TO " . strtoupper(date("F Y", strtotime($endPeriod)))); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

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
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('K4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('L4', "Score");
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
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $vd['voice_remark']);

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

    public function summaryVoiceToExcel()
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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "SUMMARY OF VOICE ASSESMENT PERIOD: " . strtoupper(date("F Y", strtotime($startPeriod))) . " TO " . strtoupper(date("F Y", strtotime($endPeriod)))); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


        // Table 1 Overall Voice Assessment Summary
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "A. OVERALL SUMMARY");
        $excel->setActiveSheetIndex(0)->setCellValue('A5', "No");
        $excel->getActiveSheet()->mergeCells('A5:A6');
        $excel->setActiveSheetIndex(0)->setCellValue('B5', "Item");
        $excel->getActiveSheet()->mergeCells('B5:B6');
        $excel->setActiveSheetIndex(0)->setCellValue('C5', "Assessment");
        $excel->getActiveSheet()->mergeCells('C5:C6');
        $excel->setActiveSheetIndex(0)->setCellValue('D5', "Result");
        $excel->getActiveSheet()->mergeCells('D5:G5');
        $excel->setActiveSheetIndex(0)->setCellValue('D6', "Good");
        $excel->setActiveSheetIndex(0)->setCellValue('E6', "Need improve");
        $excel->setActiveSheetIndex(0)->setCellValue('F6', "Less");
        $excel->setActiveSheetIndex(0)->setCellValue('G6', "Bad");
        $excel->setActiveSheetIndex(0)->setCellValue('H5', "Voice Qty");
        $excel->getActiveSheet()->mergeCells('H5:H6');

        $summaryVoiceOverall = $this->voice->getVoiceSummaryResultByPeriod($startPeriod, $endPeriod);

        $summaryVoiceByAgent = $this->voice->getVoiceSummaryResultByAgent($startPeriod, $endPeriod);

        // var_dump($summaryVoiceOverall);die;

        // Header 2 tabel nya pada baris ke-4
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('K4', "Remark");
        $excel->setActiveSheetIndex(0)->setCellValue('L4', "Score");
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
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $vd['voice_remark']);

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

    // WA REVIEW
    // WA Review Summary
    public function wareviewsummary()
    {
        check_access();
        $data['title'] = 'Summary of WA Review';

        if(!$this->input->post('wareviewSummaryDateStart') && !$this->input->post('wareviewSummaryDateEnd')) {
            $data['startPeriod'] = date("Y-m-01");
            $data['endPeriod'] = date("Y-m-d");
        } else {
            $data['startPeriod'] = $this->input->post('wareviewSummaryDateStart');
            $data['endPeriod'] = $this->input->post('wareviewSummaryDateEnd');
        }

        $data['scoreList'] = array_column($this->voice->getWaReviewScorelist(), 'score', 'level');
        $data['wareviewSummaryAllByPeriod'] = $this->voice->getWaSummaryByPeriodByAgent($data['startPeriod'], $data['endPeriod']);
        $data['wareviewSummaryAllTotal'] = $this->voice->getWaSummaryByPeriod($data['startPeriod'], $data['endPeriod'])[0
        $data['wareviewUnproperAll'] = $this->voice->getWaUnproperByPeriodByAgent($data['startPeriod'], $data['endPeriod']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/wa-review-summary', $data);
        $this->load->view('templates/footer');
    }


    // detail list hasil review WA
    public function wareviewlist()
    {
        check_access();
        $data['title'] = 'WA Reply Review List';

        if(!$this->input->post('waReviewAllStartPeriod') && !$this->input->post('waReviewAllEndPeriod')) {
            $data['startPeriod'] = date("Y-m-01");
            $data['endPeriod'] = date("Y-m-d");
        } else {
            $data['startPeriod'] = $this->input->post('waReviewAllStartPeriod');
            $data['endPeriod'] = $this->input->post('waReviewAllEndPeriod');
        }

        $data['scoreList'] = array_column($this->voice->getWaReviewScorelist(), 'score', 'level');
        $data['waReviewList'] = $this->voice->getWaReviewByPeriodByAgent($data['startPeriod'], $data['endPeriod']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/wa-review-list', $data);
        $this->load->view('templates/footer');
    }

    // WA Review By Agent
    public function wareviewbyagent()
    {
        check_access();
        $data['title'] = 'Summary of WA Review';

         if (!$this->input->post('wareviewByAgentDateStart')) {
            $data['startPeriod'] = date("Y-m-01", strtotime('-1 months'));
            $data['endPeriod'] = date("Y-m-d");
            $data['agent'] = $this->session->userdata('user_id');
        } else {
            $data['startPeriod'] = date("Y-m-01", strtotime($this->input->post('wareviewByAgentDateStart')));
            $data['endPeriod'] = date("Y-m-d", strtotime($this->input->post('wareviewByAgentDateEnd')));
            $data['agent'] = $this->input->post('wareviewByAgentSelectAgent');
        }

        $data['allAgent'] = $this->voice->getAllActiveAgent();
        $data['scoreList'] = array_column($this->voice->getWaReviewScorelist(), 'score', 'level');
        $data['wareviewListByAgent'] = $this->voice->getWaReviewByPeriodByAgent($data['startPeriod'], $data['endPeriod'], $data['agent']);
        $data['wareviewSummaryByAgent'] = $this->voice->getWaSummaryByPeriodByAgent($data['startPeriod'], $data['endPeriod'], $data['agent']);
        $data['wareviewUnproperByAgent'] = $this->voice->getWaUnproperByPeriodByAgent($data['startPeriod'], $data['endPeriod'], $data['agent']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('voice/wa-review-byagent', $data);
        $this->load->view('templates/footer');
    }

    // isi form review balasan WA
    public function wareviewform()
    {
        check_access();
        $data['title'] = 'Filling WA Review';

        $data['allActiveAgent'] = $this->voice->getAllActiveAgent();
        $data['scoreList'] = array_column($this->voice->getWaReviewScorelist(), 'score', 'level');

        $this->form_validation->set_rules('waReviewSurveyPeriod', 'Period', 'required');
        $this->form_validation->set_rules('waReviewSurveyAgent', 'Agent Name', 'required|trim');
        $this->form_validation->set_rules('waReviewSurveyPhone', 'Customer Phone', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('voice/wa-review-survey', $data);
            $this->load->view('templates/footer');
        } else {
            $newData = [
                'period' => date("Y-m-01", strtotime($this->input->post('waReviewSurveyPeriod'))),
                'datetime' => $this->input->post('waReviewSurveyConversationDate'),
                'agent' => $this->input->post('waReviewSurveyAgent'),
                'ticket_number' => $this->input->post('waReviewSurveyTicket') ?: NULL,
                'system_code' => $this->input->post('waReviewSurveySystemCode') ?: NULL,
                'customer_phone' => $this->input->post('waReviewSurveyPhone'),
                'score_response' => $this->input->post('waReviewSurveyResponse'),
                'response_remark' => $this->input->post('waReviewSurveyResponseRemark') ?: NULL,
                'score_accuracy' => $this->input->post('waReviewSurveyAccuracy'),
                'accuracy_remark' => $this->input->post('waReviewSurveyAccuracyRemark') ?: NULL,
                'score_wording' => $this->input->post('waReviewSurveyWording'),
                'wording_remark' => $this->input->post('waReviewSurveyWordingRemark') ?: NULL,
                'remark' => $this->input->post('waReviewSurveyRemark') ?: NULL,
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s"),
            ];
            $fileUpload = $_FILES['waReviewSurveyExcelFile'];
            if ($fileUpload['size'] <= 0) {
                if ($this->voice->addNewWaReview($newData) > 0 ) {
                    $this->session->set_flashdata('message', 'Saved!|success|New WA review saved - Without chat raw!');
                    redirect('voice/wareviewform');
                };
            } else {
                $id = $this->voice->addNewWaReview($newData);
                if ($this->waReviewUploadExcelDetail($fileUpload, $id) > 0 ) {
                    $this->session->set_flashdata('message', 'Saved!|success|New WA review with chat raw saved!');
                    redirect('voice/wareviewform');
                }
            }
        }
    }

    public function waReviewCurrentMonthScore()
    {
        $result = $this->voice->getWaReviewByAgentByMonth($this->input->post('agent'), $this->input->post('period'));
        $maxScore = (int) $this->db->get_where('wa_review_score', ['level' => 'high'])->row_array()['score'];
        $data = [
            'averageScore' => number_format((($result['avg_score_response'] + $result['avg_score_accuracy'] + $result['avg_score_wording']) / (3 * $maxScore)) * 100, 1),
            'qty' => $result['qty']
        ];
        echo json_encode($data);
    }

    public function waReviewUploadExcelDetail($fileUpload, $id)
    {
        if (!empty($fileUpload['name'])) {
            // 1. Get file extension
            $extension = pathinfo($fileUpload['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
            } elseif ($extension == 'xlsx') {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            } else {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
            }

            // Supados PhpSpreadsheet kersa maca cell anu aya rumusna
            $reader->setReadDataOnly(false);

            // Load file excel ti tmp
            $spreadsheet = $reader->load($fileUpload['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            
            $highestRow = $sheet->getHighestRow();
            $dataUploaded = []; 
            
            date_default_timezone_set('Asia/Jakarta');

            // Looping ti baris 2 (sanggeus header) dugi ka baris pangtungtungna
            for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
                
                // Cokot nilai nilat tiap kolom
                $valA = $sheet->getCell('A' . $rowNum)->getCalculatedValue();
                $valB = $sheet->getCell('B' . $rowNum)->getCalculatedValue();
                $valH = $sheet->getCell('H' . $rowNum)->getCalculatedValue();
                $valJ = $sheet->getCell('J' . $rowNum)->getCalculatedValue();

                // ==================================================================
                // PROSES SARINGAN (VALIDASI) - DIEU LOGIKANA, MANG!
                // ==================================================================
                // Skip upami:
                // - Kolom A kosong, ATAWA boga nilai error bawaan Excel (kawas #VALUE!, #N/A, jsb)
                // - ATAWA Kolom H (sender) kosong/mung spasi hungkul
                // - ATAWA Kolom J (message) kosong/mung spasi hungkul
                if (
                    empty($valA) || strpos(strval($valA), '#') === 0 || 
                    trim($valH) === '' || 
                    trim($valJ) === ''
                ) {
                    continue; // Luncat, ulah diprosés, langsung beralih ka baris salajengna!
                }
                // ==================================================================

                // Prosés konversi tanggal Excel ka format DB MySQL
                // (Excel ngetang poe ti taun 1900, matak dikirangan 25569)
                $unix_date = ($valA - 25569) * 86400;
                $unix_date_wib = $unix_date - 25200; // Saluyukeun zona waktu WIB
                $dt = date("Y-m-d H:i:s", $unix_date_wib);

                // Masukkeun data anu tos LULUS sensor langsung kana array utama
                $dataUploaded[] = [
                    'review_id'    => $id,
                    'sender'       => trim($valH),
                    'datetime'     => $dt,
                    'message'      => trim($valJ),
                    'response_time'=> $valB
                ];
            }

            // Upami saatos disaring tétéla teu aya data anu lulus sensor, ulah nembak DB
            if (empty($dataUploaded)) {
                return 0; 
            }

            // Upload ka database via model sacara borongan (Batch)
            if ($this->voice->performUploadWaRaw($dataUploaded) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function wareviewdetailchat()
    {
        $reviewId = $this->input->post('id');
        echo json_encode($this->voice->getDetailWaById($reviewId));
    }
}
