<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Setting_model', 'setting');
        $this->load->model('Obidience_model', 'obidience');
        $this->load->model('Productivity_model', 'productivity');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'General Setting';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();

        $data['allDashboardInfo'] = $this->setting->getAllRawGeneralInfo();
        $data['allItemsInfo'] = $this->db->get('dashboard_item')->result_array();
        $data['surveyItem'] = $this->db->get('survey_setting')->row_array()['show_survey'];
        $data['minQtySurvey'] = $this->db->get('survey_setting')->row_array()['qty_min'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('setting/general-setting', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-setting');
    }

    public function toggleDashboardItem()
    {
        if(!$this->input->post('value') || !$this->input->post('id')) {
            $data['id'] = $this->uri->segment(3);
            $this->uri->segment(4) == 0 ? $data['is_active'] = 1 : $data['is_active'] = 0;
        } else {
            $data['id'] = $this->input->post('id'); 
            $this->input->post('value') == 0 ? $data['is_active'] = 1 : $data['is_active'] = 0;        
        }

        if($this->setting->setDashboardItemStatus($data) > 0) {
            redirect('setting/index');
        }
    }

    public function toggleSurveyDisplay()
    {
        $status = $this->uri->segment(3) == 1 ? $status = 0 : $status = 1;
        if ($this->setting->performToggleSurveyDisplay($status) > 0 ) {
            redirect('setting/index');
        }
    }

    public function setSurveyActiveness()
    {        
        $data = [
            'show_survey' => $this->input->post('buttonActivateSurvey'),
            'qty_min' => $this->input->post('minQtySurvey')
        ];

        $this->setting->updateSurveyActiveness($data);
        redirect('setting/index');
        //echo "<script>location.reload(false)</script>";
        
    }

    public function overtime()
    {
        check_access();
        $data['title'] = 'Overtime Setting';

        $this->form_validation->set_rules('maximumOvertimeHourSeidPermanent', 'Maximum overtime hour SEID', 'integer');
        $this->form_validation->set_rules('maximumOvertimeHourSeidContract', 'Maximum overtime hour SEID', 'integer');
        $this->form_validation->set_rules('maximumOvertimeHourOts', 'Maximum overtime hour OTS', 'integer');
        if($this->form_validation->run() == false ) {            
            $data['maximumOvertimeHourSeidPermanent'] = $this->db->get_where('overtime_setting', ['employement' => 'Permanent'])->row_array()['upper_limit'];
            $data['maximumOvertimeHourSeidContract'] = $this->db->get_where('overtime_setting', ['employement' => 'Contract'])->row_array()['upper_limit'];
            $data['maximumOvertimeHourOts'] = $this->db->get_where('overtime_setting', ['employement' => 'OTS'])->row_array()['upper_limit'];
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/overtime-setting', $data);
            $this->load->view('templates/footer');
        } else {
            $updateData = [
                [
                    'employement' => 'Permanent',
                    'upper_limit' =>$this->input->post('maximumOvertimeHourSeidPermanent')
                ],
                [
                    'employement' => 'Contract',
                    'upper_limit' =>$this->input->post('maximumOvertimeHourSeidContract')
                ],
                [
                    'employement' => 'OTS',
                    'upper_limit' =>$this->input->post('maximumOvertimeHourOts')
                ]
            ];
            if($this->obidience->setMaximumOvertimeHour($updateData) > 0) {
                $this->session->set_flashdata('message', "Succesly Updated!|success|Maximum overtime hour updated");
                redirect('setting/overtime');
            }
        }
    }

    public function leave()
    {
        check_access();
        $data['title'] = 'Leave Setting';

        $this->form_validation->set_rules('settingMaxLeaveDaily', 'Maximum overtime hour', 'integer');

        if($this->form_validation->run() == false ) {            
            $data['maxLeavePerDay'] = $this->setting->getMaxLeavePerDay();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/leave-setting', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->setting->setMaxLeavePerDay($this->input->post('settingMaxLeaveDaily')) > 0) {
                $this->session->set_flashdata('message', 'Data saved|success|You have set maximum leave per day!');
                redirect('setting/leave');
            }
        }
    }

    public function kpi()
    {
        check_access();
        $data['title'] = 'KPI Setting';
        $data['allFiscals'] = $this->setting->getAllFiscals();
        
        if (!$this->input->post('kpiTargetSelectFiscal')) {
            $data['latestFiscal'] = $this->setting->getLatestFiscals()['fiscal'];
        } else {
            $data['latestFiscal'] = $this->input->post('kpiTargetSelectFiscal'); 
        }
        
        $data['allTargets'] = $this->setting->getAllTarget($data['latestFiscal']);
        $data['allKpiMeasurement'] = $this->setting->getAllKpiMeasurement($data['latestFiscal']);
        $data['allJobdesks'] = $this->setting->getAllJobdesk();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('setting/kpi-setting', $data);
        $this->load->view('templates/footer');
    }

    public function kpiAdd()
    {
    	$data['title'] = 'Add KPI target';
        $data['allJobdesks'] = $this->setting->getAllJobdesk();
        $data['allFiscals'] = $this->setting->getAllFiscals();
        
        if (!$this->input->post('kpiAddSelectFiscal')) {
            $data['jobdesk'] = $this->setting->getLatestFiscals()['fiscal'];
        } else {
            $data['jobdesk'] = $this->input->post('kpiTargetSelectFiscal'); 
        }

        !$this->input->post('kpiNewTargetAddRowQty') ? $data['rowQty'] = 3 : $data['rowQty'] = $this->input->post('kpiNewTargetAddRowQty');

        for($i = 0; $i < $data['rowQty']; $i++){
            $this->form_validation->set_rules('kpiNewTargetAddItem' . $i,'Weight', 'required');
            $this->form_validation->set_rules('kpiNewTargetAddWeight' . $i,'Weight', 'required|numeric');
            $this->form_validation->set_rules('kpiNewTargetAddTarget' . $i,'Weight', 'required|numeric');
        }

        if ( $this->form_validation->run() == false ) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/kpi-target-add', $data);
            $this->load->view('templates/footer');
        } else {
            $dataSubmit = [];
            for($i = 0; $i < $data['rowQty']; $i++) {
                $dataSubmit[] = [
                'fiscal' => $this->input->post('kpiNewTargetAddFiscal'),
                'jobcode' => 'cs-ccc-cc10',
                'item' => $this->input->post('kpiNewTargetAddItem' . $i),
                'description' => $this->input->post('kpiNewTargetAddDesc' . $i),
                'weight' => $this->input->post('kpiNewTargetAddWeight' . $i),
                'target' => $this->input->post('kpiNewTargetAddTarget' . $i),
            ];
            }

            if ( $this->setting->addNewKpiTarget($dataSubmit) > 0){
                $this->session->set_flashdata('message', 'KPI target|success|New KPI target saved!');
            }
            redirect('setting/kpi');
        }
    }

    public function kpiMeasurementAdd()
    {
        $data['title'] = 'Add KPI Measurement';
        $data['allJobdesks'] = $this->setting->getAllJobdesk();
        $data['allFiscals'] = $this->setting->getAllFiscals();
    }

    public function breakschedule()
    {
    	check_access();
		$data['title'] = 'Allocate break schedule';

		if (!$this->input->post('breakScheduleSearchDateInput')) {
            $curdate = $this->setting->getLatestDate();
        } else {
            $curdate = date("Y-m-d", strtotime($this->input->post('breakScheduleSearchDateInput')));
        }
        $latestDate = $this->setting->getLatestDate();
		$data['breakSchedule'] = $this->setting->getBreakSchedule($curdate);
        $data['breakDate'] = $this->setting->getBreakDate();
		$data['agents'] = $this->setting->getAllAgent();
		$data['unAllocatedBreak'] = $this->setting->getUnallocatedBreak($curdate);

		$this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('setting/breakschedule');
        $this->load->view('templates/footer');
    }

    public function modalNewCopyBreakSchedule()
    {
        //insert New Break Date
        $newDate = [
            'date_start' => $this->input->post('copyBreakScheduleStartdate'),
            'date_end' => $this->input->post('copyBreakScheduleEnddate'),
            'remark' => $this->input->post('copyBreakScheduleRemark'),
            'saved_by' => $this->session->userdata('user_id'),
            'saved_at' => date("Y-m-d H:i:s")
        ];
        $dateId = $this->setting->insertNewBreakDate($newDate);

        // copy detail break allocation
        $latesBreakDateId = $this->input->post('copyBreakScheduleSourceid');
        if ($this->setting->copyBreakSchedule($dateId, $latesBreakDateId) > 0) {
            $this->session->set_flashdata('message', 'Copied!|success|New break copied form last one!');
            redirect('setting/breakschedule');
        }
    }

    public function deleteBreakScheduleGroup($id)
    {
        if ($this->setting->deleteScheduleGroup($id) > 0) {
            if ($this->setting->deleteScheduleDetail($id) > 0) {
                $this->session->set_flashdata('message', 'Deleted!|info|Schedule Group and Detail deleted!');
                redirect('setting/breakschedule');
            } else {
                $this->session->set_flashdata('message', 'Deleted!|info|Only Group Schedule deleted!');
                redirect('setting/breakschedule');
            }
        }
    }

    public function updateBreakScheduleGroup()
    {
        $data = $this->input->post('dataUpdate');
        // $data = $this->input->post('dataCollectBreakScheduleUpdate');
        $updated = [];
        for($i = 0; $i < count($data); $i ++) {
            if ((int) $data[$i]['1'] > 0) {
                $updated[] = [
                    'break_date_id' => (int) $data[$i]['0'],
                    'break_group' => (int) $data[$i]['1'],
                    'name' => $data[$i]['2'],
                ];
            }
        }

        // delete break schedule
        if ($this->setting->deleteScheduleDetail($updated[0]['break_date_id']) > 0 ) {
            if ($this->setting->insertBatchScheduleDetail($updated) > 0) {
                $result = '<script>window.location.reload(false);</script>';
                echo json_encode($result);
                // $this->session->set_flashdata('message', 'Success!|info|Break schedule already updated!');
                // redirect('setting/breakschedule');
            }
        }
    }

    public function deleteBreakSchedule()
    {
    	$id = $this->uri->segment(3);
    	if ($this->setting->performDeleteBreackScheduleById($id) > 0) {
    		$this->session->set_flashdata('message', 'Success!|success|Break schedule deleted!');
    		redirect('setting/breakschedule');
    	}
    }

    public function addNewBreakSchedule()
    {
    	$data = [
    		'break_date_id' => 2,
    		'break_group' => $this->input->post('addNewBreakScheduleGroup'),
    		'name' => $this->input->post('addNewBreakScheduleName')
    	];

    	if ($this->setting->performAddNewBreakSchedule($data) > 0) {
    		$this->session->set_flashdata('message', 'Success!|success|Break schedule added!');
    		redirect('setting/breakschedule');
    	}
    }

    public function updateBreakSchedule()
    {
    	$data = $this->input->post('schedule');
    	if ($this->setting->performUpdateBreakSchedule($data) > 0) {
            $this->session->set_flashdata('message', 'Success|success|Break schedule updated!');
            redirect('setting/breakschedule');
        }
    }

    public function breakdateById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->setting->getBreakdateById($id));
    }

    public function modalEditBreakdate()
    {
        $updateData = [
            'id' => $this->input->post('editBreakdateId'),
            'date_start' => $this->input->post('editBreakdateStartdate'),
            'date_end' => $this->input->post('editBreakdateEnddate'),
            'remark' => $this->input->post('editBreakdateRemark'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        if ($this->setting->performUpdateBreakdate($updateData) > 0) {
            $this->session->set_flashdata('message', 'Success|success|Break date updated!');
            redirect('setting/breakschedule');
        }    
    }

    public function dailyproductivity()
    {
        check_access();
        $data['title'] = 'Daily Productivity';
        $data['allProductivityDailyTarget'] = $this->productivity->getAllProductivityDailyTarget();

        $this->form_validation->set_rules('target_cs-ccc-cc10', 'Target CA senior', 'integer');
        $this->form_validation->set_rules('target_cs-ccc-cc11', 'Target CA <6 bulan', 'integer');
        $this->form_validation->set_rules('target_cs-ccc-cc12', 'Target CA 6 - 12 bulan', 'integer');
        $this->form_validation->set_rules('target_cs-ccc-cc13', 'Target Agent WA', 'integer');
        $this->form_validation->set_rules('target_cs-ccc-cc20', 'Target Product Asst.', 'integer');
        $this->form_validation->set_rules('target_cs-ccc-cc30', 'Target Part Specialist', 'integer');


        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/dailyproductivity', $data);
            $this->load->view('templates/footer');            
        } else {
            $data = [                
                [
                    'jobcode' => 'cs-ccc-cc10',
                    'target' =>$this->input->post('target_cs-ccc-cc10')
                ],
                [
                    'jobcode' => 'cs-ccc-cc11',
                    'target' =>$this->input->post('target_cs-ccc-cc11')
                ],
                [
                    'jobcode' => 'cs-ccc-cc12',
                    'target' =>$this->input->post('target_cs-ccc-cc12')
                ],
                [
                    'jobcode' => 'cs-ccc-cc13',
                    'target' =>$this->input->post('target_cs-ccc-cc13')
                ],
                [
                    'jobcode' => 'cs-ccc-cc14',
                    'target' =>$this->input->post('target_cs-ccc-cc14')
                ],
                [
                    'jobcode' => 'cs-ccc-cc15',
                    'target' =>$this->input->post('target_cs-ccc-cc15')
                ],
                [
                    'jobcode' => 'cs-ccc-cc20',
                    'target' =>$this->input->post('target_cs-ccc-cc20')
                ],
                [
                    'jobcode' => 'cs-ccc-cc30',
                    'target' =>$this->input->post('target_cs-ccc-cc30')
                ],
                [
                    'jobcode' => 'cs-ccc-cc40',
                    'target' =>$this->input->post('target_cs-ccc-cc40')
                ]
            ];

            if($this->productivity->setProductivityDailyTarget($data) > 0) {
                $this->session->set_flashdata('message', "Succesly Updated!|success|Target of daily productivity updated");
                redirect('setting/dailyproductivity');
            }
        }
    }

    public function addgeneralinfo()
    {
        $data['title'] = 'Add General Information';

        $this->form_validation->set_rules('settingAddGeneralInfoDetail', 'Detail Info', 'required|min_length[20]');
        $this->form_validation->set_rules('settingAddGeneralInfoStatus', 'Status', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/add-general-info', $data);
            $this->load->view('templates/footer'); 
        } else {
            $newData = [
                'detail_info' => $this->input->post('settingAddGeneralInfoDetail'),
                'status' => $this->input->post('settingAddGeneralInfoStatus'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
            ];
            
            if ($this->setting->insertSingleGeneralInfo($newData) > 0) {
                $this->session->set_flashdata('message', 'Sucessly added|success|New info successly added!');
                redirect('setting');
            }
        }
    }

    public function editgeneralinfo($id)
    {
        $data['title'] = 'Edit General Information';
        $data['infoContent'] = $this->setting->getGeneralInfoById($id);

        $this->form_validation->set_rules('settingEditGeneralInfoDetail', 'Detail Info', 'required|min_length[20]');
        $this->form_validation->set_rules('settingEditGeneralInfoStatus', 'Status', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/edit-general-info', $data);
            $this->load->view('templates/footer'); 
        } else {
            $updateData = [
                'id' => $this->input->post('settingEditGeneralInfoId'),
                'detail_info' => $this->input->post('settingEditGeneralInfoDetail'),
                'status' => $this->input->post('settingEditGeneralInfoStatus'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
            
            if ($this->setting->editSingleGeneralInfo($updateData) > 0) {
                $this->session->set_flashdata('message', 'Sucessly added|success|Content info successly edited!');
                redirect('setting');
            }
        }
    }

    public function deletegeneralinfo($id)
    {
        if ($this->setting->deleteSingleGeneralInfo($id) > 0) {
            $this->session->set_flashdata('message', 'Deleted|info|Content info successly deleted!');
            redirect('setting');
        }
    }

    public function test()
    {
        var_dump($_POST);
        echo "<br>";
        var_dump($_FILES);
    }

    public function workingcalendar()
    {
        check_access();
        $data['title'] = 'Working calendar';
        $data['allWorkingCalendar'] = $this->setting->getAllWorkingCalendar();

        $this->form_validation->set_rules('addNewWorkingCalendarMonth', 'Month-Year', 'required|trim');
        $this->form_validation->set_rules('addNewWorkingCalendarDays', 'Number working days', 'required|trim|integer');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/working-calendar', $data);
            $this->load->view('templates/footer'); 
        } else {
            $newData = [
                'working_month' => date("Y-m-01", strtotime($this->input->post('addNewWorkingCalendarMonth'))),
                'working_day' => $this->input->post('addNewWorkingCalendarDays')
            ];
            // cek jika sudah existing
            if ($this->setting->getSingleWorkingMonth($newData['working_month'])) {
                $this->session->set_flashdata('message', 'Data Existing|error|Working month already exist!');
                redirect('setting/workingcalendar');
            } else {
                if ($this->setting->addSingleWorkingMonth($newData) > 0) {
                    $this->session->set_flashdata('message', 'Successly added|success|New working month added!');
                    redirect('setting/workingcalendar');
                }
            }
        }
    }

    public function addMultipleWorkingcalendar()
    {
        $data['title'] = 'Add Multiple Working Calendar';
        $this->form_validation->set_rules('addNewWorkingCalendarMonth', 'Month-Year', 'required|trim');
        $this->form_validation->set_rules('addNewWorkingCalendarDays', 'Number working days', 'required|trim|integer');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/working-calendar-multiple', $data);
            $this->load->view('templates/footer'); 
        } else {

        }
    }

    public function deleteWorkingmonth($id)
    {
        if ($this->setting->deleteWorkingMonth($id) > 0) {
            $this->session->set_flashdata('message', 'Deleted|info|Working month deleted!');
            redirect('setting/workingcalendar');
        }
    }

    public function workingmonthById()
    {
        echo json_encode($this->setting->getSingleWorkingMonthById($this->input->post('id')));
    }

    public function editWorkingmonth()
    {
        $updateData = [
            'id' => $this->input->post('addNewWorkingCalendarId'),
            'working_month' => date("Y-m-01", strtotime($this->input->post('addNewWorkingCalendarMonth'))),
            'working_day' => $this->input->post('addNewWorkingCalendarDays')
        ];
        if ($this->setting->performEditWorkingMonth($updateData) > 0) {
            $this->session->set_flashdata('message', 'Successly Updated|success|Working month updated!');
            redirect('setting/workingcalendar');
        }
    }

    public function vote()
    {
        check_access();
        $data['title'] = 'General Votting';
        $data['allVotes'] = $this->setting->getAllVotes();

        $this->form_validation->set_rules('addGneralVoteName', 'Vote name', 'trim|required');
        $this->form_validation->set_rules('addGneralVoteDesc', 'Description', 'trim');
        $this->form_validation->set_rules('addGneralVoteDatalist', 'Data list', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('setting/general-vote', $data);
            $this->load->view('templates/footer'); 
        } else {
            $newVote = [
                'vote_name' => $this->input->post('addGneralVoteName'),
                'vote_desc' => $this->input->post('addGneralVoteDesc'),
                'data_list' => $this->input->post('addGneralVoteDatalist'),
                'vote_start' => $this->input->post('addGneralVoteDateStart'),
                'vote_end' => $this->input->post('addGneralVoteDateEnd'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s")
            ];
            
            if ($this->setting->addNewGeneralVote($newVote) > 0) {
                $this->session->set_flashdata('message', 'New Vote Added|success|New Voting successly added to roll!');
                redirect('setting/vote');
            }
        }
    }

    public function toggleGeneralVote()
    {
        $stts = $this->input->post('stts');
        $id = $this->input->post('id');
        echo json_encode($stts);
        $this->setting->toggleVoteStatus($id, $stts);
    }

    public function generalVoteById()
    {
        echo json_encode($this->db->get_where('vote_list', ['id' => $this->input->post('id')])->row_array());
    }

    public function editGeneralVote()
    {
        $updateVote = [
            'id' => $this->input->post('addGneralVoteId'),
            'vote_name' => $this->input->post('addGneralVoteName'),
            'vote_desc' => $this->input->post('addGneralVoteDesc'),
            'data_list' => $this->input->post('addGneralVoteDatalist'),
            'vote_start' => $this->input->post('addGneralVoteDateStart'),
            'vote_end' => $this->input->post('addGneralVoteDateEnd'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        
        if ($this->setting->editGeneralVote($updateVote) > 0) {
            $this->session->set_flashdata('message', 'Updated!|success|Voting data on list successly updated!');
            redirect('setting/vote');
        }
    }

    public function deleteGeneralVote()
    {
        $id = $this->uri->segment(3);
        if ($this->setting->deleteVoteById($id) > 0) {
            $this->session->set_flashdata('message', 'Success|info|One of Voting successly removed from list!');
            redirect('setting/vote');
        }
    }

}
