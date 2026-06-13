<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Auth_model', 'auth');
    }

    public function index()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login to Logsheet';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $user_id = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->auth->getUser($user_id);

        //cek user
        if ($user) {
            //jika user aktif
            if ($user['is_active'] == 1) {
                if ($user['is_locked'] == 1) {
                    $this->_locked($user_id);
                } else {
                    //cek password     
                    if (password_verify($password, $user['password'])) {                        
                        $data = [
                            'user_id' => $user['user_id'],
                            'is_active' => $user['is_active'],
                            'jobcode' => $user['jobcode'],
                            'employement' => $user['status'],
                            'role_access' => $user['role_access'],
                            'role' => $user['role'],
                            'userfullname' => $user['fullname'],
                            'ip_address' => $this->input->ip_address(),
                            'theme_name' => $user['theme_name'],
                            'theme_text' => $user['theme_text'],
                            'theme_id' => $user['theme_id'],
                            'profile_photo' => $user['profile_photo'],
                            'user_password' => $password
                        ];
                        $this->session->set_userdata($data);
                        if ($this->auth->updateLoginIp($data['user_id'], $data['ip_address']) > 0 ) {
                            redirect('dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Incorrect password!</div>');
                        redirect('auth');
                    }
                }
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    This username was inactive</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Username invalid or not registered</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">You have been logout!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = 'Access forbidden';
        $this->load->view('auth/blocked', $data);
    }

    public function banned()
    {
        $data['title'] = 'User Account Banned';
        $this->load->view('auth/banned', $data);
    }

    private function _locked($user_id = null)
    {
        $data['title'] = 'User locked';
        $data['user_id'] = $user_id;
        $this->load->view('auth/locked', $data);
    }

    public function resetAccount()
    {
        $ip = $this->input->post('locked_ipaddress');
        $user_id = $this->input->post('locked_userid');
        $data = [
            'user_id' => $user_id,
            'ip_address' => $ip,
            'datetime' => date("Y-m-d H:i:s"),
            'reason' => 'locked',
            'is_reseted' => 1
        ];
        if ($this->auth->requestResetAccount($data) > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset request submited!</div>');
            redirect('auth');
        }
    }

    public function formResetPassword()
    {
        $data['title'] = 'Reset password request';
        $this->load->view('auth/password_reset', $data);
    }

    public function resetPassword()
    {
        $ip = $this->input->post('reset_ipaddress');
        $user_id = $this->input->post('reset_userid');
        $data = [
            'user_id' => $user_id,
            'ip_address' => $ip,
            'datetime' => date("Y-m-d H:i:s"),
            'reason' => 'forgot password',
            'is_unlocked' => 1
        ];
        if ($this->auth->requestResetAccount($data) > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset request submited!</div>');
            redirect('auth');
        }
    }
}
