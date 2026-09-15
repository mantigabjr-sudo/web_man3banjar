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

    // Bantuan Reset Darurat Akun Admin
    public function reset_admin(){
        $key = $this->input->get('key');
        if($key === 'man3banjar2026'){
            // 1. Reset / Buat akun admin
            $cek_admin = $this->db->where('username', 'admin')->get('users')->row();
            if($cek_admin){
                $this->db->where('username', 'admin')->update('users', [
                    'password' => md5('admin123')
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'admin',
                    'password' => md5('admin123'),
                    'nama_lengkap' => 'Administrator MAN 3 Banjar',
                    'role' => 'admin'
                ]);
            }

            // 2. Reset / Buat akun kamad
            $cek_kamad = $this->db->where('username', 'kamad')->get('users')->row();
            if($cek_kamad){
                $this->db->where('username', 'kamad')->update('users', [
                    'password' => md5('kamad')
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'kamad',
                    'password' => md5('kamad'),
                    'nama_lengkap' => 'Kepala Madrasah',
                    'role' => 'admin_master'
                ]);
            }

            // 3. Reset / Buat akun kesiswaan
            $cek_kesiswaan = $this->db->where('username', 'kesiswaan')->get('users')->row();
            if($cek_kesiswaan){
                $this->db->where('username', 'kesiswaan')->update('users', [
                    'password' => md5('kesiswaan')
                ]);
            } else {
                $this->db->insert('users', [
                    'username' => 'kesiswaan',
                    'password' => md5('kesiswaan'),
                    'nama_lengkap' => 'Wakamad Kesiswaan',
                    'role' => 'admin_kesiswaan'
                ]);
            }

            echo "<div style='font-family:sans-serif; max-width:500px; margin:50px auto; padding:24px; border-radius:16px; border:1px solid #bbf7d0; background:#f0fdf4;'>";
            echo "<h3 style='color:#15803d; margin-top:0;'>Akun Admin Berhasil Direset!</h3>";
            echo "<p>Kredensial login yang siap digunakan:</p>";
            echo "<ul>";
            echo "<li>Username: <strong>admin</strong> | Password: <strong>admin123</strong></li>";
            echo "<li>Username: <strong>kamad</strong> | Password: <strong>kamad</strong></li>";
            echo "<li>Username: <strong>kesiswaan</strong> | Password: <strong>kesiswaan</strong></li>";
            echo "</ul>";
            echo "<a href='" . base_url('auth') . "' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#10b981; color:#fff; text-decoration:none; border-radius:8px; font-weight:bold;'>&larr; Klik untuk Login Sekarang</a>";
            echo "</div>";
            return;
        }
        show_404();
    }
}