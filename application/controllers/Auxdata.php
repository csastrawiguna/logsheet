<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Auxdata extends MY_Controller
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

    public function agentdaily()
    {
        $data['title'] = 'AUX Agent Daily';
        $allowSelectAgent = ['1', '5', '6', '7', '9'];

        if(!$this->input->post('auxDailyByAgentStartPeriod') && !$this->input->post('auxDailyByAgentEndPeriod')) {
            $data['startPeriod'] = date("Y-m-d", strtotime("-30 days"));
            $data['endPeriod'] = date("Y-m-d");
            $data['agent'] = $this->session->userdata('user_id');
            $data['isOh'] = ['1', '0'];
        } else {
            $data['startPeriod'] = $this->input->post('auxDailyByAgentStartPeriod');
            $data['endPeriod'] = $this->input->post('auxDailyByAgentEndPeriod');
            $data['agent'] = $this->input->post('auxByAgentSelectAgent');
            $data['isOh'] = $this->input->post('auxDailyByAgentIsoh');
        }

        $data['auxDailyByAgent'] = $this->aux->getAuxDailyAllByPeriodByAgent($data['startPeriod'], $data['endPeriod'], $data['agent'], $data['isOh']);
        $data['auxDailyByAgentSummaryAll'] = $this->aux->getSummaryAuxDailyAllByPeriodByAgent($data['startPeriod'], $data['endPeriod'], $data['agent']);
        $data['allAgents'] = $this->aux->getAllActiveAgent();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('auxdata/aux-daily-byagent', $data);
        $this->load->view('templates/footer', $data);
    }

    public function dailyall()
    {
        $data['title'] = 'AUX Daily All';
        $allowSelectAgent = ['1', '5', '6', '7', '9'];

        if(!$this->input->post('auxDailyAllStartPeriod') && !$this->input->post('auxDailyAllEndPeriod')) {
            $data['startPeriod'] = date("Y-m-d", strtotime("-7 days"));
            $data['endPeriod'] = date("Y-m-d");
        } else {
            $data['startPeriod'] = $this->input->post('auxDailyAllStartPeriod');
            $data['endPeriod'] = $this->input->post('auxDailyAllEndPeriod');
        }

        $data['auxDailyByPeriod'] = $this->aux->getAuxDailyAllByPeriodByAgent($data['startPeriod'], $data['endPeriod']);
        $data['allAgents'] = $this->aux->getAllActiveAgent();

        $this->form_validation->set_rules('addSingleAuxDailyAgent', 'Agent', 'trim|required');
        $this->form_validation->set_rules('addSingleAuxDailyDate', 'Date', 'trim|required');
        $this->form_validation->set_rules('addSingleAuxDailyStaffedtime', 'Staffed time', 'trim|required|numeric');

        if ($this->form_validation->run() == false) {
            
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('auxdata/aux-daily-all', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $newData = [
                'date' => date("Y-m-d", strtotime($this->input->post('addSingleAuxDailyDate'))),
                'is_oh' => $this->input->post('addSingleAuxDailyIsoh'),
                'agent' => $this->input->post('addSingleAuxDailyAgent'),
                'staffed_time' => $this->input->post('addSingleAuxDailyStaffedtime'),
                'ext' => $this->input->post('addSingleAuxDailyExtension'),
                'aux_0' => $this->input->post('addSingleAuxDailyAux0'),
                'aux_1' => $this->input->post('addSingleAuxDailyAux1'),
                'aux_2' => $this->input->post('addSingleAuxDailyAux2'),
                'aux_3' => $this->input->post('addSingleAuxDailyAux3'),
                'aux_4' => $this->input->post('addSingleAuxDailyAux4'),
                'aux_5' => $this->input->post('addSingleAuxDailyAux5'),
                'aux_6' => $this->input->post('addSingleAuxDailyAux6'),
                'aux_7' => $this->input->post('addSingleAuxDailyAux7'),
                'aux_8' => $this->input->post('addSingleAuxDailyAux8'),
                'aux_9' => $this->input->post('addSingleAuxDailyAux9'),
                'aux_1099' => $this->input->post('addSingleAuxDailyAux1099'),
                'remark' => $this->input->post('addSingleAuxDailyRemark'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s")
            ];
            var_dump($newData);die;

            // upload to database
            if ($this->aux->addNewAuxDailySingleData($newData) > 0) {
                $this->session->set_flashdata('message', 'Success|success|Single AUX data saved!');
                redirect('auxdata/dailyall');
            }
        }
    }

    public function deleteSingleDaily($id)
    {
       if ($this->aux->deleteSingleAuxdaily($id) > 0) {
            $this->session->set_flashdata('message', 'Success|info|One row of AUX daily deleted!');
            redirect('auxdata/dailyall');
        }
    }

    public function singleAuxdailyByDateByAgent()
    {
        $date = date("Y-m-d", strtotime($this->input->post('date')));
        $agent = $this->input->post('agent');
        echo json_encode($this->aux->getAuxDailyAllByPeriodByAgent($date, $date, $agent)[0]);
    }

    public function updateSingleAuxDaily()
    {
        $this->form_validation->set_rules('addSingleAuxDailyAgent', 'Agent', 'trim|required');
        $this->form_validation->set_rules('addSingleAuxDailyDate', 'Date', 'trim|required');
        $this->form_validation->set_rules('addSingleAuxDailyStaffedtime', 'Staffed time', 'trim|required|numeric');
        
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', 'Failed|error|Failed to update Single AUX data!');
            redirect('auxdata/dailyall');
        } else {
            $updateData = [
                'id' => $this->input->post('addSingleAuxDailyId'),
                'date' => date("Y-m-d", strtotime($this->input->post('addSingleAuxDailyDate'))),
                'is_oh' => $this->input->post('addSingleAuxDailyIsoh'),
                'agent' => $this->input->post('addSingleAuxDailyAgent'),
                'staffed_time' => $this->input->post('addSingleAuxDailyStaffedtime'),
                'ext' => $this->input->post('addSingleAuxDailyExtension'),
                'aux_0' => $this->input->post('addSingleAuxDailyAux0'),
                'aux_1' => $this->input->post('addSingleAuxDailyAux1'),
                'aux_2' => $this->input->post('addSingleAuxDailyAux2'),
                'aux_3' => $this->input->post('addSingleAuxDailyAux3'),
                'aux_4' => $this->input->post('addSingleAuxDailyAux4'),
                'aux_5' => $this->input->post('addSingleAuxDailyAux5'),
                'aux_6' => $this->input->post('addSingleAuxDailyAux6'),
                'aux_7' => $this->input->post('addSingleAuxDailyAux7'),
                'aux_8' => $this->input->post('addSingleAuxDailyAux8'),
                'aux_9' => $this->input->post('addSingleAuxDailyAux9'),
                'aux_1099' => $this->input->post('addSingleAuxDailyAux1099'),
                'remark' => $this->input->post('addSingleAuxDailyRemark'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            // upload to database
            if ($this->aux->editAuxDailySingleData($updateData) > 0) {
                $this->session->set_flashdata('message', 'Success|success|Single AUX data successly updated!');
                redirect('auxdata/dailyall');
            }
        }

    }

    public function uploadAuxDaily()
    {
        if (!empty($_FILES['uploadAuxDailyFile']['name'])) {
            // get file extension
            $extension = pathinfo($_FILES['uploadAuxDailyFile']['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
            } elseif ($extension == 'xlsx') {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            } else {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
            }

            // KEDAH FALSE: Supados PhpSpreadsheet kersa maca cell anu aya rumusna
            $reader->setReadDataOnly(false);

            // load file excel ti tempat saheulaanan (tmp)
            $spreadsheet = $reader->load($_FILES['uploadAuxDailyFile']['tmp_name']);
            $sheet = $spreadsheet->getSheetByName('Upload');
            
            // NGEUSIAN ARRAY SACARA MANUAL NGANGGO KALKULASI PASTI
            $allDataInSheet = [];
            $highestRow = $sheet->getHighestRow();

            // Looping ti baris 1 dugi ka baris pangtungtungna nu aya dataan
            for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
                $allDataInSheet[] = [
                    'A' => $sheet->getCell('A' . $rowNum)->getCalculatedValue(),
                    'B' => $sheet->getCell('B' . $rowNum)->getCalculatedValue(),
                    'C' => $sheet->getCell('C' . $rowNum)->getCalculatedValue(),
                    'D' => $sheet->getCell('D' . $rowNum)->getCalculatedValue(),
                    'E' => $sheet->getCell('E' . $rowNum)->getCalculatedValue(),
                    'F' => $sheet->getCell('F' . $rowNum)->getCalculatedValue(),
                    'G' => $sheet->getCell('G' . $rowNum)->getCalculatedValue(),
                    'H' => $sheet->getCell('H' . $rowNum)->getCalculatedValue(),
                    'I' => $sheet->getCell('I' . $rowNum)->getCalculatedValue(),
                    'J' => $sheet->getCell('J' . $rowNum)->getCalculatedValue(),
                    'K' => $sheet->getCell('K' . $rowNum)->getCalculatedValue(),
                    'L' => $sheet->getCell('L' . $rowNum)->getCalculatedValue(),
                    'M' => $sheet->getCell('M' . $rowNum)->getCalculatedValue(),
                    'N' => $sheet->getCell('N' . $rowNum)->getCalculatedValue(),
                    'O' => $sheet->getCell('O' . $rowNum)->getCalculatedValue(),
                    'P' => $sheet->getCell('P' . $rowNum)->getCalculatedValue(),
                    'Q' => $sheet->getCell('Q' . $rowNum)->getCalculatedValue(),
                    'R' => $sheet->getCell('R' . $rowNum)->getCalculatedValue()
                ];
            }

            // array Count
            $dataUploaded = []; 
            $numrow = 1;
            foreach ($allDataInSheet as $row) {
                if ($numrow > 1) {
                    // 1. TRIM Sangkan euweuh karakter spasi siluman
                    $valA = trim($row['A']);

                    // 2. ANTISIPASI ERROR FORMULA EXCEL (Kawas #N/A, #VALUE!, #REF!, jsb.)
                    // Ciri utama error formula biasana diawalan ku karakter pager (#)
                    if (strpos($valA, '#') === 0) {
                        $numrow++;
                        continue; // Skip / Luncatan
                    }

                    // 3. ANTISIPASI KOSONG (EMPTY / NULL)
                    if ($valA == '' || $valA == NULL) {
                        $numrow++;
                        continue; // Skip / Luncatan
                    }

                    // 4. ANTISIPASI TANGGAL 1900-01-01 ATAWA TANGGAL SAKRAL LAINNA
                    $convertedDate = date("Y-m-d", strtotime($valA));
                    if ($convertedDate == '1900-01-01' || $convertedDate == '1970-01-01' || $valA == '1900-01-01') {
                        $numrow++;
                        continue; // Skip / Luncatan
                    }
                    
                    $dataUploaded[] = [
                        'date'         => date("Y-m-d", strtotime($row['A'])),
                        'is_oh'        => $row['R'],
                        'agent'        => $row['B'],
                        'ext'          => $row['D'],
                        'staffed_time' => $row['E'],
                        'aux_0'        => $row['F'],
                        'aux_1'        => $row['G'],
                        'aux_2'        => $row['H'],
                        'aux_3'        => $row['I'],
                        'aux_4'        => $row['J'],
                        'aux_5'        => $row['K'],
                        'aux_6'        => $row['L'],
                        'aux_7'        => $row['M'],
                        'aux_8'        => $row['N'],
                        'aux_9'        => $row['O'],
                        'aux_1099'     => $row['P'],
                        'remark'       => $row['Q'],
                        'saved_by'     => $this->session->userdata('user_id'),
                        'saved_at'     => date("Y-m-d H:i:s")
                    ];
                }
                $numrow++;
            }
            
            // upload ka database via model
            if (!empty($dataUploaded)) {
                $nums = $this->aux->uploadAuxDailyFromExcel($dataUploaded);
                if ($nums > 0) {
                    $this->session->set_flashdata('message', 'Success|success|' . $nums . ' of AUX Daily data uploaded!');
                    redirect('auxdata/dailyall');
                } else {
                    $this->session->set_flashdata('message', 'Failed|danger|Failed to upload data!');
                    redirect('auxdata/dailyall');
                }
            } else {
                $this->session->set_flashdata('message', 'Warning|warning|There was no file to be uploaded!');
                redirect('auxdata/dailyall');
            }
        }
    }
}
