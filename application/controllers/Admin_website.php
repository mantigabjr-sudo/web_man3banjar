<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_website extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('session');
        $this->load->helper(['url','form']);

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $role = $this->session->userdata('role');

        $allowed = [
            'admin',
            'admin_master',
            'admin_humas',
            'wakil_humas',
            'operator_humas'
        ];

        if(!in_array($role, $allowed)){
            show_error('Anda tidak memiliki akses ke menu Website Madrasah.', 403);
        }
    }

    public function profil(){

        $data['profil'] = $this->db
            ->limit(1)
            ->get('website_profil')
            ->row();

        $this->load->view('admin_website/profil', $data);
    }

    public function save_profil(){

        $data = [
            'judul_profil' => $this->input->post('judul_profil'),
            'isi_profil'  => $this->input->post('isi_profil'),
            'visi'        => $this->input->post('visi'),
            'misi'        => $this->input->post('misi'),
            'tujuan'      => $this->input->post('tujuan'),
            'alamat'      => $this->input->post('alamat'),
            'telepon'     => $this->input->post('telepon'),
            'email'       => $this->input->post('email'),
			'whatsapp'      => $this->input->post('whatsapp'),
			'facebook_url'  => $this->input->post('facebook_url'),
			'instagram_url' => $this->input->post('instagram_url'),
			'youtube_url'   => $this->input->post('youtube_url'),
			'jam_layanan'   => $this->input->post('jam_layanan'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $profil = $this->db
            ->limit(1)
            ->get('website_profil')
            ->row();

        if($profil){
            $this->db->where('id', $profil->id);
            $this->db->update('website_profil', $data);
        } else {
            $this->db->insert('website_profil', $data);
        }

        $this->session->set_flashdata('success', 'Profil website berhasil diperbarui.');
        redirect('admin_website/profil');
    }

    public function video(){

        $data['video'] = $this->db
            ->order_by('created_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get('website_video')
            ->result();

        $this->load->view('admin_website/video', $data);
    }

    public function save_video(){

        $judul = trim($this->input->post('judul'));
        $youtube_url = trim($this->input->post('youtube_url'));

        if(empty($judul) || empty($youtube_url)){
            $this->session->set_flashdata('error', 'Judul dan URL YouTube wajib diisi.');
            redirect('admin_website/video');
        }

        $this->db->insert('website_video', [
            'judul' => $judul,
            'deskripsi' => $this->input->post('deskripsi'),
            'youtube_url' => $youtube_url,
            'status' => 'Draft',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video berhasil disimpan sebagai Draft.');
        redirect('admin_website/video');
    }

    public function publish_video($id){

        $video = $this->db
            ->where('id', $id)
            ->get('website_video')
            ->row();

        if(!$video){
            show_404();
        }

        /*
         * Supaya hanya satu video profil yang aktif,
         * semua video lain dikembalikan ke Draft.
         */
        $this->db->update('website_video', [
            'status' => 'Draft'
        ]);

        $this->db->where('id', $id);
        $this->db->update('website_video', [
            'status' => 'Published',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video profil berhasil dipublish.');
        redirect('admin_website/video');
    }

    public function draft_video($id){

        $video = $this->db
            ->where('id', $id)
            ->get('website_video')
            ->row();

        if(!$video){
            show_404();
        }

        $this->db->where('id', $id);
        $this->db->update('website_video', [
            'status' => 'Draft',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video dikembalikan menjadi Draft.');
        redirect('admin_website/video');
    }

    public function delete_video($id){

        $this->db->where('id', $id);
        $this->db->delete('website_video');

        $this->session->set_flashdata('success', 'Video berhasil dihapus.');
        redirect('admin_website/video');
    }
	public function pamflet(){

    $data['pamflet'] = $this->db
        ->order_by('tanggal', 'DESC')
        ->order_by('created_at', 'DESC')
        ->get('website_pamflet')
        ->result();

    $this->load->view('admin_website/pamflet', $data);
}

public function save_pamflet(){

    $judul = trim($this->input->post('judul'));

    if(empty($judul)){
        $this->session->set_flashdata('error', 'Judul pamflet wajib diisi.');
        redirect('admin_website/pamflet');
    }

    if(empty($_FILES['gambar']['name'])){
        $this->session->set_flashdata('error', 'Gambar pamflet wajib diupload.');
        redirect('admin_website/pamflet');
    }

    $upload_path = './assets/pamflet/';

    if(!is_dir($upload_path)){
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|webp';
    $config['max_size']      = 4096;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if(!$this->upload->do_upload('gambar')){
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('admin_website/pamflet');
    }

    $upload = $this->upload->data();

    $this->db->insert('website_pamflet', [
        'judul'      => $judul,
        'deskripsi'  => $this->input->post('deskripsi'),
        'gambar'     => $upload['file_name'],
        'tanggal'    => $this->input->post('tanggal') ?: date('Y-m-d'),
        'status'     => 'Draft',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet berhasil disimpan sebagai Draft.');
    redirect('admin_website/pamflet');
}

public function publish_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_pamflet', [
        'status' => 'Published',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet berhasil dipublish.');
    redirect('admin_website/pamflet');
}

public function draft_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_pamflet', [
        'status' => 'Draft',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet dikembalikan menjadi Draft.');
    redirect('admin_website/pamflet');
}

public function delete_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    if(!empty($pamflet->gambar)){
        $file = FCPATH.'assets/pamflet/'.$pamflet->gambar;

        if(file_exists($file)){
            unlink($file);
        }
    }

    $this->db->where('id', $id);
    $this->db->delete('website_pamflet');

    $this->session->set_flashdata('success', 'Pamflet berhasil dihapus.');
    redirect('admin_website/pamflet');
}
	public function ptk(){

    if(!$this->db->field_exists('tampil_website', 'ptk')){
        show_error('Kolom tampil_website belum ada di tabel ptk. Jalankan SQL update database terlebih dahulu.');
    }

    if(!$this->db->field_exists('urutan_website', 'ptk')){
        show_error('Kolom urutan_website belum ada di tabel ptk. Jalankan SQL update database terlebih dahulu.');
    }

    $data['ptk'] = $this->db
        ->order_by('urutan_website', 'ASC')
        ->order_by('nama_lengkap', 'ASC')
        ->get('ptk')
        ->result();

    $this->load->view('admin_website/ptk', $data);
}

public function save_ptk_urutan(){

    $urutan = $this->input->post('urutan');

    if(!empty($urutan)){
        foreach($urutan as $ptk_id => $nilai){
            $this->db->where('id', $ptk_id);
            $this->db->update('ptk', [
                'urutan_website' => (int)$nilai
            ]);
        }
    }

    $this->session->set_flashdata('success', 'Urutan PTK di website berhasil diperbarui.');
    redirect('admin_website/ptk');
}

public function show_ptk($id){

    $ptk = $this->db
        ->where('id', $id)
        ->get('ptk')
        ->row();

    if(!$ptk){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('ptk', [
        'tampil_website' => 1
    ]);

    $this->session->set_flashdata('success', 'PTK berhasil ditampilkan di website.');
    redirect('admin_website/ptk');
}

public function hide_ptk($id){

    $ptk = $this->db
        ->where('id', $id)
        ->get('ptk')
        ->row();

    if(!$ptk){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('ptk', [
        'tampil_website' => 0
    ]);

    $this->session->set_flashdata('success', 'PTK disembunyikan dari website.');
    redirect('admin_website/ptk');
}
public function tentang(){

    $data['profil'] = $this->db
        ->limit(1)
        ->get('website_profil')
        ->row();

    $this->load->view('admin_website/tentang', $data);
}

public function save_tentang(){

    $data = [
        'sejarah'          => $this->input->post('sejarah'),
        'fasilitas'        => $this->input->post('fasilitas'),
        'prestasi'         => $this->input->post('prestasi'),
        'ekstrakurikuler'  => $this->input->post('ekstrakurikuler'),
        'maps_embed_url'   => $this->input->post('maps_embed_url'),
        'updated_at'       => date('Y-m-d H:i:s')
    ];

    $profil = $this->db
        ->limit(1)
        ->get('website_profil')
        ->row();

    if($profil){
        $this->db->where('id', $profil->id);
        $this->db->update('website_profil', $data);
    } else {
        $this->db->insert('website_profil', $data);
    }

    $this->session->set_flashdata('success', 'Tentang madrasah berhasil diperbarui.');
    redirect('admin_website/tentang');
}

public function galeri(){

    $data['galeri'] = $this->db
        ->order_by('tanggal', 'DESC')
        ->order_by('created_at', 'DESC')
        ->get('website_galeri')
        ->result();

    $this->load->view('admin_website/galeri', $data);
}

public function save_galeri(){

    $judul = trim($this->input->post('judul'));

    if(empty($judul)){
        $this->session->set_flashdata('error', 'Judul galeri wajib diisi.');
        redirect('admin_website/galeri');
    }

    if(empty($_FILES['gambar']['name'])){
        $this->session->set_flashdata('error', 'Gambar galeri wajib diupload.');
        redirect('admin_website/galeri');
    }

    $upload_path = './assets/galeri/';

    if(!is_dir($upload_path)){
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|webp';
    $config['max_size']      = 4096;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if(!$this->upload->do_upload('gambar')){
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('admin_website/galeri');
    }

    $upload = $this->upload->data();

    $this->db->insert('website_galeri', [
        'judul'      => $judul,
        'deskripsi'  => $this->input->post('deskripsi'),
        'gambar'     => $upload['file_name'],
        'tanggal'    => $this->input->post('tanggal') ?: date('Y-m-d'),
        'status'     => 'Draft',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri berhasil disimpan sebagai Draft.');
    redirect('admin_website/galeri');
}

public function publish_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_galeri', [
        'status' => 'Published',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri berhasil dipublish.');
    redirect('admin_website/galeri');
}

public function draft_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_galeri', [
        'status' => 'Draft',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri dikembalikan menjadi Draft.');
    redirect('admin_website/galeri');
}

public function delete_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    if(!empty($galeri->gambar)){
        $file = FCPATH.'assets/galeri/'.$galeri->gambar;

        if(file_exists($file)){
            unlink($file);
        }
    }

    $this->db->where('id', $id);
    $this->db->delete('website_galeri');

    $this->session->set_flashdata('success', 'Galeri berhasil dihapus.');
    redirect('admin_website/galeri');
}
    public function download(){
        $data['downloads'] = $this->db
            ->order_by('tanggal', 'DESC')
            ->get('website_download')
            ->result();

        $this->load->view('admin_website/download', $data);
    }

    public function save_download(){
        $judul = $this->input->post('judul');
        $keterangan = $this->input->post('keterangan');
        $tanggal = $this->input->post('tanggal');

        $config['upload_path']   = FCPATH.'assets/downloads/';
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar';
        $config['max_size']      = 10240; // 10MB
        $config['file_name']     = time().'_'.url_title($judul, 'dash', true);

        if(!is_dir($config['upload_path'])){
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if($this->upload->do_upload('file_download')){
            $uploadData = $this->upload->data();
            $file_path = $uploadData['file_name'];

            $this->db->insert('website_download', [
                'judul'      => $judul,
                'keterangan' => $keterangan,
                'file_path'  => $file_path,
                'tanggal'    => $tanggal
            ]);

            $this->session->set_flashdata('success', 'File berhasil diunggah.');
        } else {
            $error = $this->upload->display_errors('','');
            $this->session->set_flashdata('error', 'Gagal mengunggah file: '.$error);
        }

        redirect('admin_website/download');
    }

    public function delete_download($id){
        $download = $this->db->where('id', $id)->get('website_download')->row();

        if(!$download){
            show_404();
        }

        if(!empty($download->file_path)){
            $file = FCPATH.'assets/downloads/'.$download->file_path;
            if(file_exists($file)){
                unlink($file);
            }
        }

        $this->db->where('id', $id)->delete('website_download');

        $this->session->set_flashdata('success', 'File berhasil dihapus.');
        redirect('admin_website/download');
    }
}