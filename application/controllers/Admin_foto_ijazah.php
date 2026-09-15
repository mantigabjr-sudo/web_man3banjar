<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_foto_ijazah extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
        $allowed = ['admin', 'admin_master', 'admin_kesiswaan', 'admin_kurikulum'];
        if(!in_array($this->session->userdata('role'), $allowed)){
            redirect('dashboard');
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

        // Query Daftar Siswa Kelas XII & Status Fotonya
        $where_clause = "WHERE sk.kelas_id IN ($kelas_in) AND s.status_siswa = 'aktif'";
        $params = [];

        if(!empty($kelas_id)){
            $where_clause .= " AND k.id = ?";
            $params[] = $kelas_id;
        }

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

        $siswa_result = $this->db->query($sql_siswa, $params)->result_array();

        if(!empty($status_filter)){
            if($status_filter == 'verified'){
                $siswa_result = array_filter($siswa_result, function($row){
                    return !empty($row['verif_id']);
                });
            } else if($status_filter == 'unverified'){
                $siswa_result = array_filter($siswa_result, function($row){
                    return empty($row['verif_id']);
                });
            }
        }

        $data['siswa_list'] = $siswa_result;
        $data['selected_kelas'] = $kelas_id;
        $data['selected_status'] = $status_filter;

        // Daftar foto mentah yang belum diklaim
        $data['unclaimed_photos'] = $this->db->where('status', 'pending')
            ->order_by('id', 'DESC')
            ->get('foto_ijazah_verifikasi')
            ->result_array();

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

    // Download Semua Foto yang Telah Diverifikasi dalam format ZIP (Bernama {NISN}.jpg)
    public function download_zip(){
        $kelas_id = $this->input->get('kelas_id');
        
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
        foreach($verified_list as $row){
            $file_name = !empty($row['file_verified']) ? $row['file_verified'] : ($row['nisn'] . '.jpg');
            $file_path = $verified_dir . $file_name;

            if(file_exists($file_path)){
                // Tambahkan ke zip dengan nama {NISN}.jpg
                $this->zip->read_file($file_path, $file_name);
                $added_count++;
            }
        }

        if($added_count === 0){
            $this->session->set_flashdata('error', 'File foto fisik tidak ditemukan di folder verified.');
            redirect('admin_foto_ijazah');
            return;
        }

        $zip_filename = 'FOTO_IJAZAH_NISN_' . $suffix . '_' . date('Ymd_His') . '.zip';
        $this->zip->download($zip_filename);
    }
}
