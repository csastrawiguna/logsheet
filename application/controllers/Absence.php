<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absence extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Absence_model', 'absence');
        is_logged_in();
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Absent Summary';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        if (!$this->input->post('absenceSummaryDateStart') || !$this->input->post('absenceSummaryDateEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-6 months"));
            $endPeriod = date("Y-m-d");
        } else {
            $startPeriod = $this->input->post('absenceSummaryDateStart');
            $endPeriod = $this->input->post('absenceSummaryDateEnd');
        }
        
        $data['absenceSummaryByPeriod'] = $this->absence->getSummaryByPeriod($startPeriod, $endPeriod);
        $data['working_day'] = $this->absence->getTotalWorkingDays($startPeriod, $endPeriod)['working_day'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absence/index', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-absence');
    }

    public function detail()
    {
        check_access();
        $data['title'] = 'Absent Detail Data';
        $data['user'] = $this->session->userdata('user_id');

        if(!$this->input->post('absentDetailDateStart') && !$this->input->post('absentDetailDateEnd')){
            $startPeriod = date("Y-m-d", strtotime("-6 months"));
            $endPeriod = date("Y-m-d");
        } else{
            $startPeriod = $this->input->post('absentDetailDateStart');
            $endPeriod = $this->input->post('absentDetailDateEnd');
        }

        $data['allAgents'] = $this->absence->getAllActiveAgent();
        $data['allAbsentData'] = $this->absence->getAllAbsentData($startPeriod, $endPeriod);

        $this->form_validation->set_rules('absentAddDate', 'Absent date', 'required');
        $this->form_validation->set_rules('absentAddAgent', 'Agent', 'required');
        $this->form_validation->set_rules('absentAddPermitType', 'Permit type', 'required');        

        if($this->form_validation->run() == false){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('absence/detail', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-absence');
        } else{
            $data = [
                'absent_date' => $this->input->post('absentAddDate'),
                'cti_id' => $this->input->post('absentAddAgent'),
                'permit_type' => $this->input->post('absentAddPermitType'),
                'permit_reason' => $this->input->post('absentAddReason'),
                'permit_remark' => $this->input->post('absentAddRemark'),
                'input_by' => $this->session->userdata('user_id'),
                'input_at' => date("Y-m-d h:i:s")
            ];
            if($this->absence->checkExistingAbsence($this->input->post('absentAddAgent'), $this->input->post('absentAddDate')) > 0 ){
                $this->session->set_flashdata('message', 'Data Existing|error|Absent data already exist!');
                redirect('absence/detail');
            } else {
                if($this->absence->addNewAbsentData($data) > 0 ){
                    $this->session->set_flashdata('message', 'Absent data|success|New absent data successly saved!');
                    redirect('absence/detail');
                }
            }
        }
    }

    public function absentById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('daily_absence', ['absent_id' => $id])->row_array());
    }

    public function deleteAbsenceById($id)
    {        
        if($this->absence->deleteAbsenceById($id) > 0){
            $this->session->set_flashdata('message', 'Absent data|info|Absent data successly deleted!');
            redirect('absence/detail');
        }
    }

    public function editAbsentById()
    {
        $data = [
            'absent_id' => $this->input->post('absentAddDateId'),
            'absent_date' => $this->input->post('absentAddDate'),
            'cti_id' => $this->input->post('absentAddAgent'),
            'permit_type' => $this->input->post('absentAddPermitType'),
            'permit_reason' => $this->input->post('absentAddReason'),
            'permit_remark' => $this->input->post('absentAddRemark'),            
            'last_modified_by' => $this->session->userdata('user_id'),
            'last_modified_at' => date("Y-m-d h:i:s")
        ];

        if($this->absence->updateAbsenceById($data) > 0){
            $this->session->set_flashdata('message', 'Absent data|success|Absent data successly updated!');
            redirect('absence/detail');
        }
    }

    public function byagent()
    {
        $data['title'] = 'Absent By Agent';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        if(!$this->input->post()){
            $agent = $this->session->userdata('user_id');
            $startPeriod = date("Y-m-d", strtotime("-7 months"));
            $endPeriod = date("Y-m-d");
        } else{
            $agent = $this->input->post('absenceByAgentSelectAgent');
            $startPeriod = date("Y-m-d", strtotime($this->input->post('absenceByAgentDateStart')));
            $endPeriod = date("Y-m-d", strtotime($this->input->post('absenceByAgentDateEnd')));
        }
        $data['agent'] = $agent;
        $data['startPeriod'] = $startPeriod;
        $data['endPeriod'] = $endPeriod;
        $data['allAgent'] = $this->absence->getAllAgent();
        $data['absentByAgentByPeriod'] = $this->absence->getAllAbsentDataByAgentByPeriod($agent, $startPeriod, $endPeriod);
        $data['absentByAgentTotal'] = $this->absence->getAllAbsentDataByAgentTotal($agent, $startPeriod, $endPeriod);
        $data['absentByAgentByPeriodDetail'] = $this->absence->getAllAbsentDataByAgentByPeriodDetail($agent, $startPeriod, $endPeriod); 

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('absence/byagent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-absence');
    }

    public function absentDataForChart()
    {   
        $agent = $this->input->post('agent');
        $startPeriod = $this->input->post('startPeriod');
        $endPeriod = $this->input->post('endPeriod');
        $json_data = [];
        foreach($this->absence->getAllAbsentDataByAgentByPeriod($agent, $startPeriod, $endPeriod) as $row){
            $json_data['labels'][] = date("M-y", strtotime($row['absent_date']));
            $json_data['sick'][] = $row['permit_sick'];
            $json_data['unpaid_leave'][] = $row['permit_unpaid_leave'];
        }
        echo json_encode($json_data);
    }

    public function toExcelAbsentDetail()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Absence Administrator')->setLastModifiedBy('Absence Administrator')->setTitle("Detail of Daily Absence")->setSubject("Absence")->setDescription("Detail of Daily Absence")->setKeywords("Daily Absence");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "CCC DETAIL OF DAILY ABSENCE"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Date"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Agent"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NPK");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Fullname");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Absent"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Reason");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Remark"); 

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
        // $startPeriod = $this->input->post('absentDetailDateStart');
        // $endPeriod = $this->input->post('absentDetailDateEnd');
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $detailAbsenceData = $this->absence->toExcelDetailAbsentData($startPeriod, $endPeriod);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($detailAbsenceData as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, date("d-M-y", strtotime($data['absent_date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['cti_id']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['npk']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['fullname']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['permit_type']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['permit_reason']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data['permit_remark']);

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
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(45);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail of Daily Absence");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CCC Detail of Daily Absence.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }
}
