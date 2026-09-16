<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_struktur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->helper('access');
        $allowed = ['admin', 'admin_master', 'admin_website', 'admin_humas', 'wakil_humas', 'operator_humas'];
        if (!in_array($this->session->userdata('role'), $allowed)) {
            show_error('Akses ditolak.', 403);
        }
    }

    public function index($slug = 'kepala-madrasah') {
        $map = [
            'kepala-madrasah' => 'Kepala Madrasah',
            'wakamad' => 'Wakamad',
            'guru' => 'Guru',
            'wali-kelas' => 'Wali Kelas',
            'koordinator-eskul' => 'Koordinator Eskul',
            'tata-usaha' => 'Tata Usaha'
        ];

        if(!array_key_exists($slug, $map)){
            $slug = 'kepala-madrasah';
        }

        $kategori = $map[$slug];
        $kategori_options = $map;

        $this->db->select('struktur_organisasi.*, ptk.nama_lengkap, ptk.nuptk, ptk.nip, ptk.jabatan as ptk_jabatan');
        $this->db->from('struktur_organisasi');
        $this->db->join('ptk', 'ptk.id = struktur_organisasi.ptk_id');
        $this->db->where('struktur_organisasi.kategori', $kategori);
        $this->db->order_by('struktur_organisasi.urutan', 'ASC');
        $this->db->order_by('struktur_organisasi.id', 'ASC');
        $data['struktur'] = $this->db->get()->result();

        $data['kategori'] = $kategori;
        $data['kategori_slug'] = $slug;
        $data['kategori_options'] = $kategori_options;

        // Ambil list semua PTK untuk dropdown tambah
        $this->db->select('id, nama_lengkap, nip');
        $this->db->order_by('nama_lengkap', 'ASC');
        $data['list_ptk'] = $this->db->get('ptk')->result();

        $this->load->view('admin/struktur_organisasi', $data);
    }

    public function add() {
        $kategori = $this->input->post('kategori');
        $ptk_id = $this->input->post('ptk_id');
        $jabatan = $this->input->post('jabatan');
        $urutan = (int)$this->input->post('urutan');
        $kategori_slug = $this->input->post('kategori_slug');

        $kategori_slug = $this->input->post('kategori_slug');

        if(empty($ptk_id)){
            $this->session->set_flashdata('error', 'Silakan pilih PTK.');
            redirect('admin_struktur/index/'.$kategori_slug);
        }

        $this->db->insert('struktur_organisasi', [
            'kategori' => $kategori,
            'ptk_id' => $ptk_id,
            'jabatan' => $jabatan,
            'urutan' => $urutan,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Anggota struktur berhasil ditambahkan.');
        redirect('admin_struktur/index/'.$kategori_slug);
    }

    public function sync($slug) {
        $map = [
            'kepala-madrasah' => 'Kepala Madrasah',
            'wakamad' => 'Wakamad',
            'guru' => 'Guru',
            'wali-kelas' => 'Wali Kelas',
            'koordinator-eskul' => 'Koordinator Eskul',
            'tata-usaha' => 'Tata Usaha'
        ];

        if(!array_key_exists($slug, $map)){
            redirect('admin_struktur');
        }

        $kategori = $map[$slug];
        
        // Ambil PTK ID yang sudah ada di kategori ini untuk mencegah duplikat
        $existing = $this->db->where('kategori', $kategori)->get('struktur_organisasi')->result_array();
        $existing_ids = array_column($existing, 'ptk_id');

        $this->db->select('id, jabatan, nama_lengkap');
        
        if($slug == 'guru') {
            $this->db->where("jabatan LIKE '%Guru%'");
        } else if($slug == 'tata-usaha') {
            $this->db->where("(jabatan LIKE '%Tata Usaha%' OR jabatan LIKE '%Administrasi%')");
        } else if($slug == 'kepala-madrasah') {
            $this->db->where("jabatan LIKE '%Kepala%'");
        } else if($slug == 'wakamad') {
            $this->db->where("(jabatan LIKE '%Wakil%' OR jabatan LIKE '%Wakamad%')");
        } else if($slug == 'wali-kelas') {
            $this->db->where("jabatan LIKE '%Wali%'");
        } else if($slug == 'koordinator-eskul') {
            $this->db->where("(jabatan LIKE '%Koordinator%' OR jabatan LIKE '%Eskul%' OR jabatan LIKE '%Ekstrakurikuler%')");
        } else {
            $this->db->where("1=0"); // fallback empty
        }

        $ptk_matches = $this->db->get('ptk')->result();
        $inserted = 0;

        foreach($ptk_matches as $p) {
            if(!in_array($p->id, $existing_ids)) {
                $this->db->insert('struktur_organisasi', [
                    'kategori' => $kategori,
                    'ptk_id' => $p->id,
                    'jabatan' => $kategori == 'Guru' ? 'Guru' : ($kategori == 'Tata Usaha' ? 'Staf Tata Usaha' : $kategori),
                    'urutan' => 99,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $inserted++;
            }
        }

        if($inserted > 0) {
            $this->session->set_flashdata('success', "Berhasil menarik $inserted data PTK secara otomatis ke dalam kategori ini.");
        } else {
            $this->session->set_flashdata('info', 'Tidak ada data PTK baru yang cocok atau semua sudah ditambahkan.');
        }

        redirect('admin_struktur/index/'.$slug);
    }

    public function update() {
        $id = $this->input->post('id');
        $kategori = $this->input->post('kategori');
        $ptk_id = $this->input->post('ptk_id');
        $jabatan = $this->input->post('jabatan');
        $urutan = (int)$this->input->post('urutan');

        $this->db->where('id', $id);
        $this->db->update('struktur_organisasi', [
            'ptk_id' => $ptk_id,
            'jabatan' => $jabatan,
            'urutan' => $urutan
        ]);

        $this->session->set_flashdata('success', 'Data struktur berhasil diperbarui.');
        redirect('admin_struktur/index/'.$this->input->post('kategori_slug'));
    }

    public function delete($id, $slug) {
        $row = $this->db->where('id', $id)->get('struktur_organisasi')->row();
        if($row){
            $kategori = $row->kategori;
            $this->db->where('id', $id)->delete('struktur_organisasi');
            $this->session->set_flashdata('success', 'Data berhasil dihapus dari struktur.');
            redirect('admin_struktur/index/'.$slug);
        } else {
            show_404();
        }
    }
}
