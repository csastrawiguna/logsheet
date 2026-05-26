<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menumanagement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Menumanagement_model', 'menumanagement');
        is_logged_in();
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Menu Access Management';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAccessLevel'] = $this->menumanagement->getAllAccessLevel();

        if (!$this->input->post('menumanagementSelectAccess')) {
            $data['accessLevel'] = $this->session->userdata('role_access');
        } else {
            $data['accessLevel'] = $this->input->post('menumanagementSelectAccess');
        }

        $data['role'] = $this->menumanagement->getRoleByAccessLevel($data['accessLevel']);
        $data['allMenus'] = $this->menumanagement->getAllMenus($data['accessLevel']);
        $data['unassignedMenu'] = $this->menumanagement->getUnassignedMenu($data['accessLevel']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('menumanagement/index', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-menumanagement');
    }

    public function submenu()
    {
        check_access();
        $data['title'] = 'Submenu Access Management';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAccessLevel'] = $this->menumanagement->getAllAccessLevel();

        if (!$this->input->post('menumanagementSelectAccessSubmenu')) {
            $data['accessLevel'] = $this->session->userdata('role_access');
        } else {
            $data['accessLevel'] = $this->input->post('menumanagementSelectAccessSubmenu');
        }

        $data['role'] = $this->menumanagement->getRoleByAccessLevel($data['accessLevel']);
        $data['allMenus'] = $this->menumanagement->getAllMenus($data['accessLevel']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('menumanagement/submenu', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-menumanagement');
    }

    public function accesslevel()
    {
        check_access();
        $data['title'] = 'Access Level Management';
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->result_array();
        $data['allAccessLevel'] = $this->menumanagement->getAllAccessLevel();     

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('menumanagement/accesslevel', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-menumanagement');
    }

    public function dismissMenuAccess()
    {
        $menu_id = $this->uri->segment(3);
        $role_access = $this->uri->segment(4);
        if ($this->menumanagement->performDismissMenuAccess($menu_id, $role_access) > 0) {
            $this->session->set_flashdata('message', 'Success|info|Menu access successly dismissed!');
            redirect('menumanagement/index');
        }
    }

    public function addMenuAccess()
    {
        $data = $this->input->post('data');
        if ($this->menumanagement->performAddMenuAccess($data) > 0) {
            $this->session->set_flashdata('message', 'Success|success|Menu access successly added!');
            redirect('menumanagement/index');
        }
    }

    public function toggleSubmenuAccess()
    {
        $submenuid = $this->input->post('submenuid');
        $roleaccess = $this->input->post('roleaccess');
        $checkAccess = $this->input->post('checkAccess');

        if ($checkAccess == 'false') {
            $this->menumanagement->deleteSubmenuAccess($submenuid, $roleaccess);
        } else {
            $this->menumanagement->addSubmenuAccess($submenuid, $roleaccess);
        }        
    }
}
