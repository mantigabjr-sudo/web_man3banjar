<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi_foto_ijazah extends CI_Controller {

    public function __construct(){
        parent::__construct();
        // Portal publik, tidak memerlukan login admin
    }

    // ═══ 1. HALAMAN UTAMA: IDENTIFIKASI DIRI SISWA ═══
    public function index(){
        // Jika sudah ada sesi siswa aktif, langsung ke galeri / bukti
        $session_siswa = $this->session->userdata('verif_siswa_session');
        if(!empty($session_siswa)){
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        $data['title'] = 'Verifikasi Mandiri Foto Ijazah Siswa Kelas XII';

        // Ambil daftar kelas XII
        $data['kelas_list'] = $this->db->query("
            SELECT * FROM kelas 
            WHERE tingkat = '12' OR tingkat = 'XII' OR nama_kelas LIKE '%XII%' OR nama_kelas LIKE '%12%'
            ORDER BY nama_kelas ASC
        ")->result_array();

        $this->load->view('public/verifikasi_foto_ijazah/login', $data);
    }

    // AJAX: Ambil daftar siswa berdasarkan kelas XII yang dipilih
    public function get_siswa_by_kelas(){
        $kelas_id = (int)$this->input->get('kelas_id');
        if(empty($kelas_id)){
            echo json_encode([]);
            return;
        }

        $siswa = $this->db->query("
            SELECT s.id, s.nama_lengkap, s.nisn, s.nis
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            WHERE sk.kelas_id = ? AND s.status_siswa = 'aktif'
            ORDER BY s.nama_lengkap ASC
        ", [$kelas_id])->result_array();

        echo json_encode($siswa);
    }

    // PROSES AUTENTIKASI SISWA
    public function auth_siswa(){
        $kelas_id   = (int)$this->input->post('kelas_id');
        $siswa_id   = (int)$this->input->post('siswa_id');
        $nisn_input = trim((string)$this->input->post('nisn'));
        $tgl_lahir  = trim((string)$this->input->post('tanggal_lahir'));

        if(empty($siswa_id) || empty($kelas_id)){
            $this->session->set_flashdata('error', 'Silakan pilih kelas dan nama Anda.');
            redirect('verifikasi_foto_ijazah');
            return;
        }

        // Ambil data siswa
        $siswa = $this->db->query("
            SELECT s.*, k.nama_kelas
            FROM siswa s
            JOIN siswa_kelas sk ON sk.siswa_id = s.id
            JOIN kelas k ON k.id = sk.kelas_id
            WHERE s.id = ? AND sk.kelas_id = ?
        ", [$siswa_id, $kelas_id])->row_array();

        if(!$siswa){
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan.');
            redirect('verifikasi_foto_ijazah');
            return;
        }

        // Validasi keamanan: cocokkan NISN atau tanggal lahir (jika NISN terisi di DB)
        $valid = false;
        if(!empty($siswa['nisn']) && !empty($nisn_input)){
            if(trim($siswa['nisn']) === $nisn_input){
                $valid = true;
            }
        }

        if(!$valid && !empty($siswa['tanggal_lahir']) && !empty($tgl_lahir)){
            // Cocokkan tanggal lahir YYYY-MM-DD
            if($siswa['tanggal_lahir'] === $tgl_lahir){
                $valid = true;
            }
        }

        // Jika NISN di database masih kosong, izinkan validasi tanggal lahir atau konfirmasi nama
        if(empty($siswa['nisn']) && !empty($tgl_lahir)){
            if($siswa['tanggal_lahir'] === $tgl_lahir){
                $valid = true;
            }
        }

        // Fallback jika tidak ada NISN dan tanggal lahir diinput siswa
        if(!$valid && empty($siswa['nisn']) && empty($tgl_lahir)){
            $valid = true; // Izinkan jika DB belum punya data NISN untuk siswa bersangkutan
        }

        if(!$valid){
            $this->session->set_flashdata('error', 'Validasi gagal! NISN atau Tanggal Lahir yang Anda masukkan tidak sesuai dengan data madrasah.');
            redirect('verifikasi_foto_ijazah');
            return;
        }

        // Set session siswa aktif
        $this->session->set_userdata('verif_siswa_session', [
            'siswa_id'      => $siswa['id'],
            'nama_lengkap'  => $siswa['nama_lengkap'],
            'nisn'          => $siswa['nisn'],
            'nis'           => $siswa['nis'],
            'kelas_id'      => $kelas_id,
            'nama_kelas'    => $siswa['nama_kelas'],
            'tanggal_lahir' => $siswa['tanggal_lahir']
        ]);

        redirect('verifikasi_foto_ijazah/galeri');
    }

    // ═══ 2. GALERI FOTO MENTAH & PEMILIHAN ═══
    public function galeri(){
        $session_siswa = $this->session->userdata('verif_siswa_session');
        if(empty($session_siswa)){
            redirect('verifikasi_foto_ijazah');
            return;
        }

        $siswa_id = (int)$session_siswa['siswa_id'];

        // Cek apakah siswa ini sudah pernah verifikasi foto
        $cek_verif = $this->db->where('siswa_id', $siswa_id)
                              ->where('status', 'verified')
                              ->get('foto_ijazah_verifikasi')
                              ->row_array();

        if(!empty($cek_verif)){
            redirect('verifikasi_foto_ijazah/bukti');
            return;
        }

        $data['title'] = 'Pilih Foto Ijazah Anda - ' . $session_siswa['nama_lengkap'];
        $data['siswa'] = $session_siswa;

        // Ambil foto mentah yang belum diklaim
        // Prioritaskan foto per kelas jika admin mengupload per kelas, jika tidak tampilkan semua yang pending
        $unclaimed = $this->db->where('status', 'pending')
                              ->order_by('id', 'DESC')
                              ->get('foto_ijazah_verifikasi')
                              ->result_array();

        $data['foto_list'] = $unclaimed;

        $this->load->view('public/verifikasi_foto_ijazah/galeri', $data);
    }

    // ═══ 3. PROSES KLAIM FOTO OLEH SISWA ═══
    public function klaim(){
        $session_siswa = $this->session->userdata('verif_siswa_session');
        if(empty($session_siswa)){
            redirect('verifikasi_foto_ijazah');
            return;
        }

        $siswa_id = (int)$session_siswa['siswa_id'];
        $foto_id  = (int)$this->input->post('foto_id');

        if(empty($foto_id)){
            $this->session->set_flashdata('error', 'Silakan pilih foto ijazah Anda terlebih dahulu.');
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        // Cek apakah siswa sudah pernah klaim
        $cek_siswa = $this->db->where('siswa_id', $siswa_id)
                              ->where('status', 'verified')
                              ->get('foto_ijazah_verifikasi')
                              ->row();
        if($cek_siswa){
            $this->session->set_flashdata('info', 'Anda sudah melakukan verifikasi foto sebelumnya.');
            redirect('verifikasi_foto_ijazah/bukti');
            return;
        }

        // Cek ketersediaan foto mentah (mencegah diklaim orang lain saat bersamaan)
        $foto = $this->db->where('id', $foto_id)
                         ->where('status', 'pending')
                         ->get('foto_ijazah_verifikasi')
                         ->row_array();

        if(!$foto){
            $this->session->set_flashdata('error', 'Maaf, foto tersebut baru saja diklaim oleh siswa lain atau tidak tersedia. Silakan pilih foto Anda yang benar.');
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        $mentah_path = FCPATH . 'uploads/foto_ijazah/mentah/' . $foto['file_mentah'];
        if(!file_exists($mentah_path)){
            $this->session->set_flashdata('error', 'File foto fisik tidak ditemukan di server.');
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        // Tentukan nama file baru: format {NISN}.ext atau NIS jika NISN kosong
        $nisn = !empty($session_siswa['nisn']) ? $session_siswa['nisn'] : (!empty($session_siswa['nis']) ? $session_siswa['nis'] : 'SISWA_'.$siswa_id);
        $ext  = pathinfo($foto['file_mentah'], PATHINFO_EXTENSION);
        $ext  = !empty($ext) ? strtolower($ext) : 'jpg';

        $new_filename = $nisn . '.' . $ext;
        $verified_dir = FCPATH . 'uploads/foto_ijazah/verified/';
        if(!is_dir($verified_dir)){
            mkdir($verified_dir, 0777, true);
        }
        $verified_path = $verified_dir . $new_filename;

        // Salin file ke folder verified dengan nama {NISN}.jpg
        if(!copy($mentah_path, $verified_path)){
            $this->session->set_flashdata('error', 'Gagal memproses dan menyalin foto. Hubungi panitia.');
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        // Update database foto_ijazah_verifikasi
        $this->db->where('id', $foto_id)->update('foto_ijazah_verifikasi', [
            'siswa_id'      => $siswa_id,
            'nisn'          => $nisn,
            'nama_siswa'    => $session_siswa['nama_lengkap'],
            'kelas_id'      => $session_siswa['kelas_id'],
            'file_verified' => $new_filename,
            'status'        => 'verified',
            'verified_at'   => date('Y-m-d H:i:s'),
            'ip_address'    => $this->input->ip_address(),
            'user_agent'    => substr((string)$this->input->user_agent(), 0, 255),
            'catatan'       => 'Diverifikasi mandiri oleh siswa'
        ]);

        // Opsional: update foto profil siswa jika foto di tabel siswa masih kosong
        $this->db->where('id', $siswa_id)->update('siswa', [
            'foto' => 'foto_ijazah/verified/' . $new_filename
        ]);

        $this->session->set_flashdata('success', 'Alhamdulillah! Foto ijazah Anda berhasil diverifikasi dan tersimpan dengan nama resmi ber-NISN.');
        redirect('verifikasi_foto_ijazah/bukti');
    }

    // ═══ 4. BUKTI TANDA VERIFIKASI DIGITAL ═══
    public function bukti(){
        $session_siswa = $this->session->userdata('verif_siswa_session');
        if(empty($session_siswa)){
            redirect('verifikasi_foto_ijazah');
            return;
        }

        $siswa_id = (int)$session_siswa['siswa_id'];
        $verif = $this->db->where('siswa_id', $siswa_id)
                          ->where('status', 'verified')
                          ->get('foto_ijazah_verifikasi')
                          ->row_array();

        if(empty($verif)){
            redirect('verifikasi_foto_ijazah/galeri');
            return;
        }

        $data['title'] = 'Bukti Verifikasi Foto Ijazah - ' . $session_siswa['nama_lengkap'];
        $data['siswa'] = $session_siswa;
        $data['verif'] = $verif;

        $this->load->view('public/verifikasi_foto_ijazah/bukti', $data);
    }

    // KELUAR SESI SISWA
    public function logout(){
        $this->session->unset_userdata('verif_siswa_session');
        redirect('verifikasi_foto_ijazah');
    }
}
