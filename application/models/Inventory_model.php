<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends CI_Model
{	
    public function addNewPC($data)
    {
    	$this->db->insert('assets_pc', $data);
    	return $this->db->affected_rows();
    }

    public function deletePcById($pc_id)
    {
    	$this->db->where('pc_id', $pc_id);
    	$this->db->delete('assets_pc');
    	return $this->db->affected_rows();	
    }

    public function updatePcById($data)
    {
        $this->db->where('pc_id', $data['pc_id']);
        $this->db->set('pc_deptown', $data['pc_deptown']);
        $this->db->set('pc_brand', $data['pc_brand']);
        $this->db->set('pc_model', $data['pc_model']);
        $this->db->set('pc_sn', $data['pc_sn']);
        $this->db->set('pc_ip', $data['pc_ip']);
        $this->db->set('pc_spec', $data['pc_spec']);
        $this->db->set('pc_recdate', $data['pc_recdate']);
        $this->db->set('pc_remark', $data['pc_remark']);
        $this->db->set('pc_status', $data['pc_status']);
        $this->db->set('last_modified_by', $data['last_modified_by']);
        $this->db->set('last_modified_at', $data['last_modified_at']);
        $this->db->update('assets_pc');
        return $this->db->affected_rows();
    }
    
    public function addNewHeadset($data)
    {
        $this->db->insert('assets_headset', $data);
        return $this->db->affected_rows();
    }

    public function getAllHeadset()
    {
        $this->db->order_by('headset_recdate', 'DESC');
        return $this->db->get('assets_headset')->result_array();
    }

    public function getAllIpphone()
    {
        $this->db->order_by('ipphone_sn', 'DESC');
        return $this->db->get('assets_ipphone')->result_array();
    }

    public function getAllInventories()
    {
        $this->db->select('assets_inventory.id AS id');
        $this->db->select('assets_inventory.user_id AS user_id');
        $this->db->select('assets_inventory.remark AS remark');   
        $this->db->select('assets_pc.pc_id AS pc_id');
        $this->db->select('assets_pc.pc_model AS pc_model');
        $this->db->select('assets_pc.pc_ip AS pc_ip');
        $this->db->select('assets_pc.pc_spec AS pc_spec');
        $this->db->select('assets_monitor.monitor_id AS monitor_id');
        $this->db->select('assets_monitor.monitor_brand AS monitor_brand');
        $this->db->select('assets_monitor.monitor_size AS monitor_size');
        $this->db->select('assets_monitor.monitor_model AS monitor_model');        
        $this->db->select("GROUP_CONCAT(assets_monitor.monitor_id, '|', assets_monitor.monitor_size, '|', assets_monitor.monitor_brand, '|', assets_monitor.monitor_model, '|', assets_monitor.monitor_sn, '|', assets_monitor.monitor_recdate SEPARATOR '#') AS monitor_data");
        $this->db->select('assets_ipphone.ipphone_id AS ipphone_id');
        $this->db->select('assets_ipphone.ipphone_model AS ipphone_model');
        $this->db->select('assets_headset.headset_id AS headset_id');
        $this->db->select('assets_headset.headset_model AS headset_model');
        $this->db->select('assets_headset.headset_recdate AS headset_recdate');
        $this->db->select('assets_headset.headset_id AS headset_id');
        $this->db->select('assets_headset.headset_model AS headset_model');
        $this->db->select('assets_headset.headset_recdate AS headset_recdate');
        $this->db->join('assets_pc', 'assets_pc.pc_id = assets_inventory.pc_id');
        $this->db->join('assets_monitor', 'assets_monitor.monitor_id = assets_inventory.monitor_id', 'LEFT');
        $this->db->join('assets_ipphone', 'assets_ipphone.ipphone_id = assets_inventory.ipphone_id');
        $this->db->join('assets_headset', 'assets_headset.headset_id = assets_inventory.headset_id');
        $this->db->group_by('user_id');
        return $this->db->get('assets_inventory')->result_array();
    }

    public function getAllInventoriesNew()
    {
        $query = "SELECT 
                    assets_inventory.id AS id,
                    assets_inventory.user_id AS user_id,
                    assets_inventory.remark AS remark,   
                    assets_pc.pc_id AS pc_id,
                    assets_pc.pc_model AS pc_model,
                    assets_pc.pc_ip AS pc_ip,
                    assets_pc.pc_spec AS pc_spec,
                    assets_pc.pc_recdate AS pc_recdate,
                    (SELECT monitor_id FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_id,
                    (SELECT monitor_brand FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_brand,
                    (SELECT monitor_size FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_size,
                    (SELECT monitor_model FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_model,
                    (SELECT monitor_sn FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_sn,
                    (SELECT monitor_recdate FROM assets_monitor WHERE assets_inventory.monitor1_id = assets_monitor.monitor_id) AS monitor1_recdate,
                    (SELECT monitor_id FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_id,
                    (SELECT monitor_brand FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_brand,
                    (SELECT monitor_size FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_size,
                    (SELECT monitor_model FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_model,
                    (SELECT monitor_sn FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_sn,
                    (SELECT monitor_recdate FROM assets_monitor WHERE assets_inventory.monitor2_id = assets_monitor.monitor_id) AS monitor2_recdate,
                    assets_ipphone.ipphone_id AS ipphone_id,
                    assets_ipphone.ipphone_brand AS ipphone_brand,
                    assets_ipphone.ipphone_model AS ipphone_model,
                    assets_ipphone.ipphone_sn AS ipphone_sn,
                    assets_ipphone.ipphone_recdate AS ipphone_recdate,
                    assets_headset.headset_id AS headset_id,
                    assets_headset.headset_brand AS headset_brand,
                    assets_headset.headset_model AS headset_model,
                    assets_headset.headset_sn AS headset_sn,
                    assets_headset.headset_recdate AS headset_recdate
                FROM assets_inventory
                JOIN assets_pc ON assets_inventory.pc_id = assets_pc.pc_id
                JOIN assets_headset ON assets_inventory.headset_id = assets_headset.headset_id
                JOIN assets_ipphone ON assets_inventory.ipphone_id = assets_ipphone.ipphone_id
                LEFT JOIN assets_monitor ON assets_inventory.monitor1_id = assets_monitor.monitor_id OR assets_inventory.monitor2_id = assets_monitor.monitor_id
                -- LEFT JOIN assets_monitor ON assets_inventory.monitor2_id = assets_monitor.monitor_id
                GROUP BY assets_inventory.user_id";
        return $this->db->query($query)->result_array();
    }
}
