<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $role = $this->session->userdata('role');
        if($role == 'admin_pmb' || $role == 'admin_ppdb'){
            redirect('admin_ppdb/dashboard');
        }
    }

    public function index(){
        $this->load->helper('access');
        $role = $this->session->userdata('role');

        $data['title'] = 'Dashboard Admin Website';
        $data['userInitial'] = strtoupper(substr((string)$this->session->userdata('username'), 0, 1));
        $data['username'] = $this->session->userdata('username');
        $data['roleText'] = role_label($role);
        $data['role'] = $role;

        // Stats Foto Ijazah
        $data['total_verified_foto'] = $this->db->table_exists('foto_ijazah_verifikasi') 
            ? $this->db->where('status', 'verified')->count_all_results('foto_ijazah_verifikasi') 
            : 0;
        $data['total_pending_foto'] = $this->db->table_exists('foto_ijazah_verifikasi') 
            ? $this->db->where('status', 'pending')->count_all_results('foto_ijazah_verifikasi') 
            : 0;

        // Stats Berita
        $data['total_berita'] = $this->db->table_exists('berita') 
            ? $this->db->count_all_results('berita') 
            : 0;
        $data['total_berita_pub'] = $this->db->table_exists('berita') 
            ? $this->db->where('status_berita', 'Published')->count_all_results('berita') 
            : 0;

        // Stats Banner Slider
        $data['total_banner'] = $this->db->table_exists('website_banner') 
            ? $this->db->count_all_results('website_banner') 
            : 0;

        // Stats Pamflet & Galeri
        $data['total_pamflet'] = $this->db->table_exists('website_pamflet') 
            ? $this->db->count_all_results('website_pamflet') 
            : 0;
        $data['total_galeri'] = $this->db->table_exists('website_galeri') 
            ? $this->db->count_all_results('website_galeri') 
            : 0;
        $data['total_video'] = $this->db->table_exists('website_video') 
            ? $this->db->count_all_results('website_video') 
            : 0;

        // Stats PPDB (khusus Super Admin)
        $data['total_ppdb'] = $this->db->table_exists('ppdb') 
            ? $this->db->count_all_results('ppdb') 
            : ($this->db->table_exists('ppdb_pendaftar') ? $this->db->count_all_results('ppdb_pendaftar') : 0);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
