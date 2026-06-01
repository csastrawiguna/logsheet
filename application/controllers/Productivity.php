<?php
defined('BASEPATH') or exit('No direct script access allowed');

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Productivity extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Productivity_model', 'productivity');
        is_logged_in();
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Summary of Productivity';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['lastMonthSummaryProductivity'] = $this->productivity->getSummaryProductivityByPeriod(date("Y-m-01", strtotime('-1 months')), date("Y-m-01", strtotime('-1 months')), 'total', 'DESC');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/index', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-productivity');
    }

    public function byAgent()
    {
        $data['title'] = 'Productivity by Agent';
        $data['allAgent'] = $this->productivity->getAllAgent();
        $latestPeriod = $this->productivity->getAllPeriod()[0]['period'];
        if (!$this->input->post('productivity_agent')) {
            $agent = $this->session->userdata('user_id');
        } else {
            $agent = $this->input->post('productivity_agent');
        }
        $data['productivityByPeriodByAgent'] = $this->productivity->getProductivityByPeriodByAgent($agent, date("Y-m-01", strtotime('-12 months')), $latestPeriod);
        $data['average'] = $this->productivity->getAverageProductivityByPeriodByAgent($agent, date("Y-m-01", strtotime('-12 months')), $latestPeriod);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/productivity_by_agent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-productivity');
    }

    public function byperiod()
    {
        check_access();
        $data['title'] = 'Productivity by Period';
        $prevMonth = date("Y-m-01", strtotime("-1 months"));
        $data['allAgent'] = $this->productivity->getAllAgent();
        $data['lastMonthProductivity'] = $this->productivity->getSummaryProductivityByPeriod($prevMonth, $prevMonth);

        $this->form_validation->set_rules('addProductivityPeriod','Period of Productivity', 'required');
        $this->form_validation->set_rules('addProductivityAgent', 'Agent Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('productivity/productivity_by_period', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-productivity');
        } else{
            $data = [
                'period' => date("Y-m-01", strtotime($this->input->post('addProductivityPeriod'))),
                'agent' => $this->input->post('addProductivityAgent'),
                'icall' => $this->input->post('addProductivityIcall'),
                'callback' => $this->input->post('addProductivityCallback'),
                'follow_up' => $this->input->post('addProductivityFollowup'),
                'sms' => $this->input->post('addProductivitySms'),
                'webchat' => $this->input->post('addProductivityWebchat'),
                'whatsapp' => $this->input->post('addProductivityWhatsapp'),
                'sharp_id' => $this->input->post('addProductivitySharpid'),
                'email' => $this->input->post('addProductivityEmail'),
                'notif_sap' => $this->input->post('addProductivityNotifSap'),
                'complaint' => $this->input->post('addProductivityComplaint'),
                'part_code' => $this->input->post('addProductivityPartcode'),
                'others' => $this->input->post('addProductivityOthers'),
                'work_hour' => $this->input->post('addProductivityWorkHour')
            ];
            if ( $this->productivity->addSingleProductivity($data) > 0 ) {
                $this->session->set_flashdata('message', 'Single Productivity Added|success|Productivity of 1 Agent successly added...!');
                redirect('productivity/byperiod'); 
            }
        }      
    }

    public function uploadProductivityFromExcel()
    {
        $this->_uploadExcelDataProductivity();
    }

    public function productivityByPeriodByAgent()
    {
        $agent = $this->input->post('agent');
        $startPeriod = date("Y-m-01", strtotime($this->input->post('startPeriod')));
        $endPeriod = date("Y-m-01", strtotime($this->input->post('endPeriod')));
        echo json_encode($this->productivity->getProductivityByPeriodByAgent($agent, $startPeriod, $endPeriod));
    }

    public function averageProductivityByPeriodByAgent()
    {
        $agent = $this->input->post('agent');
        $startPeriod = date("Y-m-01", strtotime($this->input->post('startPeriod')));
        $endPeriod = date("Y-m-01", strtotime($this->input->post('endPeriod')));
        echo json_encode($this->productivity->getAverageProductivityByPeriodByAgent($agent, $startPeriod, $endPeriod));
    }

    public function productivityById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('productivity', ['id' => $id])->row_array());
    }

    public function deleteSingleProductivity()
    {        
        $data['period'] = $this->input->post('addProductivityPeriod');
        $data['agent'] = $this->input->post('addProductivityAgent');
        if($this->productivity->deleteSingleProductivity($data) > 0){
            $this->session->set_flashdata('message', 'Successly Deleted|info|Productivity data successly deleted...!');
            redirect('productivity/byperiod');                        
        }
    }

    public function editSingleProductivity()
    {
        $data = [
            'period' => date("Y-m-01", strtotime($this->input->post('addProductivityPeriod'))),
            'agent' => $this->input->post('addProductivityAgent'),
            'icall' => $this->input->post('addProductivityIcall'),
            'callback' => $this->input->post('addProductivityCallback'),
            'follow_up' => $this->input->post('addProductivityFollowup'),
            'sms' => $this->input->post('addProductivitySms'),
            'whatsapp' => $this->input->post('addProductivityWhatsapp'),
            'sharp_id' => $this->input->post('addProductivitySharpid'),
            'email' => $this->input->post('addProductivityEmail'),
            'notif_sap' => $this->input->post('addProductivityNotifSap'),
            'complaint' => $this->input->post('addProductivityComplaint'),
            'part_code' => $this->input->post('addProductivityPartcode'),
            'others' => $this->input->post('addProductivityOthers'),
            'work_hour' => $this->input->post('addProductivityWorkHour')
        ];
        if($this->productivity->editSingleProductivity($data) > 0){
            $this->session->set_flashdata('message', 'Successly Updated|success|Productivity data successly updated...!');
            redirect('productivity/byperiod');                        
        }

    }

    public function getSummaryProductivity()
    {
        // echo json_encode($_POST);
        $startPeriod = date("Y-m-01", strtotime($this->input->post('startPeriod')));
        $endPeriod = date("Y-m-01", strtotime($this->input->post('endPeriod')));
        $orderBy = $this->input->post('orderBy');
        $orderType = $this->input->post('orderType');
        echo json_encode($this->productivity->getSummaryProductivityByPeriod($startPeriod, $endPeriod, $orderBy, $orderType));
    }

    public function daily()
    {
        check_access();
        $data['title'] = 'Daily Productivity';
        $data['allAgents'] = $this->productivity->getAllAgent();

        if (!$this->input->post()) {
            $startPeriod = date("Y-m-d", strtotime("-30 days"));
            $endPeriod = date("Y-m-d");
            $agent = $this->session->userdata('user_id');
            $jobcode = $this->session->userdata('jobcode');
        } else {
            $startPeriod = $this->input->post('productivityDailySelectDateStart');
            $endPeriod = $this->input->post('productivityDailySelectDateEnd');
            $agent = $this->input->post('productivityDailySelectAgent');
            $jobcode = $this->productivity->getJobcodeByAgent($this->input->post('productivityDailySelectAgent'));
        }

        $data['allProductivityDailyTarget'] = $this->productivity->getAllProductivityDailyTarget();
        $data['productivityDailyTargetByAgent'] = $this->productivity->getProductivityDailyTarget($jobcode);
        if (count($this->productivity->getProductivityDailyData($startPeriod, $endPeriod, $agent)) < 1) {
            $data['allProductivityDailyData'] = null;
            $data['totalProductivityDailyData'] = null;
            $data['transitionProductivityDaily'] = null;
        } else {
            $data['allProductivityDailyData'] = $this->productivity->getProductivityDailyData($startPeriod, $endPeriod, $agent);
            $data['totalProductivityDailyData'] = $this->productivity->getTotalProductivityDailyData($startPeriod, $endPeriod, $agent);
            $data['transitionProductivityDaily'] = $this->productivity->getProductivityDailyTransition($startPeriod, $endPeriod);
        }

        $this->_autosetzerotarget($startPeriod, $endPeriod);
        $data['summbyProductivityDailyByAgent'] = $this->productivity->getSummbyProductivityDailyByAgent($startPeriod, $endPeriod);
        $data['summbyProductivityDailyByAgentNonzero'] = $this->productivity->getSummbyProductivityDailyByAgentNonzero($startPeriod, $endPeriod);
        $data['summbyProductivityDailyByAgentNonzeroMgt'] = $this->productivity->getSummbyProductivityDailyByAgentNonzeroMgt($startPeriod, $endPeriod);
        // $data['selectDate'] = $selectDate
        $data['fuschedule'] = $this->productivity->getFuschedule($startPeriod, $endPeriod);
        $data['waschedule'] = $this->productivity->getWaschedule($startPeriod, $endPeriod);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/productivity_daily_oh', $data);
        $this->load->view('templates/footer');
    }

    public function productivityDailySummaryToExcel()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        // Load class PHPExcel
        $excel = new PHPExcel();
        // Settingan awal file excel
        $excel->getProperties()->setCreator('Summary & Detail Daily Productivity')->setLastModifiedBy($this->session->userdata('user_id'))->setTitle("Daily Productivity")->setSubject("Productivity")->setDescription("Daily OH Productivity")->setKeywords("Daily Productivity");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => array('bold' => true), // Set font jadi bold

            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // align center
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // align middle
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // top border
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // right border
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // bottom border
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN) // left border
            )
        ];

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // align middle
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // align center
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // top border
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // right border
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // bottom border
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // left border
            )
        ];
        
        // Title
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "SUMMARY & DETAIL DAILY PRODUCTIVITY (OH)"); // Set kolom A1 dengan judul
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold cell A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 14 cell A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // Subtitle Summary
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "a. Summary By Agent"); // Set kolom A1 dengan judul
        $excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE); // Set bold cell A1
        $excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12); // Set font size 14 cell A1
        $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // get data
        $startPeriod = $this->uri->segment(3);
        $endPeriod = $this->uri->segment(4);
        $summ = $this->productivity->getSummbyProductivityDailyByAgentNonzero($startPeriod, $endPeriod);
        $details = $this->productivity->getDetailProductivityDailyByAgentNonzero($startPeriod, $endPeriod);

        // SUMMARY DAILY PRODUCITIVITY
        // Buat header tabel nya pada baris ke 6
        $excel->setActiveSheetIndex(0)->setCellValue('A6', "Agent");
        $excel->setActiveSheetIndex(0)->setCellValue('B6', "Target");
        $excel->setActiveSheetIndex(0)->setCellValue('C6', "iCall");
        $excel->setActiveSheetIndex(0)->setCellValue('D6', "Email");
        $excel->setActiveSheetIndex(0)->setCellValue('E6', "Whatsapp");
        $excel->setActiveSheetIndex(0)->setCellValue('F6', "Follow Up");
        $excel->setActiveSheetIndex(0)->setCellValue('G6', "Total");
        $excel->setActiveSheetIndex(0)->setCellValue('H6', "Achievement");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G6')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H6')->applyFromArray($style_col);

        $noSumm = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numRowSumm = 7; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($summ as $row) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numRowSumm, $row['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numRowSumm, $row['target_daily']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numRowSumm, $row['icall']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numRowSumm, $row['whatsapp_reply']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numRowSumm, $row['sms_email']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numRowSumm, $row['followup']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numRowSumm, $row['total']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numRowSumm, number_format($row['ratio_daily'] * 100, 1) , '%');
            $numRowSumm++;
        }

        // DETAIL DAILY PRODUCITIVITY
        // Subtitle Detail
        // $subtitleRowDetail = $numRowSumm + count($summ) + 3;
        // $excel->setActiveSheetIndex(0)->setCellValue('A' . ($numRowSumm + count($summ) + 3), "b. Detail Data By Day"); // Set kolom A1 dengan judul
        // $excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE); // Set bold cell A1
        // $excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12); // Set font size 14 cell A1
        // $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        // $noDetail = 1; // Untuk penomoran tabel, di awal set dengan 1
        // $numRowDetail = $numRowSumm + count($summ) + 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        // // Buat header tabel nya pada baris ke 3
        // $excel->setActiveSheetIndex(0)->setCellValue('A' . $numRowDetail, "Date");
        // $excel->setActiveSheetIndex(0)->setCellValue('B' . $numRowDetail, "Agent");
        // $excel->setActiveSheetIndex(0)->setCellValue('C' . $numRowDetail, "Assignment");
        // $excel->setActiveSheetIndex(0)->setCellValue('D' . $numRowDetail, "Target");
        // $excel->setActiveSheetIndex(0)->setCellValue('E' . $numRowDetail, "iCall");
        // $excel->setActiveSheetIndex(0)->setCellValue('F' . $numRowDetail, "Whatsapp");
        // $excel->setActiveSheetIndex(0)->setCellValue('G' . $numRowDetail, "Follow Up");
        // $excel->setActiveSheetIndex(0)->setCellValue('H' . $numRowDetail, "Total");
        // $excel->setActiveSheetIndex(0)->setCellValue('I' . $numRowDetail, "Remark");

        // // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        // $excel->getActiveSheet()->getStyle('A' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('B' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('C' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('D' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('E' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('F' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('G' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('H' . $numRowDetail)->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('I' . $numRowDetail)->applyFromArray($style_col);

        // $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        // $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        // foreach ($details as $row) { // Lakukan looping pada variabel siswa
        //     $excel->setActiveSheetIndex(0)->setCellValue('A' . $numRowDetail, date("d-M-y", strtotime($row['date'])));
        //     $excel->setActiveSheetIndex(0)->setCellValue('B' . $numRowDetail, $row['agent']);
        //     $excel->setActiveSheetIndex(0)->setCellValue('C' . $numRowDetail, ucwords($row['assignment']));
        //     $excel->setActiveSheetIndex(0)->setCellValue('D' . $numRowDetail, $row['target']);
        //     $excel->setActiveSheetIndex(0)->setCellValue('E' . $numRowDetail, $row['icall']);
        //     $excel->setActiveSheetIndex(0)->setCellValue('F' . $numRowDetail, $row['whatsapp_reply']);
        //     $excel->setActiveSheetIndex(0)->setCellValue('G' . $numRowDetail, $row['followup']);
        //     $excel->setActiveSheetIndex(0)->setCellValue('H' . $numRowDetail, ($row['icall'] + $row['whatsapp_reply'] + $row['followup']));
        //     $excel->setActiveSheetIndex(0)->setCellValue('I' . $numRowDetail, $row['remark'] == true ? '' : ucwords($row['remark']));

        //     // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
        //     $excel->getActiveSheet()->getStyle('A' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('B' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('C' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('D' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('E' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('F' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('G' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('H' . $numRowDetail)->applyFromArray($style_row);
        //     $excel->getActiveSheet()->getStyle('I' . $numRowDetail)->applyFromArray($style_row);
        //     $no++;

        //     // Tambah 1 setiap kali looping
        //     $numrow++; // Tambah 1 setiap kali looping    
        // }

        // // Set width kolom
        // $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        // $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        // $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        // $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        // $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        // $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        // $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        // $excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        // $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);


        // // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        // $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // // Set orientasi kertas jadi LANDSCAPE
        // $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // // Set judul file excel nya
        // $excel->getActiveSheet(0)->setTitle("Summary & Detail Daily Productivity Office Hour");
        // $excel->setActiveSheetIndex(0);
        // // Proses file excel
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Daily Productivity (OH).xlsx"'); // Set nama file excel nya
        // header('Cache-Control: max-age=0');
        // $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        // $write->save('php://output');
    }

    // DAILY SCHEDULE
    public function dailySchedule()
    {
        $data = [];
        $result = $this->productivity->getAllDailySchedule();
        // foreach ($result as $rs) {
        //     $data[] = [
        //         'title' => $rs['agent'],
        //         'start' => $rs['start_date'],
        //         'end' => $rs['end_date'],
        //         'color' => $rs['color'],
        //         'id' => $rs['id'],
        //         'reason' => $rs['reason'],
        //         'description' => $rs['description'],
        //         'permitType' => $rs['permit_type']
        //     ];
        // }
        echo json_encode($result);
    }

    private function _autosetzerotarget($startPeriod, $endPeriod)
    {
        $this->productivity->setTargetZeroByPeriod($startPeriod, $endPeriod);
    }

    public function addproductivitydaily()
    {
        $data['title'] = 'Add Daily Productivity';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/productivity_daily_add', $data);
        $this->load->view('templates/footer');
    }

    public function addproductivitydailymultiple()
    {
        var_dump($_POST);
    }

    public function addproductivitydailysingledate()
    {
        $this->_uploadExcelProductivityDailySingledate();
    }

    public function productivitydailytransition()
    {
        $startPeriod = $this->input->post('startPeriod');
        $endPeriod = $this->input->post('endPeriod');
        $agent = $this->input->post('agent');
        $jobcode = $this->productivity->getJobcodeByAgent($this->input->post('agent'));

        $target = $this->productivity->getProductivityDailyTarget($jobcode)['target'];
        $result = $this->productivity->getProductivityDailyByDateByAgent($startPeriod, $endPeriod, $agent);
        // $result = $this->productivity->getProductivityDailyByDateByAgent('2022-01-24', '2022-01-27', 'Okti');

        $data = [];
        foreach ($result as $row) {
            $data['totalproductivity'][] = $row['totalProductivity'];
            $data['target'][] = $row['target'];
            $data['date'][] = date("d-M", strtotime($row['date']));
        }        
        echo json_encode($data);
    }

    public function updateProductivityDaily()
    {

    }

    public function interval()
    {
        admin_access();
        $data['title'] = 'Productivity by Interval';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/productivity_interval', $data);
        $this->load->view('templates/footer');
    }

    public function maintain()
    {
        admin_access();
        $data['title'] = 'Maintan OH Data';

        if (!$this->input->post('productivityDailyEditStartdate')) {
            $data['startDate'] = date("Y-m-d", strtotime("-1 days"));
            $data['endDate'] = date("Y-m-d", strtotime("-1 days"));
            $data['agent'] = '';
        } else {
            $data['startDate'] = $this->input->post('productivityDailyEditStartdate');
            $data['endDate'] = $this->input->post('productivityDailyEditEnddate');
            $data['agent'] = $this->input->post('productivityDailyEditAgent');
        }

        $data['allAgents'] = $this->productivity->getAllAgent();
        $data['dataforedit'] = $this->productivity->getProductivityOhByAgent($data['startDate'], $data['endDate'], $data['agent']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('productivity/maintain-prodoh', $data);
        $this->load->view('templates/footer');
    }

    public function deleteDailySingle($id)
    {
        if ($this->productivity->performDeleteDailySingle($id) > 0) {
            $this->session->set_flashdata('message', 'Successly Deleted|info|Productivity data deleted from storage!');
            redirect('productivity/maintain');
        }
    }

    public function deleteDailyMulti()
    {
        $ids = $this->input->post('lists');
        $num = 0;
        foreach ($ids as $row) {
            if ($this->productivity->performDeleteDailySingle($row) > 0) {
                $num += 1;
            }
        }
        $this->session->set_flashdata('message', "$num Rows Deleted|info|Productivity data deleted from storage!");
        redirect('productivity/maintain');
    }

    public function getDailySingleById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('productivity_daily', ['id' => $id])->row_array());
    }

    public function editDailySingle()
    {
        $updateData = [
            'id' => $this->input->post('editProductivityDailyId'),
            'icall' => $this->input->post('editProductivityDailyIcall'),
            'whatsapp_reply' => $this->input->post('editProductivityDailyWhatsapp'),
            'followup' => $this->input->post('editProductivityDailyFollowup'),
            'assignment' => $this->input->post('editProductivityDailyAssignment'),
            'target' => $this->input->post('editProductivityDailyTarget'),
            'remark' => $this->input->post('editProductivityDailyRemark')
        ];
        if ($this->productivity->performEditDailySingle($updateData) > 0 ) {
            $this->session->set_flashdata('message', "Succesly Updated|success|Productivity data on database updated!");
            redirect('productivity/maintain');
        }
    }

    public function maintaininterval()
    {
        admin_access();
        $data['title'] = 'Maintan Prod Interval';
        $data['dataToEdit'] = $this->db->get('productivity_interval')->result_array();

        $this->form_validation->set_rules('1[agent]', 'Agent', 'required');
        $this->form_validation->set_rules('1[icall]', 'Call', 'required');
        
        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('productivity/maintain-interval', $data);
            $this->load->view('templates/footer');
        } else {
            $updateData = $this->input->post();
            
            foreach ($updateData as $row) {
                // check existing agent name
                if ($this->db->get_where('productivity_interval', ['agent' => $row['agent']])->num_rows() > 0 ) {
                    $update += $this->productivity->updateProductivityInterval($row);
                } else {
                    $add += $this->productivity->addProductivityInterval($row);
                }
            }

            $this->session->set_flashdata("message", "Successly update productivity|success|Agent baru: $add, update : $update data");
            redirect('productivity/maintaininterval'); 
        }
    }

    public function deleteproductivityinterval()
    {
        admin_access();
        $agent = $this->uri->segment(3);
        if ($this->productivity->deleteProductivityIntervalByAgent($agent) > 0) {
            $this->session->set_flashdata('message', 'Successly Deleted|info|Productivity data : ' . $agent . ' deleted!');
            redirect('productivity/maintaininterval');
        }
    }

    public function deleteproductivityintervalgroup()
    {
        admin_access();
        $agent = $this->input->post('lists');
        $num = 0;
        foreach ($agent as $row) {
            if ($this->productivity->deleteProductivityIntervalByAgent($row) > 0) {
                $num += 1;
            }
        }
        $this->session->set_flashdata('message', "$num Rows Deleted|info|Productivity data deleted from storage!");
        redirect('productivity/maintaininterval');
    }

    public function byinterval()
    {
        //$var = str_replace("&nbsp;&nbsp;&nbsp;", "|", $_POST['inputRawProductivity']);
        $var = str_replace("&nbsp;&nbsp;", "|", $this->input->post('inputRawProductivity'));
        $var = str_replace("&nbsp;", "", $var);
        // $var = str_replace(" ", "|", $var);
        $var = str_replace("<p>", "", $var);
        $var = str_replace("</p>", "", $var);

        // echo $var;
        $arr = explode('<br>', $var);
        $arr = str_replace("  ", "|", $arr);

        $transp = [];
        foreach ($arr as $key) {
            $transp[] = explode("|", $key);
        }
        $header = [];
        $result = [];
        // var_dump($var);die;
        foreach($transp[0] as $x) {
            $header[] = strtolower(trim($x));
        }

        for ($x = 1; $x < count($transp); $x++) {
            $row['datetime'] = date("Y-m-d H:i", strtotime($this->input->post('inputRawProductivityTime')));

            if (count($transp[$x]) < 3) {
                continue;
            } else {
                for ($y = 0; $y < count($transp[$x]); $y++) {
                    $row[$header[$y]] = trim($transp[$x][$y]);
                }
            }
            $result[] = $row;
        }
        // var_dump($result);die;

        $update = 0;
        $add = 0;
        
        foreach ($result as $row) {
            // check existing agent name
            if ($this->db->get_where('productivity_interval', ['agent' => $row['agent']])->num_rows() > 0 ) {
                $update += $this->productivity->updateProductivityInterval($row);
            } else {
                $add += $this->productivity->addProductivityInterval($row);
            }
        }
        
        $this->session->set_flashdata("message", "Successly update productivity|success|Agent baru: $add, update : $update data");
        redirect('productivity/daily');        
    }


    // UPLOAD PRODUCTIVITY
    private function _uploadExcelDataProductivity()
    {        
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $config['upload_path'] = './productivityfile';
        $config['allowed_types'] = 'xlsx|xls|csv|ods';
        $config['max_size'] = '10496';
        $config['overwrite'] = true;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('productivityAddExcel')) {
            var_dump($this->upload->error_msg);
        } else {
            $data_upload = $this->upload->data();            
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load('productivityfile/' . $data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true);

            // var_dump($sheet);
            // echo "<br>";

            $data = array();
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($numrow > 1) {
                    array_push($data, [
                        'period' => date("Y-m-01", strtotime($row['B'])),
                        'agent' => $row['C'],
                        'icall' => $row['D'],
                        'callback' => $row['E'],
                        'follow_up' => $row['F'],
                        'sms' => $row['G'],
                        'webchat' => $row['H'],
                        'whatsapp' => $row['I'],
                        'sharp_id' => $row['J'],
                        'email' => $row['K'],
                        'notif_sap' => $row['L'],
                        'complaint' => $row['M'],
                        'part_code' => $row['N'],
                        'others' => $row['O'],
                        'work_hour' => $row['P']
                    ]);
                }
                $numrow++;
            }            

            // insert data
            if($this->productivity->checkExistingPeriod($data) > 0) {
                //delete file from server
                if($this->productivity->deleteProductivityByPeriod($data) > 0){
                    if($this->productivity->insertBatchProductivity($data) > 0 ){
                        unlink(realpath('productivityfile/' . $data_upload['file_name']));
                        $this->session->set_flashdata('message', 'Productivity Data Updated|success|Perious Data Successly updated...!');
                        redirect('productivity/byperiod');                        
                    }
                }
            } else {
                if($this->productivity->insertBatchProductivity($data) > 0 ){
                    unlink(realpath('productivityfile/' . $data_upload['file_name']));
                    $this->session->set_flashdata('message', 'Successly Uploaded|success|Productivity data successly added...!');
                    redirect('productivity/byperiod');                        
                }
            }            
        }
    }

    // file upload functionality
    public function _uploadExcelProductivityDailySingledate()
    {
        if (!empty($_FILES['productivityDailyAddSingleFile']['name'])) {
            // get file extension
            $extension = pathinfo($_FILES['productivityDailyAddSingleFile']['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ($extension == 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            // file path
            $reader->setReadDataOnly(true);
            // $reader->setReadEmptyCells(false);
            $reader->setLoadSheetsOnly('count');
            $spreadsheet = $reader->load($_FILES['productivityDailyAddSingleFile']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(false, false, true, true, true, true, true, true, true, true, true);
            // echo '<pre>';var_dump($allDataInSheet);die;
            
            $dataUpload = [];
            for ($x = 2; $x <= count($allDataInSheet); $x++) {
                if (empty($allDataInSheet[$x]['L']) || strlen($allDataInSheet[$x]['L']) < 3) {
                    continue;
                } else {                
                    $dataUpload[] = [
                        'date' => date("Y-m-d", strtotime($this->input->post('productivityDailyAddSingleDate'))),
                        'agent' => $allDataInSheet[$x]['L'],
                        'icall' => $allDataInSheet[$x]['M'],
                        'whatsapp_reply' => $allDataInSheet[$x]['N'],
                        'sms_email' => $allDataInSheet[$x]['O'],
                        'followup' => $allDataInSheet[$x]['P'],
                        'assignment' => strtolower($allDataInSheet[$x]['Q']),
                        'target' => $this->_job2target($allDataInSheet[$x]['L'], strtolower($allDataInSheet[$x]['Q'])),
                        'remark' => $this->_emptyvalue2string($allDataInSheet[$x]['R']),
                        'saved_by' => $this->session->userdata('user_id'),
                        'saved_at' => date("Y-m-d H:i:s")
                    ];
                }
            }
            
            // insert batch
            if ($this->productivity->insertProductivityDaily($dataUpload) > 0) {
                $this->session->set_flashdata('message', 'Successly uploaded|success|Productivity daily data (OH) uploaded!');
                redirect('productivity/daily');
            }
        }
    }

    private function _emptyvalue2string($val)
    {
        if ($val == '' || $val == null || $val == 1) {
            return '';
        } else {
            return strtolower($val);
        }
    }

    private function _job2target($agent, $job)
    {
        if (strtolower($job) == 'follow up' || strtolower($job) == 'fu') {
            $target = $this->productivity->getTargetByJobcode('cs-ccc-cc14');
        } else {
            $target = $this->productivity->getTargetByName($agent);
        }

        return $target;

    }

    //EXPORT TO EXCEL
    public function detailByPeriodToExcel()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Detail Agent Productivity')->setLastModifiedBy('Detail Agent Productivity')->setTitle("Agent Productivity")->setSubject("Productivity")->setDescription("Agent Productivity by Period")->setKeywords("Agent Productivity");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
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
        ];

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
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
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL AGENT PRODUCTIVITY BY PERIOD"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        // $excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "Month"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Name"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Call"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Callback"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "FU Call"); // Set kolom F3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "SMS"); // Set kolom F3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Webchat"); // Set kolom F3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Whatsapp"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "SharpID"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Email"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Notif SAP"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Complaint"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Part Code"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Others"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Total"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Working hour"); // Set kolom E3 dengan tulisan "ALAMAT"

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

        // query Data
        if (!$this->input->post('selectSummaryProductivityStart')) {
            $startPeriod = $this->uri->segment(3);
            $endPeriod = $this->uri->segment(4);
        } else {
            $startPeriod = $this->input->post('selectSummaryProductivityStart');
            $endPeriod = $this->input->post('selectSummaryProductivityEnd');
        }
        $productivityData = $this->productivity->getProductivityDetailByPeriod($startPeriod, $endPeriod);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($productivityData as $row) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, date("F Y", strtotime($row['period'])));
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $row['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $row['icall']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row['callback']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row['follow_up']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row['sms']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row['webchat']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $row['whatsapp']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $row['sharp_id']);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $row['email']);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $row['notif_sap']);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $row['complaint']);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $row['part_code']);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $row['others']);
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, ($row['icall'] + $row['callback'] + $row['follow_up'] + $row['sms'] + $row['webchat'] + $row['whatsapp'] + $row['sharp_id'] + $row['email'] + $row['notif_sap'] + $row['complaint'] + $row['part_code'] + $row['others']));
            $excel->setActiveSheetIndex(0)->setCellValue('P' . $numrow, $row['work_hour']);

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
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(15); // Set width kolom E


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detai of Productivity");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Productivity detail by period.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    //EXPORT TO EXCEL PROD DAILY (OH)
    public function detailDailyToExcel()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Detail Agent Productivity')->setLastModifiedBy('Detail Agent Productivity')->setTitle("Agent Productivity")->setSubject("Productivity")->setDescription("Agent Productivity by Period")->setKeywords("Agent Productivity");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
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
        ];

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
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
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DETAIL DAILY PRODUCTIVITY (OH)"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        // $excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "Date");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Agent");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Assignment");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Target");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "iCall");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Whatsapp");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Email");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Follow Up");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Total");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Remark");

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

        // query Data
        if (!$this->input->post('productivityDailyEditStartdate')) {
            $startPeriod = $this->uri->segment(3);
            $endPeriod = $this->uri->segment(4);
            $agent = '';
        } else {
            $startPeriod = $this->input->post('productivityDailyEditStartdate');
            $endPeriod = $this->input->post('productivityDailyEditEnddate');
            $agent = $this->input->post('productivityDailyEditAgent');
        }
        $productivityData = $this->productivity->getProductivityOhByAgent($startPeriod, $endPeriod, $agent);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($productivityData as $row) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, date("d-M-y", strtotime($row['date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $row['agent']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, ucwords($row['assignment']));
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row['target']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row['icall']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row['whatsapp_reply']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row['sms_email']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $row['followup']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, ($row['icall'] + $row['whatsapp_reply'] + $row['followup']));
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $row['remark'] == true ? '' : ucwords($row['remark']));

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
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Detail Daily Productivity (OH)");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Daily Productivity (OH).xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
