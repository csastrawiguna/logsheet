<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inventory_model', 'inventory');
        $this->load->library('form_validation');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'CCC Inventory Data';
        // $data['allInventory'] = $this->db->get('assets_inventory')->result_array();
        $data['allInventory'] = $this->inventory->getAllInventoriesNew();
        $data['allPcs'] = $this->db->get('assets_pc')->result_array();
        $data['allHeadsets'] = $this->db->get('assets_headset')->result_array();
        $data['allIpphones'] = $this->db->get('assets_ipphone')->result_array();
        $data['allMonitors'] = $this->db->get('assets_monitor')->result_array();        
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('inventory/index', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-aux', $data);
    }

    public function items()
    {
        check_access();
        $data['title'] = 'CCC Inventory Items';
        $data['allPc'] = $this->db->get('assets_pc')->result_array();
        $data['allMonitor'] = $this->db->get('assets_monitor')->result_array();
        $data['allIpphone'] = $this->db->get('assets_ipphone')->result_array();
        $data['allHeadset'] = $this->db->get('assets_headset')->result_array();
        $data['allOthers'] = $this->db->get('assets_others')->result_array();        

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('inventory/items', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-inventory', $data);
    }

    public function pc()
    {
        check_access();
        $data['title'] = 'CCC Inventory - PC';
        $data['allPc'] = $this->db->get('assets_pc')->result_array();

        $this->form_validation->set_rules('addAssetsPcBrand', 'PC brand', 'trim|required');
        $this->form_validation->set_rules('addAssetsPcModel', 'PC model', 'trim|required');
        $this->form_validation->set_rules('addAssetsPcSn', 'PC serial no.', 'trim|required');
        $this->form_validation->set_rules('addAssetsPcIp', 'IP address', 'trim|required');

        if ( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('inventory/pc', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data = [
            	'pc_deptown' => $this->input->post('addAssetsPcDeptown'),
                'pc_brand' => $this->input->post('addAssetsPcBrand'),
                'pc_model' => $this->input->post('addAssetsPcModel'),
                'pc_sn' => $this->input->post('addAssetsPcSn'),
                'pc_spec' => $this->input->post('addAssetsPcModel'),
                'pc_Ip' => $this->input->post('addAssetsPcIp'),
                'pc_remark' => $this->input->post('addAssetsPcRemark'),
                'pc_recdate' => $this->input->post('addAssetsPcRecdate'),
                'pc_status' => $this->input->post('addAssetsPcStatus'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
                'last_modified_by' => null,
                'last_modified_at' => null
            ];

            if ( $this->inventory->addNewPC($data) > 0 ) {
                $this->session->set_flashdata('message', 'New PC Added|success|You have added new PC!');
                redirect('inventory/pc');
            }
        }
    }

    public function deletepcbyid()
    {
        if(!$this->input->post()) {
            $pc_id = $this->uri->segment(3);
        } else {
            $pc_id = $this->input->post('pc_id');
        }

        if ( $this->inventory->deletePcById($pc_id) > 0 ) {
            $this->session->set_flashdata('message', 'Successly Deleted|info|1 PC have been removed from list!');
            redirect('inventory/items');
        }
    }

    public function getPcById()
    {
        $pc_id = $this->input->post('pc_id');
        echo json_encode($this->db->get_where('assets_pc', ['pc_id' => $pc_id])->row_array());
    }

    public function updatePcById()
    {        
        $data = [
            'pc_id' => $this->input->post('addAssetsPcId'),
            'pc_deptown' => $this->input->post('addAssetsPcDeptown'),
            'pc_brand' => $this->input->post('addAssetsPcBrand'),
            'pc_model' => $this->input->post('addAssetsPcModel'),
            'pc_sn' => $this->input->post('addAssetsPcSn'),
            'pc_spec' => $this->input->post('addAssetsPcSpec'),
            'pc_ip' => $this->input->post('addAssetsPcIp'),
            'pc_remark' => $this->input->post('addAssetsPcRemark'),
            'pc_recdate' => $this->input->post('addAssetsPcRecdate'),
            'pc_status' => $this->input->post('addAssetsPcStatus'),            
            'last_modified_by' => $this->session->userdata('user_id'),
            'last_modified_at' => date("Y-m-d h:i:s")
        ];

        if ( $this->inventory->updatePcById($data) > 0 ) {
            $this->session->set_flashdata('message', 'Successly Updated|success|PC data successly modified!');
            redirect('inventory/pc');
        }
    }

    public function monitor()
    {
        check_access();
        $data['title'] = 'CCC Inventory - Monitor';
        $data['allMonitor'] = $this->db->get('assets_monitor')->result_array();

        $this->form_validation->set_rules('addAssetsMonitorBrand', 'Monitor brand', 'trim|required');
        $this->form_validation->set_rules('addAssetsMonitorModel', 'Monitor model', 'trim|required');
        $this->form_validation->set_rules('addAssetsMonitorSn', 'PC serial no.', 'trim|required');

        if ( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('inventory/monitor', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data = [
            	'monitor_deptown' => $this->input->post('addAssetsMonitorDeptown'),
                'monitor_brand' => $this->input->post('addAssetsMonitorBrand'),
                'monitor_model' => $this->input->post('addAssetsMonitorModel'),
                'monitor_sn' => $this->input->post('addAssetsMonitorSn'),
                'monitor_size' => $this->input->post('addAssetsMonitorSize'),
                'monitor_remark' => $this->input->post('addAssetsMonitorRemark'),
                'monitor_recdate' => $this->input->post('addAssetsMonitorRecdate'),
                'monitor_status' => $this->input->post('addAssetsMonitorStatus'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
                'last_modified_by' => null,
                'last_modified_at' => null
            ];

            if ( $this->inventory->addNewPC($data) > 0 ) {
                $this->session->set_flashdata('message', 'New Monitor Added|success|You have added new monitor!');
                redirect('inventory/monitor');
            }
        }
    }

    public function headset()
    {
        check_access();
        $data['title'] = 'CCC Inventory - Headset';
        $data['allHeadset'] = $this->inventory->getAllHeadset();

        $this->form_validation->set_rules('addAssetsHeadsetBrand', 'Headset brand', 'trim|required');
        $this->form_validation->set_rules('addAssetsHeadsetModel', 'Headset model', 'trim|required');
        $this->form_validation->set_rules('addAssetsHeadsetSn', 'Headset serial no.', 'trim|required');

        if ( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('inventory/headset', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data = [
                'headset_brand' => $this->input->post('addAssetsHeadsetBrand'),
                'headset_model' => $this->input->post('addAssetsHeadsetModel'),
                'headset_sn' => $this->input->post('addAssetsHeadsetSn'),
                'headset_remark' => $this->input->post('addAssetsHeadsetRemark'),
                'headset_recdate' => $this->input->post('addAssetsHeadsetRecDate'),
                'headset_status' => $this->input->post('addAssetsHeadsetStatus'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
                'last_modified_by' => '',
                'last_modified_at' => ''
            ];

            if ( $this->inventory->addNewHeadset($data) > 0 ) {
                $this->session->set_flashdata('message', 'New Headset Added|success|You have added new headset!');
                redirect('inventory/headset');
            }
        }
    }

    public function ipphone()
    {
        check_access();
        $data['title'] = 'CCC Inventory - IP Phone';
        $data['allIpphone'] = $this->inventory->getAllIpphone();

        $this->form_validation->set_rules('addAssetsIpphoneBrand', 'IP phone brand', 'trim|required');
        $this->form_validation->set_rules('addAssetsIpphoneModel', 'IP phone model', 'trim|required');
        $this->form_validation->set_rules('addAssetsIpphoneSn', 'IP phone serial no.', 'trim|required');

        if ( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('inventory/ipphone', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data = [
                'headset_brand' => $this->input->post('addAssetsIpphoneBrand'),
                'headset_model' => $this->input->post('addAssetsIpphoneModel'),
                'headset_sn' => $this->input->post('addAssetsIpphoneSn'),
                'headset_remark' => $this->input->post('addAssetsIpphoneRemark'),
                'headset_recdate' => $this->input->post('addAssetsIpphoneRecDate'),
                'headset_status' => $this->input->post('addAssetsIpphoneStatus'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d h:i:s"),
                'last_modified_by' => '',
                'last_modified_at' => ''
            ];

            if ( $this->inventory->addNewHeadset($data) > 0 ) {
                $this->session->set_flashdata('message', 'New Headset Added|success|You have added new headset!');
                redirect('inventory/headset');
            }
        }
    }
}
