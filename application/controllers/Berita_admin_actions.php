<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'controllers/Berita.php';

class Berita_admin_actions extends Berita {

    private $kategori_options = ['Prestasi','Kegiatan','Pengumuman','PPDB','Akademik','Keagamaan','Ekstrakurikuler'];

    public function __construct(){
        parent::__construct();
        $this->load->helper(['access', 'text', 'url']);
    }

    private function guardBerita(){
        if(!$this->session->userdata('logged_in') || !can_admin_menu('berita')){
            show_error('Anda tidak memiliki akses kelola berita.', 403);
        }
    }

    private function normalizeKategori($kategori){
        $kategori = trim((string)$kategori);
        return in_array($kategori, $this->kategori_options) ? $kategori : 'Kegiatan';
    }

    private function hasFeaturedColumns(){
        return $this->db->field_exists('is_featured', 'berita')
            && $this->db->field_exists('featured_order', 'berita')
            && $this->db->field_exists('featured_at', 'berita');
    }

    private function makeSlug($judul, $id = null){
        if(!$this->db->field_exists('slug', 'berita')){
            return null;
        }

        $slug = url_title(convert_accented_characters($judul), '-', true);
        $slug = trim($slug, '-');

        if(empty($slug)){
            $slug = 'berita';
        }

        $base = $slug;
        $i = 2;

        while(true){
            $this->db->where('slug', $slug);

            if(!empty($id)){
                $this->db->where('id !=', $id);
            }

            $exists = $this->db->get('berita')->row();

            if(!$exists){
                return $slug;
            }

            $slug = $base.'-'.$i;
            $i++;
        }
    }

