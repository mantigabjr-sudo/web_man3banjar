<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function get_setting(){
        $q = $this->db->get('website_setting');
        return $q->num_rows() > 0 ? $q->row() : (object)[
            'nama_sekolah' => 'MAN 3 Banjar',
            'slogan' => 'Madrasah Mandiri Berprestasi',
            'npsn' => '30315344',
            'nsm' => '131163030003',
            'akreditasi' => 'A',
            'alamat' => 'Jl. Sungai Batang, Martapura Barat, Banjar, Kalsel',
            'telepon' => '(0511) 7943000',
            'email' => 'man3banjar@kemenag.go.id',
            'nama_kamad' => 'Drs. H. Saifi, M.Pd',
            'sambutan_kamad' => 'Assalamu\'alaikum Wr. Wb. Selamat datang di portal resmi MAN 3 Banjar...',
            'visi' => 'Terwujudnya generasi yang berakhlak mulia dan berprestasi unggul.',
            'logo' => 'logo.png'
        ];
    }

    public function get_banners(){
        return $this->db->where('is_active', 1)
                        ->order_by('urutan', 'ASC')
                        ->get('website_banner')
                        ->result();
    }

    public function get_berita_terbaru($limit = 6){
        return $this->db->select('b.*, k.nama_kategori, k.slug as kategori_slug')
                        ->from('berita b')
                        ->join('berita_kategori k', 'k.id = b.kategori_id', 'left')
                        ->where('b.is_published', 1)
                        ->order_by('b.published_at', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->result();
    }

    public function get_berita_by_slug($slug){
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('slug', $slug);
        $this->db->update('berita');

        return $this->db->select('b.*, k.nama_kategori, k.slug as kategori_slug')
                        ->from('berita b')
                        ->join('berita_kategori k', 'k.id = b.kategori_id', 'left')
                        ->where('b.slug', $slug)
                        ->where('b.is_published', 1)
                        ->get()
                        ->row();
    }

    public function get_kategori_list(){
        return $this->db->get('berita_kategori')->result();
    }

    public function get_galeri($limit = 12){
        return $this->db->select('g.*, k.nama_album')
                        ->from('galeri g')
                        ->join('galeri_kategori k', 'k.id = g.album_id', 'left')
                        ->order_by('g.id', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->result();
    }

    public function get_downloads(){
        return $this->db->order_by('id', 'DESC')->get('website_download')->result();
    }

    public function get_ptk_list(){
        return $this->db->where('is_active', 1)->order_by('nama_lengkap', 'ASC')->get('website_ptk')->result();
    }
}
