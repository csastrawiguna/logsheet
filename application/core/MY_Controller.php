<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $sidebar_menu = [];

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            
            // 2. Sangkan teu looping (antepkeun halaman login atanapi auth tetep tiasa diaksés)
            // Ganti 'auth' atanapi 'login' saluyu sareng nami controller login Anjeun
            if ($this->uri->segment(1) !== 'auth' && $this->uri->segment(1) !== 'index') {
                
                // Set flashdata sakedap kanggo masihan terang ka user
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your session was ended!</div>');
                
                // Direct ka halaman login
                redirect('auth/index'); 
                exit; // Wajib nganggo exit supados kode di handapna moal dijalankeun deui
            }
        }

        // Cek naha user geus login atawa acan
        if ($this->session->userdata('role_access')) {
            $this->load->model('Menu_model');
            $role_access = $this->session->userdata('role_access');

            // Tarik data menu saperti biasa
            $menu_data = $this->Menu_model->getMenuByRole($role_access);
            foreach ($menu_data as $key => $m) {
                $menu_data[$key]['submenu'] = $this->Menu_model->getSubmenuByMenu($m['id'], $role_access);
            }

            // Simpen dina properti global
            $this->sidebar_menu = $menu_data;
        }
        $this->load->vars(['sidebar_menu' => $menu_data]);
    }
}