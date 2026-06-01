<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blackbook extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Blackbook_model', 'blackbook');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Black Book';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        if (!$this->input->post()) {
            $startPeriod = date("Y-m-d", strtotime('-3 months'));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = date("Y-m-d", strtotime($this->input->post('selectSummaryBlackbookStart')));
            $endPeriod = date("Y-m-d", strtotime($this->input->post('selectSummaryBlackbookEnd')));
        }

        $items = $this->blackbook->getBlackbookItems();
        $data['blackbookItems'] = [];

        foreach ($items as $row) {
            if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'call' ) {
                $data['blackbookItems']['call'] [] = $row['type'];
            } else if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'cti' ) {
                $data['blackbookItems']['cti'] [] = $row['type'];
            } else if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'email' ) {
                $data['blackbookItems']['email'] [] = $row['type'];
            } else if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'notif' ) {
                $data['blackbookItems']['notif'] [] = $row['type'];
            } else if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'sharpid' ) {
                $data['blackbookItems']['sharpid'] [] = $row['type'];
            } else if (strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'wa' || strtolower(substr($row['type'], 0, strpos($row['type'], ' '))) == 'sms' ) {
                $data['blackbookItems']['wa'] [] = $row['type'];
            } else {
                $data['blackbookItems']['others'] [] = $row['type'];
            }
        }


        $data['summaryBlackbookByPeriod'] = $this->blackbook->getSummaryBlackbookByPeriod($startPeriod, $endPeriod);
        $data['summaryBlackbookByPeriodSubtotal'] = $this->blackbook->getSummaryBlackbookByPeriodSubtotal($startPeriod, $endPeriod)[0];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('blackbook/index', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-blackbook');
    }

    public function byagent()
    {        
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAgents'] = $this->blackbook->getAllActiveAgent();

        if($this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9 ){
            $data['title'] = "Black Note of Agent";
            $agent = $this->blackbook->getAllActiveAgent()[0]['user_id'];            
        } else{            
            $data['title'] = "Black Note of " . $this->session->userdata('user_id');
            $agent = $this->session->userdata('user_id');
        }

        if(!$this->input->post('blackbookByAgentDateStart') && !$this->input->post('blackbookByAgentDateEnd')) {
            $startPeriod = date("Y-m-d", strtotime("-6 months"));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = $this->input->post('blackbookByAgentDateStart');
            $endPeriod = $this->input->post('blackbookByAgentDateEnd');
            $agent = $this->input->post('blackbookByAgentSelectAgent');
        }

        $data['allBlackNotesByAgent'] = $this->blackbook->getBlackNotesByAgentByPeriod($agent, $startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('blackbook/byagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-blackbook');
    }

    public function detail()
    {
        check_access();
        $data['title'] = "Detail of Agent's Black Note";
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAgents'] = $this->blackbook->getAllActiveAgent();

        if(!$this->input->post('blackbookDetailDateStart') && !$this->input->post('blackbookDetailDateEnd')) {
            $startPeriod = date("Y-m-d", strtotime("-3 months"));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = $this->input->post('blackbookDetailDateStart');
            $endPeriod = $this->input->post('blackbookDetailDateEnd');
        }

        $data['allBlackNotes'] = $this->blackbook->getBlackNotesByPeriod($startPeriod, $endPeriod);
        $data['allBlackNotesType'] = $this->blackbook->getBlackNotesType();
        $data['allBlackNotesByAgent'] = $this->blackbook->getBlackNotesByPeriodByAgent($startPeriod, $endPeriod);
        $data['scoreLevels'] = $this->blackbook->getAllBlackbookScoringLevel();

        $this->form_validation->set_rules('blackbookAddAgent', 'Agent' ,'trim|required');
        $this->form_validation->set_rules('blackbookAddDate', 'Date' ,'trim|required');
        $this->form_validation->set_rules('blackbookAddSinType', 'Type of Sin' ,'trim|required');

        if( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('blackbook/detail', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'agent' => $this->input->post('blackbookAddAgent'),
                'date' => $this->input->post('blackbookAddDate'),
                'type' => $this->input->post('blackbookAddSinType'),
                'score' => $this->_getBlackbookItemScore($this->input->post('blackbookAddSinType')),
                'detail' => $this->input->post('blackbookAddDetail'),
                'remark' => $this->input->post('blackbookAddRemark'),
                'voice_link' => $this->input->post('blackbookAddVoicelink'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
                'last_modified_by' => null,
                'last_modified_at' => null
            ];

            if( $this->blackbook->addNewSingleNote($data) > 0 ){
                $this->session->set_flashdata('message', "Succesly saved!|success|New single agent's sin saved!");
                redirect('blackbook/detail');
            } else {
                $this->session->set_flashdata('message', "Faily saved!|error|Failed to saved new black note!");
                redirect('blackbook/detail');
            }
        }
    }

    private function _getBlackbookItemScore($blacknote)
    {
        $lvl = $this->db->get_where('blackbook_scoring', ['type' => $blacknote])->row_array()['level'];
        return $this->db->get_where('blackbook_scoring_level', ['level' => $lvl])->row_array()['score'];
    }

    public function delete()
    {
        if(!$this->input->post('blacknoteId')) {
            $blacknoteId = $this->uri->segment(3);
        } else {
            $blacknoteId = $this->input->post('blacknoteId');
        }

        if( $this->blackbook->deleteById($blacknoteId) > 0 ) {
            $this->session->set_flashdata('message', "Succesly deleted!|info|An agent's black note deleted!");
            redirect('blackbook/detail');
        } else {
            $this->session->set_flashdata('message', "Failed to delete!|error|Failed to deleted data'!");
            redirect('blackbook/detail');
        }
    }

    public function toExcelBlackbookDetail()
    {
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);

        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Blackbook Admin')->setLastModifiedBy('Blackbook Admin')->setTitle("Detail of Black Note")->setSubject("Blackbook")->setDescription("Detail of Blackbook")->setKeywords("Daily Blackbook");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL OF AGENT BLACK NOTE (BLACKBOOK)");
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Date"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Agent"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Jenis Dosa");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Detail");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Remark"); 
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Input by");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Input at"); 
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Voice link"); 

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

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        // $startPeriod = $this->input->post('absentDetailDateStart');
        // $endPeriod = $this->input->post('absentDetailDateEnd');
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $detailAbsenceData = $this->blackbook->toExcelDetailBlackbookData($startPeriod, $endPeriod);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($detailAbsenceData as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, date("d-M-Y", strtotime($data['date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['type']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['score']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['detail']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data['saved_by']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, date("d-M-y H:i", strtotime($data['saved_at'])));
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data['voice_link']);

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
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(30);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of Blackbook");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CCC Detail of Blackbook.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }

    public function getSummary()
    {
        //$startPeriod = $this->input->post('startPeriod');
        //$endPeriod = $this->input->post('endPeriod');
        $startPeriod = '2022-01-01';
        $endPeriod = '2022-04-01';
        $result = $this->blackbook->getSummaryBlackbookByCategory($startPeriod, $endPeriod)[0];        
        $json_data = [];        
        $json_data['labels'] = ['Wrong service area', 'Wrong notif', 'Unproper reply', 'No address', 'Messy address', 'Wrong info', 'Wrong system code', 'Others'];
        $json_data['blackbookSummary'] = [
            $result['wrong_service_area'], $result['unproper_notif'], $result['unproper_reply'], $result['no_address'], $result['messy_address'], $result['wrong_info'], $result['wrong_system_code'], $result['others']
        ];
        
        echo json_encode($json_data);
    }

    public function blackbookById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->blackbook->getBlackbookById($id));
    }

    public function editBlackbookById()
    {
        $data = [
            'id' => $this->input->post('blackbookAddId'),
            'agent' => $this->input->post('blackbookAddAgent'),
            'date' => $this->input->post('blackbookAddDate'),
            'type' => $this->input->post('blackbookAddSinType'),
            'detail' => $this->input->post('blackbookAddDetail'),
            'remark' => $this->input->post('blackbookAddRemark'),
            'voice_link' => $this->input->post('blackbookAddVoicelink'),
            'last_modified_by' => $this->session->userdata('user_id'),
            'last_modified_at' => date("Y-m-d H:i:s"),
        ];
        
        if ( $this->blackbook->editBlackbookById($data) > 0 ) {
            $this->session->set_flashdata('message', "Succesly updated!|success|Blackbook data successly updated!");
            redirect('blackbook/detail');
        }
    }

    public function transition()
    {
        check_access();
        $data['title'] = 'Transition of Blackbook';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('blackbook/transition', $data);
        $this->load->view('templates/footer');
    }

    public function scoring()
    {
        check_access();
        $data['title'] = 'Blackbook Scoring';
        $data['allBlackbookScoring'] = $this->blackbook->getAllBlackbookScoring();
        
        $levels = $this->blackbook->getAllBlackbookScoringLevel();
        $data['allBlackbookScoringLevel'] = [];
         foreach ($levels as $row) {
            $data['allBlackbookScoringLevel'][$row['level']] = $row['score'];
         }

        $this->form_validation->set_rules('blackbookScoringAddType', 'Type', 'required|trim');
        $this->form_validation->set_rules('blackbookScoringAddBahasa', 'Type', 'required|trim');
        $this->form_validation->set_rules('blackbookScoringAddLevel', 'Type', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('blackbook/scoring', $data);
            $this->load->view('templates/footer');
        } else {
            $newData = [
                'type' => $this->input->post('blackbookScoringAddType'),
                'bahasa' => $this->input->post('blackbookScoringAddBahasa'),
                'level' => $this->input->post('blackbookScoringAddLevel'),
                'is_active' => 1
            ];

            if ( $this->blackbook->addBlackbookScoringSingleRow($newData) > 0 ) {
                $this->session->set_flashdata('message', "Succesly updated!|success|New Blackbook scoring added!");
                redirect('blackbook/scoring');
            }
        }
    }

    public function updatescoring()
    {
        $rows = count($this->blackbook->getAllBlackbookScoring());
        $data = [];

        for ($x = 1; $x <= $rows; $x++) {
            $data[] = [
                'id' => $this->input->post()[$x . '_id'],
                'level' => $this->input->post()[$x . '_val'],
                'is_active' => $this->input->post()[$x . '_status'],
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
        }

        if ( $this->blackbook->updateBatchBlackbookScoring($data) > 0 ) {
            $this->session->set_flashdata('message', "Succesly updated!|success|Blackbook scoring updated!");
            redirect('blackbook/scoring');
        }
    }

    public function updatescoringlevel()
    {
        $data = [
            ['level' => 'low', 'score' => $this->input->post('updateBlackbookScoreLevelLow')],
            ['level' => 'medium', 'score' => $this->input->post('updateBlackbookScoreLevelMedium')],
            ['level' => 'high', 'score' => $this->input->post('updateBlackbookScoreLevelHigh')]
        ];
        // $data[] = ['low' => $this->input->post('updateBlackbookScoreLevelLow')];
        // $data[] = ['medium' => $this->input->post('updateBlackbookScoreLevelMedium')];
        // $data[] = ['high' => $this->input->post('updateBlackbookScoreLevelHigh')];
            
        echo "<pre>";
        var_dump($data);

        if ( $this->blackbook->updateBatchBlackbookScoreLevel($data) > 0 ) {
            $this->session->set_flashdata('message', "Succesly updated!|success|Blackbook score level updated!");
            redirect('blackbook/scoring');
        }
    }

    public function updatesinglerowscoring()
    {
        $this->form_validation->set_rules('blackbookScoringAddType', 'Type', 'required|trim');
        $this->form_validation->set_rules('blackbookScoringAddBahasa', 'Type', 'required|trim');
        $this->form_validation->set_rules('blackbookScoringAddLevel', 'Type', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "Error!|error|Check if some form field was empty!");
            redirect('blackbook/scoring');
        } else {
            $updateData = [
                'id' => $this->input->post('blackbookScoringAddId'),
                'type' => $this->input->post('blackbookScoringAddType'),
                'bahasa' => $this->input->post('blackbookScoringAddBahasa'),
                'level' => $this->input->post('blackbookScoringAddLevel'),
                'is_active' => $this->input->post('blackbookScoringAddBahasaIsactive'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s"),
            ];

            if ( $this->blackbook->updateBlackbookScoringSingleRow($updateData) > 0 ) {
                $this->session->set_flashdata('message', "Succesly updated!|success|New Blackbook scoring successly updated!");
                redirect('blackbook/scoring');
            }
        }
    }

    public function singlescoring()
    {
        echo json_encode($this->db->get_where('blackbook_scoring', ['id' => $this->input->post('id')])->row_array());
    }

    public function deletescoringsingle()
    {
        $id = $this->uri->segment(3);
        if ( $this->blackbook->deleteBlackbookScoringSingle($id) > 0 ) {
            $this->session->set_flashdata('message', "Deleted!|info|Single Blackbook Scoring deleted!");
            redirect('blackbook/scoring');
        }
    }

    public function repeatquestion()
    {
        backoffice_access();
        $data['title'] = 'Agent Repeat Question';

        $this->form_validation->set_rules('repeatQuestionAddAgent', 'Agent', 'required');
        $this->form_validation->set_rules('repeatQuestionAddDate', 'Date', 'required');
        $this->form_validation->set_rules('repeatQuestionAddCategory', 'Category', 'required');
        $this->form_validation->set_rules('repeatQuestionAddDetail', 'Detail', 'trim|required');

        if ($this->form_validation->run() == false) {
            if(!$this->input->post('repeatQuestionDateStart')) {
                $startPeriod = date("Y-m-d", strtotime("-3 months"));
                $endPeriod = date("Y-m-d");
              } else {
                $startPeriod = $this->input->post('repeatQuestionDateStart');
                $endPeriod = $this->input->post('repeatQuestionDateEnd');
              }
            $data['summaryByAgents'] = $this->blackbook->getSummaryRepeatQuestion($startPeriod, $endPeriod);
            $data['summaryByAgentsSubtotal'] = $this->blackbook->getSummaryRepeatQuestionSubtotal($startPeriod, $endPeriod);
            $data['detailList'] = $this->blackbook->getDetailsRepeatQuestion($startPeriod, $endPeriod);
            $data['allAgents'] = $this->blackbook->getAllActiveAgent();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('blackbook/repeat-question', $data);
            $this->load->view('templates/footer');
        } else {
            $newData = [
                'agent' => $this->input->post('repeatQuestionAddAgent'),
                'date' => $this->input->post('repeatQuestionAddDate'),
                'category' => $this->input->post('repeatQuestionAddCategory'),
                'detail' => $this->input->post('repeatQuestionAddDetail'),
                'remark' => $this->input->post('repeatQuestionAddRemark'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s")
            ];

            if ( $this->blackbook->addNewRepeatQuestion($newData) > 0 ) {
                $this->session->set_flashdata('message', "Succesly saved!|success|Repeat Question data saved!");
                redirect('blackbook/repeatquestion');
            }
        }
    }

    public function editrepeatquestion()
    {
        $updateData = [
            'id' => $this->input->post('repeatQuestionAddId'),
            'agent' => $this->input->post('repeatQuestionAddAgent'),
            'date' => $this->input->post('repeatQuestionAddDate'),
            'category' => $this->input->post('repeatQuestionAddCategory'),
            'detail' => $this->input->post('repeatQuestionAddDetail'),
            'remark' => $this->input->post('repeatQuestionAddRemark'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        if ( $this->blackbook->editRepeatQuestion($updateData) > 0 ) {
            $this->session->set_flashdata('message', "Succes!|success|Repeat Question data updated!");
            redirect('blackbook/repeatquestion');
        }
    }

    public function deleterepeatquestion()
    {
        if(!$this->input->post('repeatQuestionAddId')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('repeatQuestionAddId');
        }

        if ( $this->blackbook->deleterepeatquestion($id) > 0 ) {
            $this->session->set_flashdata('message', "Succes!|info|You've deleted Repeat Question data!");
            redirect('blackbook/repeatquestion');
        }
    }

    public function repeatquestionById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->blackbook->getRepeatQuestionById($id));
    }

    public function toExcelRepeatQuestionDetail()
    {
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);

        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Blackbook Admin')->setLastModifiedBy('Blackbook Admin')->setTitle("Detail of Black Note")->setSubject("Blackbook")->setDescription("Detail of Blackbook")->setKeywords("Daily Blackbook");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL OF AGENT REPEAT QUESTION");
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Period : ' . date("d M Y", strtotime($startPeriod)) . ' to ' . date("d M Y", strtotime($endPeriod)));
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11); // Set font size 15 untuk kolom A1

        // Buat header tabel nya pada baris ke 4
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B4', "Date"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C4', "Agent"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D4', "Category");
        $excel->setActiveSheetIndex(0)->setCellValue('E4', "Detail");
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Remark"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Saved by");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Saved at");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        // $startPeriod = $this->input->post('absentDetailDateStart');
        // $endPeriod = $this->input->post('absentDetailDateEnd');
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $detailAbsenceData = $this->blackbook->getDetailsRepeatQuestion($startPeriod, $endPeriod);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 5
        foreach ($detailAbsenceData as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, date("d-M-Y", strtotime($data['date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['category']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['detail']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['remark']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['saved_by']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, date("d-M-y H:i", strtotime($data['saved_at'])));

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
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of Repeat Question");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CCC Detail of Agents Repeat Question.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }

    public function dailymonitoring()
    {
        $data['title'] = 'CASHLES Info Monitoring';

        $data['daysDuration'] = $this->db->get_where('general_setting', ['item' => 'daily_agent_info_monitoring_default_duration'])->row_array()['value'];
        $data['id'] = $this->db->get_where('general_setting', ['item' => 'daily_agent_info_monitoring_default_duration'])->row_array()['id'];
        if (!$this->input->post('agentMonitoringDateStart')) {
            $data['startPeriod'] = date("Y-m-d", strtotime("-" . $data['daysDuration'] . " days"));
            $data['endPeriod'] = date("Y-m-d");
        } else {
            $data['startPeriod'] = date("Y-m-d", strtotime($this->input->post('agentMonitoringDateStart')));
            $data['endPeriod'] = date("Y-m-d", strtotime($this->input->post('agentMonitoringDateEnd')));
        }

        $data['detailMonitoring'] = $this->blackbook->getDetailMonitoring($data['startPeriod'], $data['endPeriod']);
        $data['summaryMonitoring'] = $this->blackbook->getSummaryMonitoring($data['startPeriod'], $data['endPeriod']);
        $data['summaryMonitoringTotal'] = $this->blackbook->getSummaryMonitoringTotal($data['startPeriod'], $data['endPeriod']);
        
        $data['agents'] = $this->blackbook->getAllActiveAgent();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('blackbook/daily-monitoring', $data);
        $this->load->view('templates/footer');
    }

    public function addDailyMonitoring()
    {
        $this->form_validation->set_rules('cashlessInfoMonitoringDate', 'Date', 'required');
        $this->form_validation->set_rules('cashlessInfoMonitoringSource', 'Data Source', 'required');
        $this->form_validation->set_rules('cashlessInfoMonitoringAgent', 'Agent', 'required');
        $this->form_validation->set_rules('cashlessInfoMonitoringCustomerData', 'Customer data', 'trim|required');
        $this->form_validation->set_rules('cashlessInfoMonitoringAgentDone', 'Is Done', 'required');

        if ($this->form_validation->run() == false) {
            redirect('blackbook/dailymonitoring');
        } else {
            $newData = [
                    'info_type' => 'cashless info',
                    'source' => $this->input->post('cashlessInfoMonitoringSource'),
                    'agent' => $this->input->post('cashlessInfoMonitoringAgent'),
                    'date' => $this->input->post('cashlessInfoMonitoringDate'),
                    'customer_data' => $this->input->post('cashlessInfoMonitoringCustomerData'),
                    'done_by_agent' => $this->input->post('cashlessInfoMonitoringAgentDone'),
                    'saved_by' => $this->session->userdata('user_id'),
                    'saved_at' => date("Y-m-d H:i:s")
                ];
            // check if phone, date, agent are existing in database
            if ($this->db->get_where('daily_agent_info_monitoring', ['customer_data' => $newData['customer_data'], 'date' => $newData['date'], 'agent' => $newData['agent'], ])->num_rows() > 0) {
                $this->session->set_flashdata('message', "Data Exist!|error|Phone, Date, and Agent existing on database!");
                redirect('blackbook/dailymonitoring');
            } else {
                if ( $this->blackbook->addNewAgentCashlessMonitoring($newData) > 0 ) {
                    // parse to Blackbook if no info cashless payment
                    if ($newData['done_by_agent'] == 0) {
                        $parseToBlackbook = [
                            'agent' => $newData['agent'],
                            'date' => $newData['date'],
                            'type' => 'No cashless info',
                            'score' => $this->_getBlackbookItemScore('No cashless info'),
                            'detail' => $newData['source'] . ' ' . $newData['customer_data'],
                            'remark' => null,
                            'voice_link' => null,
                            'saved_by' => $this->session->userdata('user_id'),
                            'saved_at' => date("Y-m-d h:i:s"),
                            'last_modified_by' => null,
                            'last_modified_at' => null
                        ];
                        $this->blackbook->addNewSingleNote($parseToBlackbook);
                    }

                    $this->session->set_flashdata('message', "Succes!|success|Monitoring data saved!");
                    redirect('blackbook/dailymonitoring');
                } else {
                    $this->session->set_flashdata('message', "Failed!|error|Mungkin ada kolom yang belum terisi!");
                    redirect('blackbook/dailymonitoring');
                }
            }
        }
    }

    public function settingDailyMonitoring()
    {
        $this->form_validation->set_rules('cashlessInfoMonitoringSettingValue', 'Data Source', 'required|numeric');

        if ($this->form_validation->run() == false) {
            redirect('blackbook/dailymonitoring');
        } else {
            $setting = [
                'id' => $this->input->post('cashlessInfoMonitoringSettingId'),
                'value' => $this->input->post('cashlessInfoMonitoringSettingValue'),
            ];

            if ( $this->blackbook->updateGeneralSetting($setting) > 0 ) {
                $this->session->set_flashdata('message', "Succes!|success|Data duration updated!");
                redirect('blackbook/dailymonitoring');
            } else {
                $this->session->set_flashdata('message', "Failed!|error|Faied to update data setting!");
                redirect('blackbook/dailymonitoring');
            }
        }
    }

    public function deleteAgentCashlessMonitoring()
    {
        if(!$this->input->post('dailyMonitoringId')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('dailyMonitoringId');
        }

        if( $this->blackbook->deleteAgentCashlessMonitoring($id) > 0 ) {
            $this->session->set_flashdata('message', "Deleted!|info|Data successly deleted!");
            redirect('blackbook/dailymonitoring');
        } else {
            $this->session->set_flashdata('message', "Failed!|error|Failed to deleted data'!");
            redirect('blackbook/dailymonitoring');
        }
    }

    public function agentCashlessMonitoringById()
    {
        echo json_encode($this->db->get_where('daily_agent_info_monitoring', ['id' => $this->input->post('id')])->row_array());
    }

    public function editDailyMonitoring()
    {
        $data['title'] = 'Edit Data Cashless Info Monitoring';
           
        $this->form_validation->set_rules('editCashlessInfoMonitoringDate', 'Date', 'required');
        $this->form_validation->set_rules('editCashlessInfoMonitoringSource', 'Data Source', 'required');
        $this->form_validation->set_rules('editCashlessInfoMonitoringAgent', 'Agent', 'required');
        $this->form_validation->set_rules('editCashlessInfoMonitoringCustomerData', 'Customer data', 'trim|required');
        $this->form_validation->set_rules('editCashlessInfoMonitoringAgentDone', 'Is Done', 'required');
        
        $this->input->post('editCashlessInfoMonitoringId') ? $dataId = $this->input->post('editCashlessInfoMonitoringId') : $dataId = $this->uri->segment(3);
        $data['singleData'] = $this->db->get_where('daily_agent_info_monitoring', ['id' => $dataId])->row_array();
        $data['agents'] = $this->blackbook->getAllActiveAgent();

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('blackbook/edit-daily-monitoring', $data);
            $this->load->view('templates/footer');
        } else {
            $updateData = [
                'id' => $this->input->post('editCashlessInfoMonitoringId'),
                'source' => $this->input->post('editCashlessInfoMonitoringSource'),
                'agent' => $this->input->post('editCashlessInfoMonitoringAgent'),
                'date' => $this->input->post('editCashlessInfoMonitoringDate'),
                'customer_data' => $this->input->post('editCashlessInfoMonitoringCustomerData'),
                'done_by_agent' => $this->input->post('editCashlessInfoMonitoringAgentDone'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            // execute update data with flash message
            if ( $this->blackbook->editNewAgentCashlessMonitoring($updateData) > 0 ) {
                $this->session->set_flashdata('message', "Succes!|success|Monitoring data saved!");
                redirect('blackbook/dailymonitoring');
            } else {
                $this->session->set_flashdata('message', "Failed!|error|Mungkin ada kolom yang belum terisi!");
                redirect('blackbook/dailymonitoring');
            }
        }
    }

}