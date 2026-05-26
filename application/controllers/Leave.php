<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leave extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Leave_model', 'leave');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Leave';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();        

        $this->load->view('templates/header-leave', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('leave/index', $data);
        $this->load->view('templates/footer-leave');
        // $this->load->view('templates/footer-leave');
    }

    public function allCalendarData()
    {
        $data = [];
        $result = $this->leave->getAllLeaveData();
        foreach ($result as $rs) {
            $data[] = [
                'title' => $rs['agent'],
                'start' => $rs['start_date'],
                'end' => $rs['end_date'],
                'color' => $rs['color'],
                'id' => $rs['id'],
                'reason' => $rs['reason'],
                'description' => $rs['description'],
                'permitType' => $rs['permit_type']
            ];
        }
        echo json_encode($data);
    }

    public function addNewLeave()
    {
        $this->form_validation->set_rules('addLeaveType', 'Type of leave', 'required');
        $this->form_validation->set_rules('addLeaveReason', 'Reason of leave', 'required');
        $this->form_validation->set_rules('addLeaveDescription', 'Description', 'trim|required');
        
        if($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', 'Reason please...|error|Please input Reason and Description of leave!');
            redirect('leave');
        } else {
            // check enddate and startdate        
            if ($this->input->post('addLeaveStartDate') > $this->input->post('addLeaveEndDate')) {
                $this->session->set_flashdata('message', 'Wrong input date|error|Start date should be earlier or smaller!');
                redirect('leave');
            } else if ($this->input->post('addLeaveStartDate') == $this->input->post('addLeaveEndDate')) {
                $enddate = date("Y-m-d", strtotime("+1 day", strtotime($this->input->post('addLeaveEndDate'))));
            } else {
                $enddate = date("Y-m-d", strtotime("+1 day", strtotime($this->input->post('addLeaveEndDate'))));
            }

            $data = [
                'agent' => $this->session->userdata('user_id'),
                'permit_type' => $this->input->post('addLeaveType'),
                'reason' => $this->input->post('addLeaveReason'),
                'description' => $this->input->post('addLeaveDescription'),
                'start_date' => $this->input->post('addLeaveStartDate'),
                'end_date' => $enddate,
                'permit_status' => 'new',
                'color' => '#007bff',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => $this->session->userdata('user_id'),
                'last_modified_at' => null,
                'last_modified_by' => null
            ];

            // get max leave per day
            $maxLeave = $this->db->get('leave_setting')->row_array()['max_leave'];

            // Check if date and agent existing at database
            if ($this->leave->checkExistingLeave($data) > 0) {
                $this->session->set_flashdata('message', 'Proposal existing|warning|You have proposed leave on those date!');
                redirect('leave/index');
            } else {  
                // check existing leave on certain date          
                if ($this->leave->checkLeaveOnDate($this->input->post('addLeaveStartDate'), $this->input->post('addLeaveEndDate')) >= $maxLeave) {
                    $this->session->set_flashdata('message', 'Quota exceeded|error|It reach maximum quota for leave!');
                    redirect('leave/index');
                } else {
                    if ($this->leave->addNewLeave($data) > 0) {
                        $this->session->set_flashdata('message', "Succesly proposed!|success|Leave proposal successly submited!");
                        redirect('leave/index');
                    } else {
                        $this->session->set_flashdata('message', 'Failed|error|Failed to propose leave!');
                        redirect('leave/index');
                    }
                }
            }
        }
    }

    public function getEventById()
    {
        $id = $this->input->post('id');
        echo json_encode($this->leave->getEventById($id));
    }

    public function getEventByDate()
    {
        $date = $this->input->post('date');
        echo json_encode($this->leave->getEventByDate($date));
    }

    public function deleteEventById()
    {
        if (!$this->input->post('id')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('id');
        }
        if ($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9) {
            if ($this->leave->deleteEventById($id) > 0) {
                $this->session->set_flashdata('message', "Succesly deleted!|info|Your leave proposal successly deleted!");
                redirect('leave/index');
            }            
        } else {
            if ($this->leave->getEventById($id)['agent'] != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('message', 'Access Denied!|error|Your have no permission to perform this action!');
                redirect('leave/index');
            } else{
                if ($this->leave->deleteEventById($id) > 0) {
                $this->session->set_flashdata('message', 'Successly deleted|info|Your leave proposal successly deleted!');
                redirect('leave/index');
            }  
            }
        } 
    }

    public function dropEventById()
    {
        if (!$this->input->post('id')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('id');
        }
        if ($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9) {
            if ($this->leave->dropEventById($id) > 0) {
                $this->session->set_flashdata('message', "Succesly deleted!|info|Your leave proposal successly deleted!");
                redirect('leave/index');
            }            
        } else {
            if ($this->leave->getEventById($id)['agent'] != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('message', 'Access Denied!|error|Your have no permission to perform this action!');
                redirect('leave/index');
            } else {
                if ($this->leave->dropEventById($id) > 0) {
                    $this->session->set_flashdata('message', "Succesly deleted!|info|Your leave proposal successly deleted!");
                    redirect('leave/index');
                }   
            }
        } 
    }

    public function updateEventById()
    {
        // set status color
        $status = $this->input->post('addLeaveStatus');
        if (strtolower($status) == 'approved') {
            $color = '#28a745';
        } else if (strtolower($status) == 'rejected') {
            $color = '#dc3545';
        } else if (strtolower($status) == 'cancelled') {
            $color = '#6c757d';
        } else {
            $color = '#007bff';
        }

        $data = [
            'id' => $this->input->post('addLeaveId'),
            'permit_type' => $this->input->post('addLeaveType'),
            'reason' => $this->input->post('addLeaveReason'),
            'description' => $this->input->post('addLeaveDescription'),
            'start_date' => $this->input->post('addLeaveStartDate'),
            'end_date' => $this->input->post('addLeaveEndDate'),
            'color' => $color,
            'permit_status' => $status,
            'last_modified_by' => $this->session->userdata('user_id'),
            'last_modified_at' => date("Y-m-d H:i:s")
        ];

        if ($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9){
            if ($this->leave->updateEventById($data) > 0) {
                $this->session->set_flashdata('message', 'Successly updated|success|Your leave proposal successly updated!');
                redirect('leave/index');
            }
        } else {
            if($this->leave->getEventById($this->input->post('addLeaveId'))['agent'] != $this->session->userdata('user_id')){
                $this->session->set_flashdata('message', 'Access denied|error|You have no access to perform this action!');
                redirect('leave/index');
            }
            // } else{
            //     $this->session->set_flashdata('message', 'Successly updated|success|Leave proposal successly updated!');
            //     redirect('leave/index');
            // }
        }
    }

    public function summary()
    {
        check_access();
        $data['title'] = 'Summary of Leave';

        $this->load->view('templates/header-leave', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('leave/summary', $data);
        $this->load->view('templates/footer-leave');
        // $this->load->view('templates/footer-leave');
    }    
}
