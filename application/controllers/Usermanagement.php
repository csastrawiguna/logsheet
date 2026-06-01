<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usermanagement extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usermanagement_model', 'usermanagement');
        $this->load->library('form_validation');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'User Management';
        $data['alluser'] = $this->usermanagement->getAllUser($this->session->userdata('role_access'));
        // $data['allDepartment'] = $this->usermanagement->getAllDepartment();
        // $data['allAccess'] = $this->usermanagement->getAllAccess($this->session->userdata('role_access'));
        $data['alluserDesc'] = $this->usermanagement->getAllUserDesc($this->session->userdata('role_access'));
        $data['alluserBirthdate'] = $this->usermanagement->getAllUserBirthdate($this->session->userdata('role_access'));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('usermanagement/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {
        admin_access();
        $data['title'] = 'Add New User';
        $data['allUserid'] = $this->usermanagement->getAllUserId();
        $data['allDepartment'] = $this->usermanagement->getAllDepartment();
        $data['allAccess'] = $this->usermanagement->getAllAccess($this->session->userdata('role_access'));
        $data['alluserDesc'] = $this->usermanagement->getAllUserDesc($this->session->userdata('role_access'));

        $this->form_validation->set_rules('user_id', 'User ID', 'required');
        $this->form_validation->set_rules('fullname', 'Fullname', 'required');
        $this->form_validation->set_rules('npk', 'NPK', 'required');
        $this->form_validation->set_rules('joindate', 'Join date', 'required');
        $this->form_validation->set_rules('status', 'Employement status', 'required');
        $this->form_validation->set_rules('deptjobdesk', 'Jobdesk', 'required');
        $this->form_validation->set_rules('role_access', 'Access', 'required');

        if ($this->form_validation->run() ==  false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('usermanagement/add', $data);
            $this->load->view('templates/footer', $data);
        } else {
            if ($this->usermanagement->getUserById($this->input->post('user_id')) > 0) {
                $this->session->set_flashdata('message', 'Add New User|error|User ID already exist!');
                redirect('usermanagement/index');
            } else {
                if (!$this->input->post('email_address')) {
                    $email_address = '-';
                } else {
                    $email_address = $this->input->post('email_address');
                }
                
                $data = [
                    'user_id' => $this->input->post('user_id'),
                    // 'password' => 'sharp1234',
                    'password' => password_hash('sharp1234', PASSWORD_BCRYPT),
                    'fullname' => $this->input->post('fullname'),
                    'npk' => $this->input->post('npk'),
                    'birthdate' => $this->input->post('birthdate'),
                    'joindate' => $this->input->post('joindate'),
                    'retiredate' => $this->input->post('retiredate'),
                    'status' => $this->input->post('status'),
                    'email_address' => $email_address,
                    'email_personal' => $this->input->post('emailPersonal'),
                    'jobcode' => $this->input->post('deptjobdesk'),
                    'role_access' => $this->input->post('role_access'),
                    'user_moodle' => $this->input->post('userMoodle'),
                    'is_active' => $this->input->post('is_active'),
                    'replacement_for' => $this->input->post('replacement_for'),
                    'mpr_approval' => $this->input->post('mpr_approval'),
                    'remark' => $this->input->post('user_add_remark'),
                    'view_theme' => 1,
                    'photo' => 'nophoto.png',
                    'bg' => 'bg_default1.jpg',
                    'quote' => 'Your quote is lorem ipsum...'
                ];
                $cccinfodata = [
                    'id' => $this->input->post('user_id'),
                    'name' => $this->input->post('fullname'),
                    'access' => 2,
                    'area_scope' => 'CCC',
                    'password' => password_hash('sharp1234', PASSWORD_BCRYPT),
                ];

                if ($this->usermanagement->addUser($data) > 0) {
                    if ($this->usermanagement->addUserToCccinfo($cccinfodata) > 0 ) {
                        $this->session->set_flashdata('message', 'Logsheet & CCCInfo|success|New user LOGSHEEET &amp; CCCINFO added!');
                    } else {
                        $this->session->set_flashdata('message', 'Logsheet Only|success|Successly add new LOGSHEET user only!');
                    }
                } else {
                    $this->session->set_flashdata('message', 'New User|warning|Failed to add new user!');
                }
                redirect('usermanagement/index');
            }
        }
    }

    public function edit($id)
    {
        admin_access();
        $data['title'] = 'Edit User Data';
        $data['allUserid'] = $this->usermanagement->getAllUserId();
        $data['allDepartment'] = $this->usermanagement->getAllDepartment();
        $data['allAccess'] = $this->usermanagement->getAllAccess($this->session->userdata('role_access'));
        $data['alluserDesc'] = $this->usermanagement->getAllUserDesc($this->session->userdata('role_access'));
        $data['userDetail'] = $this->usermanagement->getUserById($id);

        $this->form_validation->set_rules('user_id', 'User ID', 'required');
        $this->form_validation->set_rules('fullname', 'Fullname', 'required');
        $this->form_validation->set_rules('npk', 'NPK', 'required');
        $this->form_validation->set_rules('joindate', 'Join date', 'required');
        $this->form_validation->set_rules('status', 'Employement status', 'required');
        $this->form_validation->set_rules('deptjobdesk', 'Jobdesk', 'required');
        $this->form_validation->set_rules('role_access', 'Access', 'required');

        if ($this->form_validation->run() ==  false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar', $data);
            $this->load->view('usermanagement/edit', $data);
            $this->load->view('templates/footer', $data);
            // $this->load->view('templates/footer-usermanagement', $data);
        } else {
            if (!$this->input->post('email_address')) {
                $email_address = '-';
            } else {
                $email_address = $this->input->post('email_address');
            }
            $data = [
                'user_id' => $this->input->post('user_id'),
                'fullname' => $this->input->post('fullname'),
                'npk' => $this->input->post('npk'),
                'birthdate' => $this->input->post('birthdate'),
                'joindate' => $this->input->post('joindate'),
                'retiredate' => $this->input->post('retiredate'),
                'status' => $this->input->post('status'),
                'email_address' => $email_address,
                'email_personal' => $this->input->post('emailPersonal'),
                'jobcode' => $this->input->post('deptjobdesk'),
                'role_access' => $this->input->post('role_access'),
                'user_moodle' => $this->input->post('userMoodle'),
                'is_active' => $this->input->post('is_active'),
                'replacement_for' => $this->input->post('replacement_for'),
                'mpr_approval' => $this->input->post('mpr_approval'),
                'remark' => $this->input->post('user_edit_remark'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            if ($this->usermanagement->updateUser($data) > 0) {
                $this->session->set_flashdata('message', 'Success|success|User data updated!');
                redirect('usermanagement/index');
            }
        }

    }

    public function getUserById()
    {
        $user_id = $this->input->post('user_id');
        echo json_encode($this->usermanagement->getUserById($user_id));
    }

    public function deleteUserById()
    {
        if (empty($this->input->post('user_id'))) {
            $user_id = $this->uri->segment(3);
        } else {
            $user_id = $this->input->post('user_id');
        }

        if ($this->usermanagement->deleteUserById($user_id) > 0) {
            $this->session->set_flashdata('message', 'User Deletion|info|User successly deleted!');
        } else {
            $this->session->set_flashdata('message', 'User Deletion|warning|Failed to delete user!');
        }
        redirect('usermanagement/index');
    }

    public function editUserById()
    {
        if (!$this->input->post('emailAddress')) {
            $email_address = '-';
        } else {
            $email_address = $this->input->post('emailAddress');
        }
        $data = [
            'user_id' => $this->input->post('user_id'),
            'fullname' => $this->input->post('fullname'),
            'npk' => $this->input->post('npk'),
            'birthdate' => $this->input->post('birthdate'),
            'joindate' => $this->input->post('joindate'),
            'retiredate' => $this->input->post('retiredate'),
            'status' => $this->input->post('status'),
            'email_address' => $email_address,
            'email_personal' => $this->input->post('emailPersonal'),
            'user_moodle' => $this->input->post('userMoodle'),
            'jobcode' => $this->input->post('deptjobdesk'),
            'role_access' => $this->input->post('role_access'),
            'is_active' => $this->input->post('is_active')
        ];

        if ($this->usermanagement->updateUser($data) > 0) {
            $this->session->set_flashdata('message', 'Updating User|success|User data successly edited!');
        } else {
            $this->session->set_flashdata('message', 'Updating User|error|Failed to edit user data!');
        }
        redirect('usermanagement/index');
    }

    public function reset()
    {
        check_access();
        $data['title'] = 'Password reset - Unlock user';
        $data['allLockedUser'] = $this->usermanagement->getAllLockedUser();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('usermanagement/reset', $data);
        $this->load->view('templates/footer', $data);
        // $this->load->view('templates/footer-usermanagement', $data);
    }

    public function resetUser()
    {
        $data = [
            'user_id' => $this->input->post('user_id'),
            'is_unlocked' => 1,
            'reset_by' => $this->session->userdata('user_id'),
            'reset_on' => date("Y-m-d H:i:s")
        ];
        $user_id = $this->input->post('user_id');
        $this->usermanagement->resetUser($data);

        if ($this->usermanagement->unlockUser($user_id) > 0) {
            $this->session->set_flashdata('message', 'Unlock User|success|User account successly unlocked!');
        } else {
            $this->session->set_flashdata('message', 'Unlock User|warning|Failed to unlock user!');
        }
        redirect('usermanagement/reset');
    }

    public function resetPassword()
    {
        $data = [
            'id' => $this->input->post('id'),
            'user_id' => $this->input->post('user_id'),
            'password' => password_hash('sharp1234', PASSWORD_BCRYPT),
            'is_reseted' => 1,
            'reset_by' => $this->session->userdata('user_id'),
            'reset_on' => date("Y-m-d H:i:s")
        ];
        $this->usermanagement->resetForgotPassword($data);
        $this->usermanagement->resetUserPassword($data);
    }

    public function dismissResetRequest()
    {
        $data = [
            'id' => $this->input->post('id'),
            'reason' => 'Dismissed by admin',
            'is_reseted' => 1,
            'status' => 1,
            'reset_by' => $this->session->userdata('user_id'),
            'reset_on' => date("Y-m-d H:i:s")
        ];
        echo json_encode($this->usermanagement->dismissResetRequest($data));
    }

    public function viewactive()
    {
        admin_access();

        $data['title'] = 'All Active Users';
        $data['allUsers'] = $this->usermanagement->getAllActiveUsers();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('usermanagement/all-users', $data);
        $this->load->view('templates/footer', $data);
    }

    public function exportActiveUser()
    {
        $this->_exportUsers($this->usermanagement->getAllActiveUsers());
    }

    public function exportWholeUser()
    {
        $this->_exportUsers($this->usermanagement->getWholeUsers());
    }

    private function _exportUsers($userdata)
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Administrator')->setLastModifiedBy('Administrator')->setTitle("Users list")->setSubject("User data")->setDescription("All User Data");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font jadi bold

            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border right dengan garis tipis
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis 
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "LIST OF CCC MEMBER"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14); // Set font size 15 untuk kolom A1
        // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "CTI ID"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Fullname"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NPK");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Borndate");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Joindate"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Status");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Office Email"); 
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Personal Email"); 
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Replacement for"); 

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        // $startPeriod = $this->input->post('absentDetailDateStart');
        // $endPeriod = $this->input->post('absentDetailDateEnd');
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($userdata as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['user_id']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['fullname']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['npk']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, date("d-M-y", strtotime($data['birthdate'])));
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, date("d-M-y", strtotime($data['joindate'])));
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, strtoupper($data['status']));
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data['email_address']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data['email_personal']);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data['replacement_for']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(30);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        // $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("CCC User List");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="CCC User List.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }
}
