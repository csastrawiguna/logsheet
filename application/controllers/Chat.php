<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Chat_model', 'chat');
        $this->load->model('Elearning_model', 'elearning');
        is_logged_in();
    }

    public function index()
    {
        //check_access();
        $data['title'] = 'Chat_chit';        
        $user = $this->session->userdata('user_id');
        $data['currentUser'] = $user;

        // auto-delete chat
        $this->_autodeletechat();

        $this->form_validation->set_rules('formChatMesage', 'Form Chat', 'required|trim');
        $data['allUsers'] = $this->db->get_where('user', ['is_active' => 1])->result_array();
        
        $timeLimitChat = date("Y-m-d H:i:s", strtotime("-" . $this->db->get_where('chat_setting', ['item' => 'day_chat_existing'])->row_array()['value'] ."days"));
        $timeLimitPinned = date("Y-m-d H:i:s", strtotime("-" . $this->db->get_where('chat_setting', ['item' => 'day_pinned_stucked'])->row_array()['value'] ."days"));
        $orderlist = $this->db->get_where('chat_setting', ['item' => 'message_order'])->row_array()['value'];

        if ($orderlist == 0) {
            $results = $this->chat->getAllChat($timeLimitChat);
        } else {
            $results = $this->chat->getAllChatStickyFirst($timeLimitChat);
        }

        $data['allChat'] = [];
        foreach ($results as $row) {
            if ($row['replied_to'] == NULL || $row['replied_to'] == 0) {
                $data['allChat'][] = [
                    'id' => $row['id'],
                    'userid' => $row['userid'], 
                    'message' => $row['message'],
                    'datetime' => $row['datetime'],
                    'photo' => $row['photo'],
                    'is_sticky' => $row['is_sticky'],
                    'note_sticky' => $row['note_sticky'],
                    'replied_to' => $row['replied_to'],
                    'quota_limit' => $row['quota_limit'],
                    'initial' => $row['initial_name'],
                    'replied_to_userid' => '',
                    'replied_to_message' => '',
                    'tagged_by' => $row['tagged_by']
                ];
            } else {
                $data['allChat'][] = [
                    'id' => $row['id'],
                    'userid' => $row['userid'], 
                    'message' => $row['message'],
                    'datetime' => $row['datetime'],
                    'photo' => $row['photo'],
                    'is_sticky' => $row['is_sticky'],
                    'note_sticky' => $row['note_sticky'],
                    'replied_to' => $row['replied_to'],
                    'quota_limit' => $row['quota_limit'],
                    'initial' => $row['initial_name'],
                    'tagged_by' => $row['tagged_by'],
                    'replied_to_userid' => $this->chat->getRepliedTo($row['replied_to'])['userid'],
                    'replied_to_message' => $this->chat->getRepliedTo($row['replied_to'])['message']
                ];
            }
        }

        $data['allSticky'] = $this->chat->getAllStickyChat($timeLimitPinned);
        if ($this->input->post('postMessageIssticky') == 1) {
            $isSticky = 1;
        } else {
            $isSticky = 0;
        }

        if ($this->input->post('postMessageIsTagged') == TRUE || $this->input->post('postMessageIsTagged') == 'TRUE' ) {
            $tagged_by = open;
        } else {
            $tagged_by = NULL;
        }

        if ($this->input->post('postMessageQuotaLimit') == 0) {
            $quotaLimit = 0;
            $listTextHeader = '';
        } else {
            $quotaLimit = $this->input->post('postMessageQuotaLimit');
            $newId = (int) $this->chat->getLatestChatId() + 1;
            $listTextHeader = 
                '<p>Daftar/list:</p>
                <ol id="voulenteerList' . $newId . '">
                    <li></li>
                </ol>';
        }

        if( $this->form_validation->run() == false ) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('chat/index', $data);
            $this->load->view('templates/footer');
        } else {
            // var_dump($this->input->post('formChatMesage'));
            $data = [
                'message' => $this->input->post('formChatMesage') . $listTextHeader,
                'datetime' => date("Y-m-d H:i:s"),
                'userid' => $user,
                'replied_to' => $this->input->post('postMessageRepliedto'),
                'is_sticky' => $isSticky,
                'note_sticky' => $this->input->post('postMessageStickyNote'),
                'tagged_by' => $tagged_by,
                'quota_limit' => $quotaLimit
            ];
            if($this->chat->postNewMessage($data) > 0) {
                redirect('chat');
            }
        }
    }

    private function _autodeletechat()
    {
        $duration = $this->db->get_where('general_setting', ['item' => 'chat_chit_latest_message'])->row_array()['item'];
        $limit = "-" . $duration;
        $this->chat->performAutoDelete(date("Y-m-d", strtotime($limit)));
    }

    public function get_new_messages() {
        $last_id = $this->input->get('last_id');        
        echo json_encode($this->chat->getChatsAfterId($last_id));
    }

    public function add_volunteer_with_limit() {
        $id = $this->input->post('id');
        $nama_user = $this->session->userdata('user_id');
        
        // Gunakeun htmlspecialchars sangkan aman tina XSS mun nami user aya anéhan
        $safe_name = htmlspecialchars($nama_user, ENT_QUOTES, 'UTF-8');
        $new_li = "<li>" . $nama_user . "</li>";

        // Rumus ngitung jumlah <li> (Case Insensitive aman)
        $count_sql = "(LENGTH(message) - LENGTH(REPLACE(message, '<li>', ''))) / 4";
        // echo json_encode(['message' => $nama_user . ' - ' . $id .  ' - sampai hitung <li>']);

        // Update sakaligus cek kondisi
        $this->db->set('message', "REPLACE(message, '</ol>', '$new_li</ol>')", FALSE);
        $this->db->set('datetime', date("Y-m-d H:i:s"));
        $this->db->where('id', $id);
        // // Tambahan: Pastikeun kolom message mémang boga <ol> sangkan REPLACE jalan
        $this->db->like('message', '</ol>'); 
        $this->db->where("$count_sql < quota_limit", NULL, FALSE);
        
        // // Cek naha user geus aya dina list (substring check)
        $this->db->not_like('message', "<li>$nama_user</li>"); 

        $this->db->update('chat');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'success', 'new_name' => $nama_user]);
        } else {
            // Cek deui naha pinuh atawa geus daptar pikeun feedback nu leuwih jentre
            echo json_encode(['status' => 'error', 'message' => 'Gagal! Full-booked atau sudah daftar!']);
        }
    }

    public function remove_volunteer()
    {
        $id = $this->input->post('id'); // ID talatah/chat
        $nama_user = $this->session->userdata('user_id');
        
        // Pastikeun ngaran user aman
        $target_li = "<li>" . $nama_user . "</li>";

        // Update message: hapus <li>aran_user</li> tina kolom message
        $this->db->set('message', "REPLACE(message, '$target_li', '')", FALSE);
        $this->db->set('datetime', date("Y-m-d H:i:s"));
        $this->db->where('id', $id);
        // Tambahan kaamanan: ngan bisa ngahapus mun mémang ngaranna aya di dinya
        $this->db->like('message', $target_li); 

        $this->db->update('chat');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Berhasil kaluar tina antrean']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal! Anjeun memang teu aya dina daptar.']);
        }
    }

    public function admin_remove_volunteer()
    {
        // Cek heula bisi aya user iseng nembak URL ieu
        $allowedUser = ['1', '5', '9'];
        if (!in_array($this->session->userdata('role_access'), $allowedUser)) {
            echo json_encode(['status' => 'error', 'message' => 'Anjeun sanes admin!']);
            return;
        }

        $id = $this->input->post('id');
        $nama_target = $this->input->post('nama_user'); // Ngaran nu rek dihapus ku admin
        
        $target_li = "<li>" . $nama_target . "</li>";

        $this->db->set('message', "REPLACE(message, '$target_li', '')", FALSE);
        $this->db->set('datetime', date("Y-m-d H:i:s"));
        $this->db->where('id', $id);
        $this->db->update('chat');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'User ' . $nama_target . ' geus dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal ngahapus, data ' . $target_li . ' teu kapanggih.']);
        }
    }

    public function postMessage()
    {
        // 1. Ambil data ti POST
        $msg = $this->input->post('message');
        $quotaLimit = (int) $this->input->post('postMessageQuotaLimit');
        $newId = (int) $this->chat->getLatestChatId() + 1;

        // 2. Logic keur header (mun quota > 0, tambahkeun list HTML)
        $listTextHeader = ($quotaLimit > 0) ? 
            '<p>Daftar/list:</p><ol></ol>' : '';

        // 3. Susun data keur DB
        $data = [
            'message'     => $this->input->post('message') . $listTextHeader,
            'datetime'    => date("Y-m-d H:i:s"),
            'userid'      => $this->session->userdata('user_id'),
            'replied_to'  => $this->input->post('replied_to') ?: null,
            'is_sticky'   => $this->input->post('is_sticky') ?: 0,
            'note_sticky' => $this->input->post('note_sticky') ?: '',
            'tagged_by'   => $this->input->post('tagged_by'),
            'quota_limit' => $quotaLimit
        ];

        $insert = $this->chat->postNewMessage($data);

        // IEU NU PENTING: Sangkan AJAX suksés, kirimkeun response JSON
        if ($insert) {
            echo json_encode(['status' => 'success', 'message' => 'Pesan terkirim']);
        } else {
            // Mun gagal, kirim status 500 sangkan ditéwak ku block error di JS
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpen di DB']);
        }
    }

    public function updateMessage()
    {
        $updatedata = [
            'id' => $this->input->post('id'),
            'message' => $this->input->post('message'),
            'datetime' => date("Y-m-d H:i:s"),
            'userid' => $this->session->userdata('user_id'),
            'is_sticky' => $this->input->post('is_sticky'),
            'note_sticky' => $this->input->post('note_sticky'),
        ];
        $this->chat->editMessage($updatedata);
    }

    public function allchat()
    {
        $data['allUsers'] = $this->db->get_where('user', ['is_active' => 1])->result_array();
        
        $timeLimitChat = date("Y-m-d H:i:s", strtotime("-" . $this->db->get_where('chat_setting', ['item' => 'day_chat_existing'])->row_array()['value'] ."days"));
        $timeLimitPinned = date("Y-m-d H:i:s", strtotime("-" . $this->db->get_where('chat_setting', ['item' => 'day_pinned_stucked'])->row_array()['value'] ."days"));
        $orderlist = $this->db->get_where('chat_setting', ['item' => 'message_order'])->row_array()['value'];

        if ($orderlist == 0) {
            $results = $this->chat->getAllChat($timeLimitChat);
        } else {
            $results = $this->chat->getAllChatStickyFirst($timeLimitChat);
        }

        $data['allchats'] = [];
        foreach ($results as $row) {
            if ($row['replied_to'] == NULL || $row['replied_to'] == 0) {
                $data['allchats'][] = [
                    'id' => $row['id'],
                    'userid' => $row['userid'], 
                    'message' => $row['message'],
                    'datetime' => $row['datetime'],
                    'photo' => $row['photo'],
                    'is_sticky' => $row['is_sticky'],
                    'note_sticky' => $row['note_sticky'],
                    'replied_to' => $row['replied_to'],
                    'replied_to_userid' => '',
                    'replied_to_message' => '',
                    'quota_limit' => $row['quota_limit'],
                    'initial' => $row['initial_name'],
                    'tagged_by' => $row['tagged_by']
                ];
            } else {
                $data['allchats'][] = [
                    'id' => $row['id'],
                    'userid' => $row['userid'], 
                    'message' => $row['message'],
                    'datetime' => $row['datetime'],
                    'photo' => $row['photo'],
                    'is_sticky' => $row['is_sticky'],
                    'note_sticky' => $row['note_sticky'],
                    'replied_to' => $row['replied_to'],
                    'quota_limit' => $row['quota_limit'],
                    'initial' => $row['initial_name'],
                    'tagged_by' => $row['tagged_by'],
                    'replied_to_userid' => $this->chat->getRepliedTo($row['replied_to'])['userid'],
                    'replied_to_message' => $this->chat->getRepliedTo($row['replied_to'])['message']
                ];
            }
        }
        $this->load->view('chat/intervalupdatechat', $data);
    }

    public function allpinned()
    {
        $timeLimitPinned = date("Y-m-d H:i:s", strtotime("-" . $this->db->get_where('chat_setting', ['item' => 'day_pinned_stucked'])->row_array()['value'] ."days"));
        $data['allSticky'] = $this->chat->getAllStickyChat($timeLimitPinned);
        $this->load->view('chat/intervalupdatepinned', $data);
    }

    public function update()
    {
        if ($this->input->post('editMessageIssticky') == 1) {
            $isSticky = 1;
            $noteSticky = $this->input->post('editMessageStickyNote');
        } else {
            $isSticky = 0;
            $noteSticky = NULL;
        }

        $updatedata = [
            'id' => $this->input->post('editMessageId'),
            'message' => $this->input->post('editMessageDetail'),
            'datetime' => date("Y-m-d H:i:s"),
            'userid' => $this->session->userdata('user_id'),
            'is_sticky' => $isSticky,
            'note_sticky' => $noteSticky,
        ];

        if($this->chat->editMessage($updatedata) > 0) {
            redirect('chat');
        }
    }

    public function tagmessage()
    {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');

        // check
        // $check = $this->db->get_where('chat', ['id' => $id])->row();

        // 2. Setel kondisi WHERE (ID kudu pas, sarta tagged_by kudu masih kosong)
        $this->db->where('id', $id);
        $this->db->group_start(); // Mimiti grup kondisi tambahan
            $this->db->where('tagged_by', NULL);
            $this->db->or_where('tagged_by', '');
            $this->db->or_where('tagged_by', 'open');
        $this->db->group_end();

        // 3. Jalankeun UPDATE
        $this->db->update('chat', [
            'tagged_by' => $user_id
        ]);

        // 4. Pariksa naha aya baris data nu bener-bener ka-update?
        if ($this->db->affected_rows() > 0) {
            // Mun leuwih ti 0, hartina anjeun nu pangheulana ngetag
            $data = [
                'message' => '<p><em>-- Tagged by ' . $user_id . ' --</em></p>',
                'datetime' => date("Y-m-d H:i:s"),
                'userid' => $user_id,
                'replied_to' => $id,
                'is_sticky' => 0,
                'note_sticky' => NULL,
                'tagged_by' => NULL
            ];
            $this->chat->postNewMessage($data);
            $response = ['status' => 'success', 'message' => 'Berhasil ngetag!'];            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit; // Pareuman prosés sangkan euweuh karakter séjén nu milu kacitak
        } else {
            // Mun 0, hartina kondisina teu kacumponan (geus aya nu ngetag ti heula)
            $response = ['status' => 'error', 'message' => 'Telat Breh, geus dikonci!'];
            echo json_encode(['status' => 'error', 'message' => 'Telat Breh, geus di-tag nu lain!']);
        }
    }

    public function checkTagging()
    {
        $id = $this->input->post('id');
        echo json_encode($this->chat->checkTaggedMessage($id));
        // echo json_encode($id);
    }

    public function dismisspinned()
    {
        $id = $this->input->post('id');
        $this->chat->performDismissSticky($id);
    }

    public function searchmessage()
    {
        $data['title'] = 'Search Chat Message';
        $data['messageClue'] = $this->input->post('searchMessageClue');
        $data['grabbedMessage'] = $this->chat->searchChatMessage($this->input->post('searchMessageClue'), date("Y-m-01 00:00:00", strtotime("-1 year")));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('chat/search', $data);
        $this->load->view('templates/footer');
    }

    public function setting()
    {
        admin_access();
        $data['title'] = 'Chat Message Setting';
        $data['existingSetting'] = [];
        foreach ($this->chat->getExistingChatSetting() as $row) {
            $data['existingSetting'][$row['item']] = $row['value'];
        }

        $this->form_validation->set_rules('chatSettingDayValue', 'Durasi chat tampil', 'required|trim|numeric');
        $this->form_validation->set_rules('chatSettingPinnedValue', 'Durasi pinned message', 'required|trim|numeric');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('chat/setting', $data);
            $this->load->view('templates/footer');
        } else {
            $updateData = [
                [
                    'item' => 'day_chat_existing',
                    'value' => $this->input->post('chatSettingDayValue')
                ],
                [
                    'item' => 'day_pinned_stucked',
                    'value' => $this->input->post('chatSettingPinnedValue')
                ],
                [
                    'item' => 'message_order',
                    'value' => $this->input->post('chatSettingOrderby')
                ],
            ];

            if($this->chat->updateChatSetting($updateData) > 0) {
                $this->session->set_flashdata('message', "Succesly Updated!|success|Chat setting already updated");
                redirect('chat/setting');
            }
        }
    }

    public function messagebyid()
    {
        $id = $this->input->post('chatid');
        echo json_encode($this->chat->getMessageById($id));
    }

    public function messagefullbyid()
    {
        $id = $this->input->post('chatid');
        echo json_encode($this->chat->getMessageFullInfoById($id));
    }

    public function skapefeedback()
    {
        $data = [
            'category' => $this->input->post('skapefeedbackCategory'),
            'solution_title' => $this->input->post('skapefeedbackTitle'),
            'solution_id' => $this->input->post('skapefeedbackLink'),
            'feedback' => $this->input->post('skapefeedbackComment'),
            'saved_by' => $this->session->userdata('user_id'),
            'saved_at' => date("Y-m-d H:i:s")
        ];

        if ($this->elearning->addNewSkapeFeedback($data) > 0) {
            $this->session->set_flashdata('message', "Succesly Added!|success|All Feedback di Elearning - SKAPE Feedback");
            redirect('chat');
        }
    }

    public function template()
    {
        $data['title'] = 'Reply Templates';

        $this->form_validation->set_rules('addTemplateName', 'Title (judul)', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('addTemplateWording', 'Detail wording', 'required|trim|min_length[10]');

        if ($this->form_validation->run() == false) {
            $data['templates'] = $this->chat->getAllTemplates();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('chat/reply-template', $data);
            $this->load->view('templates/footer');
        } else {
            $newTemplate = [
                'name' => $this->input->post('addTemplateName'),
                'wording' => $this->input->post('addTemplateWording'),
                'remark' => $this->input->post('addTemplateRemark'),
                'status' => 1,
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s")
            ];

            if ($this->chat->addNewTempate($newTemplate) > 0) {
                $this->session->set_flashdata('message', "Succes!|success|New reply template newly added");
                redirect('chat/template');
            } else {
                $this->session->set_flashdata('message', "Failed!|error|Check the title and detail wording content");
                redirect('chat/template');
            }
        }
    }

    public function edittemplate()
    {
        $data['title'] = 'Edit Templates';
        $allowedUser = ['1', '5', '9'];

        if (!in_array($this->session->userdata('role_access'), $allowedUser)) {
            $this->session->set_flashdata('message', "Succes!|error|You do not have access");
            redirect('chat/template');
        }
        
        $id = $this->uri->segment(3);

        $this->form_validation->set_rules('editTemplateName', 'Title (judul)', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('editTemplateWording', 'Detail wording', 'required|trim|min_length[10]');

        if ($this->form_validation->run() == false) {
            $data['template'] = $this->db->get_where('chat_reply_template', ['id' => $id])->row_array();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('chat/edit-template', $data);
            $this->load->view('templates/footer');
        } else {
            $udpateTemplate = [
                'id' => $this->input->post('editTemplateId'),
                'name' => $this->input->post('editTemplateName'),
                'wording' => $this->input->post('editTemplateWording'),
                'remark' => $this->input->post('editTemplateRemark'),
                'status' => 1,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            if ($this->chat->editTempate($udpateTemplate) > 0) {
                $this->session->set_flashdata('message', "Succes!|success|New reply template newly added");
                redirect('chat/template');
            } else {
                $this->session->set_flashdata('message', "Failed!|error|Check the title and detail wording content");
                redirect('chat/template');
            }
        }
    }

    public function deletetemplate()
    {
        $id = $this->uri->segment(3);
        if ($this->chat->deleteTempate($id) > 0) {
            $this->session->set_flashdata('message', "Deleted!|info|Template deleted from storage");
            redirect('chat/template');
        }
    }
}
