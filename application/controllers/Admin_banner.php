<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_banner extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form','access']);

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        if(!can_admin_menu('website')){
            show_error('Anda tidak memiliki akses kelola banner website.', 403);
        }
    }

    private function ensureTable(){
        if(!$this->db->table_exists('website_banner')){
            $this->session->set_flashdata('error', 'Tabel website_banner belum tersedia. Jalankan SQL migrasi banner slider terlebih dahulu.');
            return false;
        }

        return true;
    }

    private function uploadBanner($field = 'gambar'){
        if(empty($_FILES[$field]['name'])){
            return '';
        }

        $upload_path = './assets/banner/';

        if(!is_dir($upload_path)){
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 8192;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($field)){
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            return '';
        }

        $upload = $this->upload->data();
        return $upload['file_name'];
    }

    private function deleteFileIfUnused($filename){
        if(empty($filename) || !$this->db->table_exists('website_banner')){
            return;
        }

        $filename = basename($filename);
        $used = $this->db->where('gambar', $filename)->count_all_results('website_banner');

        if($used > 0){
            return;
        }

        $file = FCPATH.'assets/banner/'.$filename;

        if(file_exists($file) && is_file($file)){
            unlink($file);
        }
    }

    public function index(){
        if(!$this->ensureTable()){
            $data['banner'] = [];
            $this->load->view('admin_banner/index', $data);
            return;
        }

        $data['banner'] = $this->db
            ->order_by('urutan', 'ASC')
            ->order_by('id', 'DESC')
            ->get('website_banner')
            ->result();

        $this->load->view('admin_banner/index', $data);
    }

    public function save(){
        if(!$this->ensureTable()){
            redirect('admin_banner');
        }

        $judul = trim($this->input->post('judul'));
        $gambar = $this->uploadBanner('gambar');

        if(empty($judul)){
            $this->session->set_flashdata('error', 'Judul banner wajib diisi.');
            redirect('admin_banner');
        }

        if(empty($gambar)){
            $this->session->set_flashdata('error', 'Gambar banner wajib diupload.');
            redirect('admin_banner');
        }

        $this->db->insert('website_banner', [
            'judul' => $judul,
            'subjudul' => trim($this->input->post('subjudul')),
            'deskripsi' => trim($this->input->post('deskripsi')),
            'gambar' => $gambar,
            'button_text' => trim($this->input->post('button_text')),
            'button_url' => trim($this->input->post('button_url')),
            'status' => $this->input->post('status') == 'Published' ? 'Published' : 'Draft',
            'urutan' => (int)$this->input->post('urutan'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Banner slider berhasil ditambahkan.');
        redirect('admin_banner');
    }

    public function update($id){
        if(!$this->ensureTable()){
            redirect('admin_banner');
        }

        $banner = $this->db->where('id', $id)->get('website_banner')->row();

        if(!$banner){
            show_404();
        }

        $data = [
            'judul' => trim($this->input->post('judul')),
            'subjudul' => trim($this->input->post('subjudul')),
            'deskripsi' => trim($this->input->post('deskripsi')),
            'button_text' => trim($this->input->post('button_text')),
            'button_url' => trim($this->input->post('button_url')),
            'status' => $this->input->post('status') == 'Published' ? 'Published' : 'Draft',
            'urutan' => (int)$this->input->post('urutan'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if(empty($data['judul'])){
            $this->session->set_flashdata('error', 'Judul banner wajib diisi.');
            redirect('admin_banner');
        }

        $new_gambar = $this->uploadBanner('gambar');

        if(!empty($new_gambar)){
            $old_gambar = $banner->gambar;
            $data['gambar'] = $new_gambar;
        }

        $this->db->where('id', $id);
        $this->db->update('website_banner', $data);

        if(!empty($old_gambar)){
            $this->deleteFileIfUnused($old_gambar);
        }

        $this->session->set_flashdata('success', 'Banner slider berhasil diperbarui.');
        redirect('admin_banner');
    }

    public function publish($id){
        if(!$this->ensureTable()){
            redirect('admin_banner');
        }

        $this->db->where('id', $id)->update('website_banner', [
            'status' => 'Published',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Banner berhasil dipublish.');
        redirect('admin_banner');
    }

    public function draft($id){
        if(!$this->ensureTable()){
            redirect('admin_banner');
        }

        $this->db->where('id', $id)->update('website_banner', [
            'status' => 'Draft',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Banner dikembalikan ke Draft.');
        redirect('admin_banner');
    }

    public function delete($id){
        if(!$this->ensureTable()){
            redirect('admin_banner');
        }

        $banner = $this->db->where('id', $id)->get('website_banner')->row();

        if(!$banner){
            show_404();
        }

        $gambar = $banner->gambar;
        $this->db->where('id', $id)->delete('website_banner');
        $this->deleteFileIfUnused($gambar);

        $this->session->set_flashdata('success', 'Banner slider berhasil dihapus.');
        redirect('admin_banner');
    }
}
