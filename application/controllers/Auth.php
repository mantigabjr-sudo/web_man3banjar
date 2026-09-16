<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
    }

    public function index(){
        $this->load->view('auth/login');
    }

    public function login(){
        $username = $this->input->post('username');
        $raw_password = $this->input->post('password');
        $password = md5($raw_password);

        $user = $this->Auth_model->login($username, $password, $raw_password);

        if($user){
            $this->session->set_userdata([
				'logged_in' => true,
				'user_id' => $user->id,
				'username' => $user->username,
				'role' => $user->role,
				'ptk_id' => isset($user->ptk_id) ? $user->ptk_id : null,
                'siswa_id' => isset($user->siswa_id) ? $user->siswa_id : null
			]);

           $this->load->helper('access');

			if($user->role == 'admin_pmb' || $user->role == 'admin_ppdb'){
				redirect('admin_ppdb/dashboard');
			} elseif(in_array($user->role, ['admin_website', 'admin_humas', 'operator_humas', 'wakil_humas'])){
				redirect('dashboard');
			} elseif(is_admin_role_value($user->role)){
				redirect('dashboard');
			} elseif($user->role == 'siswa') {
                redirect('siswa_dashboard');
            } else {
				redirect('user_dashboard');
			}
        } else {
            $this->session->set_flashdata('error','Username atau Password salah');
            redirect('auth');
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('auth');
    }

    // Bantuan Reset Darurat & Setup Akun Admin
    public function reset_admin(){
        $key = $this->input->get('key');
        if($key === 'man3banjar2026'){
            // 1. Reset / Buat akun Super Admin
            $cek_admin = $this->db->where('username', 'admin')->get('users')->row();
            if($cek_admin){
                $this->db->where('username', 'admin')->update('users', [
                    'password' => md5('admin123'),
                    'role' => 'admin'
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'admin',
                    'password' => md5('admin123'),
                    'nama_lengkap' => 'Super Administrator MAN 3 Banjar',
                    'role' => 'admin'
                ]);
            }

            // 2. Reset / Buat akun Admin Website & Humas
            $cek_web = $this->db->where('username', 'admin_web')->get('users')->row();
            if($cek_web){
                $this->db->where('username', 'admin_web')->update('users', [
                    'password' => md5('web123'),
                    'role' => 'admin_website'
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'admin_web',
                    'password' => md5('web123'),
                    'nama_lengkap' => 'Admin Website & Humas',
                    'role' => 'admin_website'
                ]);
            }

            // Alias akun humas
            $cek_humas = $this->db->where('username', 'humas')->get('users')->row();
            if($cek_humas){
                $this->db->where('username', 'humas')->update('users', [
                    'password' => md5('humas123'),
                    'role' => 'admin_website'
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'humas',
                    'password' => md5('humas123'),
                    'nama_lengkap' => 'Admin Website & Humas',
                    'role' => 'admin_website'
                ]);
            }

            // 3. Reset / Buat akun Admin PMB / PPDB
            $cek_pmb = $this->db->where('username', 'admin_pmb')->get('users')->row();
            if($cek_pmb){
                $this->db->where('username', 'admin_pmb')->update('users', [
                    'password' => md5('pmb123'),
                    'role' => 'admin_pmb'
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'admin_pmb',
                    'password' => md5('pmb123'),
                    'nama_lengkap' => 'Panitia PMB MAN 3 Banjar',
                    'role' => 'admin_pmb'
                ]);
            }

            // Alias akun pmb
            $cek_pmb_alias = $this->db->where('username', 'pmb')->get('users')->row();
            if($cek_pmb_alias){
                $this->db->where('username', 'pmb')->update('users', [
                    'password' => md5('pmb123'),
                    'role' => 'admin_pmb'
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'pmb',
                    'password' => md5('pmb123'),
                    'nama_lengkap' => 'Panitia PMB MAN 3 Banjar',
                    'role' => 'admin_pmb'
                ]);
            }

            echo "<div style='font-family:sans-serif; max-width:580px; margin:40px auto; padding:28px; border-radius:20px; border:1px solid #bbf7d0; background:#ffffff; box-shadow:0 14px 35px rgba(0,0,0,0.06);'>";
            echo "<h3 style='color:#15803d; margin-top:0; font-size:22px;'>Akun Admin Berhasil Disiapkan!</h3>";
            echo "<p style='color:#64748b; font-size:14px;'>Sistem telah membuat/memperbarui 2 portal admin terpisah beserta Super Admin:</p>";
            
            echo "<div style='margin-bottom:16px; padding:14px; border-radius:12px; background:#f0fdf4; border:1px solid #dcfce7;'>";
            echo "<h4 style='margin:0 0 6px 0; color:#166534;'>1. Admin Website & Humas</h4>";
            echo "<div style='font-size:13.5px; color:#334155;'>";
            echo "Username: <strong>admin_web</strong> (atau <strong>humas</strong>)<br>";
            echo "Password: <strong>web123</strong> (atau <strong>humas123</strong>)<br>";
            echo "Hak Akses: <em>Berita, Banner, Profil, Video, Galeri, Pamflet, Foto Ijazah</em>";
            echo "</div></div>";

            echo "<div style='margin-bottom:16px; padding:14px; border-radius:12px; background:#eff6ff; border:1px solid #dbeafe;'>";
            echo "<h4 style='margin:0 0 6px 0; color:#1e40af;'>2. Admin PMB / PPDB</h4>";
            echo "<div style='font-size:13.5px; color:#334155;'>";
            echo "Username: <strong>admin_pmb</strong> (atau <strong>pmb</strong>)<br>";
            echo "Password: <strong>pmb123</strong><br>";
            echo "Hak Akses: <em>Calon Siswa, Verifikasi Berkas, Siswa Diterima/Ditolak, Pengaturan PMB</em>";
            echo "</div></div>";

            echo "<div style='margin-bottom:20px; padding:14px; border-radius:12px; background:#f8fafc; border:1px solid #e2e8f0;'>";
            echo "<h4 style='margin:0 0 6px 0; color:#334155;'>3. Super Admin (Akses Keduanya)</h4>";
            echo "<div style='font-size:13.5px; color:#334155;'>";
            echo "Username: <strong>admin</strong> | Password: <strong>admin123</strong>";
            echo "</div></div>";

            echo "<a href='" . base_url('auth') . "' style='display:inline-block; width:100%; text-align:center; padding:12px 20px; background:linear-gradient(135deg,#15803d,#22c55e); color:#fff; text-decoration:none; border-radius:12px; font-weight:bold; box-sizing:border-box;'>&larr; Buka Halaman Login Sekarang</a>";
            echo "</div>";
            return;
        }
        show_404();
    }
}