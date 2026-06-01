<?php
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Survey_model', 'survey');
        is_logged_in();        
    }

    public function index()
    {
        $data['title'] = 'Tolong isi ya...';
        $data['isdonesurvey'] = $this->db->get_where('survey', ['agent' => $this->session->userdata('user_id')])->num_rows();

        $this->form_validation->set_rules('surveyQ1', 'Kuesioner 1', 'required');
        $this->form_validation->set_rules('surveyQ2', 'Kuesioner 2', 'required');
        $this->form_validation->set_rules('surveyQ3', 'Kuesioner 3', 'required');
        $this->form_validation->set_rules('surveyQ4', 'Kuesioner 4', 'required|trim');
        $this->form_validation->set_rules('surveyQ5', 'Kuesioner 1', 'required|trim');

        if ( $this->form_validation->run() ==  false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('survey/index', $data);
            $this->load->view('templates/footer', $data);            
        } else {
            $question = [
                'Penting tidak mengetahui pencapaian kerja',
                'Pencapaian kerja disampaikan setiap...',
                'Jika pencapaian kerja di bulan sebelumnya kurang',
                'Apakah aplikasi Logsheet bermanfaat?',
                'Fitur yang diharapkan ada di aplikasi Logsheet'
            ];

            $answer = [
                $this->input->post('surveyQ1'),
                $this->input->post('surveyQ2'),
                $this->input->post('surveyQ3'),
                $this->input->post('surveyQ4'),
                $this->input->post('surveyQ5')
            ];
            
            $surveyData = [];
            for($i = 0; $i < 5; $i++){
                $surveyData[$i] = [
                    'agent' => $this->session->userdata('user_id'),
                    'questioner' => $question[$i],
                    'jawaban' => $answer[$i],
                    'datetime' => date("Y-m-d h:i:s")
                ];
            };

            if($this->_submitSurvey($surveyData) > 0 ){
                $this->session->set_flashdata('message', 'Berhasil disimpan!|info|Terima kasih sudah bersedia mengisi survey!');
                redirect('dashboard');
            }
        }
    }

    private function _submitSurvey($data)
    {
        $this->db->insert_batch('survey', $data);
        return $this->db->affected_rows();
    }

    public function collectEmail()
    {
        $data['title'] = 'Collect Email';
        if($this->_getPersonalEmail($this->session->userdata('user_id')) == '' || $this->_getPersonalEmail($this->session->userdata('user_id')) == null) {
            $data['isfilledemail'] = 0;
        } else {
            $data['isfilledemail'] = 1;
        }

        $this->form_validation->set_rules('collectEmailPersonal', 'Alamat email', 'required|trim');
        if ( $this->form_validation->run() == false ) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('survey/collect-email', $data);
            $this->load->view('templates/footer', $data);                        
        } else {
            if($this->_updatePersonal($this->input->post('collectEmailPersonal'), $this->session->userdata('user_id')) > 0 ) {
                $this->session->set_flashdata('message', 'Berhasil disimpan!|info|Terima kasih sudah bersedia mengisi data!');
                redirect('dashboard');
            }
        }
	}

	public function wfhorwfo()
	{
		$data['title'] = 'Tolong isi ya...';
        $data['isdonesurvey'] = $this->db->get_where('survey_wfhwfo', ['agent' => $this->session->userdata('user_id')])->num_rows();

        $this->form_validation->set_rules('surveyWfhQ1', 'Refer WFH/WFO', 'required');
        $this->form_validation->set_rules('surveyWfhQ2', 'Alasan', 'required');

        if ( $this->form_validation->run() ==  false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('survey/survey-wfhwfo', $data);
            $this->load->view('templates/footer', $data);            
        } else {
            $question = [
                'Ada jadwal WFH, ikut WFH atau pilih WFO',
                'Alasannya ...'
            ];
            var_dump($_POST);
            $answer = [
                $this->input->post('surveyWfhQ1'),
                $this->input->post('surveyWfhQ2'),                
            ];
            
            $surveyData = [
                'agent' => $this->session->userdata('user_id'),
                'answer' => $this->input->post('surveyWfhQ1'),
                'reason' => $this->input->post('surveyWfhQ2'),
                'saved_at' => date("Y-m-d h:i:s")
            ];         

            if($this->_submitSurveyWfh($surveyData) > 0 ){
                $this->session->set_flashdata('message', 'Berhasil disimpan!|info|Terima kasih sudah bersedia mengisi survey!');
                redirect('dashboard');
            }
        }
	}

	private function _submitSurveyWfh($data)
    {
    	$this->db->insert('survey_wfhwfo', $data);
        return $this->db->affected_rows();
    }

    private function _getPersonalEmail($userid)
    {
        $this->db->where('user_id', $userid);
        return $this->db->get('user')->row_array()['email_personal'];
    }

    private function _updatePersonal($personalEmail, $userid)
    {
        $this->db->where('user_id', $userid);
        $this->db->set('email_personal', $personalEmail);
        $this->db->update('user');
        return $this->db->affected_rows();
    }

    public function skapefeedback()
    {
        $data['title'] = 'Feedback NEW SKAPE';

        $leaderAccess = ['1', '2', '5', '6', '9'];
        $data['feedbackByAgent'] = $this->survey->getFeedbackByAgent($this->session->userdata('user_id'), $this->session->userdata('role_access'), $leaderAccess);
        $data['isdonesurvey'] = $this->db->get_where('survey_newskape_feedback', ['agent' => $this->session->userdata('user_id')])->num_rows();

        $this->form_validation->set_rules('feedbackNewskapeCategory', 'Kategori', 'required');
        $this->form_validation->set_rules('feedbackNewskapeDetail', 'Detail', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('survey/skape-feedback', $data);
            $this->load->view('templates/footer', $data);     
        } else {
            $data = [
                'category' => $this->input->post('feedbackNewskapeCategory'),
                'detail' => $this->input->post('feedbackNewskapeDetail'),
                'agent' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s")
            ];
            if ($this->survey->insertFeedback($data) > 0) {
                $this->session->set_flashdata('message', 'Berhasil disimpan!|success|Feedback telah disimpan!');
                redirect('survey/skapefeedback');
            }
        }
    }

    public function deleteSkapeFeedback()
    {
        $id = $this->uri->segment(3);
        if ($this->survey->deleteFeedback($id) > 0 ) {
            $this->session->set_flashdata('message', 'Berhasil!|info|Feedback telah dihapus!');
            redirect('survey/skapefeedback');
        }
    }

    public function feedbackbyid()
    {
        $id = $this->input->post('id');
        echo json_encode($this->survey->getFeedbackById($id));
    }

    public function editfeedback()
    {
        $data = [
            'id' => $this->input->post('feedbackNewskapeId'),
            'category' => $this->input->post('feedbackNewskapeCategory'),
            'detail' => $this->input->post('feedbackNewskapeDetail'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d h:i:s"),
        ];
        var_dump($data);
        if ($this->survey->updateFeedback($data) > 0 ) {
            $this->session->set_flashdata('message', 'Berhasil!|success|Feedback telah berhasil diperbaharui!');
            redirect('survey/skapefeedback');
        }
    }

    public function toExcelFeedback()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('New SKAPE commment')->setLastModifiedBy('New SKAPE commment')->setTitle("Detail of New SKAPE Feedback")->setSubject("New SKAPE")->setDescription("Detail of New SKAPE feedback")->setKeywords("New SKAPE Feedback");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "NEW SKAPE FEEDBACK"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Agent"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Tanggal"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Kategori");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Detail feedback/masukan");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "updated_by"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "updated_at");        

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        
        $detailFeedback = $this->db->get('survey_newskape_feedback')->result_array();
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($detailFeedback as $row) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $row['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, date("d-M-Y", strtotime($row['date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row['category']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row['detail']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row['updated_by']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row['updated_at']);
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(95);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);        


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of New SKAPE Feedback");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Detail of New SKAPE Feedback"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }

}