<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chat_model extends CI_Model
{
    public function getAllChat($timelimit)
    {
        $query = "SELECT chat.id AS id, chat.userid AS userid, chat.message AS message, chat.datetime AS datetime, chat.replied_to AS replied_to, chat.quota_limit AS quota_limit, user.initial_name AS initial_name, chat.tagged_by AS tagged_by, user.photo AS photo, chat.is_sticky AS is_sticky, chat.note_sticky AS note_sticky FROM chat JOIN user ON user.user_id = chat.userid WHERE chat.datetime >= '$timelimit' ORDER BY DATE_FORMAT(chat.datetime, '%Y-%m-%d') DESC, chat.datetime DESC";
        return $this->db->query($query)->result_array();
    }

    public function getChatsAfterId($id)
    {
        $query = "SELECT chat.id AS id, chat.userid AS userid, chat.message AS message, chat.datetime AS datetime, chat.replied_to AS replied_to, chat.quota_limit AS quota_limit, user.initial_name AS initial_name, chat.tagged_by AS tagged_by, user.photo AS photo, chat.is_sticky AS is_sticky, chat.note_sticky AS note_sticky FROM chat JOIN user ON user.user_id = chat.userid WHERE chat.datetime > '$id' ORDER BY DATE_FORMAT(chat.datetime, '%Y-%m-%d') DESC, chat.datetime DESC";
        return $this->db->query($query)->result_array();
    }

    public function getAllChatStickyFirst($timelimit)
    {
        $query = "SELECT chat.id AS id, chat.userid AS userid, chat.message AS message, chat.datetime AS datetime, chat.replied_to AS replied_to, chat.tagged_by AS tagged_by, chat.quota_limit AS quota_limit, user.initial_name AS initial_name, user.photo AS photo, chat.is_sticky AS is_sticky, chat.note_sticky AS note_sticky FROM chat JOIN user ON user.user_id = chat.userid WHERE chat.datetime >= '$timelimit' ORDER BY DATE_FORMAT(chat.datetime, '%Y-%m-%d') DESC, chat.is_sticky DESC, chat.datetime DESC";
        return $this->db->query($query)->result_array();
    }

    public function getAllStickyChat($timelimit)
    {
        $query = "SELECT chat.id AS id, chat.userid AS userid, chat.message AS message, chat.datetime AS datetime, chat.quota_limit AS quota_limit, user.initial_name AS initial_name, chat.tagged_by AS tagged_by, user.photo AS photo, chat.is_sticky AS is_sticky, chat.note_sticky AS note_sticky FROM chat JOIN user ON user.user_id = chat.userid WHERE chat.datetime >= '$timelimit' AND chat.is_sticky = 1 ORDER BY DATE_FORMAT(chat.datetime, '%Y-%m-%d') DESC, chat.is_sticky DESC, chat.datetime DESC";
        return $this->db->query($query)->result_array();
    }

    public function performAutoDelete($dateLimit)
    {
        $this->db->where('datetime <', $dateLimit);
        $this->db->delete('chat');
    }

    public function performDismissSticky($id)
    {
        $this->db->where('id', $id);
        $this->db->update('chat', ['is_sticky' => 0, 'note_sticky' => null]);
    }

    public function postNewMessage($data)
    {
        $this->db->insert('chat', $data);
        return $this->db->affected_rows();
    }

    public function editMessage($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('chat', $data);
        return $this->db->affected_rows();
    }

    public function checkTaggedMessage($id)
    {
        $this->db->where('id', $id);
        $this->db->where('tagged_by', 'open');
        return $this->db->get('chat')->row_array()['tagged_by'];
    }

    public function setTagMessage($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->set('tagged_by', $user_id);
        $this->db->update('chat');
        return $this->db->affected_rows();
    }

    public function checkExistingQuery($full = FALSE)
    {
        if ($full) {
            $query = $this->db->query('SHOW FULL PROCESSLIST');
        } else {
            $query = $this->db->query('SHOW PROCESSLIST');
        }
        return $query->result_array();
    }

    public function searchChatMessage($clue, $datelimit)
    {
        $this->db->like('message', $clue);
        $this->db->where('datetime >=', $datelimit);
        $this->db->order_by('datetime', 'DESC');
        return $this->db->get('chat')->result_array();
    }

    public function getExistingChatSetting()
    {
        return $this->db->get('chat_setting')->result_array();
    }

    public function updateChatSetting($data)
    {
        $this->db->update_batch('chat_setting', $data, 'item');
        return $this->db->affected_rows();
    }

    public function getRepliedTo($id)
    {
        $this->db->select('id, userid, message');
        $this->db->where('id', $id);
        return $this->db->get('chat')->row_array();
    }

    public function getLatestChatId()
    {
        $this->db->select('MAX(id) AS id');
        return $this->db->get('chat')->row_array()['id'];
    }

    // templates
    public function getMessageById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('chat')->row_array();
    }

    public function getMessageFullInfoById($id)
    {
        $query = "SELECT chat.id AS id, chat.userid AS userid, chat.message AS message, chat.datetime AS datetime, chat.replied_to AS replied_to, chat.quota_limit AS quota_limit, chat.tagged_by AS tagged_by, user.photo AS photo, chat.is_sticky AS is_sticky, chat.note_sticky AS note_sticky FROM chat JOIN user ON user.user_id = chat.userid WHERE chat.datetime = '$id' ORDER BY DATE_FORMAT(chat.datetime, '%Y-%m-%d') DESC, chat.datetime DESC";
        return $this->db->query($query)->row_array();
    }

    public function getAllTemplates()
    {
        $this->db->order_by('saved_at', 'DESC');
        return $this->db->get('chat_reply_template')->result_array();
    }

    public function addNewTempate($data)
    {
        $this->db->insert('chat_reply_template', $data);
        return $this->db->affected_rows();
    }

    public function editTempate($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('chat_reply_template', $data);
        return $this->db->affected_rows();
    }

    public function deleteTempate($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('chat_reply_template');
        return $this->db->affected_rows();
    }
}
