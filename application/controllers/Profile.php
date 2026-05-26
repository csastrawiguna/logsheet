<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Profile_model', 'profile');
        is_logged_in();        
    }

    public function index()
    {
        $data['title'] = 'Profile';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->row_array();
        $data['userDetail'] = $this->profile->getUserById($this->session->userdata['user_id']);
        $oldPassword = $data['userDetail']['password'];

        $this->form_validation->set_rules('oldPassword', 'Old password', 'required');
        $this->form_validation->set_rules('newPassword', 'New password', 'required|matches[confirmNewPassword]');
        $this->form_validation->set_rules('confirmNewPassword', 'New password', 'required|matches[newPassword]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('profile/index', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-profile');
        } else {
            if (password_verify($this->input->post('oldPassword'), $oldPassword) == false) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Old password not matched!</div>');
            } else {
                $data = [
                    'password' => password_hash($this->input->post('newPassword'), PASSWORD_BCRYPT),
                    'user_id' => $this->session->userdata('user_id')
                ];

                if ($this->profile->updatePassword($data) > 0) {
                    $this->session->set_flashdata('message', 'Password update|success|New password successly saved!');
                }
            }
            redirect('profile');
        }
    }

    public function edit()
    {
        $data['title'] = 'Edit profile';
        $data['currentData'] = $this->profile->getCurrentProfileData($this->session->userdata('user_id'));
        $data['allbackground'] = $this->profile->getAllBackground();

        $this->form_validation->set_rules('profileEditPersonalEmail', 'Personal email', 'trim|required|valid_email');
        $this->form_validation->set_rules('profileEditPersonalQuote', 'Personal quote', 'trim|max_length[202]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('profile/edit-profile', $data);
            $this->load->view('templates/footer');
        } else {
            $updateData = [
                'email_personal' => $this->input->post('profileEditPersonalEmail'),
                'quote' => $this->input->post('profileEditPersonalQuote'),
                'user_id' => $this->session->userdata('user_id')
            ];
            if ($this->profile->updatePersonalInfo($updateData) > 0) {
                $this->session->set_flashdata('message', 'Email and Quote updated|success|Personal email and quote updated!');
                redirect('profile');
            }
        }
    }

    public function changepicture()
    {
        $data['title'] = 'Preview profile picture';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('profile/profile-preview', $data);
        $this->load->view('templates/footer');
    }

    public function processuploadpicture()
    {
        if ($_FILES['profileEditProfilePicture']['error'] == 4) {
            $this->session->set_flashdata('message', 'Error|error|Belum pilih file foto!');
            redirect('profile/edit');
        } else {
            $convertedFileName = explode('.', $_FILES['profileEditProfilePicture']['name'])[0];
            $convertedFileType = explode('.', $_FILES['profileEditProfilePicture']['name'])[1];
            $config['upload_path'] = './assets/img/profile';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 800;
            $config['file_name'] =  'pp_' . strtolower($this->session->userdata('user_id')) . '_' . date("Ymd") . '.' . $convertedFileType;
            $config['overwrite'] = true;
            
            $this->load->library('upload', $config);
            //$this->upload->initialize($config);

            if (!$this->upload->do_upload('profileEditProfilePicture'))
            {
                $error = ['error' => $this->upload->display_errors()];
                $stringError = $this->upload->display_errors();
                $this->session->set_flashdata("message", "Error|error|$stringError");
                redirect('profile/edit');
            }
            else
            {
                $data = ['upload_data' => $this->upload->data()];
                $update = [
                    'user_id' => $this->session->userdata('user_id'),
                    'photo' => $config['file_name']
                ];

                if($this->profile->updatePersonalInfo($update) > 0 ) {
                    $this->session->set_flashdata('message', 'Success|success|Profile picture changed!');
                    redirect('profile');
                }
            }
        }
    }

    public function removephoto()
    {
        if($this->profile->removeProfilePhoto($this->session->userdata('user_id')) > 0 ) {
            $this->session->set_flashdata('message', 'Success|info|Profile picture removed!');
            redirect('profile');
        }
    }

    public function updatebackground()
    {
        $data = [
            'user_id' => $this->input->post('user_id'),
            'bg' => $this->input->post('bg'),
            'bg_position' => $this->input->post('bg_position')
        ];
        $this->profile->updateBackground($data);
    }

    public function setViewTheme()
    {
        $theme_id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        empty($this->input->post('method')) ? $uri = $this->input->post('controller') : $uri = $this->input->post('controller') . '/' . $this->input->post('method');
        $this->db->set('view_theme', $theme_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('user');
        $this->session->unset_userdata('theme_id', 'theme_text', 'theme_name');
        $data = [
            'theme_id' => $theme_id,
            'theme_text' => $this->input->post('theme_text'),
            'theme_name' => $this->input->post('theme_name')
        ];
        $this->session->set_userdata($data);
        redirect($uri);
    }
}
