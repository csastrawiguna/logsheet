<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        // Load kabéh nu diperlukeun di awal
        $this->load->dbutil();
        $this->load->library('zip');
        $this->load->helper('file');
        $this->load->library('ftp');
        
        // Atur waktu éksekusi lila bisi datana loba (Unlimited)
        set_time_limit(0);
        ini_set('memory_limit', '512M');
    }

    /**
     * Script Utama: Jalankeun via Task Scheduler unggal subuh - Export Daily, Weekly, Monthly
     //  * URL: http://192.168.188.254/logsheet/index.php/export/auto_backup_gfs
     */

    public function auto_backup_gfs() 
    {
        // 1. Konfigurasi FTP
        $config['hostname'] = '192.168.180.140';
        $config['username'] = 'gay0900276';
        $config['password'] = '2026Februar!'; 
        $config['debug']    = TRUE;

        // Cek koneksi sakaligus connect
        if (!$this->ftp->connect($config)) {
            die("Gagal konek ka FTP!");
        }

        $yesterday    = date('Y-m-d', strtotime("-1 days"));
        $day_of_week  = date('N'); 
        $day_of_month = date('j');

        // 2. Nangtukeun Tipe Backup & Retensi
        if ($day_of_month == 1) {
            $sub = "Monthly";
            $retention = 0; 
        } elseif ($day_of_week == 1) {
            $sub = "Weekly";
            $retention = 45; 
        } else {
            $sub = "Daily";
            $retention = 7; 
        }

        // Path Konfigurasi
        $remote_path = "callcenterdata/Cuparsa/DBBackup/Logsheet/" . $sub . "/"; 
        $zip_name    = "LOGSHEET_Backup_" . $sub . "_" . $yesterday . ".zip";
        $local_temp  = FCPATH . "temp/" . $zip_name; // Pastikeun folder 'temp' aya di root CI

        // 3. PROSES NGUMPULKEUN DATA
        $tables = $this->db->list_tables();
        $summary_log = "BACKUP TYPE: [$sub]\n";
        $summary_log .= "DATE DATA  : $yesterday\n";
        $summary_log .= "RUNTIME    : " . date('Y-m-d H:i:s') . "\n";
        $summary_log .= "------------------------------------------\n";

        $data_found = false;

        foreach ($tables as $table) {
            if ($table == 'log_export') continue;

            $col = '';
            if ($this->db->field_exists('saved_at', $table)) $col = 'saved_at';
            elseif ($this->db->field_exists('input_at', $table)) $col = 'input_at';
            elseif ($this->db->field_exists('updated_at', $table)) $col = 'updated_at';
            elseif ($this->db->field_exists('last_modified_at', $table)) $col = 'last_modified_at';
            elseif ($this->db->field_exists('absent_date', $table)) $col = 'absent_date';
            elseif ($this->db->field_exists('date', $table)) $col = 'date';

            if ($col != '') {
                $this->db->where("DATE($col)", $yesterday);
                $query = $this->db->get($table);

                if ($query->num_rows() > 0) {
                    $csv_data = $this->dbutil->csv_from_result($query);
                    $this->zip->add_data("{$table}_{$yesterday}.csv", $csv_data);
                    $summary_log .= "[OK] $table: " . $query->num_rows() . " rows\n";
                    $data_found = true;
                }
            }
        }

        // 4. SIMPEN KA LOKAL SAMENTARA TULUY UPLOAD KA FTP
        if ($data_found) {
            $this->zip->add_data("LOGSHEET_summary_log.txt", $summary_log);
            
            // Simpen di lokal heula
            if ($this->zip->archive($local_temp)) {
                // Upload ka FTP (Parameter ka-4 FALSE meh teu nanya permission)
                if ($this->ftp->upload($local_temp, $remote_path . $zip_name, 'binary', NULL)) {
                    echo "Suksés! File $zip_name geus di-upload ka FTP folder $sub.";
                } else {
                    echo "Gagal Upload ka FTP. Cek folder $remote_path geus aya acan?";
                }
                
                // Hapus file samentara di lokal
                if (file_exists($local_temp)) {
                    unlink($local_temp);
                }
            }
        } else {
            echo "Teu aya data anyar kamari ($yesterday).";
        }

        // 5. FUNGSI BERSIH-BERSIH FTP (SAACAN CLOSE)
        // Fungsi ieu bakal mariksa file mana wae nu geus liwat ti poe retensi
        if ($retention > 0) {
            $deleted = $this->_cleanup_old_files($remote_path, $retention);
            echo "Auto-Clean: $deleted file lami dihapus tina folder $sub.";
        }

        // 6. PROSES MASTER LOG TXT (Append ka FTP)
        $log_file_name = "LOGSHEET_master_log_export.txt";
        $local_log_path = FCPATH . "temp/" . $log_file_name;
        $remote_log_dir = "/callcenterdata/Cuparsa/DBBackup/Logsheet/"; // Folder utama log

        // Cek daptar file di folder utama log
        $list_ftp_root = $this->ftp->list_files($remote_log_dir);
        $log_content = "--- LOG ENTRY: " . date('Y-m-d H:i:s') . " ---\n" . $summary_log . "\n\n";

        // Mun file master geus aya di FTP, download heula meh bisa ditambahan
        $file_exists_on_ftp = false;
        if ($list_ftp_root) {
            foreach ($list_ftp_root as $f) {
                if (basename($f) == $log_file_name) {
                    $file_exists_on_ftp = true;
                    break;
                }
            }
        }

        if ($file_exists_on_ftp) {
            // Download file nu lami ka lokal temp
            if ($this->ftp->download($remote_log_dir . $log_file_name, $local_log_path, 'ascii')) {
                $existing_content = read_file($local_log_path);
                // Tambahkeun log anyar di luhur (atawa di handap, bebas)
                $log_content = $log_content . "------------------------------------------\n" . $existing_content;
            }
        }

        // Tulis file log gabungan ka lokal
        write_file($local_log_path, $log_content);

        // Upload deui ka FTP (Nimpah file lami)
        if ($this->ftp->upload($local_log_path, $remote_log_dir . $log_file_name, 'ascii')) {
            echo "Master Log parantos di-update di FTP.<br>";
        }

        // Beberes file log di lokal
        if (file_exists($local_log_path)) { unlink($local_log_path); }

        $this->ftp->close();
    }

    /**
     * Fungsi Private pikeun ngahapus file kadaluwarsa
     */
    private function _cleanup_old_files($remote_path, $days) 
    {
        // 1. Ambil daptar file ti FTP (Lain glob lokal)
        $files = $this->ftp->list_files($remote_path);
        $now   = time();
        $count = 0;

        if ($files) {
            foreach ($files as $file) {
                // FTP list_files biasana mulangkeun path lengkep, urang pariksa naha file ZIP
                if (pathinfo($file, PATHINFO_EXTENSION) == 'zip') {
                    
                    // 2. Strategi paling aman: Cek tanggal tina ngaran file
                    // Sabab filemtime di FTP sakapeung teu akurat gumantung setting server FTP-na
                    if (preg_match('/(\d{4}-\d{2}-\d{2})/', $file, $matches)) {
                        $file_date = strtotime($matches[1]);
                        $limit_date = $now - ($days * 86400);

                        if ($file_date < $limit_date) {
                            // 3. Mupus file di FTP (Lain unlink lokal)
                            if ($this->ftp->delete_file($file)) {
                                $count++;
                            }
                        }
                    }
                }
            }
        }
        return $count;
    }

    public function daily_csv() 
    {
        $config['hostname'] = '192.168.180.140';
        $config['username'] = 'gay0900276';
        $config['password'] = '2026Februar!';
        $config['debug']    = TRUE; // Aktifkeun keur debug mun masih gagal

        if (!$this->ftp->connect($config)) {
            die("Gagal konek ka FTP!");
        }

        $tables = $this->db->list_tables();
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $summary_log = "LOG EXPORT TANGGAL: $yesterday\n" . date('Y-m-d H:i:s') . "\n==========================\n";

        $temp_folder = FCPATH . "temp/";
        if (!is_dir($temp_folder)) {
            mkdir($temp_folder, 0777, true);
        }

        foreach ($tables as $table) {
            if ($table == 'log_export') continue;

            $col = '';
            if ($this->db->field_exists('saved_at', $table)) $col = 'saved_at';
            elseif ($this->db->field_exists('input_at', $table)) $col = 'input_at';
            elseif ($this->db->field_exists('updated_at', $table)) $col = 'updated_at';
            elseif ($this->db->field_exists('last_modified_at', $table)) $col = 'last_modified_at';
            elseif ($this->db->field_exists('absent_date', $table)) $col = 'absent_date';
            elseif ($this->db->field_exists('date', $table)) $col = 'date';

            if ($col != '') {
                // PERBAIKAN QUERY: Pake DATE murni SQL
                $this->db->where("DATE($col)", $yesterday);
                $query = $this->db->get($table);

                if ($query->num_rows() > 0) {
                    $csv_data = $this->dbutil->csv_from_result($query);
                    $file_name = "{$table}_{$yesterday}.csv";
                    $temp_path = $temp_folder . $file_name;
                    
                    write_file($temp_path, $csv_data);
                    // --- IEU LOGIKA MKDIR PIKEUN SERVER FTP ---
                    $remote_base_dir = "/callcenterdata/Cuparsa/DBBackup/Logsheet/";
                    $remote_table_dir = $remote_base_dir . $table . "/";

                    // Cek heula folder tabelna, mun can aya karek jieun
                    // CI3 FTP list_files biasana méré array eusi folder
                    $list_dir = $this->ftp->list_files($remote_base_dir);
                    
                    $remote_table_dir = $remote_base_dir . $table . "/";

                    // 1. Ambil daptar file/folder di level base (Logsheet)
                    $list_existing = $this->ftp->list_files($remote_base_dir);

                    // 2. Bersihkeun nami folder pikeun babandingan (ngaleungitkeun path lengkep)
                    $folder_exists = FALSE;
                    if ($list_existing) {
                        foreach ($list_existing as $item) {
                            // CI3 list_files sakapeung méré full path, urang cokot tungtungna hungkul
                            if (basename($item) == $table) {
                                $folder_exists = TRUE;
                                break;
                            }
                        }
                    }

                    // 3. Ukur nyieun folder lamun bener-bener can aya
                    if (!$folder_exists) {
                        // Mun gagal di dieu, urang antepkeun sangkan teu fatal error
                        $this->ftp->mkdir($remote_table_dir, FALSE);
                    }

                    // 4. Langsung upload
                    $remote_file_path = $remote_table_dir . $file_name;
                    $this->ftp->upload($temp_path, $remote_file_path, 'binary');

                    // Jalur lengkep ka file tujuan
                    $remote_file_path = $remote_table_dir . $file_name;

                    $remote_path = "/callcenterdata/Cuparsa/DBBackup/Logsheet/{$table}/{$file_name}";
                    
                    // Proses Upload
                    if ($this->ftp->upload($temp_path, $remote_file_path, 'binary', FALSE)) {
                        // ... (logika simpen log sukses ka DB) ...
                        $summary_log .= "[OK] $table: Berhasil diupload\n";
                    } else {
                        // Ieu jang debug mun masih gagal upload
                        $summary_log .= "[FAIL] $table: Gagal upload ka $remote_file_path\n";
                    }

                    if (file_exists($$temp_path)) {
                        unlink($$temp_path);
                    }
                } else {
                    $summary_log .= "[SKIP] $table: No data found for $yesterday\n";
                }
            }
        }

        // --- PROSES LOG TXT ---
        $log_file_name = "master_log_export.txt";
        $local_log_path = $temp_folder . $log_file_name;
        $remote_log_dir = "/callcenterdata/Cuparsa/DBBackup/Logsheet/";

        // Coba download file lami mun aya
        $list_ftp = $this->ftp->list_files($remote_log_dir);
        $new_content = $summary_log;

        // Perbaikan cek in_array (biasana list_files ngan méré ngaran filena hungkul, gumantung server)
        if ($list_ftp && in_array($log_file_name, $list_ftp)) {
            $this->ftp->download($remote_log_dir . $log_file_name, $local_log_path, 'ascii');
            $existing_content = read_file($local_log_path);
            $new_content = $existing_content . "\n\n" . $summary_log;
        }

        $list = $this->ftp->list_files("/callcenterdata/Cuparsa/DBBackup/Logsheet/");
        echo "<pre>";
        print_r($list); // Ningali daftar folder nu aya di FTP
        echo "</pre>";

        write_file($local_log_path, $new_content);
        $this->ftp->upload($local_log_path, $remote_log_dir . $log_file_name, 'ascii');
        @unlink($local_log_path);

        $this->ftp->close();
        echo "Beres! Log parantos di-update.";
    }

}