    public function add(){
        $this->guardBerita();

        $judul = trim($this->input->post('judul'));
        $isi = trim($this->input->post('isi'));

        if(empty($judul) || empty($isi)){
            $this->session->set_flashdata('error', 'Judul dan isi berita wajib diisi.');
            redirect('berita');
        }

        $gambar = '';

        if(!empty($_FILES['gambar']['name'])){
            $upload_path = './assets/news/';

            if(!is_dir($upload_path)){
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 8192;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if(!$this->upload->do_upload('gambar')){
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('berita');
            }

            $upload = $this->upload->data();
            $gambar = $upload['file_name'];
        }

        $data = [
            'judul' => $judul,
            'isi' => $isi,
            'gambar' => $gambar,
            'status_berita' => 'Draft',
            'published_at' => null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if($this->db->field_exists('slug', 'berita')){
            $data['slug'] = $this->makeSlug($judul);
        }

        if($this->db->field_exists('kategori', 'berita')){
            $data['kategori'] = $this->normalizeKategori($this->input->post('kategori'));
        }

        if($this->hasFeaturedColumns() && $this->input->post('is_featured') == '1'){
            $data['is_featured'] = 1;
            $data['featured_order'] = (int)$this->input->post('featured_order');
            $data['featured_at'] = date('Y-m-d H:i:s');
        }

        $this->db->insert('berita', $data);
        $berita_id = $this->db->insert_id();

        $this->uploadMultiBerita($berita_id);

        $this->session->set_flashdata('success', 'Berita berhasil disimpan sebagai Draft.');
        redirect('berita/regenerate_pamflet/'.$berita_id);
    }

    private function uploadMultiBerita($berita_id){
        if(empty($_FILES['gambar_multi']['name'][0])){
            return;
        }

        if(!$this->db->table_exists('berita_gambar')){
            return;
        }

        $upload_path = './assets/news/';

        if(!is_dir($upload_path)){
            mkdir($upload_path, 0777, true);
        }

        $total = count($_FILES['gambar_multi']['name']);

        for($i = 0; $i < $total; $i++){
            if(empty($_FILES['gambar_multi']['name'][$i])){
                continue;
            }

            $_FILES['file_berita']['name'] = $_FILES['gambar_multi']['name'][$i];
            $_FILES['file_berita']['type'] = $_FILES['gambar_multi']['type'][$i];
            $_FILES['file_berita']['tmp_name'] = $_FILES['gambar_multi']['tmp_name'][$i];
            $_FILES['file_berita']['error'] = $_FILES['gambar_multi']['error'][$i];
            $_FILES['file_berita']['size'] = $_FILES['gambar_multi']['size'][$i];

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 8192;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if($this->upload->do_upload('file_berita')){
                $up = $this->upload->data();

                $last = $this->db
                    ->select_max('urutan')
                    ->where('berita_id', $berita_id)
                    ->get('berita_gambar')
                    ->row();

                $urutan = !empty($last->urutan) ? ((int)$last->urutan + 1) : 1;

                $this->db->insert('berita_gambar', [
                    'berita_id' => $berita_id,
                    'gambar' => $up['file_name'],
                    'urutan' => $urutan,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function update($id){
        $this->guardBerita();

        $update = [];
        $judul = trim($this->input->post('judul'));

        if($this->db->field_exists('slug', 'berita') && !empty($judul)){
            $berita = $this->db->where('id', $id)->get('berita')->row();

            if($berita && (empty($berita->slug) || $berita->judul != $judul)){
                $update['slug'] = $this->makeSlug($judul, $id);
            }
        }

        if($this->db->field_exists('kategori', 'berita') && $this->input->post('kategori') !== null){
            $update['kategori'] = $this->normalizeKategori($this->input->post('kategori'));
        }

        if($this->hasFeaturedColumns()){
            $is_featured = $this->input->post('is_featured') == '1' ? 1 : 0;
            $update['is_featured'] = $is_featured;
            $update['featured_order'] = (int)$this->input->post('featured_order');
            $update['featured_at'] = $is_featured ? date('Y-m-d H:i:s') : null;
        }

        if(!empty($update)){
            $this->db->where('id', $id);
            $this->db->update('berita', $update);
        }

        parent::update($id);
    }

    public function set_kategori($id){
        $this->guardBerita();

        if(!$this->db->field_exists('kategori', 'berita')){
            $this->session->set_flashdata('error', 'Kolom kategori belum tersedia. Sinkronkan struktur database db_labsys terlebih dahulu.');
            redirect('berita');
        }

        $berita = $this->db->where('id', $id)->get('berita')->row();

        if(!$berita){
            show_404();
        }

        $this->db->where('id', $id);
        $this->db->update('berita', [
            'kategori' => $this->normalizeKategori($this->input->post('kategori'))
        ]);

        $this->session->set_flashdata('success', 'Kategori berita berhasil diperbarui.');
        redirect('berita');
    }

    public function featured($id){
        $this->guardBerita();

        if(!$this->hasFeaturedColumns()){
            $this->session->set_flashdata('error', 'Kolom featured belum tersedia. Jalankan SQL migrasi featured berita terlebih dahulu.');
            redirect('berita');
        }

        $berita = $this->db->where('id', $id)->get('berita')->row();

        if(!$berita){
            show_404();
        }

        $this->db->where('id', $id);
        $this->db->update('berita', [
            'is_featured' => 1,
            'featured_order' => (int)$this->input->get('order'),
            'featured_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Berita berhasil dijadikan Berita Utama.');
        redirect('berita');
    }

    public function unfeatured($id){
        $this->guardBerita();

        if(!$this->hasFeaturedColumns()){
            $this->session->set_flashdata('error', 'Kolom featured belum tersedia. Jalankan SQL migrasi featured berita terlebih dahulu.');
            redirect('berita');
        }

        $berita = $this->db->where('id', $id)->get('berita')->row();

        if(!$berita){
            show_404();
        }

        $this->db->where('id', $id);
        $this->db->update('berita', [
            'is_featured' => 0,
            'featured_order' => 0,
            'featured_at' => null
        ]);

        $this->session->set_flashdata('success', 'Berita berhasil dihapus dari Berita Utama.');
        redirect('berita');
    }

    public function generate_missing_slugs(){
        $this->guardBerita();

        if(!$this->db->field_exists('slug', 'berita')){
            $this->session->set_flashdata('error', 'Kolom slug belum tersedia. Jalankan SQL migrasi slug berita terlebih dahulu.');
            redirect('berita');
        }

        $rows = $this->db
            ->group_start()
                ->where('slug IS NULL', null, false)
                ->or_where('slug', '')
            ->group_end()
            ->order_by('id', 'ASC')
            ->get('berita')
            ->result();

        $total = 0;

        foreach($rows as $r){
            $this->db->where('id', $r->id);
            $this->db->update('berita', [
                'slug' => $this->makeSlug($r->judul, $r->id)
            ]);
            $total++;
        }

        $this->session->set_flashdata('success', 'Slug berita berhasil dibuat untuk '.$total.' data.');
        redirect('berita');
    }

    public function edit($id){
        $this->guardBerita();
        parent::edit($id);
    }

    public function delete($id){
        $this->guardBerita();
        parent::delete($id);
    }

    public function delete_gambar($id){
        $this->guardBerita();
        parent::delete_gambar($id);
    }

    public function regenerate_pamflet($id){
        $this->guardBerita();
        parent::regenerate_pamflet($id);
    }

    public function download_pamflet($id){
        $this->guardBerita();
        parent::download_pamflet($id);
    }
}
