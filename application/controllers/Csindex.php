<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Csindex extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Csindex_model', 'csindex');
        $this->_autoDeleteLastSixMonthsData();
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'CS Index';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $inputPeriod = $this->input->post('selectSurveyPeriod');
        if (!$inputPeriod) {
            $data['surveyData'] = $this->csindex->getLatestPeriodSurveyData();
        } else {
            $data['surveyData'] = $this->csindex->getSurveyDataByPeriod($inputPeriod);
        }
        $data['period'] = $this->csindex->getAllPeriod();
        $data['latestPeriod'] = $this->csindex->getAllPeriod()[0]['period'];

        $this->form_validation->set_rules('doSurveyQ1', 'Questioner 1', 'required');
        $this->form_validation->set_rules('doSurveyQ2', 'Questioner 2', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('csindex/index', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-csindex');
        } else {
            $this->submitSurvey();
        }
    }

    public function surveyDataByPeriod()
    {
        $period = $this->input->post('period');
        echo json_encode($this->csindex->getSurveyDataByPeriod($period));
    }

    public function deleteSurveyById()
    {
        // $id = $this->input->post('surveyId');
        if ($this->input->post('surveyId')) {
            $id = $this->input->post('surveyId');
        } else {
            $id = $this->uri->segment(3);
        }
        if ($this->csindex->deleteSurvey($id) > 0) {
            $this->session->set_flashdata('message', 'CS Index|info|Data successly deleted!');
        } else {
            $this->session->set_flashdata('message', 'CS Index|error|Failed to delete data!');
        }
        redirect('csindex/index');
    }

    public function submitSurvey()
    {
        $data = [
            'id' => $this->input->post('surveyId'),
            'questioner_1' => $this->input->post('doSurveyQ1'),
            'questioner_2' => $this->input->post('doSurveyQ2'),
            'is_done' => 1,
            'survey_datetime' => date("Y-m-d h:i:s"),
            'survey_by' => $this->session->userdata('user_id')
        ];

        if ($this->csindex->submitSurvey($data) > 0) {
            $this->session->set_flashdata('message', 'CS Index|success|Survey result successly saved!');
        } else {
            $this->session->set_flashdata('message', 'CS Index|error|Failed to save survey result!');
        }
        redirect('csindex/index');
    }

    public function getSurveyById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->csindex->getSurveyDataById($id));
    }

    public function resultbyagent()
    {        
        $data['title'] = 'Result by Agent';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['agents'] = $this->csindex->getAllAgents();
        $data['period'] = $this->csindex->getAllPeriod();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('csindex/resultbyagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-csindex');
    }

    public function resultByAgentById()
    {
        $agent = $this->input->post('agent');
        $startPeriod = $this->input->post('startPeriod');
        $endPeriod = $this->input->post('endPeriod');
        echo json_encode($this->csindex->getResultByAgentByPeriod($agent, $startPeriod, $endPeriod));        
    }

    public function summary()
    {
        check_access();
        $data['title'] = 'Summary of CS Index Survey';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        
        if (!$this->input->post('csindexSummaryStartPeriod') || !$this->input->post('csindexSummaryEndPeriod')) {
            $startPeriod = date("Y-m-01", strtotime("-12 Months"));
            $endPeriod = $this->csindex->getAllPeriod()[0]['period'];
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('csindexSummaryStartPeriod')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('csindexSummaryEndPeriod')));
        }
        $data['startPeriod'] = $startPeriod;
        $data['endPeriod'] = $endPeriod;
        $data['summary'] = $this->csindex->getSummary($startPeriod, $endPeriod, 'DESC');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('csindex/summary', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-csindex');
    }

    public function getSummary()
    {           
        $startPeriod = date("Y-m-01", strtotime($this->uri->segment(3)));
        $endPeriod = date("Y-m-01", strtotime($this->uri->segment(4)));
        $result = $this->csindex->getSummary($startPeriod, $endPeriod, 'ASC');
        $json_data = [];
        foreach($result as $row){
            $json_data['labels'][] = date("M-y", strtotime($row['period']));
            $json_data['csindex'][] = round($row['total_result'] * 100, 1);
        }
        echo json_encode($json_data);
    }

    public function resultdetail()
    {
        check_access();
        $data['title'] = 'Result Detail';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['period'] = $this->csindex->getAllPeriod();
        $data['latestPeriod'] = $this->csindex->getAllPeriod()[0]['period'];
        $data['latestPeriodSurveyResult'] = $this->csindex->getSurveyResultByPeriod($data['latestPeriod']);
        $data['csareaResultByPeriod'] = $this->csindex->getCsareaResultByPeriod($data['latestPeriod']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('csindex/detail', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-csindex');
    }

    public function getResultDetailByPeriod()
    {
        $period = $this->input->post('period');
        echo json_encode($this->csindex->getSurveyResultByPeriod($period));
    }

    public function getCsareaResultByPeriod()
    {
        $period = $this->input->post('period');
        echo json_encode($this->csindex->getCsareaResultByPeriod($period));
    }

    public function manage()
    {
        check_access();
        $data['title'] = 'Manage Survey Data';
        $data['allData'] = $this->csindex->getAllListedData();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('csindex/manage_data', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-csindex');
    }

    public function deleteSurveyByPeriod()
    {
        $period = $this->uri->segment(3);        
        if($this->csindex->deleteSurveyByPeriod($period) > 0){
            $this->session->set_flashdata('message', 'Data Deletion|success|Survey data successly deleted!');
        } else {
            $this->session->set_flashdata('message', 'Data Deletion|error|Failed to delete data!');
        }
        redirect('csindex/manage');
    }

    public function transition()
    {
        check_access();
        $data['title'] = 'Transition of CS Index';
        $latestPeriod = $this->csindex->getAllPeriod()[0]['period'];
        $data['latestPeriod'] = $this->csindex->getAllPeriod()[0]['period'];
        $data['csindexTransition'] = $this->csindex->getCsindexTransition(date("Y-m-01", strtotime("-7 Months")), $latestPeriod);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('csindex/transition', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-csindex');
    }

    public function getTransition()
    {
        $startPeriod = date("Y-m-01", strtotime($this->input->post('startPeriod')));
        $endPeriod = date("Y-m-01", strtotime($this->input->post('endPeriod')));
        echo json_encode($this->csindex->getCsindexTransition($startPeriod, $endPeriod));
    }

    private function _autoDeleteLastSixMonthsData()
    {
        $this->csindex->autoDeleteLastSixMonthsData(date("Y-m-01", strtotime('-6 months')));
    }

    public function uploadCsIndexData()
    {
        $this->_uploadDataByExcel();
    }

    public function uploadCsIndexSurveyResult()
    {
        $this->_uploadCsindexSurveyResult();
    }

    public function downloadCsIndexByPeriod()
    {
        $this->_toExcelSurveyPeriod();
    }


    // Upload survey data from excel
    private function _uploadDataByExcel()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        $config['upload_path'] = './csindexfile';
        $config['allowed_types'] = 'xlsx|xls|csv|ods';
        $config['max_size'] = '10496';
        $config['overwrite'] = true;
        // $config['file_name'] = 'CSIndex_' . date("Y-m", strtotime($this->input->post('addSurveyPeriod')));

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('addSurveyData')) {
            var_dump($this->upload->error_msg);
        } else {
            $data_upload = $this->upload->data();
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load('csindexfile/' . $data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true, true, true, true, true, true, true, true);

            // var_dump($sheet);
            // echo "<br>";

            $data = array();
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($row['A'] == '' || $row['A'] == NULL) {
                    continue;
                } else {
                    if ($numrow > 1) {
                        array_push($data, array(
                            'period' => date("Y-m-01", strtotime($this->input->post('addSurveyPeriod'))),
                            'data_datetime' => $row['A'],
                            'customer_name' => $row['B'],
                            'customer_phone' => $row['C'],
                            'customer_city' => $row['D'],
                            'system_code' => $row['E'],
                            'data_model' => $row['F'],
                            'i_detail' => $row['G'],
                            'action_detail' => $row['H'],
                            'data_remark' => $row['I'],
                            'agent' => $row['J'],
                            'questioner_1' => '',
                            'questioner_2' => '',
                            'is_done' => 0,
                            'survey_datetime' => '',
                            'survey_by' => ''
                        ));
                    }
                }
                $numrow++;
            }

            $this->db->insert_batch('csindex_survey', $data);
            //delete file from server
            unlink(realpath('csindexfile/' . $data_upload['file_name']));

            //upload success
            $this->session->set_flashdata('message', 'CS Index Data|success|Successly uploaded...!');
            //redirect halaman
            redirect('csindex/index');
        }
    }

    private function _uploadCsindexSurveyResult()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        $config['upload_path'] = './csindexfile';
        $config['allowed_types'] = 'xlsx|xls|csv|ods';
        $config['max_size'] = '10496';
        $config['overwrite'] = true;
        // $config['file_name'] = 'CSIndex_' . date("Y-m", strtotime($this->input->post('addSurveyPeriod')));

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('csindexAddSurveyResult')) {
            var_dump($this->upload->error_msg);
        } else {
            $data_upload = $this->upload->data();
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load('csindexfile/' . $data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true, true, true, true, true, true, true, true, true, true, true, true);

            // var_dump($sheet);
            // echo "<br>";

            $data = [];
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($numrow > 1) {
                    if ($row['B'] == '' || $row['B'] == NULL) {
                        continue;
                    } else {
                        array_push($data, [
                            'period' => date("Y-m-01", strtotime($this->input->post('csindexAddResultPeriod'))),
                            'data_datetime' => $row['A'],
                            'customer_name' => $row['B'],
                            'customer_phone' => $row['C'],
                            'customer_city' => $row['D'],
                            'system_code' => $row['H'],
                            'data_model' => $row['K'],
                            'i_detail' => $row['L'],
                            'action_detail' => $row['M'],
                            'data_remark' => $row['N'],
                            'agent' => $row['E'],
                            'questioner_1' => $this->_alphabetToValue($row['F']),
                            'questioner_2' => $this->_alphabetToValue($row['G']),
                            'is_done' => 1,
                            'survey_datetime' => 'n/a',
                            'survey_by' => 'n/a'
                        ]);
                    }
                }
                $numrow++;
            }

            // check if the survey period existing in database
            $checkPeriod = $this->db->get_where('csindex_survey', ['period' => date("Y-m-01", strtotime($this->input->post('csindexAddResultPeriod')))])->row_array()['period'];

            if ($checkPeriod) {
                $this->session->set_flashdata('message', 'Upload failed!|error|Survey data was existing, please check them!');
                //redirect halaman
                redirect('csindex/manage');
            } else {

                $this->db->insert_batch('csindex_survey', $data);
                //delete file from server
                unlink(realpath('csindexfile/' . $data_upload['file_name']));
                //upload success
                $this->session->set_flashdata('message', 'Survey Result|success|Successly uploaded...!');
                redirect('csindex/resultdetail');
            }
        }
    }

    private function _csScoreToAlphabet($score)
    {
        if (is_numeric($score)) {
            return $score;
        } else {
            if ($score == 3) {
                return "a";
            } else if ($score == 2) {
                return "b";
            } else if ($score == 1) {
                return "c";
            } else if ($score == -1) {
                return "d";
            } else {
                return "e";
            }
        }
    }

    private function _alphabetToValue($data)
    {
        if (is_numeric($data)) {
            if ($data == 5) {
                return 3;
            } else if ($data == 4) {
                return 2;
            } else if ($data == 3) {
                return 1;
            } else if ($data == 2) {
                return -1;
            } else {
                return -2;
            }
        } else {
            if (strtolower($data) == 'a') {
                return 3;
            } else if (strtolower($data) == 'b') {
                return 2;
            } else if (strtolower($data) == 'c') {
                return 1;
            } else if (strtolower($data) == 'd') {
                return -1;
            } else {
                return -2;
            }
        }
    }

    private function _totalCsScoreToLevel($ttlScore)
    {
        if ($ttlScore == 6) {
            return "E";
        } else if ($ttlScore == 4 || $ttlScore == 5) {
            return "S";
        } else if ($ttlScore == 2 || $ttlScore == 3) {
            return "I";
        } else {
            return "U";
        }
    }

    private function _toExcelSurveyPeriod()
    {
        if (!$this->input->post('period')) {
            $period = $this->uri->segment(3);
        } else {
            $period = $this->input->post('period');
        }

        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Elearning Administrator')->setLastModifiedBy('Elearning Administrator')->setTitle("Result of Elearning")->setSubject("Elearning")->setDescription("Result of Elearning by Period")->setKeywords("Result of Elearning");

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
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis 
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "RESULT OF CS INDEX SURVEY " . strtoupper(date("M-Y", strtotime($period)))); // Set kolom A1 dengan tulisan "RESULT OF CS INDEX SURVEY"
        $excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Agent"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Q1"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Q2"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Score Q1"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Score Q2"); // Set kolom F3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Total Score");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Level");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya        
        $survey_result = $this->csindex->getResultByPeriod($period);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($survey_result as $sr) { // Lakukan looping pada variabel siswa            
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $sr['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $this->_csScoreToAlphabet($sr['questioner_1']));
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $this->_csScoreToAlphabet($sr['questioner_2']));
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $sr['questioner_1']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $sr['questioner_2']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $sr['questioner_1'] + $sr['questioner_2']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $this->_totalCsScoreToLevel($sr['questioner_1'] + $sr['questioner_2']));

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // Set width kolom F

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        $excel->getActiveSheet(0)->setTitle("CS Index Period");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CS Index Survey Result By Period.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
