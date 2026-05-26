<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Auxdata extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Aux_model', 'aux');
    }

    public function index()
    {
        check_access();
        $data['title'] = 'AUX data';

        if(!$this->input->post('auxSummaryDateStart') && !$this->input->post('auxSummaryDateEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-6 months"));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = $this->input->post('auxSummaryDateStart');
            $endPeriod = $this->input->post('auxSummaryDateEnd');
        }
        
        $data['auxSummaryMonthly'] = $this->aux->getSummaryAuxByMonth($startPeriod, $endPeriod);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('auxdata/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-aux', $data);
    }

    public function byagent()
    {
        $data['title'] = 'AUX by Agent';
        $allowSelectAgent = [1, 5, 6, 7, 9];
        if (in_array($this->session->userdata('role_access'), $allowSelectAgent)) {
            $data['title'] = "AUX data monthly";
            $agent = $this->aux->getAllActiveAgent()[0]['user_id'];            
        } else{            
            $data['title'] = "AUX data monthly of " . $this->session->userdata('user_id');
            $agent = $this->session->userdata('user_id');
        }
        
        if(!$this->input->post('auxByAgentDateStart') && !$this->input->post('auxByAgentDateEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-6 months"));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = $this->input->post('auxByAgentDateStart');
            $endPeriod = $this->input->post('auxByAgentDateEnd');
            $agent = $this->input->post('auxByAgentSelectAgent');
        }
        
        $data['auxByAgentMonthly'] = $this->aux->getByAgentAuxByMonth($startPeriod, $endPeriod, $agent);
        $data['allAgents'] = $this->aux->getAllActiveAgent();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('auxdata/byagent', $data);
        $this->load->view('templates/footer', $data);
    }

    private function summaryFromExcel()
    {

    }

    public function uploadAuxSummary()
    {
        if (!empty($_FILES['uploadAuxSummaryFile']['name'])) {
            // get file extension
            $extension = pathinfo($_FILES['uploadAuxSummaryFile']['name'], PATHINFO_EXTENSION);

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
            $reader->setLoadSheetsOnly('rekap');

            // file path
            $spreadsheet = $reader->load($_FILES['uploadAuxSummaryFile']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(true, true, true, true, true, true, true, true, true, true, true, true, true, true, true);

            // array Count
            $dataUploaded = []; 
            $numrow = 1;
            foreach ($allDataInSheet as $row) {
                if ($numrow > 1) {
                    if($row['A'] == '' || $row['A'] == NULL) {
                        continue;
                    } else {
                        $dataUploaded[] = [
                            'month' => date("Y-m-01", strtotime($this->input->post('uploadAuxSummaryMonth'))),
                            'agent' => $row['A'],
                            'ext' => strtoupper($row['B']),
                            'staffed_time' => strtoupper($row['C']),
                            'aux_0' => strtoupper($row['D']),
                            'aux_1' => strtoupper($row['E']),
                            'aux_2' => strtoupper($row['F']),
                            'aux_3' => strtoupper($row['G']),
                            'aux_4' => strtoupper($row['H']),
                            'aux_5' => strtoupper($row['I']),
                            'aux_6' => strtoupper($row['J']),
                            'aux_7' => strtoupper($row['K']),
                            'aux_8' => strtoupper($row['L']),
                            'aux_9' => strtoupper($row['M']),
                            'aux_1099' => strtoupper($row['N']),
                            'remark' => strtoupper($row['O']),
                            'saved_by' => $this->session->userdata('user_id'),
                            'saved_at' => date("Y-m-d h:i:s")
                        ];
                    }
                }
                $numrow++;
            }
            
            // upload to database
            if ($this->aux->uploadAuxSummaryFromExcel($dataUploaded) > 0) {
                $this->session->set_flashdata('message', 'Success|success|Summary of AUX data uploaded!');
                redirect('auxdata/index');
            }
        }
    }
}
