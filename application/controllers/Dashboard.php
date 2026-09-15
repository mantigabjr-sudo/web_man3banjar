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
    }

    public function index(){
        $data['title'] = 'Dashboard Admin Madrasah';
        $data['userInitial'] = strtoupper(substr((string)$this->session->userdata('username'), 0, 1));
        $data['username'] = $this->session->userdata('username');
        $data['roleText'] = strtoupper(str_replace('_', ' ', (string)$this->session->userdata('role')));

        // Stats Foto Ijazah
        $data['total_verified_foto'] = $this->db->table_exists('foto_ijazah_verifikasi') 
            ? $this->db->where('status', 'verified')->count_all_results('foto_ijazah_verifikasi') 
            : 0;
        $data['total_pending_foto'] = $this->db->table_exists('foto_ijazah_verifikasi') 
            ? $this->db->where('status', 'pending')->count_all_results('foto_ijazah_verifikasi') 
            : 0;

        // Stats PPDB
        $data['total_ppdb'] = $this->db->table_exists('ppdb_pendaftar') 
            ? $this->db->count_all_results('ppdb_pendaftar') 
            : 0;

        // Stats Berita
        $data['total_berita'] = $this->db->table_exists('berita') 
            ? $this->db->count_all_results('berita') 
            : 0;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
