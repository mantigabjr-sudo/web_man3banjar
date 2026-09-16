<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_foto_ijazah extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
        $allowed = ['admin', 'admin_master', 'admin_website', 'admin_humas', 'wakil_humas', 'operator_humas', 'admin_kesiswaan', 'admin_kurikulum'];
        if(!in_array($this->session->userdata('role'), $allowed)){
            redirect('dashboard');
        }
        $this->ensure_setup();
    }

    private function ensure_setup(){
        // 1. Pastikan tabel foto_ijazah_verifikasi tersedia
        if (!$this->db->table_exists('foto_ijazah_verifikasi')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `foto_ijazah_verifikasi` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `siswa_id` int(11) DEFAULT NULL,
                  `nisn` varchar(20) DEFAULT NULL,
                  `nama_siswa` varchar(150) DEFAULT NULL,
                  `kelas_id` int(11) DEFAULT NULL,
                  `file_mentah` varchar(255) NOT NULL,
                  `file_verified` varchar(255) DEFAULT NULL,
                  `status` enum('pending','verified','rejected') DEFAULT 'pending',
                  `verified_at` datetime DEFAULT NULL,
                  `ip_address` varchar(45) DEFAULT NULL,
                  `user_agent` varchar(255) DEFAULT NULL,
                  `catatan` varchar(255) DEFAULT NULL,
                  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `idx_siswa_id` (`siswa_id`),
                  KEY `idx_nisn` (`nisn`),
                  KEY `idx_status` (`status`),
                  KEY `idx_kelas_id` (`kelas_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        // 2. Pastikan folder uploads tersedia
        $dir_mentah = FCPATH . 'uploads/foto_ijazah/mentah/';
        if(!is_dir($dir_mentah)){
            @mkdir($dir_mentah, 0777, true);
        }
        $dir_verified = FCPATH . 'uploads/foto_ijazah/verified/';
        if(!is_dir($dir_verified)){
            @mkdir($dir_verified, 0777, true);
        }
    }

    public function index(){
        $data['title'] = 'Kelola Verifikasi Foto Ijazah Kelas XII';

        $setting = $this->db->get('settings')->row();
        $tahun_aktif = !empty($setting->tahun_ajaran) ? trim($setting->tahun_ajaran) : '2026/2027';

        // Ambil daftar kelas XII khusus tahun ajaran aktif
        $data['kelas_list'] = $this->db->query("
            SELECT * FROM kelas 
            WHERE (tingkat = '12' OR tingkat = 'XII' OR nama_kelas LIKE '%XII%')
            AND tahun_ajaran = ?
            ORDER BY nama_kelas ASC
        ", [$tahun_aktif])->result_array();

        // Fallback jika tidak ada exact match, ambil kelas XII yang memiliki siswa aktif
        if(empty($data['kelas_list'])){
            $data['kelas_list'] = $this->db->query("
                SELECT k.*, COUNT(sk.siswa_id) as total_siswa
                FROM kelas k
                JOIN siswa_kelas sk ON sk.kelas_id = k.id
                JOIN siswa s ON s.id = sk.siswa_id AND s.status_siswa = 'aktif'
                WHERE (k.tingkat = '12' OR k.tingkat = 'XII' OR k.nama_kelas LIKE '%XII%')
                GROUP BY k.id
                HAVING total_siswa > 0
                ORDER BY k.nama_kelas ASC
            ")->result_array();
        }

        $kelas_ids = array_column($data['kelas_list'], 'id');
        if(empty($kelas_ids)) $kelas_ids = [0];
        $kelas_in = implode(',', array_map('intval', $kelas_ids));

        // Filter kelas jika ada
        $kelas_id = $this->input->get('kelas_id');
        $status_filter = $this->input->get('status');

        // Statistik
        $sql_total = "
            SELECT COUNT(DISTINCT s.id) as total_siswa
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            WHERE sk.kelas_id IN ($kelas_in)
            AND s.status_siswa = 'aktif'
        ";
        $data['total_siswa_xii'] = (int)$this->db->query($sql_total)->row()->total_siswa;

        $data['total_verified'] = (int)$this->db->where('status', 'verified')->count_all_results('foto_ijazah_verifikasi');
        $data['total_pending_photos'] = (int)$this->db->where('status', 'pending')->count_all_results('foto_ijazah_verifikasi');

        // Statistik per kelas
        $data['rekap_kelas'] = [];
        foreach($data['kelas_list'] as $k){
            $total_k = $this->db->query("
                SELECT COUNT(DISTINCT s.id) as total
                FROM siswa s
                JOIN siswa_kelas sk ON sk.siswa_id = s.id
                WHERE sk.kelas_id = ? AND s.status_siswa = 'aktif'
            ", [$k['id']])->row()->total;

            $verif_k = $this->db->query("
                SELECT COUNT(DISTINCT f.siswa_id) as total
                FROM foto_ijazah_verifikasi f
                JOIN siswa_kelas sk ON sk.siswa_id = f.siswa_id
                WHERE sk.kelas_id = ? AND f.status = 'verified'
            ", [$k['id']])->row()->total;

            $data['rekap_kelas'][] = [
                'id' => $k['id'],
                'nama_kelas' => $k['nama_kelas'],
                'total' => (int)$total_k,
                'verified' => (int)$verif_k,
                'persen' => $total_k > 0 ? round(($verif_k / $total_k) * 100) : 0
            ];
        }

        // Query Seluruh Siswa Kelas XII & Status Fotonya (untuk instant filtering tanpa reload)
        $where_clause = "WHERE sk.kelas_id IN ($kelas_in) AND s.status_siswa = 'aktif'";
        $sql_siswa = "
            SELECT 
                s.id as siswa_id,
                s.nis,
                s.nisn,
                s.nama_lengkap,
                s.jk,
                k.id as kelas_id,
                k.nama_kelas,
                f.id as verif_id,
                f.file_mentah,
                f.file_verified,
                f.status as verif_status,
                f.verified_at,
                f.ip_address
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            JOIN kelas k ON k.id = sk.kelas_id
            LEFT JOIN foto_ijazah_verifikasi f ON f.siswa_id = s.id AND f.status = 'verified'
            $where_clause
            ORDER BY k.nama_kelas ASC, s.nama_lengkap ASC
        ";

        $data['siswa_list'] = $this->db->query($sql_siswa)->result_array();
        $data['selected_kelas'] = $kelas_id;
        $data['selected_status'] = $status_filter;

        // Daftar foto mentah yang belum diklaim
        $data['unclaimed_photos'] = $this->db->where('status', 'pending')
            ->order_by('id', 'DESC')
            ->get('foto_ijazah_verifikasi')
            ->result_array();

        // Daftar semua siswa kelas XII yang belum verifikasi (untuk modal admin)
        $data['all_unverified_siswa'] = $this->db->query("
            SELECT s.id, s.nisn, s.nis, s.nama_lengkap, k.nama_kelas
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            JOIN kelas k ON k.id = sk.kelas_id
            LEFT JOIN foto_ijazah_verifikasi f ON f.siswa_id = s.id AND f.status = 'verified'
            WHERE sk.kelas_id IN ($kelas_in) AND s.status_siswa = 'aktif' AND f.id IS NULL
            ORDER BY k.nama_kelas ASC, s.nama_lengkap ASC
        ")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin_foto_ijazah/index', $data);
        $this->load->view('templates/footer');
    }

    // Fitur Scan Folder Otomatis: jika admin copy file langsung ke folder uploads/foto_ijazah/mentah
    public function scan_folder(){
        $folder = FCPATH . 'uploads/foto_ijazah/mentah/';
        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        $files = scandir($folder);
        $added = 0;
        $existing = 0;

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'JPG', 'JPEG', 'PNG', 'WEBP'];

        foreach($files as $file){
            if($file === '.' || $file === '..') continue;
            
            $file_path = $folder . $file;
            if(!is_file($file_path)) continue;

            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_ext)) continue;

            // Cek apakah sudah terdaftar di database
            $cek = $this->db->where('file_mentah', $file)->get('foto_ijazah_verifikasi')->row();
            if(!$cek){
                $this->db->insert('foto_ijazah_verifikasi', [
                    'file_mentah' => $file,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $added++;
            } else {
                $existing++;
            }
        }

        $this->session->set_flashdata('success', "Proses scan selesai! $added foto baru berhasil didaftarkan ke sistem (sudah ada: $existing).");
        redirect('admin_foto_ijazah');
    }

    // Upload file foto mentah atau file ZIP
    public function upload_foto(){
        $folder = FCPATH . 'uploads/foto_ijazah/mentah/';
        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        $kelas_id = $this->input->post('kelas_id');
        $kelas_id = !empty($kelas_id) ? (int)$kelas_id : null;

        // Cek jika upload file ZIP
        if(!empty($_FILES['zip_file']['name'])){
            $config['upload_path']   = $folder;
            $config['allowed_types'] = 'zip';
            $config['max_size']      = 0; // 0 = tidak dibatasi oleh CI (mengikuti batas PHP)
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('zip_file')){
                $this->session->set_flashdata('error', 'Gagal upload ZIP: ' . $this->upload->display_errors('', ''));
                redirect('admin_foto_ijazah');
                return;
            }

            $zip_data = $this->upload->data();
            $zip_path = $folder . $zip_data['file_name'];

            $zip = new ZipArchive;
            if ($zip->open($zip_path) === TRUE) {
                $zip->extractTo($folder);
                $zip->close();
                @unlink($zip_path); // Hapus file zip setelah diekstrak

                // Panggil scan folder otomatis
                $this->scan_folder();
                return;
            } else {
                $this->session->set_flashdata('error', 'Gagal mengekstrak arsip ZIP.');
                redirect('admin_foto_ijazah');
                return;
            }
        }

        // Upload multiple images
        if(!empty($_FILES['foto_files']['name'][0])){
            $count = count($_FILES['foto_files']['name']);
            $uploaded = 0;

            for($i = 0; $i < $count; $i++){
                if(empty($_FILES['foto_files']['name'][$i])) continue;

                $_FILES['single_file']['name']     = $_FILES['foto_files']['name'][$i];
                $_FILES['single_file']['type']     = $_FILES['foto_files']['type'][$i];
                $_FILES['single_file']['tmp_name'] = $_FILES['foto_files']['tmp_name'][$i];
                $_FILES['single_file']['error']    = $_FILES['foto_files']['error'][$i];
                $_FILES['single_file']['size']     = $_FILES['foto_files']['size'][$i];

                $config['upload_path']   = $folder;
                $config['allowed_types'] = 'jpg|jpeg|png|webp|JPG|JPEG|PNG|WEBP';
                $config['max_size']      = 0; // Mengikuti batas PHP
                $config['file_name']     = $_FILES['single_file']['name'];
                $config['overwrite']     = FALSE;

                $this->load->library('upload');
                $this->upload->initialize($config);

                if($this->upload->do_upload('single_file')){
                    $f_data = $this->upload->data();
                    
                    // Cek duplikat nama
                    $cek = $this->db->where('file_mentah', $f_data['file_name'])->get('foto_ijazah_verifikasi')->row();
                    if(!$cek){
                        $this->db->insert('foto_ijazah_verifikasi', [
                            'kelas_id' => $kelas_id,
                            'file_mentah' => $f_data['file_name'],
                            'status' => 'pending',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    $uploaded++;
                }
            }

            $this->session->set_flashdata('success', "Berhasil mengunggah $uploaded file foto mentah.");
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang dipilih untuk diunggah.');
        }

        redirect('admin_foto_ijazah');
    }

    // Reset klaim verifikasi siswa (jika salah pilih foto)
    public function reset_klaim($id){
        $verif = $this->db->where('id', $id)->get('foto_ijazah_verifikasi')->row();
        if(!$verif){
            $this->session->set_flashdata('error', 'Data verifikasi tidak ditemukan.');
            redirect('admin_foto_ijazah');
        }

        // Hapus file verified
        if(!empty($verif->file_verified)){
            $verified_path = FCPATH . 'uploads/foto_ijazah/verified/' . $verif->file_verified;
            if(file_exists($verified_path)){
                @unlink($verified_path);
            }
        }

        // Kembalikan status foto menjadi pending
        $this->db->where('id', $id)->update('foto_ijazah_verifikasi', [
            'siswa_id' => NULL,
            'nisn' => NULL,
            'nama_siswa' => NULL,
            'file_verified' => NULL,
            'status' => 'pending',
            'verified_at' => NULL,
            'ip_address' => NULL,
            'user_agent' => NULL,
            'catatan' => 'Verifikasi di-reset oleh admin pada ' . date('d-m-Y H:i')
        ]);

        $this->session->set_flashdata('success', 'Verifikasi berhasil di-reset. Foto mentah dikembalikan ke galeri belum diverifikasi.');
        
        if($this->input->is_ajax_request() || $this->input->get('is_ajax')){
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => 'success',
                'message' => 'Verifikasi berhasil di-reset. Foto mentah dikembalikan ke galeri.',
                'id'      => $id
            ]);
            return;
        }

        redirect('admin_foto_ijazah');
    }

    // Hapus foto mentah yang belum diklaim
    public function hapus_mentah($id){
        $verif = $this->db->where('id', $id)->where('status', 'pending')->get('foto_ijazah_verifikasi')->row();
        if(!$verif){
            $this->session->set_flashdata('error', 'Foto tidak ditemukan atau sudah diverifikasi.');
            redirect('admin_foto_ijazah');
        }

        $path = FCPATH . 'uploads/foto_ijazah/mentah/' . $verif->file_mentah;
        if(file_exists($path)){
            @unlink($path);
        }

        $this->db->where('id', $id)->delete('foto_ijazah_verifikasi');
        $this->session->set_flashdata('success', 'Foto mentah berhasil dihapus.');
        redirect('admin_foto_ijazah');
    }

    // Download Foto Tunggal per Siswa (Tersedia opsi Maks 1MB & Asli)
    public function download_single($id){
        $verif = $this->db->where('id', $id)->where('status', 'verified')->get('foto_ijazah_verifikasi')->row();
        if(!$verif){
            $this->session->set_flashdata('error', 'Data verifikasi siswa tidak ditemukan.');
            redirect('admin_foto_ijazah');
            return;
        }

        $file_name = !empty($verif->file_verified) ? $verif->file_verified : ($verif->nisn . '.jpg');
        $file_path = FCPATH . 'uploads/foto_ijazah/verified/' . $file_name;

        if(!file_exists($file_path)){
            $this->session->set_flashdata('error', 'File foto fisik tidak ditemukan di server.');
            redirect('admin_foto_ijazah');
            return;
        }

        $compress_1mb = (bool)$this->input->get('compress_1mb');
        $download_filename = !empty($verif->nisn) ? ($verif->nisn . '.jpg') : $file_name;

        if($compress_1mb){
            $data = $this->compress_image_max_1mb($file_path, 1048576, 786432);
            if($data !== false){
                header('Content-Type: image/jpeg');
                header('Content-Disposition: attachment; filename="' . $download_filename . '"');
                header('Content-Length: ' . strlen($data));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                echo $data;
                exit;
            }
        }

        $this->load->helper('download');
        force_download($download_filename, file_get_contents($file_path));
    }

    // Download Semua Foto yang Telah Diverifikasi dalam format ZIP (Bernama {NISN}.jpg)
    public function download_zip(){
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $kelas_id     = $this->input->get('kelas_id');
        $compress_1mb = (bool)$this->input->get('compress_1mb');
        
        $where_clause = "WHERE f.status = 'verified'";
        $params = [];
        $suffix = "Semua_Kelas";

        if(!empty($kelas_id)){
            $where_clause .= " AND sk.kelas_id = ?";
            $params[] = (int)$kelas_id;
            $k_row = $this->db->where('id', $kelas_id)->get('kelas')->row();
            if($k_row){
                $suffix = str_replace(' ', '_', $k_row->nama_kelas);
            }
        }

        $sql = "
            SELECT f.*, s.nama_lengkap, s.nisn as siswa_nisn, k.nama_kelas
            FROM foto_ijazah_verifikasi f
            JOIN siswa s ON s.id = f.siswa_id
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            JOIN kelas k ON k.id = sk.kelas_id
            $where_clause
            GROUP BY f.id
        ";

        $verified_list = $this->db->query($sql, $params)->result_array();

        if(empty($verified_list)){
            $this->session->set_flashdata('error', 'Belum ada foto terverifikasi yang siap diunduh.');
            redirect('admin_foto_ijazah');
            return;
        }

        $this->load->library('zip');
        $verified_dir = FCPATH . 'uploads/foto_ijazah/verified/';

        $added_count = 0;
        $added_files = [];
        foreach($verified_list as $row){
            $file_name = !empty($row['file_verified']) ? $row['file_verified'] : ($row['nisn'] . '.jpg');
            $file_path = $verified_dir . $file_name;

            if(isset($added_files[$file_name])) continue;

            if(file_exists($file_path)){
                $added_files[$file_name] = true;
                if($compress_1mb){
                    $compressed_content = $this->compress_image_max_1mb($file_path, 1048576);
                    if($compressed_content !== false){
                        $this->zip->add_data($file_name, $compressed_content);
                        $added_count++;
                        continue;
                    }
                }

                // Tambahkan ke zip dengan file asli
                $this->zip->read_file($file_path, $file_name);
                $added_count++;
            }
        }

        if($added_count === 0){
            $this->session->set_flashdata('error', 'File foto fisik tidak ditemukan di folder verified.');
            redirect('admin_foto_ijazah');
            return;
        }

        $prefix = $compress_1mb ? 'FOTO_IJAZAH_NISN_MAKS_1MB_' : 'FOTO_IJAZAH_NISN_ASLI_';
        $zip_filename = $prefix . $suffix . '_' . date('Ymd_His') . '.zip';
        $this->zip->download($zip_filename);
    }

    /**
     * Kompresi file foto agar pas di batas maksimal 1 MB (Target: 800 KB - 990 KB)
     * Mempertahankan dimensi asli kamera DSLR/studio dan kualitas visual setinggi mungkin
     */
    private function compress_image_max_1mb($filepath, $max_bytes = 1048576, $target_min_bytes = 786432){
        if(!file_exists($filepath)){
            return false;
        }

        $filesize = filesize($filepath);
        // Jika file asli sudah di kisaran 750 KB - 1024 KB, pakai langsung tanpa ubah
        if($filesize <= $max_bytes && $filesize >= $target_min_bytes){
            return file_get_contents($filepath);
        }

        if(!extension_loaded('gd')){
            return file_get_contents($filepath);
        }

        $image_info = @getimagesize($filepath);
        if(!$image_info){
            return file_get_contents($filepath);
        }

        $mime = $image_info['mime'];
        if($mime == 'image/jpeg' || $mime == 'image/jpg'){
            $img = @imagecreatefromjpeg($filepath);
        } elseif($mime == 'image/png'){
            $img = @imagecreatefrompng($filepath);
        } elseif($mime == 'image/webp'){
            $img = @imagecreatefromwebp($filepath);
        } else {
            return file_get_contents($filepath);
        }

        if(!$img){
            return file_get_contents($filepath);
        }

        $w = imagesx($img);
        $h = imagesy($img);

        $best_data = null;
        $best_size = 0;

        // 1. Coba pada resolusi asli kamera dengan mencari quality JPEG tinggi yang pas di bawah 1 MB
        for($q = 95; $q >= 60; $q -= 3){
            ob_start();
            imagejpeg($img, null, $q);
            $temp = ob_get_clean();
            $sz = strlen($temp);

            if($sz <= $max_bytes){
                $best_data = $temp;
                $best_size = $sz;
                // Jika sudah masuk rentang target ideal (>= 750 KB), langsung ambil!
                if($sz >= $target_min_bytes){
                    imagedestroy($img);
                    return $best_data;
                }
                break;
            }
        }

        // 2. Jika resolusi kamera luar biasa besar dan di Q60 masih > 1MB, turunkan sedikit skala secara halus
        if(!$best_data){
            for($scale = 0.9; $scale >= 0.5; $scale -= 0.1){
                $nw = intval($w * $scale);
                $nh = intval($h * $scale);
                $new_img = imagecreatetruecolor($nw, $nh);
                $white = imagecolorallocate($new_img, 255, 255, 255);
                imagefill($new_img, 0, 0, $white);
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

                for($q = 94; $q >= 70; $q -= 4){
                    ob_start();
                    imagejpeg($new_img, null, $q);
                    $temp = ob_get_clean();
                    $sz = strlen($temp);

                    if($sz <= $max_bytes){
                        $best_data = $temp;
                        imagedestroy($new_img);
                        imagedestroy($img);
                        return $best_data;
                    }
                }
                imagedestroy($new_img);
            }
        }

        imagedestroy($img);
        return $best_data ?: file_get_contents($filepath);
    }

    // Admin verifikasi foto langsung untuk siswa
    public function verifikasi_langsung(){
        $siswa_id = (int)$this->input->post('siswa_id');
        $foto_id  = (int)$this->input->post('foto_id');

        if(empty($siswa_id) || empty($foto_id)){
            $this->session->set_flashdata('error', 'Pilih siswa dan foto yang akan diverifikasi.');
            redirect('admin_foto_ijazah');
            return;
        }

        // Ambil data siswa
        $siswa = $this->db->query("
            SELECT s.*, sk.kelas_id, k.nama_kelas 
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            JOIN kelas k ON k.id = sk.kelas_id
            WHERE s.id = ?
        ", [$siswa_id])->row_array();

        if(!$siswa){
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan.');
            redirect('admin_foto_ijazah');
            return;
        }

        // Ambil foto mentah
        $foto = $this->db->where('id', $foto_id)
                         ->where('status', 'pending')
                         ->get('foto_ijazah_verifikasi')
                         ->row_array();

        if(!$foto){
            $this->session->set_flashdata('error', 'Foto mentah tidak ditemukan atau sudah terverifikasi.');
            redirect('admin_foto_ijazah');
            return;
        }

        $mentah_path = FCPATH . 'uploads/foto_ijazah/mentah/' . $foto['file_mentah'];
        if(!file_exists($mentah_path)){
            $this->session->set_flashdata('error', 'File fisik foto mentah tidak ditemukan di server.');
            redirect('admin_foto_ijazah');
            return;
        }

        // Jika siswa ini sebelumnya sudah diverifikasi foto lain, kembalikan foto lamanya ke pending
        $old_verif = $this->db->where('siswa_id', $siswa_id)
                              ->where('status', 'verified')
                              ->get('foto_ijazah_verifikasi')
                              ->row();
        if($old_verif){
            if(!empty($old_verif->file_verified)){
                @unlink(FCPATH . 'uploads/foto_ijazah/verified/' . $old_verif->file_verified);
            }
            $this->db->where('id', $old_verif->id)->update('foto_ijazah_verifikasi', [
                'siswa_id'      => NULL,
                'nisn'          => NULL,
                'nama_siswa'    => NULL,
                'file_verified' => NULL,
                'status'        => 'pending',
                'verified_at'   => NULL,
                'catatan'       => 'Diganti dengan foto baru oleh Admin'
            ]);
        }

        // Nama file resmi: {NISN}.ext (atau {NIS}.ext jika NISN kosong)
        $nisn = !empty($siswa['nisn']) ? $siswa['nisn'] : (!empty($siswa['nis']) ? $siswa['nis'] : 'SISWA_'.$siswa_id);
        $ext  = pathinfo($foto['file_mentah'], PATHINFO_EXTENSION);
        $ext  = !empty($ext) ? strtolower($ext) : 'jpg';
        $new_filename = $nisn . '.' . $ext;

        $verified_dir = FCPATH . 'uploads/foto_ijazah/verified/';
        if(!is_dir($verified_dir)){
            @mkdir($verified_dir, 0777, true);
        }
        $verified_path = $verified_dir . $new_filename;

        if(!copy($mentah_path, $verified_path)){
            $this->session->set_flashdata('error', 'Gagal menyalin file foto ke folder verified.');
            redirect('admin_foto_ijazah');
            return;
        }

        $admin_name = $this->session->userdata('nama_user') ? $this->session->userdata('nama_user') : 'Admin';

        // Update record foto_ijazah_verifikasi
        $this->db->where('id', $foto_id)->update('foto_ijazah_verifikasi', [
            'siswa_id'      => $siswa_id,
            'nisn'          => $nisn,
            'nama_siswa'    => $siswa['nama_lengkap'],
            'kelas_id'      => $siswa['kelas_id'],
            'file_verified' => $new_filename,
            'status'        => 'verified',
            'verified_at'   => date('Y-m-d H:i:s'),
            'ip_address'    => $this->input->ip_address(),
            'user_agent'    => 'Admin Panel',
            'catatan'       => 'Diverifikasi langsung oleh Admin (' . $admin_name . ')'
        ]);

        // Update tabel siswa
        $this->db->where('id', $siswa_id)->update('siswa', [
            'foto' => 'foto_ijazah/verified/' . $new_filename
        ]);

        $this->session->set_flashdata('success', 'Sukses! Foto untuk siswa <strong>' . htmlspecialchars($siswa['nama_lengkap']) . '</strong> berhasil diverifikasi langsung (File: ' . $new_filename . ').');

        if($this->input->is_ajax_request() || $this->input->post('is_ajax')){
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'        => 'success',
                'message'       => 'Sukses! Foto untuk siswa ' . $siswa['nama_lengkap'] . ' berhasil diverifikasi.',
                'siswa_id'      => $siswa_id,
                'verif_id'      => $foto_id,
                'nama_lengkap'  => $siswa['nama_lengkap'],
                'nisn'          => $nisn,
                'file_verified' => $new_filename,
                'foto_url'      => base_url('uploads/foto_ijazah/verified/' . $new_filename),
                'verified_at'   => date('d/m/Y H:i')
            ]);
            return;
        }

        redirect('admin_foto_ijazah');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // FITUR SINKRONISASI CLOUD DUA ARAH (LOKAL <-> CLOUD HOSTING)
    // ═══════════════════════════════════════════════════════════════════════

    private $cloud_url = 'https://man3banjar.sch.id/';
    private $sync_api_key = 'MAN3BANJAR_SECRET_SYNC_KEY_2026';

    // 1. AJAX: Cek status perbandingan foto mentah lokal vs hosting
    public function ajax_cek_sync_mentah(){
        header('Content-Type: application/json; charset=utf-8');

        // Scan folder lokal
        $local_folder = FCPATH . 'uploads/foto_ijazah/mentah/';
        if(!is_dir($local_folder)){
            @mkdir($local_folder, 0777, true);
        }

        $files = scandir($local_folder);
        $local_files = [];
        $valid_ext = ['jpg', 'jpeg', 'png', 'webp'];

        foreach($files as $f){
            if($f === '.' || $f === '..') continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if(in_array($ext, $valid_ext)){
                $local_files[] = $f;
            }
        }

        // Ambil daftar foto di cloud
        $ch = curl_init(rtrim($this->cloud_url, '/') . '/api/sync/get_existing_foto_mentah');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->sync_api_key
        ]);

        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menghubungi server hosting: ' . $err
            ]);
            return;
        }

        $json = json_decode($res, true);
        if(!$json || $json['status'] !== 'success'){
            echo json_encode([
                'status'  => 'error',
                'message' => 'Respon hosting tidak valid: ' . substr($res, 0, 150)
            ]);
            return;
        }

        $cloud_files = $json['files'] ?? [];
        $cloud_set = array_flip($cloud_files);

        $unsynced = [];
        foreach($local_files as $lf){
            if(!isset($cloud_set[$lf])){
                $unsynced[] = $lf;
            }
        }

        echo json_encode([
            'status'        => 'success',
            'total_local'   => count($local_files),
            'total_cloud'   => count($cloud_files),
            'unsynced_count'=> count($unsynced),
            'unsynced_files'=> $unsynced
        ]);
    }

    // 2. AJAX: Kirim 1 file foto mentah ke server hosting
    public function ajax_upload_single_mentah(){
        header('Content-Type: application/json; charset=utf-8');

        $filename = $this->input->post('filename');
        if(empty($filename)){
            echo json_encode(['status' => 'error', 'message' => 'Nama file kosong']);
            return;
        }

        $local_path = FCPATH . 'uploads/foto_ijazah/mentah/' . basename($filename);
        if(!file_exists($local_path)){
            echo json_encode(['status' => 'error', 'message' => 'File tidak ditemukan di lokal: ' . $filename]);
            return;
        }

        $cfile = new CURLFile($local_path, mime_content_type($local_path), basename($filename));

        $post_fields = [
            'file'    => $cfile,
            'api_key' => $this->sync_api_key
        ];

        $ch = curl_init(rtrim($this->cloud_url, '/') . '/api/sync/upload_foto_mentah');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->sync_api_key
        ]);

        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){
            echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . $err]);
            return;
        }

        $json = json_decode($res, true);
        if($json){
            echo json_encode($json);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Respon hosting tidak valid: ' . substr($res, 0, 100)]);
        }
    }

    // 3. AJAX: Tarik hasil verifikasi dari cloud hosting ke lokal
    public function ajax_pull_verified_cloud(){
        header('Content-Type: application/json; charset=utf-8');

        $ch = curl_init(rtrim($this->cloud_url, '/') . '/api/sync/pull_foto_verified');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->sync_api_key
        ]);

        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){
            echo json_encode(['status' => 'error', 'message' => 'Gagal koneksi: ' . $err]);
            return;
        }

        $json = json_decode($res, true);
        if(!$json || $json['status'] !== 'success'){
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data dari hosting']);
            return;
        }

        $verified_list = $json['data'] ?? [];
        $downloaded = 0;
        $updated = 0;

        $target_dir = FCPATH . 'uploads/foto_ijazah/verified/';
        if(!is_dir($target_dir)){
            @mkdir($target_dir, 0777, true);
        }

        foreach($verified_list as $row){
            $file_verified = $row['file_verified'];
            if(empty($file_verified)) continue;

            $local_file_path = $target_dir . $file_verified;

            // Unduh file gambar jika belum ada di lokal
            if(!file_exists($local_file_path) && !empty($row['download_url'])){
                $img_content = @file_get_contents($row['download_url']);
                if($img_content !== false){
                    file_put_contents($local_file_path, $img_content);
                    $downloaded++;
                }
            }

            // Update database lokal
            $cek = $this->db->where('file_mentah', $row['file_mentah'])->get('foto_ijazah_verifikasi')->row();
            if($cek){
                $this->db->where('id', $cek->id)->update('foto_ijazah_verifikasi', [
                    'siswa_id'      => $row['siswa_id'],
                    'nisn'          => $row['nisn'],
                    'nama_siswa'    => $row['nama_siswa'],
                    'kelas_id'      => $row['kelas_id'],
                    'file_verified' => $row['file_verified'],
                    'status'        => 'verified',
                    'verified_at'   => $row['verified_at'],
                    'catatan'       => $row['catatan']
                ]);
            } else {
                $this->db->insert('foto_ijazah_verifikasi', [
                    'siswa_id'      => $row['siswa_id'],
                    'nisn'          => $row['nisn'],
                    'nama_siswa'    => $row['nama_siswa'],
                    'kelas_id'      => $row['kelas_id'],
                    'file_mentah'   => $row['file_mentah'],
                    'file_verified' => $row['file_verified'],
                    'status'        => 'verified',
                    'verified_at'   => $row['verified_at'],
                    'catatan'       => $row['catatan'],
                    'created_at'    => $row['created_at'] ?? date('Y-m-d H:i:s')
                ]);
            }

            // Update foto siswa di tabel siswa jika cocok
            if(!empty($row['siswa_id'])){
                $this->db->where('id', $row['siswa_id'])->update('siswa', [
                    'foto' => 'foto_ijazah/verified/' . $file_verified
                ]);
            }

            $updated++;
        }

        echo json_encode([
            'status'     => 'success',
            'message'    => "Sinkronisasi selesai! $updated data verifikasi tersinkron, $downloaded file foto baru berhasil diunduh.",
            'updated'    => $updated,
            'downloaded' => $downloaded
        ]);
    }

    // 4. AJAX: Kirim hasil verifikasi dari lokal ke cloud hosting
    public function ajax_push_verified_cloud(){
        header('Content-Type: application/json; charset=utf-8');

        if(!$this->db->table_exists('foto_ijazah_verifikasi')){
            echo json_encode(['status' => 'error', 'message' => 'Tabel foto_ijazah_verifikasi belum ada.']);
            return;
        }

        $verified_list = $this->db->where('status', 'verified')->get('foto_ijazah_verifikasi')->result_array();
        if(empty($verified_list)){
            echo json_encode(['status' => 'info', 'message' => 'Belum ada siswa yang terverifikasi di server lokal.']);
            return;
        }

        $target_url = rtrim($this->cloud_url, '/') . '/api/sync/push_foto_verified';
        $verified_dir = FCPATH . 'uploads/foto_ijazah/verified/';
        $success_count = 0;
        $failed_count = 0;

        foreach($verified_list as $row){
            $file_verified = $row['file_verified'];
            $file_path = $verified_dir . $file_verified;

            $post_fields = [
                'api_key'       => $this->sync_api_key,
                'nisn'          => $row['nisn'],
                'nama_siswa'    => $row['nama_siswa'],
                'kelas_id'      => $row['kelas_id'],
                'file_mentah'   => $row['file_mentah'],
                'file_verified' => $file_verified,
                'verified_at'   => $row['verified_at'],
                'catatan'       => $row['catatan']
            ];

            if(!empty($file_verified) && file_exists($file_path)){
                $post_fields['file'] = new CURLFile($file_path, mime_content_type($file_path), $file_verified);
            }

            $ch = curl_init($target_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-API-KEY: ' . $this->sync_api_key
            ]);

            $res = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if(!$err){
                $json = json_decode($res, true);
                if($json && $json['status'] === 'success'){
                    $success_count++;
                    continue;
                }
            }
            $failed_count++;
        }

        echo json_encode([
            'status'  => 'success',
            'message' => "Berhasil menyinkronkan {$success_count} data verifikasi siswa ke website hosting." . ($failed_count > 0 ? " ({$failed_count} gagal)" : ""),
            'synced'  => $success_count,
            'total'   => count($verified_list)
        ]);
    }
}
