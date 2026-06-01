<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        // is_logged_in();        
    }

    public function index()
    {
        $data['title'] = 'Form Kolektif Penukaran Voucher';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('survey/form-pesanan', $data);
        $this->load->view('templates/footer', $data);            
        
    }

    public function submitForm()
    {
        $userData = $this->db->get_where('user', ['user_id' => $this->session->userdata('user_id')])->row_array();
        $data = [
            'user_id' => $this->session->userdata('user_id'),
            'npk' => $userData['npk'],
            'nama_lengkap' => $userData['fullname'],
            'npwp' => $this->input->post('formPesananNpwp'),
            'produk1' => $this->input->post('formPesananModelUnit1'),
            'kode_voucher_1' => $this->input->post('formPesananKodeVoucher1'),
            'produk2' => $this->input->post('formPesananModelUnit2'),
            'kode_voucher_2' => $this->input->post('formPesananKodeVoucher2'),
            'produk3' => $this->input->post('formPesananModelUnit3'),
            'kode_voucher_3' => $this->input->post('formPesananKodeVoucher3'),
            'produk4' => $this->input->post('formPesananModelUnit4'),
            'kode_voucher_4' => $this->input->post('formPesananKodeVoucher4'),
            'produk5' => $this->input->post('formPesananModelUnit5'),
            'kode_voucher_5' => $this->input->post('formPesananKodeVoucher5'),
            'ambil_kirim' => $this->input->post('formPesananPilihAmbilAtauEkspedisi'),
            'alamat_kirim' => $this->input->post('formPesananAlamatKirim')
        ];

        if ($this->executeSubmitForm($data) > 0) {
            $this->session->set_flashdata('message', 'Berhasil|success|Form pesanan sudah didata!');
            redirect('form/orderlist');
        }
        
    }

    public function executeSubmitForm($data)
    {
        $this->db->insert('form_pesanan', $data);
        return $this->db->affected_rows();
    }

    public function orderlist()
    {
        $data['title'] = 'Daftar Pesanan';

        $accessAgent = 3;
        $data['allOrder'] = $this->db->get('form_pesanan')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('survey/daftar-pesanan', $data);
        $this->load->view('templates/footer', $data);    
    }

    public function deleteoder()
    {
        $id = $this->uri->segment(3);
        if ($this->executeDeleteOrder($id) > 0) {
            $this->session->set_flashdata('message', 'Dihapus|info|Pesanan sudah dihapus!');
            redirect('form/orderlist');
        }
    }

    public function executeDeleteOrder($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('form_pesanan');
        return $this->db->affected_rows();
    }

}