<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cash extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        check_access();
        $data['title'] = 'CCC Cash Flow';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('cash/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-aux', $data);
    }

    public function collection()
    {
        check_access();
        $data['title'] = 'Cash Collection';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('cash/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-aux', $data);
    }

    public function allocation()
    {
        check_access();
        $data['title'] = 'Cash Allocation';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('cash/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-aux', $data);
    }
}
