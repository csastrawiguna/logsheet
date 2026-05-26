<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Assessment_model', 'assessment');
        $this->load->model('Productivity_model', 'productivity');
        $this->load->model('Csindex_model', 'csindex');
        $this->load->model('Setting_model', 'setting');
        $this->load->model('Survey_model', 'survey');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->row_array();
        $data['userBirthdate'] = $this->dashboard->getAllUserBirthdate();
        $data['userQuote'] = $this->dashboard->getQuote();
        $data['elearningAssignment'] = $this->dashboard->getAssignedElearning();
        $data['fiscal'] = $this->assessment->getLatestFiscal();
        $data['breakSchedule'] = $this->setting->getBreakSchedule(date("Y-m-d"));
        $data['breakTime'] = $this->setting->getBreaktime();
        $data['targetData'] = $this->assessment->getTargetByJobcode($this->session->userdata('jobcode'), $data['fiscal']);
        $data['allGeneralInfo'] = $this->setting->getAllGeneralInfo();
        $data['productivityInterval'] = $this->dashboard->getProductivityInterval();
        $data['isdonesurvey'] = $this->survey->countNewSurveySkapeByUserid($this->session->userdata('user_id'));
        $data['profilebg'] = $this->db->get_where('user', ['user_id' => $this->session->userdata('user_id')])->row_array()['bg'];
        $data['surveyTreshold'] = $this->db->get('survey_setting')->row_array()['qty_min'];
        $data['leaveBalance'] = $this->dashboard->getLeaveBalance($this->session->userdata('user_id'), $this->session->userdata('role_access'));

        $lebaranYear = $this->dashboard->getLastLebaranYear();
        $data['lebaranOperationData'] = $this->dashboard->getLebaranOperationByYear($lebaranYear);
        
        $latestDateProductivityDaily = $this->productivity->getLatestDate()['date'];
        $data['productivityDailyTarget'] = $this->productivity->getProductivityDailyTarget($this->session->userdata('jobcode'));
        
        $data['productivityDailyData'] = $this->productivity->getProductivityDailyData(date("Y-m-d"), date("Y-m-d", strtotime("-14 days")), $this->session->userdata('user_id'));
        $data['overtimeLeft'] = $this->dashboard->getOvertimeLeft($this->session->userdata('user_id'));
        $data['praySchedule'] = $this->dashboard->getPraySchedule();
        $data['prayScheduleTimes'] = $this->dashboard->getPrayScheduleTimes();
        $data['queueing'] = $this->dashboard->getAllQueue();
        $data['voteList'] = $this->setting->getVoteList(1, $this->session->userdata('user_id'));

        // var_dump($data);die;

        if(count($this->assessment->getTargetByJobcode($this->session->userdata('jobcode'), $data['fiscal'])) == 0) {
            $data['fiscalStart'] = '1900-01-01';
            $data['fiscalEnd'] = '1900-01-01';
        } else {
            $data['fiscalStart'] = $this->_fiscalStart($this->assessment->getTargetByJobcode($this->session->userdata('jobcode'), $data['fiscal'])[0]['fiscal']);
            $data['fiscalEnd'] = $this->_fiscalEnd($this->assessment->getTargetByJobcode($this->session->userdata('jobcode'), $data['fiscal'])[0]['fiscal']);
        }

        switch ($this->session->userdata('jobcode')) {
            case 'cs-ccc-cc20':
                $data['achievementData'] = [
                    'productivity' => $this->assessment->getProductivity($this->session->userdata('user_id'), $data['fiscalStart'], $data['fiscalEnd']),
                    'csindexRatio' => $this->csindex->getAverageResultByAgentByPeriod2($this->session->userdata('user_id'), $data['fiscalStart'], $data['fiscalEnd']),
                    'attendance' => 'n/a',
                    'knowledgeSharing' => 'n/a',
                    'qaSolution' => 'n/a'
                ];
                break;

            default:
                $data['achievementData'] = [
                    'productivity' => $this->assessment->getProductivity($this->session->userdata('user_id'), $data['fiscalStart'], $data['fiscalEnd']),
                    'csindexRatio' => $this->csindex->getAverageResultByAgentByPeriod2($this->session->userdata('user_id'), $data['fiscalStart'], $data['fiscalEnd']),
                    'elearningScore' => 'n/a',
                    'attendance' => 'n/a'
                ];
                break;
        }

        $data['ramadhanFirstdate'] = $this->dashboard->getRamadhanFirstDate();
        $data['dashboardItem'] = [];
        $data['allDashboardItems'] = $this->db->get('dashboard_item')->result_array();
        foreach($data['allDashboardItems'] as $x) {
            $data['dashboardItem'][$x['item_nick']] = $x['is_active'];
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-dashboard', $data);
    }    

    private function _fiscalStart($fiscal)
    {
        if(substr($fiscal, -1) == 'L'){
            return substr($fiscal, -5, 4) . '-10-01';
        } else {
            return substr($fiscal, -5, 4) . '-04-01';
        }
    }

    private function _fiscalEnd($fiscal)
    {
        if(substr($fiscal, -1) == 'L'){
            return (int)substr($fiscal, -5, 4) + 1 . '-03-31';
        } else {
            return substr($fiscal, -5, 4) . '-09-30';
        }
    }

    public function addqueue($agent)
    {
        $data = [
            'agent' => $agent,
            'status' => 'queueing',
            'saved_at' => date("Y-m-d H:i:s")
        ];
        if ($this->dashboard->addToQueue($data) > 0) {
            $this->session->set_flashdata('message', "Sukses!|success|Masuk dalam antrian!");
                redirect('dashboard/index');
        }
    }

    public function addfinish($agent)
    {
        $data = [
            'agent' => $agent,
            'status' => 'finish',
            'updated_at' => date("Y-m-d H:i:s")
        ];
        if ($this->dashboard->addToFinish($data) > 0) {
            $this->session->set_flashdata('message', "Berhasil!|success|Status sudah Finish!");
                redirect('dashboard/index');
        }
    }
    
    public function toreset($agent)
    {
        $data = [
            'agent' => $agent,
            'status' => 'blank',
            'saved_at' => null,
            'updated_at' => null
        ];
        if ($this->dashboard->toReset($data) > 0) {
            $this->session->set_flashdata('message', "Reset Berhasil!|info|Status berhasil di-reset!");
            redirect('dashboard/index');
        }
    }

    public function getVoteByAgent()
    {
        $id = $this->input->post('id');
        $result = $this->setting->getVoteListByIdByAgent($id, $this->session->userdata('user_id'));
        $data_list = array_map('trim', explode(',', $result['data_list']));
        $vote_to = $result['vote_to'];

        $path1 = '<div class="form-group row">
                        <div class="col">
                            <div class="pretty p-default">
                                <input type="radio" id="voteto';
        $path2 = '" name="vote_to" value="';
        $path3 = '"';
        $path4 = '><div class="state p-primary"><label>';
        $path5 = '</label></div></div></div></div>';

        $data = '';
        for ($i = 0; $i < count($data_list); $i++) {
            if ($data_list[$i] == $vote_to) {
                $data .=  $path1 . $data_list[$i] . $path2 . $data_list[$i] . $path3 . ' checked' . $path4 . $data_list[$i] . $path5;
            } else {
                $data .=  $path1 . $data_list[$i] . $path2 . $data_list[$i] . $path3 . $path4 . $data_list[$i] . $path5;
            }
        }
        echo $data;
    }

    public function getVoteById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('vote_list', ['id' =>$id])->row_array());
    }

    public function submitVote()
    {
        $data = [
            'vote_id' => $this->input->post('submitVoteId'),
            'vote_to' => $this->input->post('vote_to'),
            'voted_by' => $this->session->userdata('user_id'),
            'voted_at' => date("Y-m-d H:i:s")
        ];

        // check if vote to == voter
        if ($data['vote_to'] == $data['voted_by']) {
            $this->session->set_flashdata('message', 'Forbidden!|error|Tidak bisa vote untuk diri sendiri! wle..wle..wle...');
            redirect('dashboard/index');
        } else {
            // check existing vote
            if ($this->db->get_where('vote_detail', ['vote_id' => $data['vote_id'], 'voted_by' => $data['voted_by']])->num_rows() > 0) {
                if ($this->setting->submitRevisedVote($data) > 0) {
                    $this->session->set_flashdata('message', "Vote Updated!|success|Your vote successly revised");
                    redirect('dashboard/index');
                }
            } else {
                if ($this->setting->submitNewVote($data) > 0) {
                    $this->session->set_flashdata('message', "Vote Success!|success|Your New vote successly submitted!");
                    redirect('dashboard/index');
                }
            }
        }
    }

    public function summaryVote()
    {
        $id = $this->input->post('id');
        $result = $this->setting->getVoteSummaryById($id);
        $data = '<table class="table table-sm table-bordered"><thead><tr class="bg-light"><th class="text-center">Item</th><th class="text-center">Vote qty</th></tr></thead><tbody>';
        $ttl = $this->db->get_where('vote_detail', ['vote_id' => $id])->num_rows();

        foreach ($result as $row) {
            $data .= '<tr><td>' . $row['vote_to'] . '</td><td class="text-center">' . $row['qty'] . ' <small class="text-secondary">(' . number_format($row['qty'] / $ttl * 100, 1) . '%)</small></td></tr>';
        }
        $data .= '<tr class="text-bold"><td class="text-center">Total</td><td class="text-center">' . $ttl . '</td></tr></tbody></table>';
        echo $data;
    }

    public function detailResultVote()
    {
        $id = $this->input->post('id');
        $result = $this->setting->getVoteResultDetailById($id);
        $data = '<table class="table table-sm table-bordered"><thead><tr class="bg-light"><th>Vote to</th><th>Voted by</th><th class="text-center">Voted at</th></tr></thead><tbody>';

        foreach ($result as $row) {
            $data .= '<tr><td>' . $row['vote_to'] . '</td><td>' . $row['voted_by'] . '</td><td class="text-center">' . date("d M Y H:i", strtotime($row['voted_at'])) . '</td></tr>';
        }
        $data .= '</tbody></table>';
        echo $data;
    }

    public function addLebaranReport()
    {
        $newLebaranReport = [
            'year'=> date("Y", strtotime($this->input->post('addLebaranReportDate'))),
            'date'=> date("Y-m-d", strtotime($this->input->post('addLebaranReportDate'))),
            'inbound'=> $this->input->post('addLebaranReportCallInbound'),
            'acd'=> $this->input->post('addLebaranReportCallAcd'),
            'car'=> $this->input->post('addLebaranReportCallCar'),
            'wa_resolved'=> $this->input->post('addLebaranReportWhatsappResolved'),
            'wa_ongoing'=> $this->input->post('addLebaranReportWhatsappOngoing'),
            'email_replied'=> $this->input->post('addLebaranReportEmailReplied'),
            'followup'=> $this->input->post('addLebaranReportFollowup'),
            'complaint_reguler'=> $this->input->post('addLebaranReportComplaintReguler'),
            'complaint_urgent_qty'=> $this->input->post('addLebaranReportComplaintUrgentQty'),
            'complaint_urgent_detail'=> $this->input->post('addLebaranReportComplaintUrgentDetail'),
            'remark'=> $this->input->post('addLebaranReportComplaintRemark'),
            'saved_by'=> $this->session->userdata('user_id'),
            'saved_at'=> date("Y-m-d H:i:s")
        ];

        if ($this->dashboard->addNewLebaranReport($newLebaranReport) > 0) {
            $this->session->set_flashdata('message', "Success!|success|Daily data Lebaran Operation berhasil disimpan!");
            redirect('dashboard/index');
        }
    }

    public function getSingleLebaranOperationData()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('lebaran_operation', ['id' => $id])->row_array());
    }

    public function editLebaranReport()
    {
        $updateLebaranReport = [
            'id' => $this->input->post('addLebaranReportId'),
            'year'=> date("Y", strtotime($this->input->post('addLebaranReportDate'))),
            'date'=> date("Y-m-d", strtotime($this->input->post('addLebaranReportDate'))),
            'inbound'=> $this->input->post('addLebaranReportCallInbound'),
            'acd'=> $this->input->post('addLebaranReportCallAcd'),
            'car'=> $this->input->post('addLebaranReportCallCar'),
            'wa_resolved'=> $this->input->post('addLebaranReportWhatsappResolved'),
            'wa_ongoing'=> $this->input->post('addLebaranReportWhatsappOngoing'),
            'email_replied'=> $this->input->post('addLebaranReportEmailReplied'),
            'followup'=> $this->input->post('addLebaranReportFollowup'),
            'complaint_reguler'=> $this->input->post('addLebaranReportComplaintReguler'),
            'complaint_urgent_qty'=> $this->input->post('addLebaranReportComplaintUrgentQty'),
            'complaint_urgent_detail'=> $this->input->post('addLebaranReportComplaintUrgentDetail'),
            'remark'=> $this->input->post('addLebaranReportComplaintRemark'),
            'updated_by'=> $this->session->userdata('user_id'),
            'updated_at'=> date("Y-m-d H:i:s")
        ];

        if ($this->dashboard->updateLebaranReport($updateLebaranReport) > 0) {
            $this->session->set_flashdata('message', "Success!|success|Daily data Lebaran Operation berhasil di-update!");
            redirect('dashboard/index');
        }
    }

    public function uploadExcelLebaranSchedule()
    {
        //check upload folder
        $yrs = date("Y", strtotime($this->input->post('uploadExcelLebaranSchedule')));
        if (!is_dir('./assets/responsive_filemanager/source/'.$yrs)) {
            mkdir('./assets/responsive_filemanager/source/' . $yrs, 0777, TRUE);
        }

        $config['upload_path'] = './files/upload/source/'.$yrs;
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 4096;
        $config['file_name'] = 'Lebaran_Schedule_' . $yrs . '.xlsx';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('uploadExcelLebaranSchedule')) {
            $this->session->set_flashdata('message', 'Gagal Upload|error|' . strip_tags($this->upload->display_errors()));
            redirect('dashboard/index');
        }
        else {
            $this->session->set_flashdata('message', 'Berhasil Upload|success|Data piket Lebaran berhasil di-upload!');
            redirect('dashboard/index');
        }
    }

    // Fungsi keur nunda data ka session
    public function set_sholat_session() {
        $jadwal = $this->input->post('jadwal');
        if ($jadwal) {
            $this->session->set_userdata('jadwal_sholat', $jadwal);
            // Tambahan: simpen tanggal harita jang validasi isukna kudu nembak deui
            $this->session->set_userdata('tgl_sholat', date('Y-m-d'));
            echo json_encode(['status' => true]);
        }
    }

    // Fungsi keur ngecek data dina session
    public function get_sholat_session() {
        $jadwal = $this->session->userdata('jadwal_sholat');
        $tgl_session = $this->session->userdata('tgl_sholat');

        // Validasi: Mun geus ganti poe, anggap kosong sangkan nembak API anyar
        if ($tgl_session != date('Y-m-d')) {
            echo json_encode(null);
        } else {
            echo json_encode($jadwal);
        }
    }

}
