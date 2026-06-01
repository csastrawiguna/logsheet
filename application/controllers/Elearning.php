<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Elearning extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Elearning_model', 'elearning');
        is_logged_in();        
    }

    public function index()
    {
        check_access();
        $data['title'] = 'Elearning';
        $this->setElearningStatus();
        $data['user'] = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->row_array();
        $data['elearning_category'] = $this->elearning->getAllElearningCategory();

        $this->form_validation->set_rules('categoryPeriod', 'Periode', 'required');
        $this->form_validation->set_rules('categoryName', 'Nama atau Kategori', 'required');
        $this->form_validation->set_rules('startdate', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('enddate', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('posttestAttemp', 'Post Test Attemp', 'greater_than[0]');
        $this->form_validation->set_rules('testDuration', 'Duration', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/index', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-elearning');
        } else {
            //cek upload material
            $upload_material = strtolower($_FILES['material']['name']);
            $data = [];
            $user_id = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->row_array()['user_id'];

            if ($upload_material) {
                $config['upload_path'] = './material/';
                $config['allowed_types'] = 'pdf|ppt|pptx';
                $config['max_size']     = '10496';
                $config['overwrite'] = true;
                $config['remove_spaces'] = true;
                $config['file_name'] = date("Y-m", strtotime($this->input->post('categoryPeriod'))) . '_' . $upload_material;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('material')) {
                    $material = $this->upload->data('file_name');
                    $data = [
                        'period' => date("Y-m-01", strtotime($this->input->post('categoryPeriod'))),
                        'name' => $this->input->post('categoryName'),
                        'startdate' => $this->input->post('startdate'),
                        'enddate' => $this->input->post('enddate'),
                        'question_qty' => $this->input->post('questionQty'),
                        'test_duration' => $this->input->post('testDuration'),
                        'posttest_attemp' => $this->input->post('posttestAttemp'),
                        'passing_score' => $this->input->post('passingScore'),
                        'pretest' => $this->input->post('questionPretest'),
                        'elearning_material' => $material,
                        'created_on' => date("Y-m-d H:i:s"),
                        'created_by' => $user_id,
                        'last_modified_by' => '',
                        'last_modified_on' => ''
                    ];
                }
            } else {
                $data = [
                    'period' => date("Y-m-01", strtotime($this->input->post('categoryPeriod'))),
                    'name' => $this->input->post('categoryName'),
                    'startdate' => $this->input->post('startdate'),
                    'enddate' => $this->input->post('enddate'),
                    'test_duration' => $this->input->post('testDuration'),
                    'question_qty' => $this->input->post('questionQty'),
                    'posttest_attemp' => $this->input->post('posttestAttemp'),
                    'passing_score' => $this->input->post('passingScore'),
                    'pretest' => $this->input->post('questionPretest'),
                    'elearning_material' => '-',
                    'created_on' => date("Y-m-d H:i:s"),
                    'created_by' => $user_id,
                    'last_modified_by' => '',
                    'last_modified_on' => ''
                ];
            }

            if ($this->elearning->addCategory($data) > 0) {
                $this->session->set_flashdata('message', 'Elearning|success|New Category successly added!');
            } else {
                $this->session->set_flashdata('message', 'Elearning|error|Failed to add new category!');
            }
            redirect('elearning/index');
        }
    }

    public function get_elearning($id)
    {
        $data['elearningById'] = $this->elearning->getElearningById($id);
    }

    public function get_edit()
    {
        echo json_encode($this->elearning->getElearningById($_POST['id']));
    }

    public function delete_elearning($id)
    {
        $this->elearning->deleteQuestionaireByElearning($id);
        if ($this->elearning->deleteCategory($id) > 0) {
            $this->session->set_flashdata('message', 'E-Learning|warning|Successly deleted...');
        }
        redirect('elearning/index');
    }

    public function edit_elearning()
    {
        $upload_material = strtolower($_FILES['material']['name']);
        $data = [];

        if ($upload_material) {
            $config['upload_path'] = './material/';
            $config['allowed_types'] = 'pdf|ppt|pptx';
            $config['max_size']     = '10496';
            $config['overwrite'] = true;
            $config['remove_spaces'] = true;
            $config['file_name'] = date("Y-m", strtotime($this->input->post('categoryPeriod'))) . '_' . $upload_material;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('material')) {
                $material = $this->upload->data('file_name');
            } else {
                echo $this->upload->display_errors();
            }
        } else {
            $material = $this->elearning->getElearningById($this->input->post('categoryId'))['elearning_material'];
        }

        $user_id = $this->db->get_where('user', ['user_id' => $this->session->userdata['user_id']])->row_array()['user_id'];
        $data = [
            'id' => $this->input->post('categoryId'),
            'period' => date("Y-m-01", strtotime($this->input->post('categoryPeriod'))),
            'name' => $this->input->post('categoryName'),
            'startdate' => $this->input->post('startdate'),
            'enddate' => $this->input->post('enddate'),
            'test_duration' => $this->input->post('testDuration'),
            'question_qty' => $this->input->post('questionQty'),
            'passing_score' => $this->input->post('passingScore'),
            'posttest_attemp' => $this->input->post('posttestAttemp'),
            'pretest' => $this->input->post('questionPretest'),
            'elearning_material' => $material,
            'last_modified_by' => $user_id,
            'last_modified_on' => date("Y-m-d H:i:s")
        ];

        if ($this->elearning->editCategory($data) > 0) {
            $this->session->set_flashdata('message', 'Elearning|success|Successly edited!</div>');
        } else {
            $this->session->set_flashdata('message', 'E-Learning|error|Failed to edit!</div>');
        }
        redirect('elearning/index');
    }

    public function setElearningStatus()
    {
        $allElearning = $this->elearning->getAllElearningCategory();
        $currdate = date('Y-m-d');

        for ($i = 0; $i < count($allElearning); $i++) {
            $start[$i] = date('Y-m-d', strtotime($allElearning[$i]['startdate']));
            $end[$i] = date('Y-m-d', strtotime($allElearning[$i]['enddate']));
            if ($currdate < $start[$i] || $currdate > $end[$i]) {
                $this->elearning->autoSetElearningStatus($allElearning[$i]['id'], 0);
            } else {
                $this->elearning->autoSetElearningStatus($allElearning[$i]['id'], 1);
            }
        }
    }

    public function questionaire()
    {
        check_access();
        $data['title'] = 'Elearning Questionaire';
        $data['allElearningCategory'] = $this->elearning->getActiveElearningCategory();
        $data['allQuestionaire'] = $this->elearning->getAllQuestionaire();
        $data['allCategory'] = $this->elearning->getActiveElearningCategory();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('elearning/questionaire', $data);
        $this->load->view('templates/footer');
    }

    public function addquestionaire()
    {
        $data['title'] = 'Add questioner';

        $this->form_validation->set_rules('elearningAddQuestionerQuestion', 'Pertanyaan', 'required|trim');
        $this->form_validation->set_rules('elearningAddQuestionerOptionA', 'Pilihan A', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerOptionD', 'Pilihan B', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerOptionC', 'Pilihan C', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerOptionD', 'Pilihan D', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerOptionE', 'Pilihan E', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerCorrectkey', 'Jawaban', 'required');
        $this->form_validation->set_rules('elearningAddQuestionerStatus', 'Status', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/navbar');
            $this->load->view('elearning/questionaire-add');
            $this->load->view('templates/footer');
        } else {
            $newdata = [
                        'category' => $this->input->post('elearningAddQuestionerCategory'),
                        'period' => date("Y-m-01", strtotime($this->input->post('elearningAddQuestionerPeriod'))),
                        'question' => $this->input->post('elearningAddQuestionerQuestion'),
                        'option_a' => $this->input->post('elearningAddQuestionerOptionA'),
                        'option_b' => $this->input->post('elearningAddQuestionerOptionB'),
                        'option_c' => $this->input->post('elearningAddQuestionerOptionC'),
                        'option_d' => $this->input->post('elearningAddQuestionerOptionD'),
                        'option_e' => $this->input->post('elearningAddQuestionerOptionE'),
                        'correct_key' => $this->input->post('elearningAddQuestionerCorrectkey'),
                        'status' => $this->input->post('elearningAddQuestionerStatus'),
                        'saved_by' => $this->session->userdata('user_id'),
                        'saved_at' => date("Y-m-d H:i:s")
                    ];

            $this->elearning->addQuestionaire($newdata);
            $this->session->set_flashdata('message', 'Questioner|success|Successly added new one!');
            redirect('elearning/questionaire');
        }
    }

    public function delete_questionaire($id)
    {
        $this->elearning->deleteQuestionaire($id);
        $this->session->set_flashdata('message', 'Questioner|info|Successly deleted...');
        redirect('elearning/questionaire');
    }

    public function editquestioner($id)
    {
        $data['title'] = 'Edit Questioner';

        $this->form_validation->set_rules('elearningEditQuestionerQuestion', 'Pertanyaan', 'required|trim');
        $this->form_validation->set_rules('elearningEditQuestionerOptionA', 'Pilihan A', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerOptionD', 'Pilihan B', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerOptionC', 'Pilihan C', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerOptionD', 'Pilihan D', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerOptionE', 'Pilihan E', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerCorrectkey', 'Jawaban', 'required');
        $this->form_validation->set_rules('elearningEditQuestionerStatus', 'Status', 'required');

        if ($this->form_validation->run() == false) {
            $data['questionerData'] = $this->elearning->getQuestionaireById($id);

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/navbar');
            $this->load->view('elearning/questionaire-edit');
            $this->load->view('templates/footer');
        } else {
            $editdata = [
                'category' => $this->input->post('elearningEditQuestionerCategory'),
                'period' => date("Y-m-01", strtotime($this->input->post('elearningEditQuestionerPeriod'))),
                'question' => $this->input->post('elearningEditQuestionerQuestion'),
                'option_a' => $this->input->post('elearningEditQuestionerOptionA'),
                'option_b' => $this->input->post('elearningEditQuestionerOptionB'),
                'option_c' => $this->input->post('elearningEditQuestionerOptionC'),
                'option_d' => $this->input->post('elearningEditQuestionerOptionD'),
                'option_e' => $this->input->post('elearningEditQuestionerOptionE'),
                'correct_key' => $this->input->post('elearningEditQuestionerCorrectkey'),
                'status' => $this->input->post('elearningEditQuestionerStatus'),
                'id' => $this->input->post('elearningEditQuestionerId'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            if ($this->elearning->editQuestionaireById($editdata) > 0) {
                $this->session->set_flashdata('message', 'Questioner|success|Successly edited!');
            } else {
                $this->session->set_flashdata('message', 'Questioner|error|Failed to edit...');
            }
            redirect('elearning/questionaire');
        }
    }

    public function get_questionaire()
    {
        echo json_encode($this->elearning->getQuestionaireById($this->input->post('qid')));
    }

    public function assignquestionaire()
    {
        check_access();
        $data['title'] = 'Assign Questioner for Elearning';

        $this->input->post('questionaireAssignmentSelectElearningCategory') ? $id = $this->input->post('questionaireAssignmentSelectElearningCategory') : $id = $this->elearning->getLatestElearningCategory()['id'];            
        $data['elearningId'] = $this->db->get_where('elearning_category', ['id' => $id])->row_array()['id'];
        $data['elearningPeriod'] = $this->db->get_where('elearning_category', ['id' => $id])->row_array()['period'];
        $data['elearningName'] = $this->db->get_where('elearning_category', ['id' => $id])->row_array()['name'];        
        $data['allElearningCategory'] = $this->elearning->getAllElearningCategory();
        $data['allQuestionaireAssigned'] = $this->elearning->getAllQuestionaireAssignedById($id);
        $data['allQuestionaireUnassigned'] = $this->elearning->getAllQuestionaireUnassigned($id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('elearning/assign-questionaire', $data);
        $this->load->view('templates/footer', $data);     
    }

    public function quesionaireAssignmentToExcel()
    {
        $id = '';
        if (!$this->input->post('questionaireAssignmentSelectElearningCategory')) {
            $id = $this->uri->segment(3);
        } else {
            $id = $this->input->post('questionaireAssignmentSelectElearningCategory');
        }
        $this->_quesionsToExcel($id);
    }

    private function _quesionsToExcel($id)
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Questioner Administrator')->setLastModifiedBy('Questioner Administrator')->setTitle("Assigned Questioner")->setSubject("Questioner")->setDescription("Assigned Questioner")->setKeywords("Assigned Questioner");

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

        // $id = $this->input->post('questionaireAssignmentSelectElearningCategory');
        $elearningName = $this->db->get('elearning_category', ['id' => $id])->row_array()['name'];
        $elearningPeriod = $this->db->get('elearning_category', ['id' => $id])->row_array()['period'];
        $questionsById = $this->elearning->getQuestionsById($id);

        $excel->setActiveSheetIndex(0)->setCellValue('A1', strtoupper($elearningName));
        $excel->setActiveSheetIndex(0)->setCellValue('A2', "Period : " . date("F Y", strtotime($elearningPeriod)));
        $excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14); // Set font size 15 untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B4', "Category"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C4', "Question"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D4', "Option A");
        $excel->setActiveSheetIndex(0)->setCellValue('E4', "Option B");
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Option C"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Option D");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Option E");
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Answer Key"); 

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I4')->applyFromArray($style_col);
        
        
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($questionsById as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['category']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['question']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['option_a']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['option_b']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['option_c']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['option_d']);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data['option_e']);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data['correct_key']);

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
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Questions of Elearning");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Questions of Elearning.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');    
    }

    public function assignUnassignedQuestionaire()
    {
        $data = $this->input->post('data');
        if ($this->elearning->assignQuestionaire($data) > 0) {
            $this->session->set_flashdata('message', 'Assignment|success|Questioner successly added!');
            redirect('elearning/assignquestionaire');
        }
    }

    public function unassignQuestionaire()
    {
        $id = $this->uri->segment(3);
        if ($this->elearning->unassignQuestionaire($id) > 0) {
            $this->session->set_flashdata('message', 'Successly removed|info|Questioner removed from list!');
            redirect('elearning/assignquestionaire');
        }
    }

    public function unassignQuestionaireGroups()
    {
        $ids = $this->input->post('lists');
        $num = 0;
        for($i = 0; $i < count($ids); $i++) {
            if ($this->elearning->unassignQuestionaire($ids[$i]) > 0) {
                $num ++;
            }
        }
        $this->session->set_flashdata('message', 'Successly removed|info|' . $num . ' questiones removed from list!');
        redirect('elearning/assignquestionaire');
    }

    public function assignment()
    {
        check_access();
        $data['title'] = 'User assignment';
        $data['allElearningCategory'] = $this->elearning->getActiveElearningCategory();
        $data['getAllUser'] = $this->db->get('user')->result_array();
        $data['empty_message'] = 'Pilih Elearning';
        // $data['elearning_name'] = $this->db->get_where('elearning_category', ['id' => $this->input->post('selectElearningCategory')])->row_array()['name'];
        if(!$this->input->post('selectElearningCategory')) {
        	if(empty($this->elearning->getActiveElearningCategory()) ) {
        		$data['isPretest'] = '';
        	} else if ($this->elearning->getActiveElearningCategory()[0]['pretest'] == 0 ) {
                $data['isPretest'] = 0;
            } else {
            	$data['isPretest'] = 1;            
        	}
            $data['elearning_id'] = $this->db->get_where('elearning_category', ['status' => 1])->row_array()['id'];
            $data['elearning_name'] = $this->db->get_where('elearning_category', ['status' => 1])->row_array()['name'];
        } else {
            if ($this->db->get_where('elearning_category', ['id' => $this->input->post('selectElearningCategory')])->row_array()['pretest'] == 0) {
                $data['isPretest'] = 0;
            } else {
                $data['isPretest'] = 1;
            }            
            $data['elearning_id'] = $this->input->post('selectElearningCategory');
            $data['elearning_name'] = $this->db->get_where('elearning_category', ['id' => $this->input->post('selectElearningCategory')])->row_array()['name'];
        }

        $this->form_validation->set_rules('selectElearningCategory', 'Elearning Category', 'required');

        if ($this->form_validation->run() == false) {
            $active = $this->elearning->getActiveElearningCategory();
            if (empty($active)) {
                $id = $this->elearning->getLatestElearningId()['id'];
            } else {
                $id = $active[0]['id'];
            }
            $data['unassignedUser'] = $this->elearning->getUnassignedUser($id);
            $data['allAssignedUser'] = $this->elearning->getAssignedUserByEl($id);
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/assignment', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $data['allAssignedUser'] = $this->elearning->getAssignedUserByEl($this->input->post('selectElearningCategory'));
            $data['unassignedUser'] = $this->elearning->getUnassignedUser($this->input->post('selectElearningCategory'));
            if (empty($data['allAssignedUser'])) {
                $data['selectedElearningId'] = $this->input->post('selectElearningCategory');
            } else {
                $data['selectedElearningId'] = $data['allAssignedUser'][0]['elearning_id'];
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/assignment', $data);
            $this->load->view('templates/footer');
        }
    }

    public function getAssignedUserByEl()
    {
        $elearning_id = $this->input->post('elearning_id');
        $this->elearning->getAssignedUserByEl($elearning_id);
        redirect('elearning/assignment');
    }

    public function getAssignedByElearning()
    {
        $elearning_id = $this->input->post('elearning_id');
        $this->elearning->getAssignedUserByEl($elearning_id);
    }

    public function assignUser()
    {
        $data = $this->input->post('data'); 
        if ($this->elearning->assignUser($data) > 0) {
            redirect('elearning/assignment');
        }
    }

    public function unassignUser()
    {
        $data = [
            'elearning_id' => $this->uri->segment(3),
            'user_id' => $this->uri->segment(4),
        ];
        if ($this->elearning->unassignUser($data) > 0) {
            $this->session->set_flashdata('message', 'Assignment|success|User successly unassigned!');
            redirect('elearning/assignment');
        }
    }

    public function resetPretest()
    {
        $data = [
            'elearning_id' => $this->uri->segment(3),
            'user_id' => $this->uri->segment(4)
        ];
        if ($this->elearning->resetPretest($data) > 0) {
            $this->_deleteExamResult($data, 'pretest');
            $this->session->set_flashdata('message', 'Reset result|info|Exam result has been reset!');
        } else {
            $this->session->set_flashdata('message', 'Reset result|error|Failed to reset exam result!');
        }
        redirect('elearning/assignment');
    }

    public function resetPosttest()
    {
        $data = [
            'elearning_id' => $this->uri->segment(3),
            'user_id' => $this->uri->segment(4),
            'posttest_remedial' => $this->_getRemedial($this->uri->segment(3), $this->uri->segment(4))['posttest_remedial'],
            'score_remedial' => $this->_getRemedial($this->uri->segment(3), $this->uri->segment(4))['score_remedial']
        ];

        if ($this->elearning->resetPosttest($data) > 0) {
            $this->_deleteExamResult($data, 'posttest');
            $this->session->set_flashdata('message', 'Reset result|info|Exam result has been reset!');
        } else {
            $this->session->set_flashdata('message', 'Reset result|error|Failed to reset exam result!');
        }
        redirect('elearning/assignment');
    }

    private function _getRemedial($elearning_id, $user_id)
    {
        $gets =  $this->db->get_where('elearning_assignment', ['elearning_id' => $elearning_id, 'user_id' => $user_id])->row_array();
        $text = [];
        if ($gets['posttest_remedial'] == 0) {
            $text['score_remedial'] = $gets['posttest_score'];
        } else {
            $text['score_remedial'] = $gets['score_remedial'] . ', ' . $gets['posttest_score'];
        }
        $text['posttest_remedial'] = $gets['posttest_remedial'] + 1;
        return $text;
    }

    private function _deleteExamResult($data, $prepost)
    {
        $this->elearning->deleteExamResult($data, $prepost);
    }

    public function examination()
    {
        $this->setElearningStatus();
        $data['title'] = 'Examination';
        $data['getQuestionaire'] = $this->elearning->getActiveQuestionaire();

        $user_id = $this->session->userdata['user_id'];
        $data['allElearningAssigned'] = $this->elearning->getAllElearningAssigned($user_id);

        for ($a = 0; $a < count($data['getQuestionaire']); $a++) {
            $this->form_validation->set_rules('answer[' . $a . ']', 'Answer', 'required');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/exam_list', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer-elearning');
        }
    }

    public function pretest()
    {
        $data['title'] = 'Pretest';
        $elearning_id = $this->uri->segment(3);
        $user_id = $this->session->userdata['user_id'];
        $data['user_id'] = $this->session->userdata['user_id'];
        $question_qty = $this->db->get_where('elearning_category', ['id' => $elearning_id])->row_array()['question_qty'];
        $passing_score = $this->db->get_where('elearning_category', ['id' => $elearning_id])->row_array()['passing_score'];
        $data['getQuestionaire'] = $this->elearning->getAllQuestionaireById($elearning_id, $question_qty);

        if ($this->db->get_where('elearning_examination', ['user_id' => $user_id, 'elearning_id' => $elearning_id, 'pre_post' => 'pretest'])->num_rows() > 0)
        {
            $this->session->set_flashdata('message', 'Nehi-Nehi|error|You have submitted this examination!');
            redirect('elearning/result');
        }

        for ($a = 0; $a < count($data['getQuestionaire']); $a++) {
            $this->form_validation->set_rules('answer[' . $a . ']', 'Answer', 'trim');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/pretest', $data);
            $this->load->view('templates/footer');
            // $this->load->view('templates/footer_exam');
        } else {
            $submitData = [];
            for ($i = 0; $i < count($data['getQuestionaire']); $i++) {
                $correctKey = $this->elearning->getQuestionaireById($this->input->post('qid[' . $i . ']'))['correct_key'];
                if ($this->input->post('answer[' . $i . ']') == $correctKey) {
                    $check_correct = 1;
                } else if ($this->input->post('answer[' . $i . ']') == null) {
                    $check_correct = 0;
                } else {
                    $check_correct = 0;
                }
                $pretestStart = $this->input->post('exam_pretest_start');
                $submitData[$i] = [
                    'questionaire_id' => $this->input->post('qid[' . $i . ']'),
                    'elearning_id' => $elearning_id,
                    'pre_post' => 'pretest',
                    'user_id' => $user_id,
                    'correct_key' => $correctKey,                    
                    'answer' => $this->input->post('answer[' . $i . ']'),
                    'is_correct' => $check_correct,
                    'datetime' => date("Y-m-d H:i:s")
                ];
            }            
            $this->submitexam($submitData, $user_id, $elearning_id, $passing_score, 'pretest', $pretestStart);
        }
    }

    public function posttest()
    {
        $data['title'] = 'Post Test';
        $elearning_id = $this->uri->segment(3);
        //$elearning_id = 52;
        $user_id = $this->session->userdata['user_id'];
        $data['user_id'] = $this->session->userdata['user_id'];
        $question_qty = $this->db->get_where('elearning_category', ['id' => $elearning_id])->row_array()['question_qty'];
        $passing_score = $this->db->get_where('elearning_category', ['id' => $elearning_id])->row_array()['passing_score'];        
        $data['getQuestionaire'] = $this->elearning->getAllQuestionaireById($elearning_id, $question_qty);

        if ($this->db->get_where('elearning_examination', ['user_id' => $user_id, 'elearning_id' => $elearning_id, 'pre_post' => 'posttest'])->num_rows() > 0) {
            $this->session->set_flashdata('message', 'Nehi-Nehi|error|1.XXXYou have submitted this examination!');
            redirect('elearning/result');
        }

        if (count($data['getQuestionaire']) < 1) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('elearning/elearning-waitdate', $data);
            $this->load->view('templates/footer');
        } else {
            if($this->elearning->checkPretest($elearning_id, $user_id)['pretest'] == 1 && $this->elearning->checkPretest($elearning_id, $user_id)['pretest_done'] == 0) {
                $this->session->set_flashdata('message', 'Forbidden|error|Please perform PRETEST first!');
                redirect('elearning/examination');
            }

            for ($a = 0; $a < count($data['getQuestionaire']); $a++) {
                $this->form_validation->set_rules('answer[' . $a . ']', 'Answer', 'trim');
            }

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('elearning/posttest', $data);
                $this->load->view('templates/footer');
                // $this->load->view('templates/footer_exam');
            } else {
                $submitData = [];
                $posttestStart = $this->input->post('exam_posttest_start');
                for ($i = 0; $i < count($data['getQuestionaire']); $i++) {
                    $correctKey = $this->elearning->getQuestionaireById($this->input->post('qid[' . $i . ']'))['correct_key'];
                    if ($this->input->post('answer[' . $i . ']') == $correctKey) {
                        $check_correct = 1;
                    } else if ($this->input->post('answer[' . $i . ']') == null) {
                        $check_correct = 0;
                    } else {
                        $check_correct = 0;
                    }
                    $submitData[$i] = [
                        'questionaire_id' => $this->input->post('qid[' . $i . ']'),
                        'elearning_id' => $elearning_id,
                        'pre_post' => 'posttest',
                        'user_id' => $user_id,
                        'correct_key' => $correctKey,                    
                        'answer' => $this->input->post('answer[' . $i . ']'),
                        'is_correct' => $check_correct,
                        'datetime' => date("Y-m-d H:i:s")
                    ];
                }
                $this->submitexam($submitData, $user_id, $elearning_id, $passing_score, 'posttest', $posttestStart);
            }
        }
    }

    public function submitexam($submitData, $user_id, $elearning_id, $passing_score, $prepost, $posttestStart)
    {
        if ($this->db->get_where('elearning_examination', ['user_id' => $user_id, 'elearning_id' => $elearning_id, 'pre_post' => 'posttest'])->num_rows() > 0) {
            $this->session->set_flashdata('message', 'Nehi-Nehi|error|2. You have submitted this examination!');
            redirect('elearning/result');
        } else {
            if ($this->elearning->submitExam($submitData) > 0) {
                $this->submitScore($user_id, $elearning_id, $passing_score, $prepost, $posttestStart);
            }
            redirect('elearning/result/' . $elearning_id);
        }
    }

    private function submitScore($user_id, $elearning_id, $passing_score, $prepost, $posttestStart)
    {
        //cek sudah ujian atau belum
        $is_done = $this->db->get_where('elearning_assignment', ['user_id' => $user_id, 'elearning_id' => $elearning_id])->row_array();
        if ($is_done['posttest_done'] == 1) {
            $this->session->set_flashdata('message', 'Failed Submission|error|You have submitted this exam before!');
        } else { 
            $exam_date = $this->db->get_where('elearning_examination', ['user_id' => $user_id, 'elearning_id' => $elearning_id, 'pre_post' => $prepost])->row_array()['datetime'];
            if ($this->elearning->submitScore($exam_date, $passing_score, $posttestStart, $this->_countScore($user_id, $elearning_id, $prepost)) > 0) {
                $this->session->set_flashdata('message', 'Examination|success|BERHASIL!');
            } else {
                $this->session->set_flashdata('message', 'Examination|error|GAGAL!');
            }
        }
    }

    private function _countScore($user_id, $elearning_id, $prepost)
    {
        $countQuestionaire = (int) $this->elearning->countQuestionaireById($user_id, $elearning_id, $prepost);
        $correctAnswer = (int) $this->elearning->countCorrectAnswer($user_id, $elearning_id, $prepost);
        $res = $correctAnswer * 100 / $countQuestionaire;
        $score = 0;
        $out = [];

        $prev = $this->db->get_where('elearning_assignment', ['elearning_id' => $elearning_id, 'user_id' => $user_id])->row_array();

        if ($prev['posttest_remedial'] > 0) {
            $arr = explode(', ', $prev['score_remedial']);
            $scoPrev = end($arr);
            $score = (($scoPrev + $res) / 2);
        } else {
            $score = $res;
        }

        $out = [
            'score' => $score,
            'prepost' => $prepost,
            'elearning_id' => $elearning_id,
            'user_id' => $user_id,
        ];
        
        return $out;
    }

    public function result()
    {
        $data['title'] = 'Elearning Result';
        $user_id = $this->session->userdata('user_id');
        if (empty($this->uri->segment(3))) {
            $elearning_id = $this->elearning->getAllElearningCategory()[0]['id'];
        } else {
            $elearning_id = $this->uri->segment(3);
        }
        $data['userList'] = $this->elearning->getActiveUserId();
        $data['elearningList'] = $this->elearning->getAllElearningCategory();        
        $data['resultByCategoryByAgent'] = $this->elearning->resultByCategoryByAgent($user_id, $elearning_id);
        $data['examQuestionaireById'] = $this->elearning->examQuestionaireById($user_id, $elearning_id, 'posttest');

        $check_pretest = $this->db->get_where('elearning_assignment', ['elearning_id' => $elearning_id, 'user_id' => $user_id])->row_array();
        if (is_null($check_pretest)) {

        } else {
            if($check_pretest['pretest_done'] == 1 && $check_pretest['posttest_done'] == 0 && $this->session->userdata('role_access') == 3) {
                $this->session->set_flashdata('message', 'Denied|info|Please perform POSTTEST to see the result!');
                redirect('elearning/examination');
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('elearning/result_by_agent', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-elearning');
    }

    public function resultByCategoryByAgentDetail()
    {
        $user_id = $this->session->userdata['user_id'];
        $elearning_id = $this->input->post('elearning_id');
        $pre_post = $this->input->post('pre_post');
        $resultByCategoryByAgent = $this->elearning->resultByCategoryByAgentDetail($user_id, $elearning_id, $pre_post);
        echo json_encode($resultByCategoryByAgent);
    }

    public function resultByCategoryBySelectedAgent()
    {
        $user_id = $this->input->post('user_id');
        $elearning_id = $this->input->post('elearning_id');
        $pre_post = $this->input->post('pre_post');
        if ( $this->elearning->checkExistingElearningExamination($elearning_id) > 0 ) {
            $resultByCategoryByAgent = $this->elearning->resultByCategoryByAgentDetail($user_id, $elearning_id, $pre_post);
        } else {            
            $resultByCategoryByAgent = $this->elearning->resultByCategoryByAgentSummary($user_id, $elearning_id, $pre_post);
        }
        echo json_encode($resultByCategoryByAgent);
    }

    public function resultTest()
    {
        $user_id = 'Aliahmad';
        $elearning_id = '30';
        $pre_post = 'posttest';
        $resultByCategoryByAgent = $this->elearning->resultByCategoryByAgentSummary($user_id, $elearning_id, $pre_post);
        
        echo json_encode($resultByCategoryByAgent);
    }

    public function summary()
    {
        check_access();
        $data['title'] = 'Elearning Summary';
        if (!($this->input->post('selectCategorySummary'))) {
            $elearning_id = $this->elearning->getAllElearningCategory()[0]['id'];
        } else {
            $elearning_id = $this->input->post('selectCategorySummary');
        }

        if (!$this->input->post('selectElearningSummaryEnd')) {
            $startPeriod = date("Y-m-01", strtotime("-5 Months"));
            $endPeriod = date("Y-m-01");
        } else {
            $startPeriod = date("Y-m-01", strtotime($this->input->post('selectElearningSummaryStart')));
            $endPeriod = date("Y-m-01", strtotime($this->input->post('selectElearningSummaryEnd')));
        }

        $data['latestElearningPeriod'] = $this->elearning->getLatestElearningId()['period'];
        $data['summaryByCategory'] = $this->elearning->summaryByCategory($elearning_id);
        $data['elearning_id'] = $this->elearning->getSelectedElearningCategory($elearning_id)['id'];
        $data['elearning_name'] = $this->elearning->getSelectedElearningCategory($elearning_id)['name'];
        $data['elearning_period'] = $this->elearning->getSelectedElearningCategory($elearning_id)['period'];
        $data['summaryPeriod'] = $this->elearning->getPeriodSummary();
        $data['elearningList'] = $this->elearning->getAllElearningCategory();

        //$data['summaryResultTransition'] = $this->elearning->summaryResult(date("Y-m-01", strtotime("-5 Months")), $data['latestElearningPeriod']);
        $data['summaryResultTransition'] = $this->elearning->summaryResult($startPeriod, $endPeriod);
        // $data['summaryResultTransition'] = $this->elearning->summaryResult('2022-04-01', '2022-09-01');
        // var_dump($data['summaryResult']);
        // die;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('elearning/summary', $data);
        $this->load->view('templates/footer');
        // $this->load->view('templates/footer-elearning');
    }

    public function summaryByPeriod()
    {
        $startPeriod = $this->input->post('selectElearningSummaryStart');
        $endPeriod = $this->input->post('selectElearningSummaryEnd');
        $result = json_encode($this->elearning->summaryResult($startPeriod, $endPeriod));
        $result = json_decode($result, true);
        echo json_encode($result);
    }

    public function getSummaryByCategory()
    {
        $elearning_id = $this->input->post('elearning_id');
        echo json_encode($this->elearning->summaryByCategory($elearning_id));
    }


    //FUNCTION CONVERT TO DATETIME FORMAT
    public function toDatetime($data)
    {
        if (strtotime($data) < 1) {
            return "-";
        } else {
            return date("d-M-Y H:i:s", strtotime($data));
        }
    }

    //FUNCTION CONVERT PASS OR FAILED
    public function isPass($data)
    {
        if ($data == 0) {
            return "Failed";
        } else {
            return "Pass";
        }
    }

    private function _checkNull($data)
    {
        if (is_null($data)) {
            return 0;
        } else {
            return 1;
        }
    }

    private function _scoreToString($score)
    {
        if (is_null($score) || $score = 0) {
            return '-';
        } else {
            return $score;
        }
    }

    private function _isPassUpload($score, $passingScore)
    {
        if ((float) $score < (float) $passingScore) {
            return 0;
        } else {
            return 1;
        }
    }

    //EXPORT SUMMARY OF ELEARNING RESULT TO EXCEL
    public function export()
    {
        // Load plugin PHPExcel
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        // Load class PHPExcel
        $excel = new PHPExcel();

        // Settingan awal file excel
        $excel->getProperties()->setCreator('Elearning Administrator')->setLastModifiedBy('Elearning Administrator')->setTitle("Result of Elearning")->setSubject("Elearning")->setDescription("Result of Elearning by Period")->setKeywords("Result of Elearning");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
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
        ];

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
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
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "RESULT OF ELEARNING"); // Set kolom A1 dengan tulisan "RESULT OF ELEARNING"
        //$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16); // Set font size 15 untuk kolom A1
        //$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "CTI ID");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Fullname");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NPK"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Department"); 
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "PRETEST"); 
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Score"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Start at");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Finish at"); 
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Times (second)"); 
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "POSTTEST"); 
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "Score"); 
        $excel->setActiveSheetIndex(0)->setCellValue('K4', "Start at"); 
        $excel->setActiveSheetIndex(0)->setCellValue('L4', "Finish at"); 
        $excel->setActiveSheetIndex(0)->setCellValue('M4', "Times (second)"); 
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Is Pass?");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "REMEDIAL");
        $excel->setActiveSheetIndex(0)->setCellValue('O4', "Times");
        $excel->setActiveSheetIndex(0)->setCellValue('P4', "Score");

        $excel->getActiveSheet()->mergeCells('A3:A4');
        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->getActiveSheet()->mergeCells('D3:D4');
        $excel->getActiveSheet()->mergeCells('E3:E4');
        $excel->getActiveSheet()->mergeCells('F3:I3');
        $excel->getActiveSheet()->mergeCells('J3:M3');
        $excel->getActiveSheet()->mergeCells('N3:N4');
        $excel->getActiveSheet()->mergeCells('O3:P3');

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
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P4')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $elearning_id = $this->uri->segment(3);
        $elearning_result = $this->elearning->summaryByCategory($elearning_id);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($elearning_result as $er) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $er['user_id']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $er['fullname']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $er['npk']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $er['department']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $er['pretest_score']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $this->toDatetime($er['pretest_start']));
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $this->toDatetime($er['pretest_date']));
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $er['pretest_duration']);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $er['posttest_score']);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $this->toDatetime($er['posttest_start']));
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $this->toDatetime($er['posttest_date']));
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $er['exam_duration']);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $this->isPass($er['is_pass']));
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $er['remedial']);
            $excel->setActiveSheetIndex(0)->setCellValue('P' . $numrow, $er['score_remedial']);

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
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('O' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('P' . $numrow)->applyFromArray($style_row);
            $no++;

            // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping    
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(10); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Set width kolom F
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(10); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(10); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('p')->setWidth(20); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Result of Elearning");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Summary of Elearning Result.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    //UPLOAD QUESTIONER DARI EXCEL
    public function uploadQuestionaire()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        $config['upload_path'] = './questioner';
        $config['allowed_types'] = 'xlsx|xls|csv|ods';
        $config['max_size'] = '10496';
        $config['overwrite'] = true;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('uploadQuestionaireFile')) {
            var_dump($this->upload->error_msg);
        } else {
            $data_upload = $this->upload->data();
            $excelreader = new PHPExcel_Reader_Excel2007();
            // var_dump('questioner/' . $data_upload['file_name']);
            // die;
            $loadexcel = $excelreader->load('questioner/' . $data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true, true, true, true, true, true);

            // var_dump($sheet);
            // echo "<br>";

            $data = array();
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($row['A'] == '' || $row['A'] == null) {
                    continue;
                } else {
                    if ($numrow > 1) {
                        array_push($data, array(
                            'category' => $this->input->post('uploadQuestionaireSelectCategory'),
                            'period' => date("Y-m-01", strtotime($this->input->post('uploadQuestionairePeriod'))),
                            'question' => $row['A'],
                            'option_a' => $row['B'],
                            'option_b' => $row['C'],
                            'option_c' => $row['D'],
                            'option_d' => $row['E'],
                            'option_e' => $row['F'],
                            'correct_key' => strtoupper($row['G']),
                            'status' => '1'
                        ));
                    }
                }
                $numrow++;
            }

            $this->db->insert_batch('elearning_questionaire', $data);
            //delete file from server
            unlink(realpath('questioner/' . $data_upload['file_name']));

            //upload success
            $this->session->set_flashdata('message', 'Questioner|success|Quccessly uploaded...!');
            //redirect halaman
            redirect('elearning/questionaire');
        }
    }

    //UPLOAD ELEARNING RESULT DARI EXCEL
    public function uploadElearningResultFromExcel()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        $config['upload_path'] = './elearning_result';
        $config['allowed_types'] = 'xlsx|xls|csv|ods';
        $config['max_size'] = '10496';
        $config['overwrite'] = true;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('uploadElearningResultFromExcelFile')) {
            var_dump($this->upload->error_msg);
        } else {
            $data_upload = $this->upload->data();
            $excelreader = new PHPExcel_Reader_Excel2007();
            // var_dump('questioner/' . $data_upload['file_name']);
            // die;
            $loadexcel = $excelreader->load('elearning_result/' . $data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(true, true, true, true, true, true, true, true);

            $latestElearningId = (int)$this->elearning->getLatestElearningId()['id']; 
            $newElearningId = $latestElearningId + 1; 

            $data = [];            
            for ($numrow = 56; $numrow < count($sheet); $numrow++) {
                $data[] = [
                        'elearning_id' => $newElearningId,
                        'user_id' => $sheet[$numrow]['A'],
                        'pretest_done' => $this->_checkNull($sheet[$numrow]['B']),                        
                        'pretest_score' => $this->_scoreToString($sheet[$numrow]['C']),
                        'posttest_done' => $this->_checkNull($sheet[$numrow]['D']),
                        'posttest_score' => $sheet[$numrow]['E'],
                        'is_pass' => $this->_isPassUpload($sheet[$numrow]['E'], $this->input->post('uploadElearningResultFromExcelPassingScore')),
                        'pretest_date' => $this->_checkNull($sheet[$numrow]['F']),
                        'posttest_date' => $sheet[$numrow]['G']                        
                    ];
            }

            $dataElearningCategory = [
                'id' => $newElearningId,
                'period' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelPeriod'))),
                'name' => $this->input->post('uploadElearningResultFromExcelCategory'),
                'passing_score' => $this->input->post('uploadElearningResultFromExcelPassingScore'),
                'pretest' => 0,
                'status' => 0,
                'startdate' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelStartdate'))),
                'enddate' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelEnddate')))
            ];

            $this->db->insert('elearning_category', $dataElearningCategory);
            $this->db->insert_batch('elearning_assignment', $data);
            //delete file from server
            unlink(realpath('elearning_result/' . $data_upload['file_name']));

            //upload success
            $this->session->set_flashdata('message', 'Sucess!|success|Elearning result successly uploaded...!');
            //redirect halaman
            redirect('elearning/summary');
        }
    }
    
    public function uploadElearningResultFromExcelNew()
    {
        //$this->use('PhpOffice\PhpSpreadsheet\Spreadsheet');

        if (!empty($_FILES['uploadElearningResultFromExcelFile']['name'])) {
            $extension = pathinfo($_FILES['uploadElearningResultFromExcelFile']['name'], PATHINFO_EXTENSION);
            $allowedExtension = ['csv', 'xls', 'xlsx', 'ods', 'xml'];

            if (!in_array($extension, $allowedExtension)) {
                $this->session->set_flashdata('message', 'GAGAL!|error|Format file tidak sesuai!');
                redirect('elearning/summary');
            } else {                
                switch ($extension) {
                    case 'csv':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        break;
                    case 'ods':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
                        break;
                    case 'xml':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xml();
                        break;
                    case 'xlsx':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        break;                    
                    default:
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                        break;
                }                

                // file path
                $reader->setReadDataOnly(true);
                $reader->setLoadSheetsOnly('upload');
                $spreadsheet = $reader->load($_FILES['uploadElearningResultFromExcelFile']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(true, null, null, true, true, null, true);

                $latestElearningId = (int)$this->elearning->getLatestElearningId()['id']; 
                $newElearningId = $latestElearningId + 1;

                $numrow = 1;
                $rowsLimit = 4000;
                $uploadData = [];
                foreach ($allDataInSheet as $row) {
                    if ($numrow > 2) {
                        if ($row['I'] == null || $row['I'] == '' || empty($row['I']) ) {
                            continue;
                        } else {
                            $uploadData[] = [
                                'elearning_id' => $newElearningId,
                                'user_id' => $row['I'],
                                'pretest_done' => $this->_checkNull($row['J']),
                                'pretest_score' => $this->_scoreToString($row['K']),
                                'posttest_done' => $this->_checkNull($row['L']),
                                'posttest_score' => $row['M'],
                                'is_pass' => $this->_isPassUpload($row['M'], $this->input->post('uploadElearningResultFromExcelPassingScore')),
                                'pretest_date' => $this->_checkNull($row['N']),
                                'posttest_date' => $row['O']
                            ];
                        }
                    }
                    $numrow++;
                }

                $dataElearningCategory = [
                    'id' => $newElearningId,
                    'period' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelPeriod'))),
                    'name' => $this->input->post('uploadElearningResultFromExcelCategory'),
                    'passing_score' => $this->input->post('uploadElearningResultFromExcelPassingScore'),
                    'pretest' => 0,
                    'status' => 0,
                    'startdate' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelStartdate'))),
                    'enddate' => date("Y-m-01", strtotime($this->input->post('uploadElearningResultFromExcelEnddate')))
                ];

                $this->db->insert('elearning_category', $dataElearningCategory);
                $this->db->insert_batch('elearning_assignment', $uploadData);
                //delete file from server
                unlink(realpath('elearning_result/' . $data_upload['file_name']));

                //upload success
                $this->session->set_flashdata('message', 'Sucess!|success|Elearning result successly uploaded...!');
                //redirect halaman
                redirect('elearning/summary');
            }
        }
    }

    // EDUCATION MATERIAL
    public function educationmaterial()
    {
        $data['title'] = 'Education Material';
        $data['productMaterial'] = $this->elearning->getEducationMaterialProduct();
        $data['softskillMaterial'] = $this->elearning->getEducationMaterialNonproduct();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('elearning/education-material');
        $this->load->view('templates/footer');
    }

    // NEW AGENT TRAINING LIST
    public function newagenttraining()
    {
        $data['title'] = 'New Agent Training List';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('elearning/new-agent-training-list');
        $this->load->view('templates/footer');
    }

    // NEW AGENT TRAINING  HISTORY
    public function newagentreview()
    {
        $data['title'] = 'Agent Training History';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('elearning/new-agent-training-review');
        $this->load->view('templates/footer');
    }

    // SKAPE FEEDBACK
    public function skapefeedback()
    {
        check_access();
        $data['title'] = 'Feedback for SKAPE';
        $data['startPeriod'] = date("Y-m-d", strtotime("-1 years"));
        $data['endPeriod'] = date("Y-m-d");
        $data['allSkapeFeedback'] = $this->elearning->getAllSkapeFeedback($data['startPeriod'], $data['endPeriod']);

        $this->form_validation->set_rules('skapefeedbackCategory', 'Category', 'required');
        $this->form_validation->set_rules('skapefeedbackComment', 'Detail Feedback', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('elearning/skape-feedback');
            $this->load->view('templates/footer');        
        } else {
            $data = [
                'category' => $this->input->post('skapefeedbackCategory'),
                'solution_title' => $this->input->post('skapefeedbackTitle'),
                'solution_id' => $this->input->post('skapefeedbackLink'),
                'feedback' => $this->input->post('skapefeedbackComment'),
                'saved_by' => $this->session->userdata('user_id'),
                'saved_at' => date("Y-m-d H:i:s")
            ];

            if ($this->elearning->addNewSkapeFeedback($data) > 0) {
                $this->session->set_flashdata('message', "Succesly Added!|success|Feedback untuk SKAPE sudah disimpan");
                    redirect('elearning/skapefeedback');
            }
        }
    }

    public function deletefeedback($id)
    {
        if ($this->elearning->deleteSkapeFeedback($id) > 0) {
            $this->session->set_flashdata('message', "Deleted!|info|Feedback sudah berhasil dihapus");
            redirect('elearning/skapefeedback');
        }
    }

    public function feedbackbyid()
    {
        $id = $this->input->post('id');
        echo json_encode($this->elearning->getFeedbackById($id));
    }

    public function responsefeedback()
    {
        $updateData = [
            'id' => $this->input->post('feedbackResponseId'),
            'status' => $this->input->post('feedbackResponseStatus'),
            'remark' => $this->input->post('feedbackResponseRemark'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        if ($this->elearning->performResponseFeedback($updateData) > 0 ) {
            $this->session->set_flashdata('message', "Feedback Responsed!|success|SKAPE feedback already responsed");
            redirect('elearning/skapefeedback');
        }
    }
}
